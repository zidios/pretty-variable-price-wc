<?php
/*
 * Plugin Name: Pretty variable product price
 * Description: Show price for variable product WC. Get only first variable attribute.
 * Author:      Удачин Владимир
 * Version:     0.2
 * Requires PHP: 7.4
 *
 * License:     MIT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;

}
function prettyPrice(){
    wp_enqueue_script('mainJS', plugins_url( 'assets/js/main.js', __FILE__ ));
    wp_enqueue_style("mainsstyle", plugins_url( 'assets/css/style.css', __FILE__ ) );
    add_shortcode( 'show_pretty_price', 'show_pretty_price' );
}
function show_pretty_price( $atts ){
    $atts = shortcode_atts( array(
        'show_attrs'   => '1',
        'show_prices' => '1',
        'delimiter'  => '/',
        'currency_symbol' => ' ₽',
        'after_attr' => ' мин.',

    ), $atts );
    global $product;
    $calcBody = '';
    if(!empty($product) && is_object($product)){
        $product_prices = array();
        $product_attrs = array();
        $price_str = '';
        $attrs_str = '';
        switch (get_class($product)){
            case 'WC_Product_Variable':
                echo '<div id="pretty_product_type" class="variable" style="display: none;"></div>';
                $product_variations = $product->get_available_variations();
                foreach ($product_variations as $variation){
                    if(!empty($variation['display_price'])) array_push($product_prices, $variation['display_price']);
                    if(!empty($variation['attributes'])){
                        reset($variation['attributes']);
                        array_push($product_attrs, current($variation['attributes']));
                    }
                }
                break;
            case 'WC_Product_Simple':
                echo '<div id="pretty_product_type" class="simple" style="display: none;"></div>';
                $product_price = $product->get_price();
                $product_attrib = $product->get_attributes();
                if(!empty($product_price)) array_push($product_prices, $product_price);
                if(!empty($product_attrib)){
                    reset($product_attrib);
                    $attrib = current($product_attrib);
                    if(is_object($attrib) && get_class($attrib) == 'WC_Product_Attribute'){
                        if($attrib['is_taxonomy']){
                            $values = wc_get_product_terms( $product->get_id(), $attrib['name'], array( 'fields' => 'names' ) );
                            if(isset($values[0])) {
                                array_push($product_attrs, $values[0]);
                            }
                        }else{
                            $attrib_data = $attrib->get_data();
                            if(!empty($attrib_data) && !empty($attrib_data['value'])){
                                array_push($product_attrs, $attrib_data['value']);
                            } elseif(!empty($attrib_data) && !empty($attrib_data['options']) && !empty($attrib_data['options'][0])){
                                array_push($product_attrs, $attrib_data['options'][0]);
                            }
                        }


                    }
                }
                break;
        }


        if(!empty($product_prices) && $atts['show_prices'] === '1'){
            $price_str = implode($atts['delimiter'] , $product_prices) . $atts['currency_symbol'];
        }
        if(!empty($product_attrs) && $atts['show_attrs'] === '1'){
            $attrs_str = implode($atts['delimiter'] , $product_attrs) . $atts['after_attr'];
        }
        $calcBody = '
        <div class="pretty_price_container">
            <div class="pretty_price_row prices">
            
            '. $price_str .'
            </div>
            <div class="pretty_price_row attrs">
            '. $attrs_str .'
            </div>        
        </div>
        ';
    }

    return $calcBody;

}
add_action('init', 'prettyPrice');
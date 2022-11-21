<?php
/*
 * Plugin Name: Pretty variable product price
 * Description: Show price for variable product WC. Get only first variable attribute.
 * Author:      Удачин Владимир
 * Version:     0.1
 * Requires PHP: 7.4
 *
 * License:     MIT
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;

}
function prettyPrice(){
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
    $product_variations = $product->get_available_variations();
    $product_prices = array();
    $product_attrs = array();
    $price_str = '';
    $attrs_str = '';
    foreach ($product_variations as $variation){
        if(!empty($variation['display_price'])) array_push($product_prices, $variation['display_price']);
        if(!empty($variation['attributes'])){
            reset($variation['attributes']);
            array_push($product_attrs, current($variation['attributes']));
        }
    }
    if(!empty($product_prices) && $atts['show_prices'] === '1'){
        $price_str = implode($atts['delimiter'] , $product_prices) . $atts['currency_symbol'];
    }
    if(!empty($product_attrs) && $atts['show_attrs'] === '1'){
        $attrs_str = implode($atts['delimiter'] , $product_attrs) . $atts['after_attr'];
    }
    $calcBody = '
    <div class="pretty_price_container">
        <div class="pretty_price_row">
        '. $price_str .'
        </div>
        <div class="pretty_price_row">
        '. $attrs_str .'
        </div>        
    </div>
    ';
    return $calcBody;

}
add_action('init', 'prettyPrice');
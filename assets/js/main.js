function showButtons(){
    let variableProduct = document.querySelector('div#pretty_product_type.variable');
    let simpleProduct = document.querySelector('div#pretty_product_type.simple');
    var btn = undefined;
    if(variableProduct !== undefined && variableProduct !== null){
        btn = document.querySelector('.pretty_variable');
    }
    if(simpleProduct !== undefined && simpleProduct !== null){
        btn = document.querySelector('.pretty_simple');
    }
    if(btn !== undefined && btn !== null){
        btn.style.display = "block";
    }

}
document.addEventListener("DOMContentLoaded", function(event) {
    showButtons();
});
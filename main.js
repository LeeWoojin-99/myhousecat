function right_menu_panel_on(){
    // var target = document.getElementById("right_menu_panel");
    // target.style.transform = "translateX(-700px)";
    var target = $('#right_menu_panel');
    target.css('transform', 'translateX(-700px)');
}
function right_menu_panel_off(){
    // var target = document.getElementById("right_menu_panel");
    // target.style.transform = "translateX(700px)";
    var target = $('#right_menu_panel');
    target.css('transform', 'translateX(700px)');
}
function product_search_on(){
    // var target = document.getElementById("product_search_wrapper");
    // target.style.opacity = "1";
    // target.style.visibility = 'visible';
    var target = $('#product_search_wrapper');
    target.css('opacity', '1').css('visibility', 'visible');
}
function product_search_off(){
    // var target = document.getElementById("product_search_wrapper");
    // target.style.opacity = "0";
    // target.style.visibility = 'hidden';
    var target = $('#product_search_wrapper');
    target.css('opacity', '0').css('visibility', 'hidden');
}
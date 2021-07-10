function right_menu_panel_on(){
    var target = document.getElementById("right_menu_panel");
    target.style.transform = "translateX(-700px)";
    console.log("right_menu_panel_on()")
}

function right_menu_panel_off(){
    var target = document.getElementById("right_menu_panel");
    target.style.transform = "translateX(700px)";
}

function product_search_on(){
    var target = document.getElementById("product_search_wrapper");
    target.style.display = "block";
    target.style.animation = "fade_in 0.6s";
}

function product_search_off(){
    var target = document.getElementById("product_search_wrapper");
    setTimeout(() => target.style.display = "none", 600)
    target.style.animation = "fade_out 0.6s";
}
$(document).ready(function(){
    
    $("#search_btn_wrapper").click(function(){
        $("#right_menu_panel").css("transform", "translateX(-700px)");
    })
    $("#right_menu_panel > .x_btn").click(function(){
        $('#right_menu_panel').css('transform', 'translateX(700px)');
    })


    $("#menu_btn_wrapper").click(function(){
        $('#product_search_wrapper').css('opacity', '1').css('visibility', 'visible');
    })
    $("#product_search_bg").click(function(){
        $('#product_search_wrapper').css('opacity', '0').css('visibility', 'hidden');
    })
    

})
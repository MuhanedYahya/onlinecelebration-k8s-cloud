$(document).ready(function () {

    // index page template select icon function
    let el = $(".wrapper");
    el.click(function () {
        el.children("svg").css("display", "none");
        $(this).children("svg").css("display", "block");
        $(this).css("display", "block");
        // el.css("outline","none");
        // $(this).css("outline","3px solid var(--pink-color)");
    });
    
    // document ready end
});


// spinner loader 
$(window).on('load', function () {
    setTimeout(removeLoader, 1000); //wait for page load PLUS two seconds.
});
function removeLoader() {
    $(".overlay").fadeOut(500, function () {
        // fadeOut complete. Remove the loading div
        $(".overlay").remove(); //makes page more lightweight 
    });
}




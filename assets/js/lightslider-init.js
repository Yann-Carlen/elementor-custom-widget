jQuery(document).ready(function ($) {

    $('.image-gallery').lightSlider({
        gallery: false,
        item: 1,
        auto: true,
        loop: true,
        caption: true,
        speed: 1000,
        pause: 2500
    });

});
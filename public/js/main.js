(function ($) {
    "use strict";


    $('#store-page-slider').nivoSlider({
        effect: 'random',
        slices: 15,
        boxCols: 8,
        boxRows: 4,
        animSpeed: 500,
        pauseTime: 5000,
        startSlide: 0,
        directionNav: true,
        controlNavThumbs: true,
        pauseOnHover: false,
        manualAdvance: false
    });


    /*----------------------------
     jQuery MeanMenu
     ------------------------------ */
    jQuery('nav#dropdown').meanmenu();

    /*---------------------------------------------
     Nivo slider
     --------------------------------------------- */
    $('#ensign-nivoslider').nivoSlider({
        effect: 'random',
        slices: 15,
        boxCols: 8,
        boxRows: 4,
        animSpeed: 500,
        pauseTime: 5000,
        startSlide: 0,
        directionNav: true,
        controlNavThumbs: true,
        pauseOnHover: false,
        manualAdvance: false,
    });

    /*---------------------
     countdown
     --------------------- */
    $('[data-countdown]').each(function () {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function (event) {
            $this.html(event.strftime('<span class="cdown days"><span class="time-count">%-D</span> <p>روز</p></span> <span class="cdown hour"><span class="time-count">%-H</span> <p>ساعت</p></span> <span class="cdown minutes"><span class="time-count">%M</span> <p>دقیقه</p></span> <span class="cdown second"> <span><span class="time-count">%S</span> <p>ثانیه</p></span>'));
        });
    });

    /*----------------------------
     featured owl active
     ------------------------------ */
    $(".featured_products.best_seller_products_list , .featured_products.products-list-of-categories-carousel").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 4,
        scrollPerPage: true,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right featured_products_next'></i>", "<i class='fa fa-angle-left featured_products_prev'></i>"],
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [980, 3],
        itemsTablet: [768, 2],
        itemsMobile: [479, 1],
    });
    $(".vip-stores-container.featured_products").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: true,
        navigation: true,
        items: 4,
        scrollPerPage: true,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right featured_products_next'></i>", "<i class='fa fa-angle-left featured_products_prev'></i>"],
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [980, 2],
        itemsTablet: [768, 1],
        itemsMobile: [479, 1],
    });

    /*----------------------------
     single new product owl active
     ------------------------------ */
    $(".single_new_product_owl").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 4,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"],
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [980, 2],
        itemsTablet: [768, 1],
        itemsMobile: [479, 1],
    });

    /*----------------------------
     client owl active
     ------------------------------ */
    $(".client_owl").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: false,
        items: 7,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"],
        itemsDesktop: [1199, 5],
        itemsDesktopSmall: [980, 4],
        itemsTablet: [768, 3],
        itemsMobile: [479, 2],
    });

    /*----------------------------
     latest news owl active
     ------------------------------ */
    $(".latest_news_wrapper").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 3,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"],
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [980, 2],
        itemsTablet: [768, 1],
        itemsMobile: [479, 1],
    });

    /*----------------------------
     tab container owl active
     ------------------------------ */
    $(".tab_container_owl").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 3,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"],
        itemsDesktop: [1199, 2],
        itemsDesktopSmall: [980, 2],
        itemsTablet: [768, 2],
        itemsMobile: [479, 1],
    });

    /*----------------------------
     new container owl active
     ------------------------------ */
    $(".featured_news_content_owl").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 1,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"],
        itemsDesktop: [1199, 1],
        itemsDesktopSmall: [980, 1],
        itemsTablet: [768, 1],
        itemsMobile: [479, 1],
    });


    /*----------------------------
     new container owl active
     ------------------------------ */
    $(".news_content_owl").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 1,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-right'></i>", "<i class='fa fa-angle-left'></i>"],
        itemsDesktop: [1199, 1],
        itemsDesktopSmall: [980, 2],
        itemsTablet: [768, 1],
        itemsMobile: [479, 1],
    });

    /*----------------------------
     single product active
     ------------------------------ */
    $(".p-details-slider").owlCarousel({
        autoPlay: false,
        slideSpeed: 2000,
        pagination: false,
        navigation: true,
        items: 4,
        /* transitionStyle : "fade", */    /* [This code for animation ] */
        navigationText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        itemsDesktop: [1199, 4],
        itemsDesktopSmall: [980, 3],
        itemsTablet: [768, 3],
        itemsMobile: [479, 3],
    });

    /*----------------------------
     Category Toggle Menu
     ------------------------------ */
    $('.show-submenu').on('click', function () {
        $(this).parent().find('.submenu').toggleClass('submenu-active');
        $(this).toggleClass('submenu-active');
        return false;
    });

    /*----------------------------------------------
     Delivery Old and New Address toggle function
     ------------------------------------------------*/
    $("#ship-new-address").on('click', function () {
        $(".ship-new-address-info").slideToggle();
    });

    /*----------------------------------------------
     Personal Address toggle function
     -----------------------------------------------*/
    $("#add-new-address").on('click', function () {
        $("#add-new-address-info").slideToggle();
    });


    /*----------------------------
     Input Plus Minus Button
     ------------------------------ */
    $(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
    $(".qtybutton").on("click", function () {
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find("input").val(newVal);
    });

    /*----------------------------
     Price-slider active
     ------------------------------ */
    $("#slider-range").slider({
        range: true,
        min: 12.00,
        max: 53.00,
        values: [12, 42.34],
        slide: function (event, ui) {
            $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
        }
    });
    $("#amount").val("$" + $("#slider-range").slider("values", 0) +
        " - $" + $("#slider-range").slider("values", 1));


    /*--------------------------
     scrollUp
     ---------------------------- */
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-double-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });


    /*--------------------------
     Elevatezoom
     ---------------------------- */
    $("#zoom1").elevateZoom({
        gallery: 'gallery_01',
        responsive: true,
        galleryActiveClass: "active",
        imageCrossfade: true,
        easing: true,
        cursor: "default",
        zoomWindowFadeIn: 300,
        zoomWindowFadeOut: 350
    });


    /*----------------------------
     wow js active
     ------------------------------ */
    new WOW().init();

})(jQuery);


$('[data-toggle-checkbox]').click(function () {
    var $this = $(this);
    var checkbox = $this.find('input[type="checkbox"]');
    var checked = checkbox.is(':checked');


    if (checkbox.is(':checked')) {
        $this.removeClass('btn-bordered');
        checkbox.prop('checked', true);
    } else {
        $this.addClass('btn-bordered');
        checkbox.prop('checked', false);
    }

});
$(window).scroll(function () {
    var menu = $('#fixed-top-menu-in-guid-page');
    if(window.scrollY > 832){
        menu.css('position' , 'fixed').css('top' , '40px')
            .css('z-index' , 100)
            .css('width' , '100%')
            .css('background-color' , '#EFEFEF');
    }else{
        menu.css('position' , 'static');
    }
});
``
$(window).scroll(function () {
    var menu = $('#fixed-top-menu-in-web-view-guid-page');
    if(window.scrollY > 832){
        menu.css('position' , 'fixed')
            .css('z-index' , 100)
            .css('width' , '100%')
            .css('background-color' , '#EFEFEF');
    }else{
        menu.css('position' , 'static');
    }
});


$('[data-toggle-radio]').change(function () {
    var $this = $(this);
    var checkbox = $this.find('input[type="radio"]');
    var checked = checkbox.is(':checked');
    $this.closest('.attribute-container').find('[data-toggle-radio]').addClass('btn-bordered');;
    if (checkbox.is(':checked')) {
        $this.removeClass('btn-bordered');
        checkbox.prop('checked', true);
    } else {
        $this.addClass('btn-bordered');
        checkbox.prop('checked', false);
    }
});


$('[data-toggle]').tooltip();

var switcherySettings = {
    color: '#EF5661',
    secondaryColor: '#dfdfdf',
    jackColor: '#fff',
    jackSecondaryColor: null,
    className: 'switchery',
    disabled: false,
    disabledOpacity: 0.5,
    speed: '0.1s',
    size: 'default'
};
var switchery = document.querySelector('.switchery');
var init = new Switchery(switchery);



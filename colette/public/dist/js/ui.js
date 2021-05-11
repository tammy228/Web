window.onload = function() {
    new WOW().init();
    $("#homeLoad").fadeOut(1500);
    $("#load").fadeOut(1000);
};
$(document).ready(function () {
    $('#twzipcode').twzipcode({
        'zipcodeSel': false,
    });
    $(window).scroll(function () {
        if ($("#header").offset().top > 300) {
            $("#header").addClass("active");
        } else {
            $("#header").removeClass("active");
        }
    });

    $("button#menu").click(function () {
        $("header nav,button#menu,header ol").toggleClass("active");
        $("#shopping-cart").removeClass("active");
        $("#user").removeClass("active");
    });
    $(".mobile_menu").click(function () {
        $(this).toggleClass("active");
    });
    $(".products_menu li").click(function () {
        $(this).toggleClass("active");
    });
    $("#shopping-cart i.fa-shopping-cart").click(function () {
        $('#shopping-cart').toggleClass("active");
        $('#user').removeClass("active");
        $('#searchBar').removeClass("active");
    });
    $("#user i.fa-user").click(function () {
        $('#user').toggleClass("active");
        $('#shopping-cart').removeClass("active");
        $('#searchBar').removeClass("active");
    });

    $("#openSearchBar").click(function () {
        $('#searchBar').toggleClass("active");
        $('#shopping-cart').removeClass("active");
        $('#user').removeClass("active");
    });

    $("#product #note_title h5").click(function () {
        $(this).addClass("active");
        $('#product #content #note').addClass("active");
        $('#product #explanation_title h5').removeClass("active");
        $('#product #content #explanation').removeClass("active");
    });
    $("#product #explanation_title h5").click(function () {
        $(this).addClass("active");
        $('#product #content #explanation').addClass("active");
        $('#product #note_title h5').removeClass("active");
        $('#product #content #note').removeClass("active");
    });
    $("#loginProductButton").click(function () {
        $('#loginProduct').addClass("active");
        $('body').css('overflow','hidden');

        $('#shopping-cart').removeClass("active");
        $('#user').removeClass("active");
        $('#searchBar').removeClass("active");
    });
    $("#loginProduct #times").click(function () {
        $('#loginProduct').removeClass("active");
        $('body').css('overflow','auto');
    });
    var tracked = false;
    $("#button_tracked").hide();

    $("#button_forTracking").click(function () {
        $(this).hide();
        $("#track").show();
        $("#button_tracked").show();
        $('body').css('overflow','hidden');
        $("#tracked").show();
        $('#forTracking').hide();
        var tracked = true;
    });

    $("#button_tracked").click(function () {
        $(this).hide();
        $("#track").show();
        $("#button_forTracking").show();
        $('body').css('overflow','hidden');
        $('#forTracking').show();
        $("#tracked").hide();
        var tracked = false;
    });
    
    $("#track #track_off").click(function () {
        $("#track").hide();
        $('body').css('overflow','auto');
    });

    $("#button_addToCart").click(function () {
        $("#addToCart").show();
        $('body').css('overflow','hidden');
    });

    $("#addToCart #track_off").click(function () {
        $("#addToCart").hide();
        $('body').css('overflow','auto');
    });

    $("#orderList ul li").click(function () {
        $(this).toggleClass("active");
    });

    $("#loginProduct .item,#trackingList .photo,#farmIntroduction #banner,#shoppingList .photo,#new .photo,#product .photo .item,#banner .item .photo,#popular_products .photo,#activity .photo,.products .photo,#farmIntroduction #bigPicture .item,#userProducts .photo,#shopping-cart .photo").imgLiquid({
        fill: true,
        horizontalAlign: "center",
        verticalAlign: "center"
    });
    $('#home #banner').slick({ //首頁banner
        dots: false,
        arrows: false,
        fade: true,
        infinite: true,
        slidesToShow: 1
    });
    $('#popular_products .product').slick({ //首頁熱門產品
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        nextArrow: '#popular_products .next',
        prevArrow: '#popular_products .prev',
        responsive: [{
                breakpoint: 1199,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('#activity .row').slick({ //首頁活動快訊
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        nextArrow: '#activity .next',
        prevArrow: '#activity .prev',
        responsive: [{
                breakpoint: 1199,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('#userProducts .row').slick({ //分頁熱門產品
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        nextArrow: '#userProducts .next',
        prevArrow: '#userProducts .prev',
        responsive: [{
                breakpoint: 1199,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('#product .photo .big').slick({ //產品頁大圖
        dots: false,
        arrows: false,
        infinite: true,
        slidesToShow: 1,
        draggable: false,
        asNavFor: '#product .photo .thumbnail'
    });
    $('#product .photo .thumbnail').slick({ //產品頁小圖
        touchMove: false,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        centerMode: true,
        infinite: true,
        arrows: false,
        focusOnSelect: true,
        asNavFor: '#product .photo .big'
    });
    $('#loginProduct .photo .slick').slick({ //
        dots: true,
        arrows: false,
        infinite: true,
        slidesToShow: 1
    });
});
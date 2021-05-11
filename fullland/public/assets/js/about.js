$(function() {
    $("#scroll-button").click(function() {
      $("html, body").animate({
        scrollTop: $("#section1-content").offset().top - navHeight
      }, 400);
    });
    $("#section1-slick").on("init reInit beforeChange", function(event, slick, currentSlide, nextSlide) {
      if (slick.slideCount == 0) {
        return;
      }
      // 在init時nextSlide為undefined，因此須設置為0（nextSlide以0為起始）
      var i = (nextSlide ? nextSlide : 0) + 1;
      i = i <= 9 ? "0" + i.toString() : i;
      var len = slick.slideCount;
      len = len <= 9 ? "0" + len.toString() : len;
      $("#section1-slick-count").find(".count").text(i);
      $("#section1-slick-count").find(".amount").text(len);
    });
    $("#section1-slick").slick({
      arrows: true,
      dots: false,
      infinite: true
    });
    // var years = 340;
    // if ($(window).width() <= 1024) {
    //   years = 0;
    // } else {
    //   years = 340;
    // }
    // var swiper = new Swiper("#section1-swiper-container", {
    //   speed: 600,
    //   slidesPerView: 3,
    //   spaceBetween: 340,
    //   centeredSlides: true,
    //   navigation: {
    //     nextEl: "#section1-swiper-button-next",
    //     prevEl: "#section1-swiper-button-prev",
    //   }
    // });

    $('.swiper-wrapper').slick({
      centerMode: true,
      slidesToShow: 3,
      nextArrow: '#section1-swiper-button-next',
      prevArrow: '#section1-swiper-button-prev',
      responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          nextArrow: '#section1-swiper-button-next',
          prevArrow: '#section1-swiper-button-prev',
        }
      }]
    });

  });
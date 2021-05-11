$(function() {
      $("#scroll-button").click(function() {
        $("html, body").animate({
          scrollTop: $("#section1").offset().top - navHeight
        }, 400);
      });
      $("#section1-list").find(".item").click(function() {
        var index = $(this).index();
        $("#section1-list").find(".item.active").removeClass("active");
        $("#section1-list").find(".item").eq(index).addClass("active");
        $("#section1-img-list").find(".img-list__item.active").removeClass("active");
        $("#section1-img-list").find(".img-list__item").eq(index).addClass("active");
        $("#section1-text-list").find("h1.active").removeClass("active");
        $("#section1-text-list").find("h1").eq(index).addClass("active");
      });
      $("#section2-list").find(".item").click(function() {
        var index = $(this).index();
        $("#section2-list").find(".item.active").removeClass("active");
        $("#section2-list").find(".item").eq(index).addClass("active");
        $("#section2-img-list").find(".img-list__item.active").removeClass("active");
        $("#section2-img-list").find(".img-list__item").eq(index).addClass("active");
        $("#section2-text-list").find("h1.active").removeClass("active");
        $("#section2-text-list").find("h1").eq(index).addClass("active");
      });

      if ($(window).width() > 1024) {
        $("#section1-text-list h1").eq(1).css("margin-left", "120px");
        $("#section1-text-list h1").eq(2).css("margin-left", "240px");
        $("#section1-text-list h1").eq(3).css("margin-left", "360px");

        $("#section2-text-list h1").eq(1).css("margin-left", "120px");
        $("#section2-text-list h1").eq(2).css("margin-left", "240px");
        $("#section2-text-list h1").eq(3).css("margin-left", "360px");
        $("#section2-text-list h1").eq(4).css("margin-left", "480px");
      } else {
        $('#section1 .img-wrap-m .slick').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: true,
          arrows: true,
          autoplay: true,
        });

        $('.section2 .img-wrap-m .slick').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: true,
          arrows: true,
          autoplay: true,
        });
      }
      var section1_currently = $('#section1 .img-wrap-m .slick-dots li.slick-active button').text();
      var section1_total = $('#section1 .img-wrap-m .slick-dots li').size();
      $('#section1 .img-wrap-m .digital').text("0" + section1_currently + "/" + "0" + section1_total);

      $('#section1 .img-wrap-m .slick').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var section1_total = $('#section1 .img-wrap-m .slick-dots li').size();
        $('#section1 .img-wrap-m .digital').text("0" + (nextSlide + 1) + "/" + "0" + section1_total);
      });

      var section2_currently = $('.section2 .img-wrap-m .slick-dots li.slick-active button').text();
      var section2_total = $('.section2 .img-wrap-m .slick-dots li').size();
      $('.section2 .img-wrap-m .digital').text("0" + section2_currently + "/" + "0" + section2_total);

      $('.section2 .img-wrap-m .slick').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var section2_total = $('.section2 .img-wrap-m .slick-dots li').size();
        $('.section2 .img-wrap-m .digital').text("0" + (nextSlide + 1) + "/" + "0" + section2_total);
      });

    });
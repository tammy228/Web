
    $(function() {
      $("#scroll-button").click(function() {
        $("html, body").animate({
          scrollTop: $("#section1-content-wrap").offset().top - navHeight
        }, 400);
      });
      $("#section2-slick").slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: false,
        arrows: false,
        focusOnSelect: true,
        asNavFor: '#section2-slick-windows .text_slick,#section2-slick-windows .slick,#section2-slick-windows .text #digital .one',
        responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: true
          }
        }]
      });

      $("#section2-slick-windows .slick").slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        prevArrow: "<div class='prev'></div>",
        nextArrow: "<div class='next'></div>",
        asNavFor: '#section2-slick,#section2-slick-windows .text_slick,#section2-slick-windows .text #digital .one'
      });

      $("#section2-slick-windows .text_slick").slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        prevArrow: "#section2-slick-windows .text .text_arrow .next",
        nextArrow: "#section2-slick-windows .text .text_arrow .prev",
        asNavFor: '#section2-slick,#section2-slick-windows .slick,#section2-slick-windows .text #digital .one'
      });

      $('.section3__award-wrap-m .slick').slick({
        arrows: true,
        dots: true
      });

      var Quantity = $("#section2-slick-windows .text_slick .item").size();
      var zero = "";
      if (Quantity > 10) {
        zero = ""
      } else {
        zero = "0"
      }

      var Current = $('#section2-slick-windows .slick .slick-dots li.slick-active button').text();
      $("#section2-slick-windows .text #digital span").text(zero + Current + "/" + zero + Quantity);

      $("#section2-slick-windows .text .text_arrow .prev span").text($(".text_slick").find(".item h1").eq(1).text());

      $("#section2-slick-windows .text .text_arrow .next span").text($(".text_slick").find(".item h1").eq(Quantity - 1).text());

      /*手機板*/
      var section2_currently = $('.section2-slick-wrap .slick-dots li.slick-active button').text();
      var section2_total = $('.section2-slick-wrap .slick-dots li').size();
      $('#section2-slick_number').text("0" + section2_currently + "/" + "0" + section2_total);

      var section3_m_currently = $('.section3__award-wrap-m .slick-dots li.slick-active button').text();
      var section3_m_total = $('.section3__award-wrap-m .slick-dots li').size();
      $('.section3__award-wrap-m .digital').text("0" + section3_m_currently + "/" + "0" + section3_m_total);
      /*手機板*/

      $('#section2-slick-windows .text_slick,#section2-slick,.section3__award-wrap-m .slick').on('beforeChange', function(event, slick, currentSlide, nextSlide) {

        $("#section2-slick-windows .text #digital span").text(zero + (nextSlide + 1) + "/" + zero + Quantity);
        var after = $(".text_slick").find(".item h1").eq(nextSlide + 1).text();
        var before = $(".text_slick").find(".item h1").eq(nextSlide - 1).text();

        if (nextSlide == $("#section2-slick-windows .slick-track").size()) {
          after = $(".text_slick").find(".item h1").eq(0).text();
          $("#section2-slick-windows .text .text_arrow .prev span").text(after);
          $("#section2-slick-windows .text .text_arrow .next span").text(before);
        } else {
          after = $(".text_slick").find(".item h1").eq(nextSlide + 1).text();
          $("#section2-slick-windows .text .text_arrow .prev span").text(after);
          $("#section2-slick-windows .text .text_arrow .next span").text(before);
        }

        /*手機板*/
        if ($(window).width() <= 1024) {
          var section2_total = $('.section2-slick-wrap .slick-dots li').size();
          $('#section2-slick_number').text("0" + (nextSlide + 1) + "/" + "0" + section2_total);

          var section3_m_total = $('.section3__award-wrap-m .slick-dots li').size();
          $('.section3__award-wrap-m .digital').text("0" + (nextSlide + 1) + "/" + "0" + section3_m_total);
        }
        /*手機板*/
      });


      $("#section2-slick .section2-slick__item").click(function() {
        $("#section2-slick-windows").addClass("active");
        $("#section2-slick-windows .slick").addClass("active");
        $("#section2-slick-windows .text").addClass("active");
      });

      $("#section2-slick-windows .modal-close-button").click(function() {
        $("#section2-slick-windows").removeClass("active");
        $("#section2-slick-windows .slick").removeClass("active");
        $("#section2-slick-windows .text").removeClass("active");
      });

    });

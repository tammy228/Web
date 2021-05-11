$(function() {
      $("#scroll-button").click(function() {
        $("html, body").animate({
          scrollTop: $("#section1").offset().top - navHeight
        }, 400);
      });
    });
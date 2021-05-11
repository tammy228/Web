var navHeight = 70;
var hT, hH, wW, wH, wS, bagHeight, dt;
var tl = new TimelineMax(),
  SMController = new ScrollMagic.Controller(),
  bagElement,
  bagAmount = 0,
  bagAnimationArr = [],
  section2CertificationY,
  section3ListWidth;
var windowWidth = 'innerWidth' in window ?
  window.innerWidth :
  document.documentElement.clientWidth; //ie8以下
$(function () {
  if (windowWidth < 1920) {
    section2CertificationY = 340;
    section3ListWidth = "70%";
  } else {
    section2CertificationY = 460;
    section3ListWidth = "75%";
  }
  bagElement = $("#main-bag");
  bagElement.find(".bag-image").eq(0).addClass("active");
  bagAmount = bagElement.find(".bag-image").length - 1;
  for (var i = 0; i <= 3; i = i + 2) {
    bagAnimationArr.push({
      // 元素本身
      element: $("#section2-column" + (i + 1)),
      // 元素已經滾動的距離（從元素出現在viewport開始）
      scrollTop: 0,
      // 元素的高度
      height: 0,
      // 動畫的幀間隔
      delta: 0,
      offsetTop: 0
    });
    var offsetTop2 = $("#section2-column" + (i + 2)).offset().top;
    var nowObject = bagAnimationArr[bagAnimationArr.length - 1];
    nowObject.offsetTop = nowObject.element.offset().top;
    nowObject.height = nowObject.element.height() + (offsetTop2 - nowObject.offsetTop - nowObject.element.height());
    nowObject.delta = nowObject.height / (bagAmount * 1);
  }
  bagAnimationArr.push({
    // 元素本身
    element: $("#section2-column4"),
    // 元素已經滾動的距離（從元素出現在viewport開始）
    scrollTop: 0,
    // 元素的高度
    height: 0,
    // 動畫的幀間隔
    delta: 0,
    offsetTop: 0
  });
  var offsetTop2 = $("#section2-column5").offset().top;
  var nowObject = bagAnimationArr[bagAnimationArr.length - 1];
  nowObject.offsetTop = nowObject.element.offset().top;
  nowObject.height = nowObject.element.height() + (offsetTop2 - nowObject.offsetTop - nowObject.element.height());
  nowObject.delta = nowObject.height / (bagAmount * 1);
  $("#header-slick").on("init reInit", function (event, slick, currentSlide, nextSlide) {
    if (slick.slideCount == 0) {
      return;
    }
    // 在init時nextSlide為undefined，因此須設置為0（nextSlide以0為起始）
    var i = (nextSlide ? nextSlide : 0) + 1;
    i = i <= 9 ? "0" + i.toString() : i;
    var len = slick.slideCount;
    len = len <= 9 ? "0" + len.toString() : len;
    // $("#header-slick-count").find(".count").text(i);
    // $("#header-slick-count").find(".amount").text(len);
  });
  $("#header-slick").on("beforeChange", function (event, slick, currentSlide, nextSlide) {
    if (slick.slideCount == 0) {
      return;
    }
    $("#header-slick-count").find(".line").addClass("animation");
    var i = nextSlide + 1;
    i = i <= 9 ? "0" + i.toString() : i;
    var len = slick.slideCount;
    len = len <= 9 ? "0" + len.toString() : len;
    // $("#header-slick-count").find(".count").text(i);
    // $("#header-slick-count").find(".amount").text(len);
  });
  $("#header-slick").on("afterChange", function (event, slick, currentSlide, nextSlide) {
    if (slick.slideCount == 0) {
      return;
    }
    $("#header-slick-count").find(".line").removeClass("animation");
  });
  $("#header-slick").slick({
    arrows: false,
    dots: true,
    autoplay: true,
    speed: 1000,
    autoplaySpeed: 2000,
    easing: "swing",
    fade: true
  });
  $("#section1-slick").on("init reInit beforeChange", function (event, slick, currentSlide, nextSlide) {
    if (slick.slideCount == 0) {
      return;
    }
    // 在init時nextSlide為undefined，因此須設置為0（nextSlide以0為起始）
    var i = (nextSlide ? nextSlide : 0) + 1;
    var len = slick.slideCount;
    $("#section1-content-wrap").find(".item.active").removeClass("active").hide();
    $("#section1-content-wrap").find(".item").eq(i - 1).addClass("active").fadeIn(1000);
    i = i <= 9 ? "0" + i.toString() : i;
    len = len <= 9 ? "0" + len.toString() : len;
    // $("#section1-slick-count").find(".count").text(i);
    // $("#section1-slick-count").find(".amount").text(len);
  });
  $("#section1-slick").slick({
    arrows: false,
    dots: true,
    infinite: false,
    appendDots: $("#main-bag-button"),
    customPaging: function (slide, i) {
      return "<div class='button button" + (i + 1) + "'><div class='button__plus'><div class='button__animation'></div></div></div>";
    }
  });
  /*$("#section2-column1-slick-next-arrow").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("#section2-column-slick2").offset().top - navHeight
    }, 400, function(){
      //startSmoothScroll();
    });
  });*/
  $('#section2-column1-slick-next-arrow').click(function () {
    $('html,body').animate({
      scrollTop: $('#section2-column-slick2').offset().top - navHeight
    }, 400);
  });
  /*$("#section2-column2-slick-prev-arrow").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("#section2-column-slick1").offset().top - navHeight
    }, 400, function(){
      //startSmoothScroll();
    });
  });*/
  $('#section2-column2-slick-prev-arrow').click(function () {
    $('html,body').animate({
      scrollTop: $('#section2-column-slick1').offset().top - navHeight
    }, 400);
  });
  /*$("#section2-column2-slick-next-arrow").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("#section2-column-slick3").offset().top - navHeight
    }, 400, function(){
      startSmoothScroll();
    });
  });*/
  $('#section2-column2-slick-next-arrow').click(function () {
    $('html,body').animate({
      scrollTop: $('#section2-column-slick3').offset().top - navHeight
    }, 400);
  });
  /*$("#section2-column3-slick-prev-arrow").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("#section2-column-slick2").offset().top - navHeight
    }, 400, function(){
      //startSmoothScroll();
    });
  });*/
  $('#section2-column3-slick-prev-arrow').click(function () {
    $('html,body').animate({
      scrollTop: $('#section2-column-slick2').offset().top - navHeight
    }, 400);
  });
  /*$("#section2-column3-slick-next-arrow").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("#section2-column-slick4").offset().top - navHeight
    }, 400, function(){
      //startSmoothScroll();
    });
  });*/
  $('#section2-column3-slick-next-arrow').click(function () {
    $('html,body').animate({
      scrollTop: $('#section2-column-slick4').offset().top - navHeight
    }, 400);
  });
  /*$("#section2-column4-slick-prev-arrow").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("#section2-column-slick3").offset().top - navHeight
    }, 400, function(){
      //startSmoothScroll();
    });
  });*/
  $('#section2-column4-slick-prev-arrow').click(function () {
    $('html,body').animate({
      scrollTop: $('#section2-column-slick3').offset().top - navHeight
    }, 400);
  });
  /*
  $("#header-scroll-button").click(function() {
    stopSmoothScroll();
    $("html, body").animate({
      scrollTop: $("main").offset().top - navHeight
    }, 400, function(){
      //startSmoothScroll();
    });
  });
  if($(".section__title").outerHeight() / 2){
    $('html,body').animate({scrollTop:$('#section1-content').offset().top - navHeight}, 400);
  }
  */
  $('#header-scroll-button').click(function () {
    $('html,body').animate({
      scrollTop: $('main').offset().top - navHeight
    }, 1600);
  });

  tl.fromTo($("#header-slick"), 1.2, {
    opacity: 0
  }, {
    opacity: 1
  }, 0);
  tl.fromTo($("#nav"), 0.6, {
    y: "-100%"
  }, {
    y: "0%"
  }, 0.6);
  tl.fromTo($("#header-company-name"), 0.6, {
    opacity: 0
  }, {
    opacity: 1
  }, 0.6);
  tl.fromTo($("#header-news"), 0.6, {
    y: "100%"
  }, {
    y: "0%"
  }, 0.6);
  tl.fromTo($("#header-scorll"), 0.6, {
    y: "100%"
  }, {
    y: "0%"
  }, 0.6);
  $(document).mousewheel(function (event, delta) {
    dt = delta;
  });
  $(window).scroll(function () {
    wS = $(this).scrollTop();
    // section1在viewport正中央時的上下間距
    // var vd = (hH - wH + navHeight) / 2;
    // section1的抵達viewport的中央位置（考慮bag的高度）
    // (wS + navHeight) >= (hT + vd)
    //$("#test").text("hT:"+hT+"hH:"+hH+"wH:"+wH+"wS:"+wS+"bH:"+bagHeight+"dt:"+dt);
    if (wS < $("#section1-content").offset().top - wH || wS > $("#section2-column5").offset().top - wH) {
      if (bagElement.hasClass("active")) {
        bagElement.removeClass("active");
      }
    } else if (wS > $("#section1-content").offset().top - wH) {
      // section1出現在viewport（考慮bag的高度）
      if (!bagElement.hasClass("active")) {
        bagElement.addClass("active");
        TweenMax.set(bagElement, {
          opacity: 0,
          y: -100
        });
        setBagPosition();
        TweenMax.to(bagElement, 0.2, {
          opacity: 1,
          y: 0
        });
      }
    }
    if (wW < 1000) {
      if (wS < $("#section2").offset().top + 80 && wS > $("#section2").offset().top - 80 && dt > 0) {
        if (bagElement.hasClass("active")) {
          bagElement.removeClass("active");
          bagElement.css("position", "absolute");
          bagElement.css("margin-top", "-70px");
          $(".main__bag").css("top", "-120px");
        }
      }
      if (wS < $("#section2").offset().top - 80 && dt > 0) {
        if (!bagElement.hasClass("active")) {
          bagElement.addClass("active");
          bagElement.css("margin-top", "-120px");
        }
      }
    }
    for (var i = 0; i <= (bagAnimationArr.length - 1); i++) {
      bagAnimationArr[i].scrollTop = (wS + wH) - bagAnimationArr[i].offsetTop;
      if (bagAnimationArr[i].scrollTop >= 0) {
        var index = Math.floor(bagAnimationArr[i].scrollTop / bagAnimationArr[i].delta);
        if (index <= bagAmount) {
          bagElement.find(".bag-image.active").removeClass("active");
          bagElement.find(".bag-image").eq(index).addClass("active");
          $(".main__bag").css("top", "-70px");
        }
      }
    }
  });
  setBagPosition();

  var bodyClass = document.body.classList,

    lastScrollY = 0;

  /*if ($(window).width() < 1024) {
    bagElement.css("position", "absolute");
    bagElement.hide();
  }*/

  $("#section1-content").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        triggerHook: 0.5,
        duration: 0,
        offset: ($("#section1-content").outerHeight() / 0)
      })
      .on("enter", function (e) {
        //自動滾動到第二頁
        if (wW > 1024) {
          bagElement.css("position", "fixed");
        } else {
          bagElement.css("position", "absolute");
          bagElement.css("margin-top", "-120px");
          $(".main__bag").css("top", "-125px");
          bagElement.fadeIn();
        }
      })
      // .on("leave", function(e) {
      //   if ($(window).width() > 1024) {
      //     $("html,body").stop();
      //     bagElement.css("position", "absolute");
      //     bagElement.css("margin-top", "-120px");
      //     $(".main__bag").css("top", "-180px");
      //     bagElement.fadeIn();
      //   }
      // })
      .addTo(SMController);
  });
  // $("#section2").each(function() {
  //   new ScrollMagic.Scene({
  //       triggerElement: this,
  //       triggerHook: 0.5,
  //       duration: 0,
  //       offset: $("#section2").outerHeight()
  //     })
  //     .on("enter", function(e) {

  //       window.addEventListener('scroll', function() {
  //         var st = this.scrollY;
  //         // 判斷是向上捲動，而且捲軸超過 200px
  //         if (st < lastScrollY) {
  //           bagElement.fadeOut();
  //         } else {
  //           bagElement.fadeIn();
  //         }
  //         lastScrollY = st;
  //       });

  //     })
  //     .addTo(SMController);
  // });
  $("#section2-title").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        triggerHook: 0.5,
        duration: 0,
        offset: $("#section2-title").outerHeight()
      })
      .on("enter", function (e) {
        bagElement.css("position", "fixed");

        /*if ($(window).width() < 1024) {

          window.addEventListener('scroll', function() {
            var st = this.scrollY;
            // 判斷是向上捲動，而且捲軸超過 200px
            if (st < lastScrollY) {
              //上
              bagElement.css("position", "absolute");
              bagElement.css("margin-top", "-120px");
              $(".main__bag").css("top", "-180px");
            } else {
              bagElement.css("position", "fixed");
              bagElement.css("margin-top", "-120px");
              $(".main__bag").css("top", "-180px");
              //$(".main__bag").css("top", "0");
            }
            lastScrollY = st;
          });

        }*/
      })
      .on("leave", function (e) {
        /*if ($(window).width() < 1024) {
          bagElement.fadeIn();
        }*/
      })
      .addTo(SMController);
  });
  $("#section3-list").find(".content").each(function () {
    new ScrollMagic.Scene({
      triggerElement: this,
      triggerHook: "onEnter",
      reverse: true
    }).setTween(
      TweenMax.fromTo(this, 0.6, {
        css: {
          width: "0%"
        }
      }, {
        css: {
          width: section3ListWidth
        },
        ease: Power2.easeOut
      })
    ).addTo(SMController);
  });
  $("#section2-certification-back").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        offset: 300
      })
      .on("enter", function (e) {
        $("#section2-certification-list").addClass("active");
      })
      .on("leave", function (e) {
        $("#section2-certification-list").removeClass("active");
      })
      .addTo(SMController);
  });
  $("#section2-certification-back").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        duration: 300,
        offset: 300
      })
      .setTween($("#section2-certification-list").find("li").eq(0), {
        x: "100%"
      })
      .addTo(SMController);
  });
  $("#section2-certification-back").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        duration: 300,
        offset: 300
      })
      .setTween($("#section2-certification-list").find("li").eq(2), {
        x: "-100%"
      })
      .addTo(SMController);
  });
  $("#section2-certification-bag").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        duration: 300
      })
      .setTween($("#section2-certification-list"), {
        y: section2CertificationY
      })
      .addTo(SMController);
  });
  if ($(window).width() > 1024) {
    $("#section1-content").each(function () {
      new ScrollMagic.Scene({
          triggerElement: this,
          reverse: true,
          duration: 500
        })
        .on("enter", function (e) {
          bagElement.find(".bag-image.active").removeClass("active");
          bagElement.find(".bag-image").eq(0).addClass("active");
          bagElement.removeClass("hide-button");
        })
        .setTween(TweenMax.fromTo($("#section-back-text1"), {
          x: "100%"
        }, {
          x: "0%"
        }))

        .addTo(SMController);
    });
  } else {
    $("#section1-content").each(function () {
      new ScrollMagic.Scene({
          triggerElement: this,
          reverse: true,
          duration: 500
        })
        .on("enter", function (e) {
          bagElement.find(".bag-image.active").removeClass("active");
          bagElement.find(".bag-image").eq(0).addClass("active");
          bagElement.removeClass("hide-button");
        })
        .setTween(TweenMax.fromTo($("#section-back-text1"), {
          x: "100%",
          y: "15%"
        }, {
          x: "0%",
          y: "15%"
        }))

        .addTo(SMController);
    });
  }
  $("#section2").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true
      })
      .on("enter", function (e) {
        bagElement.addClass("hide-button");
      })
      .addTo(SMController);
  });
  $("#section-back-text2").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        duration: 500
      })
      .setTween(TweenMax.fromTo($("#section-back-text2"), {
        x: "100%"
      }, {
        x: "0%"
      }))
      .addTo(SMController);
  });
  $("#section-back-text3").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        duration: 500
      })
      .setTween(TweenMax.fromTo($("#section-back-text3"), {
        x: "100%"
      }, {
        x: "0%"
      }))
      .addTo(SMController);
  });


  $("#section2-column4").each(function () {
    new ScrollMagic.Scene({
        triggerElement: this,
        reverse: true,
        offset: 300
      })
      .on("enter", function (e) {
        // bagElement.css("position", "absolute");
        /*if ($(window).width() < 1024) {

          bagElement.fadeOut();
          bagElement.css("margin-top", "-120px");

        }*/
      })
      .addTo(SMController);
  });


  $(window).resize(function () {
    windowWidth = 'innerWidth' in window ?
      window.innerWidth :
      document.documentElement.clientWidth; //ie8以下
    if (windowWidth < 1920) {
      section2CertificationY = 410;
      section3ListWidth = "70%";
    } else {
      section2CertificationY = 450;
      section3ListWidth = "75%";
    }
  });
  // $("footer,#section2-title").hide() ;
});
window.onload = function () {
  hT = $("#section1-content").offset().top;
  hH = $("#section1-content").height();
  wW = $(window).width();
  wH = $(window).height() * 0.5;
  bagHeight = bagElement.find("img.active").height();
}

function setBagPosition() {
  var w = (bagElement.find(".bag-image").eq(0).width() / 2) * -1;
  var h = (bagElement.find(".bag-image").eq(0).height() / 2) * -1;
  bagElement.css("margin-left", w);
  bagElement.css("margin-top", "-145px");
}
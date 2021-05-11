var navHeight = 70;
var smoothScroll = {};
// requestAnimationFrame cross browser
var requestFrame = function () {
  return (
    window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    window.oRequestAnimationFrame ||
    window.msRequestAnimationFrame ||
    function (func) {
      window.setTimeout(func, 1000 / 50);
    }
  );
}();
$(function () {

  // $('html,body').animate({
  //   scrollTop: $('#section1-content').offset().top - 70
  // }, 1500);

  var home_slider = false;

  // console.log(home_slider)

  if ($(window).width() > 1024) {
    $("body").niceScroll({
      zindex: 9999,
      smoothscroll: true, //平滑移動
      // scrollspeed: 136,//速度
      // mousescrollstep: 36,//移動步數
      cursorborder: "0"
    });
  }

  //startSmoothScroll();
  $(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
      $("body").addClass("scrolling");
    } else {
      $("body").removeClass("scrolling");
    }
  });

  $('#top-button').click(function () {
    $('html,body').animate({
      scrollTop: '0px'
    }, 600);
  });

  $("#scroll-button").click(function () {
    $("html, body").animate({
      scrollTop: $("#section1").offset().top - navHeight
    }, 400);
  });

  var footerVideo = document.getElementById("footer-video");
  footerVideo.play();

  $("#m_meun").click(function () {
    $("nav").toggleClass("active");
  });
});

function startSmoothScroll() {
  // cross browser support for document scrolling
  smoothScroll.target = (document.scrollingElement ||
    document.documentElement ||
    document.body.parentNode ||
    document.body);
  smoothScroll.speed = 60;
  smoothScroll.smooth = 12;
  smoothScroll.object = new SmoothScroll();
}

function SmoothScroll() {
  smoothScroll.moving = false;
  smoothScroll.pos = smoothScroll.target.scrollTop;
  smoothScroll.frame = smoothScroll.target === document.body &&
    document.documentElement ?
    document.documentElement :
    smoothScroll.target; // safari is the new IE

  smoothScroll.target.addEventListener("mousewheel", scrolled, {
    passive: false
  });
  smoothScroll.target.addEventListener("DOMMouseScroll", scrolled, {
    passive: false
  });
}

function scrolled(e) {
  // disable default scrolling
  e.preventDefault();
  var delta = normalizeWheelDelta(e);
  smoothScroll.pos += -delta * smoothScroll.speed;
  // limit scrolling
  smoothScroll.pos = Math.max(0, Math.min(smoothScroll.pos, smoothScroll.target.scrollHeight - smoothScroll.frame.clientHeight));
  if (!smoothScroll.moving) update()
}

function normalizeWheelDelta(e) {
  if (e.detail) {
    if (e.wheelDelta) {
      // Opera
      return e.wheelDelta / e.detail / 40 * (e.detail > 0 ? 1 : -1);
    } else {
      // Firefox
      return -e.detail / 3;
    }
  } else {
    // IE,Safari,Chrome
    return e.wheelDelta / 120;
  }
}

function update() {
  if (typeof smoothScroll.target !== "undefined") {
    smoothScroll.moving = true;
    var delta = (smoothScroll.pos - smoothScroll.target.scrollTop) / smoothScroll.smooth;
    smoothScroll.target.scrollTop += delta;
    if (Math.abs(delta) > 0.5) {
      requestFrame(update);
    } else {
      smoothScroll.moving = false;
    }
  }
}

function stopSmoothScroll() {
  smoothScroll.target.removeEventListener("mousewheel", scrolled)
  smoothScroll.target.removeEventListener("DOMMouseScroll", scrolled)
  delete smoothScroll.target;
  delete smoothScroll.speed;
  delete smoothScroll.smooth;
  delete smoothScroll.object;
  delete smoothScroll.moving;
  delete smoothScroll.pos;
  delete smoothScroll.frame;
  smoothScroll = {};
}


if ($("main").hasClass("about")) {
  $(".meun_about").addClass("active")
} else if ($("main").hasClass("advantages")) {
  $(".meun_advantages").addClass("active")
} else if ($("main").hasClass("certificates")) {
  $(".meun_certificates").addClass("active")
} else if ($("main").hasClass("contact")) {
  $(".meun_contact").addClass("active")
} else if ($("main").hasClass("news")) {
  $(".meun_news").addClass("active")
} else if ($("main").hasClass("news_detail")) {
  $(".meun_news_detail").addClass("active")
} else if ($("main").hasClass("products")) {
  $(".meun_products").addClass("active")
} else if ($("main").hasClass("equipment")) {
  $(".meun_equipment").addClass("active")
}



if($("main").hasClass("index")){
  $("nav").removeClass("nav2");
  $(".nav__logo.nav__logo2").remove();
}else{
  $("nav").addClass("nav2")
  $(".nav__logo.nav__logo1").remove();
}
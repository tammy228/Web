
    var modalElement,
      modalIndex,
      modalLength,
      modalContentArr,
      modalSlickSettings,
      modalImageElement,
      levelColumnElement,
      levelArrowElement,
      levelCircleSize,
      levelDrag,
      levelIndex;
    $(function() {
      $('#top-button').click(function() {
        $('html,body').animate({
          scrollTop: '0px'
        }, 600);
      });

      modalIndex = 0;
      modalSlickSettings = {
        arrows: true,
        dots: true,
        infinite: false
      };
      levelIndex = 0;
      levelColumnElement = $("#section2-level-text").find(".column");
      levelArrowElement = $("#section2-level-arrow");
      levelCircleSize = [17, 43];
      initLevel();
      $("#scroll-button").click(function() {
        $("html, body").animate({
          scrollTop: $("#section1").offset().top - navHeight
        }, 400);
      });
      $("#section2-slick").on("init reInit beforeChange", function(event, slick, currentSlide, nextSlide) {
        if (slick.slideCount == 0) {
          return;
        }
        // 在init時nextSlide為undefined，因此須設置為0（nextSlide以0為起始）
        var i = (nextSlide ? nextSlide : 0) + 1;
        levelArrowElement.css("left", (levelDrag.startX + ((i - 1) * levelDrag.distance)) + "px");
        $("#section2-level-title").find("h1").text(levelColumnElement.eq(i - 1).find("h1").text());
        $("#section2-level-title").find("h2").text(levelColumnElement.eq(i - 1).find("h2").text());
        i = i <= 9 ? "0" + i.toString() : i;
        var len = slick.slideCount;
        len = len <= 9 ? "0" + len.toString() : len;
        $("#section2-slick-count").find(".count").text(i);
        $("#section2-slick-count").find(".amount").text(len);
        $("#section2-level-number").find(".item").text(i);

      });
      $("#section2-slick").slick({
        dots: true,
        arrow: true,
        infinite: false,
        fade: true,
        responsive: [{
          breakpoint: 1024,
          settings: {
            infinite: true,
            dots: false,
          }
        }]
      });
      
      $("#section2-slick .slick-dots").width($("#section2-level-wrap .line-row-inner").width());
      $("#section2-slick .slick-dots").css("display", "flex");

      levelArrowElement.draggable({
        axis: "x", //只限定在x軸移動
        containment: "parent", // 拖動區域
        stop: function(event, ui) {
          modalIndex = Math.ceil(ui.position.left / levelDrag.distance) - 1;
          levelIndex = Math.ceil(ui.position.left / levelDrag.distance) - 1;
          $("#section2-slick").slick("slickGoTo", modalIndex);
        },
        drag: function(event, ui) {
          if (ui.position.left < levelDrag.startX) {
            ui.position.left = levelDrag.startX;
          } else if (ui.position.left > levelDrag.endX) {
            ui.position.left = levelDrag.endX;
          }
        }
      });
      $(".modal-close-button").click(function() {
        modalElement.fadeOut();
        $("html").removeClass("modal-open");
        modalElement.find(".modal-content-wrap").removeClass("show-animation");
        modalElement.find(".modal-img-wrap").removeClass("show-animation");
      });
      $(".modal-next-arrow").click(function() {
        modalIndex++;
        if (modalIndex > (modalLength - 1)) {
          modalIndex = 0;
        }
        setModal();
        setModalSlick();
      });
      $(".modal-prev-arrow").click(function() {
        modalIndex--;
        if (modalIndex < 0) {
          modalIndex = modalLength - 1;
        }
        setModal();
        setModalSlick();
      });
      // 製袋各特點的modal
      // if ($(window).width() > 1024) {
      //   $("#section1-message-list").find(".item").click(function() {
      //     modalElement = $("#feature-modal");
      //     modalIndex = $(this).closest("li").index();
      //     openModal();
      //     setModal();
      //   });
      // }
      // 產品各尺寸的modal
      $("#section2-slick").find(".link-text").click(function() {
        modalElement = $("#size-modal");
        modalIndex = $(this).closest(".item").index();
        openModal();
        setModal();
      });
      // 產品各分類的modal
      $("#section5-img-list").find(".item").click(function() {
        modalElement = $("#category-modal");
        modalIndex = $(this).index();
        openModal();
        setModal();
        $(".share-link-button").click(function() {
          var url = window.location.href;
          $("#metaOgUrl").attr("content", url);
          var bg = modalImageElement.find(".modal-img").eq(0).css("background-image");
          bg = bg.replace("url(", "").replace(")", "");
          $("#metaOgImage").attr("content", bg);
          $("#metaOgDescription").attr("content", modalContentArr.eq(modalIndex).find(".page-name").text());
          FB.ui({
            method: "share",
            href: url,
          }, function(response) {});
        });
        $(window).resize(function() {
          initLevel();
          if (levelIndex == 0) {
            levelArrowElement.css("left", levelDrag.startX + "px");
          } else if (levelIndex == (levelColumnElement.length - 1)) {
            levelArrowElement.css("left", levelDrag.endX + "px");
          } else {
            levelArrowElement.css("left", (levelDrag.startX + ((levelIndex) * levelDrag.distance)) + "px");
          }
        });
      });
      //手機板輪播
      if ($(window).width() <= 1024) {
        $(".section1__bag-list").slick({
          dots: false,
          arrows: false,
          infinite: false,
          slidesToShow: 3,
          responsive: [{
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              infinite: true,
              asNavFor: '#section1-message-list'
            }
          }]
        });
        $("#section1-message-list").slick({
          dots: false,
          arrows: false,
          infinite: false,
          slidesToShow: 3,
          responsive: [{
            breakpoint: 1024,
            settings: {
              slidesToShow: 1,
              arrows: true,
              infinite: true,
              dots: true,
              asNavFor: '.section1__bag-list'
            }
          }]
        });
      }

      var currently = $('#section1-message-list .slick-dots li.slick-active button').text();
      var total = $('#section1-message-list .slick-dots li').size();
      $('#section1 .digital').text("0" + currently + "/" + "0" + total);

      $('#section1-message-list').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var total = $('#section1-message-list .slick-dots li').size();
        $('#section1 .digital').text("0" + (nextSlide + 1) + "/" + "0" + total);
      });

    });

    function initLevel() {
      levelDrag = {
        distance: levelColumnElement.width(),
        startX: 0,
        endX: 0
      };
      levelDrag.startX = Math.floor((levelDrag.distance - levelArrowElement.width()) / 2);
      levelDrag.endX = levelDrag.distance * (levelColumnElement.length - 1) + levelDrag.startX;
      $("#section2-level-line").css("left", Math.floor((levelDrag.distance - levelCircleSize[0]) / 2) + levelCircleSize[0]);
      $("#section2-level-line").css("right", Math.floor((levelDrag.distance - levelCircleSize[1]) / 2) + levelCircleSize[1]);
    }

    function openModal() {
      modalContentArr = modalElement.find(".modal-content-inner");
      modalLength = modalContentArr.length;
      modalElement.fadeIn();
      $("html").addClass("modal-open");
      modalElement.find(".modal-content-wrap").addClass("show-animation");
      setTimeout(function() {
        modalElement.find(".modal-img-wrap").addClass("show-animation");
        setModalSlick();
      }, 600);
    }

    function setModal() {
      modalElement.find(".modal-sub-title").text(("0" + (modalIndex + 1)).slice(-2) + "/" + ("0" + modalLength).slice(-2));

      $("#next-text").text("Bag 0" + (modalIndex + 1));
      $("#prev-text").text("Bag 0" + modalLength);

      modalContentArr.hide().eq(modalIndex).fadeIn(600);
      if (modalIndex < (modalLength - 1)) {
        modalElement.find(".modal-next-arrow").find(".text").text(modalContentArr.eq(modalIndex + 1).find(".page-name").text());
      } else {
        modalElement.find(".modal-next-arrow").find(".text").text(modalContentArr.eq(0).find(".page-name").text());
      }
      if (modalIndex > 0) {
        modalElement.find(".modal-prev-arrow").find(".text").text(modalContentArr.eq(modalIndex - 1).find(".page-name").text());
      } else {
        modalElement.find(".modal-prev-arrow").find(".text").text(modalContentArr.eq(modalLength - 1).find(".page-name").text());
      }
      if (modalIndex < (modalLength - 1)) {
        modalElement.find(".modal-next-arrow").find("#next-text.text").text("Bag 0" + (modalIndex + 2));
        modalElement.find(".modal-next-arrow").find("#next-text.text").css("opacity", 0.5)
      } else {
        modalElement.find(".modal-next-arrow").find("#next-text.text").text("Bag 0" + (modalLength - modalLength + 1));
      }
      if (modalIndex > 0) {
        modalElement.find(".modal-prev-arrow").find("#prev-text.text").text("Bag " + ("0" + (modalIndex)).slice(-2));
      } else {
        modalElement.find(".modal-prev-arrow").find("#prev-text.text").text("Bag 0" + (modalLength));
        modalElement.find(".modal-prev-arrow").find("#prev-text.text").css("opacity", 0.5)
      }
    }

    function setModalSlick() {
      modalImageElement = modalElement.find(".modal-img-slick").eq(modalIndex);
      modalElement.find(".modal-img-slick").hide();
      modalImageElement.show();
      if (modalImageElement.find(".modal-img").length > 1) {
        if (modalImageElement.hasClass("slick-initialized")) {
          modalImageElement.slick("unslick").slick(modalSlickSettings);
        } else {
          modalImageElement.slick(modalSlickSettings);
        }
      }

    }
 
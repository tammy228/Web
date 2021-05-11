$(function() {
    // nav
    //漢堡選單選取
    $('#bugermenu').click(function (e) { 
        e.preventDefault();
        $('#bugermenuSide').toggleClass('active')
    });
    var w =$(window).width();
    
    // nav scroll
    function navScroll() {
        var nav_scrollTop = parseInt($('html').scrollTop());
        console.log(nav_scrollTop)
        if(w>1279) {
            if (nav_scrollTop>800) {
                $('.nav_1').addClass('showA');
            } else {
                $('.nav_1').removeClass('showA');
            } 
            if (nav_scrollTop>0) {
                $('.nav_2').addClass('showA');
                $('.nav_3').addClass('showB');
                $('.nav_4').addClass('showC');
            } else {
                $('.nav_2').removeClass('showA');
                $('.nav_3').removeClass('showB');
                $('.nav_4').removeClass('showC');
            } 
            if($('.nav_2').hasClass('showA')) {
                $('.about, .material').css('padding-top','168px')
            } else {
                $('.about, .material').css('padding-top','0')
            }
        } else {
            $('.about, .material').css('padding-top','0')
        }
    }
    $(window).resize(function() {
        w =$(window).width();

        navScroll()

    });

    // nav scroll
    $(window).scroll(function() {
        navScroll()
    });

    $('.connection').on('click', function(e) {
        e.preventDefault()
      
        $('html, body').animate(
          {
            scrollTop: $($(this).attr('href')).offset().top,
          },
          500,
          'linear'
        )
    })
    $('.connection_home').on('click', function(e) {
        e.preventDefault()
      
        $('html, body').animate(
          {
            scrollTop: $($(this).attr('href')).offset().top,
          },
          500,
          'linear'
        )
    })


})
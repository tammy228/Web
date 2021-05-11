$(document).ready(function () {

    // loading
    $('body').waitForImages({
        finished: function(){
            setTimeout(function(){
                $(".main_home").removeClass('active');
                $("main").removeClass('activePage');
            }, 800);
            setTimeout(function(){
                $('#loadingPage').addClass('close');
            }, 1000);
        },
        waitForAll: true
    });
    
    $(window).scroll(function() {
        var top = $(window).scrollTop();
        if(top <= 0) {
            $('#header').removeClass('rolling');
        }else {
            $('#header').addClass('rolling');
        }
    })
    $(document).click(function(){
        $("#logIn").removeClass("active");
        $('.connection').removeClass('active');
        $(".shopcart").removeClass("active");
        $("#forgetPasswordLink").removeClass("active");
    });
    $("#hamOpenBtn").click(function (e) { 
        $("#hamburgerList").addClass("active");
    })
    $("#hamDeleteBtn").click(function () { 
        $("#hamburgerList").removeClass("active");
    })
    $("#buyOpenBtn").click(function (e) { 
        toggle(e,".shopcart","#hamburgerList");
    })
    //會員專區 未登入 手機
    $('#loginStatus-before-id').click(function(e) { 
        toggle(e,".member-logIn","#hamburgerList");
    });
     //會員專區 未登入 pc
    $('#loginStatusPc-before-id').click(function (e) { 
        e.stopPropagation();
        $('.member-logIn').addClass('active');
    });
     //會員專區 未登入 footer
    $('#loginStatusFt-before-id').click(function (e) { 
        e.stopPropagation();
        $('.member-logIn').addClass('active');
    });
    //登入後點選會員專區 pc
    $('#loginStatusPc-after-id').click(function (e) { 
        e.stopPropagation();
        $('.memberZonePc').toggleClass('active');
        $('#header').toggleClass('header-white');
    });
    //登入後點選會員專區 footer
    $('#loginStatusFt-after-id').click(function (e) { 
        e.stopPropagation();
        $('#memberZoneFt-id').toggleClass('active');
    });
    //聯絡我們
    $('#hamcontact-id, #ftContact-id').click(function (e) { 
        toggle(e,".connection","#hamburgerList");
    });
    $("#logIn ul, .connection .wrap, .shopcart, #forgetPasswordLink ul").click(function(e){
        e.stopPropagation();
    });
    //登入頁忘記密碼
    $("#forgetPassword_link").click(function (e) { 
        toggle(e,"#forgetPasswordLink",".member-logIn");
    });
    //會員專區已登入
    $('#signIn_btn-id').click(function () { 
        $('#loginStatusPc-before-id').removeClass('active')
        $('#loginStatusPc-after-id').addClass('active')
    });
    $('.logOut').click(function () { 
        $('#loginStatusPc-after-id').removeClass('active')
        $('#loginStatusPc-before-id').addClass('active')
    });
    //手機會員專區
    $('#loginStatus-after-id #loginStatus-text').click(function (e) { 
        e.preventDefault();
        $('#loginStatus-after-id').toggleClass('show-memberZone');
    });
    function toggle(event,obj1,obj2) {
        event.stopPropagation();
        $(obj1).addClass('active');
        $(obj2).removeClass("active");
    }
})
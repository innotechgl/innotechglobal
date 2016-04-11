$(document).ready(function () {

    var pages = $('.pages .page').length - 1;
    var current = 0;
    var height = window.innerHeight;
    var allowScrollAction = true;

    console.log('height', height);

    function resize() {
        height = window.innerHeight;
        $('.pages .page .banners, .pages .page .banners .banner').css("min-height", height);
        $($('.pages .page, .pages .page .banners .banner')[0]).css("min-height", height);

        $('.pages .page, .pages .page .banners .banner').each(function (index, element) {
            $(element).css("min-height", height);
        });


        //movePage();
    }

    function nextPage() {


        current = current + 1;
        if (current > pages) {
            current = pages;
        }
        movePage();
    }

    function prevPage() {
        current = current - 1;
        if (current < 0) {
            current = 0;
        }
        movePage();
    }

    function movePage() {

        var p = '';

        p = "translate3d(0," + String((-((height + $('header').height()) * current))) + "px,0)";
        $('header').removeClass('black');

        switch (current) {
            case 0:
                p = "translate3d(0,0,0)";
                break;

            case 3:
                $('header').addClass('black');
                break;
        }


        $('.pages').css({
            "-webkit-transform": p,
            "transform": p,
            "-moz-transform": p,
            "-o-transform": p,
            "-ms-transform": p
        }).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend", function () {

            allowScrollAction = true;


        });
    }

    function mp() {
        $('html,body').animate({
            scrollTop: $(".page.who-are-we").offset().top
        }, 1000);
    }


    resize();

    $(window).resize(function () {

        resize();
    });

    $(window).scroll(function (e) {
        if ($(document).scrollTop() > 80) {
            $('header').addClass('full-color');
        }
        else {
            $('header').removeClass('full-color');
        }
    });

    $('#move-next').click(mp);

    $(".page.home .titles span").mouseover(function (e) {
        console.log($(".page.home .titles span"));
        var i = $(".page.home .titles span").index(e.target);
        console.log('index', i);

        $(".page.home .banner").each(function (index, el) {
            $(el).css('opacity', 0);
        })
        $($(".page.home .banner")[i]).css('opacity', 1);

    });

    $('#insert-email')
        .attr('href', 'mailto:info@innotechgl.com')
        .text('info@innotechgl.com');


    var menus = function (menuId) {

        var me = this;
        this.menuId = '#' + menuId;

        this.activeMenu = '';
        this.over = false;

        this.hoverClass = 'hovered';
        this.hideClass = 'hidden';

        this.init = function () {

            this.activeMenu = $($(this.menuId + ' > ul > li.active')[0]);

            $('.mobile-select').click(function () {
                if ($('header').hasClass('on')) {
                    $('header').removeClass('on');
                }
                else {
                    $('header').addClass('on');
                }
            });

            // Mouse Over
            $(this.menuId + ' > ul > li ').mouseenter(function (e) {

                // Now we know that it is hovered and active
                $(this).addClass(me.hoverClass);

                // Get Child ul & show it
                var ul = $(this).find('ul')[0];
                $(ul).css({
                        'display': 'block'
                    })
                    .stop()
                    .animate({
                        opacity: 1
                    });
                // Hide active menu
                me.hideActiveMenus();
                //}
            });

            // Mouse Out
            $(this.menuId + ' > ul > li ').mouseleave(function (e) {

                // Now we know that it is hovered and active
                $(this).removeClass(me.hoverClass);


                // Get Child ul & show it
                var ul = $(this).find('ul')[0];
                $(ul).css({
                    'display': 'none',
                    'z-index': 99
                });
                // Show active menu
                me.bringActiveMenusBack();

            });
        }

        this.bringActiveMenusBack
            = function () {

            /*  $(this.menuId+' ul.active').each(function(index, element) {
             console.log(element);
             $(element).stop().css('display','block').animate({

             'opacity':1
             });
             });*/
        }

        this.hideActiveMenus
            = function () {
            /* $(this.menuId+' ul.active').each(function(index, element) {
             $(element).stop().css('display','none').animate({
             'opacity':0
             });
             });*/
        }

    };

    var m_menu = new menus('menu');
    m_menu.init();

});
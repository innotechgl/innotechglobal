<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <script src="modernizr.js"></script>

    <title>ONE PAGE SCROLLING NAVIGATION WITH 3D TRANSFORMS Demo</title>
    <?php include "html_header.php" ?>
    <style class="cp-pen-styles">
        *,
        *:before,
        *:after {
            -moz-box-sizing: border-box;
            box-sizing:;
            margin: 0;
            padding: 0;
        }

        .inner,
        .nav-panel ul .nav-btn:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
        }


        /*@media (max-width: 767px) {*/

            /*body { font-size: 70%; }*/
        /*}*/

        .wrapper {

            position: inherit;
            top: 0;
            left: 0;
            width: 1410px;
            height: 800px;
            -webkit-transition: -webkit-transform 1.5s;
            transition: transform 1.5s;
            -webkit-perspective: 3000;
            perspective: 3000;
            -webkit-transform-style: preserve-3d;
            transform-style: preserve-3d;
        }

        .wrapper .page {
            position: inherit;
            width: 100%;
            height: 100%;
            -webkit-transform: rotateX(180deg) scale(0.3);
            transform: rotateX(180deg) scale(0.3);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transition: -webkit-transform 1s ease-in-out;
            transition: transform 1s ease-in-out;
            will-change: transform;
        }

        /*.wrapper .page h2 {*/
            /*color: #fff;*/
            /*position: absolute;*/
            /*top: 50%;*/
            /*left: 50%;*/
            /*-webkit-transform: translateX(-50%) translateY(-50%);*/
            /*-ms-transform: translateX(-50%) translateY(-50%);*/
            /*transform: translateX(-50%) translateY(-50%);*/
            /*text-transform: uppercase;*/
            /*font-size: 3em;*/
        /*}*/

        .wrapper .page.page1 {
            background-color: #66a6b8;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(20%, #66a6b8), color-stop(80%, #5471B9));
            background-image: -webkit-linear-gradient(-280deg, #66a6b8 20%, #5471B9 80%);
            background-image: -webkit-linear-gradient(80deg, #66a6b8 20%, #5471B9 80%);
            background-image: linear-gradient(10deg, #66a6b8 20%, #5471B9 80%);
        }

        .wrapper .page.page2 {
            background-color: #f29c54;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f29c54), color-stop(100%, #DB4367));
            background-image: -webkit-linear-gradient(-315deg, #f29c54 0%, #DB4367 100%);
            background-image: -webkit-linear-gradient(45deg, #f29c54 0%, #DB4367 100%);
            background-image: linear-gradient(45deg, #f29c54 0%, #DB4367 100%);
        }

        .wrapper .page.page3 {
            background-color: #23af56;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #23af56), color-stop(100%, #67A79A));
            background-image: -webkit-linear-gradient(-405deg, #23af56 0%, #67A79A 100%);
            background-image: -webkit-linear-gradient(315deg, #23af56 0%, #67A79A 100%);
            background-image: linear-gradient(135deg, #23af56 0%, #67A79A 100%);
        }

        .wrapper .page.page4 {
            background-color: #412F2F;
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(15%, #412F2F), color-stop(85%, #6B2686));
            background-image: -webkit-linear-gradient(-430deg, #412F2F 15%, #6B2686 85%);
            background-image: -webkit-linear-gradient(290deg, #412F2F 15%, #6B2686 85%);
            background-image: linear-gradient(160deg, #412F2F 15%, #6B2686 85%);
        }

        .wrapper.active-page1 {
            -webkit-transform: translateY(0%);
            -ms-transform: translateY(0%);
            transform: translateY(0%);
        }

        .wrapper.active-page1 .page.page1 {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .wrapper.active-page2 {
            -webkit-transform: translateY(-100%);
            -ms-transform: translateY(-100%);
            transform: translateY(-100%);
        }

        .wrapper.active-page2 .page.page2 {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .wrapper.active-page3 {
            -webkit-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            transform: translateY(-200%);
        }

        .wrapper.active-page3 .page.page3 {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .wrapper.active-page4 {
            -webkit-transform: translateY(-300%);
            -ms-transform: translateY(-300%);
            transform: translateY(-300%);
        }

        .wrapper.active-page4 .page.page4 {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .nav-panel {
            position: fixed;
            top: 50%;
            right: 1em;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            z-index: 1000;
            -webkit-transition: opacity 0.5s, -webkit-transform 0.5s cubic-bezier(0.57, 1.2, 0.68, 2.6);
            transition: opacity 0.5s, transform 0.5s cubic-bezier(0.57, 1.2, 0.68, 2.6);
            will-change: transform, opacity;
        }

        .nav-panel.invisible {
            opacity: 0;
            -webkit-transform: translateY(-50%) scale(0.5);
            -ms-transform: translateY(-50%) scale(0.5);
            transform: translateY(-50%) scale(0.5);
        }

        .nav-panel ul { list-style-type: none; }

        .nav-panel ul .nav-btn {
            position: relative;
            overflow: hidden;
            width: 1em;
            height: 1em;
            margin-bottom: 0.5em;
            border: 0.12em solid #fff;
            border-radius: 50%;
            cursor: pointer;
            -webkit-transition: border-color, -webkit-transform 0.3s;
            transition: border-color, transform 0.3s;
            will-change: border-color, transform;
        }

        .nav-panel ul .nav-btn:after {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            -webkit-transform: translateX(-50%) translateY(-50%) scale(0.3);
            -ms-transform: translateX(-50%) translateY(-50%) scale(0.3);
            transform: translateX(-50%) translateY(-50%) scale(0.3);
            background-color: #fff;
            opacity: 0;
            -webkit-transition: -webkit-transform, opacity 0.3s;
            transition: transform, opacity 0.3s;
            will-change: transform, opacity;
        }

        .nav-panel ul .nav-btn.active:after,
        .nav-panel ul .nav-btn:hover:after {
            -webkit-transform: translateX(-50%) translateY(-50%) scale(0.7);
            -ms-transform: translateX(-50%) translateY(-50%) scale(0.7);
            transform: translateX(-50%) translateY(-50%) scale(0.7);
            opacity: 1;
        }

        .nav-panel ul .nav-btn:hover {
            border-color: yellow;
            -webkit-transform: scale(1.2);
            -ms-transform: scale(1.2);
            transform: scale(1.2);
        }

        .nav-panel ul .nav-btn:hover:after { background-color: yellow; }

        .nav-panel .scroll-btn {
            position: absolute;
            left: 0;
            width: 1em;
            height: 1em;
            border: 0.2em solid #fff;
            border-left: none;
            border-bottom: none;
            cursor: pointer;
            -webkit-transform-origin: 50% 50%;
            -ms-transform-origin: 50% 50%;
            transform-origin: 50% 50%;
            -webkit-transition: border-color 0.3s;
            transition: border-color 0.3s;
        }

        .nav-panel .scroll-btn.up {
            top: -1.6em;
            -webkit-transform: rotate(-45deg);
            -ms-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }

        .nav-panel .scroll-btn.down {
            bottom: -1.2em;
            -webkit-transform: rotate(135deg);
            -ms-transform: rotate(135deg);
            transform: rotate(135deg);
        }

        .nav-panel .scroll-btn:hover { border-color: yellow; }
    </style>
</head>

<body>
<div class="fluid-container">
    <div class="col-md-9" id="containment">

        <?php include "top_baner.php" ?>
        <div class="wrapper active-page1">
            <div class="page page1">
                <?php include "event_single_content.php"?>

            </div>
            <div class="page page2">
                    <button class="es-button"onclick="history.back()"> NAZAD</button>

                <?php include "gallery_content.php"?>
            </div>
            <div class="page page3">
                <?php include "events_content.php"?>
            </div>
            <div class="page page4">
                <?php include "important_phones_content.php"?>
            </div>
        </div>


        <div class="col-md-9" id="footer">
            <?php include "footer.php"?>
        </div>
    </div>
    <!-- menu and weather -->

    <div class="col-md-3" id="right-menu">
        <?php

        include "menu.php";
        ?>
        <?php include "weather2.php";
        ?>
        <div class="tara">
            <img src="images/taralogo.png" class="img-responsive taralogo">
        </div>
    </div>

</div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<?php include "menu_script.php" ?>
<?php
include "weather-script.php"
?>
</body>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script>'use strict';
    $(document).ready(function() {
        var $wrap = $(".wrapper"),
            pages = $(".page").length,
            scrolling = false,
            currentPage = 1,
            $navPanel = $(".nav-panel"),
            $scrollBtn = $(".scroll-btn"),
            $navBtn = $(".nav-btn");

        /*****************************
         ***** NAVIGATE FUNCTIONS *****
         *****************************/
        function manageClasses() {
            $wrap.removeClass(function (index, css) {
                return (css.match (/(^|\s)active-page\S+/g) || []).join(' ');
            });
            $wrap.addClass("active-page" + currentPage);
            $navBtn.removeClass("active");
            $(".nav-btn.nav-page" + currentPage).addClass("active");
            $navPanel.addClass("invisible");
            scrolling = true;
            setTimeout(function() {
                $navPanel.removeClass("invisible");
                scrolling = false;
            }, 1000);
        }
        function navigateUp() {
            if (currentPage > 1) {
                currentPage--;
                if (Modernizr.csstransforms) {
                    manageClasses();
                } else {
                    $wrap.animate({"top": "-" + ( (currentPage - 1) * 100) + "%"}, 1000);
                }
            }
        }

        function navigateDown() {
            if (currentPage < pages) {
                currentPage++;
                if (Modernizr.csstransforms) {
                    manageClasses();
                } else {
                    $wrap.animate({"top": "-" + ( (currentPage - 1) * 100) + "%"}, 1000);
                }
            }
        }

        /*********************
         ***** MOUSEWHEEL *****
         *********************/
        $(document).on("mousewheel DOMMouseScroll", function(e) {
            if (!scrolling) {
                if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
                    navigateUp();
                } else {
                    navigateDown();
                }
            }
        });

        /**************************
         ***** RIGHT NAVIGATION ****
         **************************/

        /* NAV UP/DOWN BTN PAGE NAVIGATION */
        $(document).on("click", ".scroll-btn", function() {
            if ($(this).hasClass("up")) {
                navigateUp();
            } else {
                navigateDown();
            }
        });

        /* NAV CIRCLE DIRECT PAGE BTN */
        $(document).on("click", ".nav-btn", function() {
            if (!scrolling) {
                var target = $(this).attr("data-target");
                if (Modernizr.csstransforms) {
                    $wrap.removeClass(function (index, css) {
                        return (css.match (/(^|\s)active-page\S+/g) || []).join(' ');
                    });
                    $wrap.addClass("active-page" + target);
                    $navBtn.removeClass("active");
                    $(this).addClass("active");
                    $navPanel.addClass("invisible");
                    currentPage = target;
                    scrolling = true;
                    setTimeout(function() {
                        $navPanel.removeClass("invisible");
                        scrolling = false;
                    }, 1000);
                } else {
                    $wrap.animate({"top": "-" + ( (target - 1) * 100) + "%"}, 1000);
                }
            }
        });

    });
</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
            <?php include "gallery_script.php";?>

</body>
</html>

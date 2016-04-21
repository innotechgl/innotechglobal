<!DOCTYPE html>
<html>
<head>
    <title>PHP file</title>
    <?php include "html_header.php"?>

    <style type="text/css">
        h1 {
            font-family: "Futura PT Bold", Helvetica, sans-serif;
            margin-bottom: 30px;
        }

        p {
            font-family: "Futura PT Book", Helvetica, sans-serif;
        }

        .grid .row {
            background-color: transparent;
            border: 0;
            height: 100px;
            padding-right: 0;
            background: transparent;

        }

        .grid .row .col-md-3 {
            min-height: 255px;

            padding-left: 0px !important;
            padding-right: 0px !important;
            background: transparent;

        }

        .grid .row .col-md-6 {
            min-height: 300px;
            background: transparent;

        }

        #containment {
            min-height: 255px;
            padding-left: 0px !important;
            padding-right: 0px !important;
            background: transparent;

        }

        .colmali {
            min-height: 247px;
            background: transparent;
            padding-left: 0px !important;
            padding-right: 0px !important
        }

        .margin-move-up {
            margin-top: -45px;
        }

        .margin-move-up2 {
            margin-top: -90px;
        }

        .margin-move-up3 {
            margin-top: 65px;
        }

        .taralogo {
            float: right;
        }
    </style>
</head>
<body>


<div class="fluid-container">
    <div class="col-md-9" id="containment">

        <div class="grid">
            <div class="row col-md-12">
                <div class="tile tile-clouds col-md-3 col-xs-12">
                    <a href="#">
                        <h1>Hello</h1>
                    </a>
                </div>

                <div class="tile col-md-9 col-xs-12" id="containment">
                    <div class="col-md-12 colmali"> 12</div>
                </div>
            </div>
            <div class="row col-md-12">
            </div>
            <div class="row col-md-12">
            </div>
            <div class="row col-md-12">
                <div class="tile col-md-9 col-xs-12 margin-move-up" id="containment">
                    <div class="tile-content">
                        <div class="tile-icon-large">
                            <img src="images/twittertile.png">
                        </div>
                    </div>
                    <span class="tile-label">Tile 1</span>
                </div>

                <div class="tile col-md-3 col-xs-12 margin-move-up">
                    <div class="tile-content">
                        <div class="tile-icon-large">
                            <img src="images/twittertile.png">
                        </div>
                    </div>
                    <span class="tile-label">Tile 1</span>
                </div>

            </div>
            <div class="row col-md-12"></div>
            <div class="row col-md-12"></div>
            <div class="row col-md-12">
                <div class="tile col-md-3 col-xs-12 margin-move-up2">
                    <span class="tile-label">Tile 4</span>
                </div>

                <div class="tile col-md-9 col-xs-12 margin-move-up2" id="containment">
                    <span class="tile-label">Tile 4</span>
                </div>

            </div>
            <div class="row col-md-12">
                <div class="tile tile-alizarin col-md-3 col-xs-12 margin-move-up3">
                    <span class="tile-label">Tile 4</span>
                </div>

                <div class="tile tile-alizarin col-md-3 col-xs-12 margin-move-up3">
                    <span class="tile-label">Tile 4</span>
                </div>
                <div class="tile tile-alizarin col-md-3 col-xs-12 margin-move-up3">
                    <span class="tile-label">Tile 4</span>
                </div>
                <div class="tile tile-alizarin col-md-3 col-xs-12 margin-move-up3">
                    <span class="tile-label">Tile 4</span>
                </div>

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
        <?php include "weather.php";
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
<script type="text/javascript">
    $(function () {

        var menu_ul = $('.menu > li > ul'),
            menu_a = $('.menu > li > a');

        menu_ul.hide();

        menu_a.click(function (e) {
            e.preventDefault();
            if (!$(this).hasClass('active')) {
                menu_a.removeClass('active');
                menu_ul.filter(':visible').slideUp('normal');
                $(this).addClass('active').next().stop(true, true).slideDown('normal');
            } else {
                $(this).removeClass('active');
                $(this).next().stop(true, true).slideUp('normal');
            }
        });

    });
</script>
<script type="text/javascript">

    $(function () {
        $('#menu li a').click(function () {
            $('#menu li').removeClass();
            $($(this).attr('href')).addClass('active');
        });
    });

</script>

</body>
</html>
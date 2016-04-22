<!DOCTYPE html>
<html>
<head>
    <title>PHP file</title>
    <?php include "html_header.php" ?>
<body>
<div class="fluid-container">
    <div class="col-md-9" id="containment">



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
<?php
include "weather-script.php"
?>
</body>
</head>
</html>

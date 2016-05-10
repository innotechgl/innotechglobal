<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <script src="modernizr.js"></script>

    <title>Tara</title>
    <?php include "html_header.php" ?>
    <link rel="stylesheet" type="text/css" href="css/one-page.css"/>
    <style class="cp-pen-styles">

    </style>
</head>

<body>
<div class="fluid-container">
    <div class="col-md-9" id="containment">

        <?php include "top_baner.php" ?>
        <div class="wrapper active-page1">
            <div class="page page1">
                <?php include "about_hotel_content.php"; ?>

            </div>
            <div class="page page2">

                <?php include "gallery_content.php"; ?>

            </div>
            <div class="page page3">
                <?php include "important_phones_content.php"; ?>
            </div>
            <div class="page page4">
                <?php include "floor_map_content.php"; ?>
            </div>

            <div class="page page5">
                <?php include "events_content.php"; ?>
            </div>
            <div class="page page6">
                <?php include "event_single_content.php"; ?>
            </div>
            <div class="page page7">
                <?php include "services_content.php"; ?>
            </div>
            <!-- RESERVATION form treba da bude zadnji div zbog skrola -->
            <div class="page page8">
                <?php include "form_content.php"; ?>
            </div>
            <div class="page page9">
                <?php include "form_res_thanks.php"; ?>
            </div>
            <div class="page page10">
                <?php include "about_belgrade_content.php"; ?>
            </div>

        </div>


        <div class="col-md-9" id="footer">
            <?php include "footer.php"; ?>
        </div>
    </div>
    <!-- menu and weather -->

    <div class="col-md-3" id="right-menu">

        <?php include "menu2.php";?>


        <?php include "weather2.php"; ?>
        <div class="tara">
            <ul>
                <li><img src="images/flags/france_flag.gif" alt="flag" class="language-icon img-responsive"/></li>
                <li><img src="images/flags/english_flag.gif" alt="flag" class="language-icon img-responsive"/></li>
                <li><img src="images/flags/serbian_flag.gif" alt="flag" class="language-icon img-responsive"/></li>
                <li><img src="images/flags/turkish_flag.png" alt="flag" class="language-icon img-responsive"/></li>
                <li><img src="images/flags/Flag_of_Greece.png" alt="flag" class="language-icon img-responsive"/></li>


            </ul>


        </div>
        <li><img src="images/taralogo.png" class="img-responsive taralogo"></li>
    </div>

</div>

<!--  jquery offline-->
<script type="text/javascript" src="js/jquery-1.12.1.min.js"></script>
<!-- jquery online
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<?php include "menu_script.php" ?>
<?php
include "weather-script.php"
?>
<?php include "one-page-script.php" ?>
<?php include "gallery_script.php"; ?>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<!-- Forma fade in -->
<script>
            $( ".btn-book" ).click(function() {
                $('.form-tnx').fadeIn(3300).removeClass('hidden');
            });
</script>

</body>
</html>

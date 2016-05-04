<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <script src="modernizr.js"></script>

    <title>ONE PAGE SCROLLING NAVIGATION WITH 3D TRANSFORMS Demo</title>
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
                <?php include "floor_map_content.php"; ?>
            </div>
            <div class="page page4">
                <?php include "events_content.php"; ?>
            </div>

            <div class="page page5">
                <?php include "important_phones_content.php"; ?>
            </div>
            <div class="page page6">
                <?php include "event_single_content.php"; ?>
            </div>
        </div>


        <div class="col-md-9" id="footer">
            <?php include "footer.php"; ?>
        </div>
    </div>
    <!-- menu and weather -->

    <div class="col-md-3" id="right-menu">

        <?php include "menu2.php"; ?>


        <?php include "weather2.php"; ?>
        <div class="tara">
            <img src="images/taralogo.png" class="img-responsive taralogo">
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
<?php include "one-page-script.php" ?>
<?php include "gallery_script.php"; ?>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->


</body>
</html>

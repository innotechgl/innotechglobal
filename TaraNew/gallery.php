<!DOCTYPE html>
<html>
<head>
    <title>PHP file</title>
    <?php include "html_header.php" ?>
<body>
<div class="fluid-container">
    <div class="col-md-9" id="containment">
        <?php include "top_baner.php";?>
        <button style="background: #CD5944; border: none;
    color: black;
    padding: 15px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 22px;
    font-weight: bold;
    margin: 4px 2px;
    cursor: pointer;
    float: right;
    font-family: "Futura PT Book";
    text-transform: uppercase;" onclick="history.back()"> Povratak</button>
             <?php include "gallery_content.php";?>


        <div class="col-md-9" id="footer">
            <?php include "footer.php";?>
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
<?php
include "gallery_script.php"
?>
</body>
</head>
</html>

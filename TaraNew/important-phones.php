<!DOCTYPE html>
<html>
<head>
    <title>PHP file</title>
    <?php include "html_header.php" ?>
<body>
<div class="fluid-container">
    <div class="col-md-9" id="containment">

            <?php include "top_baner.php" ?>
           <?php include "important_phones_content.php";?>

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
</head>
</html>

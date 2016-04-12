<!DOCTYPE html>
<html>
  <head>
    <title>PHP file</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UFT-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="css/style.css">
 
  </head>
<body>
<?php
include 'header.php';

?>
<hr class="hr">
<div class="fluid-container"
<div class="row">
  <div class="col-md-3">
      <?php
            include 'baners.php';
      ?></div>
  <div class="col-md-6" id="justify"><?php

  include('about_hotel_content.php');
?>
  </div>
  <div class="col-md-3">
      <?php
            include 'menu.php';

      ?>
      <?php
            include 'weather.php';

      ?>
</div>
</div>
</div>
</body>
</html>
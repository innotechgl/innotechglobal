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
  <div class="fluid-container">
  <div class="col-md-9">
<div class="row">
  <div class="col-md-12"><?php

  include "header.php";
  ?></div>
  
  <div class="col-md-4 margina"><?php

  include "baners.php";
  ?></div>
  <div class="col-md-8" id="justify"><?php

  include "about_hotel_content.php";
  ?></div>

</div>

  </div>
  <div class="col-md-3" id="right-menu">
  <?php

  include "menu2.php";
  ?>
  <?php

  include "weather.php";
  ?>
</div>
</div>
    </div>
</body>
</html>
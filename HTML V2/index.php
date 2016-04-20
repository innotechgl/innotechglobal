<!DOCTYPE html>
<html>
  <head>
    <title>PHP file</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UFT-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="css/style.css">
 <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href="css/fonts/gotham/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/futura/md/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/futura/xb/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/futura/pt/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/futura/cond/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/futura/bold/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/futura/book/styles.css" media="all" rel="stylesheet" type="text/css">
<link href="css/fonts/acrom/bld/style.css" media="all" rel="stylesheet" type="text/css">
<style type="text/css">
h1 {
  font-family: "Futura PT Bold", Helvetica, sans-serif;
  margin-bottom: 30px;
}
p
{
  font-family: "Futura PT Book", Helvetica, sans-serif;
}
</style>
  </head>
<body>
  <div class="fluid-container">
  <div class="col-md-9">
<div class="row">
  <div class="col-md-12"><?php

  include "header.php";
  ?></div>

  <hr class="hr-linija">

  <div class="col-md-12" id="justify"><?php

  include "about_hotel_content.php";
  ?></div>

</div>

  </div>
  <div class="col-md-3" id="right-menu">
  <?php

  include "menu.php";
  ?>
 <?php include "weather.php";
 ?>
</div>
</div>
    </div>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
  $(function() {
  
      var menu_ul = $('.menu > li > ul'),
             menu_a  = $('.menu > li > a');
      
      menu_ul.hide();
  
      menu_a.click(function(e) {
          e.preventDefault();
          if(!$(this).hasClass('active')) {
              menu_a.removeClass('active');
              menu_ul.filter(':visible').slideUp('normal');
              $(this).addClass('active').next().stop(true,true).slideDown('normal');
          } else {
              $(this).removeClass('active');
              $(this).next().stop(true,true).slideUp('normal');
          }
      });
  
  });
</script>


</body>
</html>
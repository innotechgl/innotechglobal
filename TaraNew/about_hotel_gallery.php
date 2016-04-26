<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Tara</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/slicebox.css" />
		<link rel="stylesheet" type="text/css" href="css/custom.css" />
		<script type="text/javascript" src="js/modernizr.custom.46884.js"></script>
	</head>




	<body>
		<div class="container">

			

			<div class="wrapper">

				<ul id="sb-slider" class="sb-slider">
					<li>
						<img src="images/1.jpg" alt="image1"/>
						
					</li>
					<li>
						<img src="images/2.jpg" alt="image2"/>
						
					</li>
					<li>
						<img src="images/3.jpg" alt="image1"/>
						
					<li>
						<img src="images/4.jpg" alt="image1"/>
					
					<li>
						<img src="images/5.jpg" alt="image1"/>
					<li>
						<img src="images/6.jpg" alt="image1"/>
						
					</li>
					<li>
					<img src="images/7.jpg" alt="image1"/>
					
					</li>
				</ul>

				<div id="shadow" class="shadow"></div>

				<div id="nav-arrows" class="nav-arrows">
					<a href="#">Next</a>
					<a href="#">Previous</a>
				</div>

				<div id="nav-options" class="nav-options">
					<!-- <span id="navPlay">Play</span>
					<span id="navPause">Pause</span> -->
				</div>

			</div><!-- /wrapper -->

		

		</div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.slicebox.js"></script>
		<script type="text/javascript">
			$(function() {
				
				var Page = (function() {

					var $navArrows = $( '#nav-arrows' ).hide(),
						$navOptions = $( '#nav-options' ).hide(),
						$shadow = $( '#shadow' ).hide(),
						slicebox = $( '#sb-slider' ).slicebox( {
							onReady : function() {

								$navArrows.show();
								$navOptions.show();
								$shadow.show();

							},
							orientation : 'h',
							cuboidsCount : 6
						} ),
						
						init = function() {

							initEvents();
							
						},
						initEvents = function() {

							// add navigation events
							$navArrows.children( ':first' ).on( 'click', function() {

								slicebox.next();
								return false;

							} );

							$navArrows.children( ':last' ).on( 'click', function() {
								
								slicebox.previous();
								return false;

							} );

							$( '#navPlay' ).ready(function() {
								
								slicebox.play();
								return false;

							} );

							$( '#navPause' ).on( 'click', function() {
								
								slicebox.pause();
								return false;

							} );

						};

						return { init : init };

				})();

				Page.init();

			});
		</script>

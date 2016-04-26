<script type="text/javascript" ></script>
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
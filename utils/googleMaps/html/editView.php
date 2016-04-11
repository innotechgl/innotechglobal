<div class="gmaps_form">
    <div class="adresa">
        <span>adresa / mesto</span><br/>
        <input type="text" name="address" id="address" size="30"/>&nbsp;&nbsp;
        <button type="button" onclick="showAddress($('address').get('value'));">Pronadji</button>
    </div>
    <!-- adresa-->
    <button type="button" onclick="clearPoints();">clear</button>
    <div id="map" style="width: <?php echo $this->width ?>px; height: <?php echo $this->height; ?>px;"></div>
    <div id="pano"></div>
    <input type="hidden" name="zoom" id="zoom" value="<?php echo $zoom; ?>"/>
    <input type="hidden" id="lat" name="lat" value="<?php echo $lat; ?>"/>
    <input type="hidden" id="lng" name="lng" value="<?php echo $lng; ?>"/>
</div><!-- gmaps_form -->
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript" src="/utils/googleMaps/js/geoTaging.js"></script>
<?php
$engine->util_googleMaps->create_points();
?>
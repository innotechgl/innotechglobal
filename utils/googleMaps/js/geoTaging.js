var options = {
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    draggableCursor: "crosshair",
    streetViewControl: false
};
var map = new google.maps.Map(document.getElementById("map"), options);
geocoder = new google.maps.Geocoder();
google.maps.event.addListener(map, "click", function (location) {
    GetLocationInfo(location.latLng);
});
google.maps.event.addListener(map, 'zoom_changed', function (oldLevel, newLevel) {
    $("zoom").set('value', map.getZoom());
});
var myPano = new google.maps.StreetViewPanorama(document.getElementById("pano"),
    { visible: false });
myPano.setPov({
    heading: 265,
    zoom: 1,
    pitch: 0
});
$('pano').hide();
google.maps.event.trigger(myPano, 'resize');
var initListener;
var marker;
function StartStreetView() {
    // street view
    if ($("streetViewBtn").val() == "Start StreetView") {
        initListener = google.maps.event.addListener(myPano, "position_changed", handlePanoMove);
        $('pano').show();
        myPano.setVisible(true);
        $("streetViewBtn").set('value', "End StreetView");
        google.maps.event.trigger(myPano, 'resize');
        GotoLatLong();
    }
    else {
        google.maps.event.removeListener(initListener);
        myPano.setVisible(false);
        $('#pano').hide();
        $("#streetViewBtn").set('value', "Start StreetView");
        google.maps.event.trigger(myPano, 'resize');
    }
}
function GetLocationInfo(latlng) {
    if (latlng != null) {
        ShowLatLong(latlng);
    }
}
function GotoLatLong() {
    if ($("lat").get('value') != "" && $("lng").get('value') != "") {
        var lat = $("lat").get('value');
        var long = $("lng").get('value');
        var latLong = new google.maps.LatLng(lat, long);
        ShowLatLong(latLong);
        map.setCenter(latLong);
    }
}
function ShowLatLong(latLong) {
    // show the lat/long
    if (marker != null) {
        marker.setMap(null);
    }
    marker = new google.maps.Marker({
        position: latLong,
        map: map
    });
    $("lat").set('value', latLong.lat());
    $("lng").set('value', latLong.lng());
    /*GetElevation(latLong.lat(), latLong.lng(), 'elevation');
     ReverseGeocode(latLong.lat(), latLong.lng(), 'address');*/
}
function handlePanoMove(location) {
    ShowLatLong(myPano.getPosition());
}
function showAddress(address) {
    if (geocoder) {
        geocoder.geocode({ 'address': address }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                /*var marker = new google.maps.Marker({
                 map: map,
                 position: results[0].geometry.location
                 });*/
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }
}
function clearPoints() {
    $("lat").set('value', '');
    $("lng").set('value', '');
    marker.setMap(null);
}
showAddress("serbia");
GotoLatLong();
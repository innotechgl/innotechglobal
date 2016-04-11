myLatlng[<?php echo $i; ?>] = new google.maps.LatLng(<?php echo $val_p['lat'] . "," . $val_p['long']; ?>);
//alert(myLatLng.toString());
marker[<?php echo $i; ?>] = new google.maps.Marker({
map: map,
position:myLatlng[<?php echo $i; ?>],
title:'<?php echo $val_p['title']; ?>'
});
map.setZoom(<?php echo $val_p['zoom']; ?>);
map.setCenter(myLatlng[<?php echo $i; ?>]);
bounds.extend(myLatlng[<?php echo $i; ?>]);
<?php
if (trim($val_p['text']) !== '') {
    ?>

    infowindow[<?php echo $i; ?>] = new google.maps.InfoWindow({
    content: '<?php echo $val_p['text']; ?>'
    });

    google.maps.event.addListener(marker[<?php echo $i; ?>], 'click', function() {
    infowindow[<?php echo $i; ?>].open(map,marker[<?php echo $i; ?>]);
    $('#infoContent .mapLink').click(function(e){
    e.preventDefault();
    var url = $(this).attr('href')+'&type=clean';

    $('#cont').stop().animate({
    opacity:0
    }, 300, 'linear', function(){
    $('#cont').empty();
    $.ajax(url,
    {
    cache: false
    }).done(function(data){
    $('#cont').append(data);
    $('#cont').stop().animate({
    opacity:1
    });
    // Add events for loaded menus
    $('#cont').ready(function(e) {
    left_menu_events();
    list_click_events();
    });
    });
    });

    });
    });
<?php
}
?>
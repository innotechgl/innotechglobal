
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    if (typeof jQuery == 'undefined') {
    document.write(unescape("%3Cscript src='src/js/lib/jquery.1.9.1.min.js' type='text/javascript'%3E%3C/script%3E"));
}
</script>
<script src="js/plugins/src/openWeather.js"></script>

<script>

$(function() {

    $('.weather-temperature').openWeather({
            key: 'c9d49310f8023ee2617a7634de23c2aa',
//            city: 'Belgrade, RS',
            lat: '44.804008',
            lng: '20.46513',

            descriptionTarget: '.weather-description',
            windSpeedTarget: '.weather-wind-speed',
            minTemperatureTarget: '.weather-min-temperature',
            maxTemperatureTarget: '.weather-max-temperature',
            humidityTarget: '.weather-humidity',
            sunriseTarget: '.weather-sunrise',
            sunsetTarget: '.weather-sunset',
            placeTarget: '.weather-place',
            iconTarget: '.weather-icon',
            customIcons: 'js/plugins/src/img/icons/weather/',
            success: function() {

        //show weather
        $('.weather-wrapper').show();

    },
            error: function(message) {

        console.log(message);

    }
        });

    });

</script>
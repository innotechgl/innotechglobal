app.directive('googleMap', function ($http, mainSettings) {
    return {
        scope: {
            googleMap: "=gmap",
            id: "@articleId"
        },
        templateUrl: 'pages/articles/includes/directives/googleMap/index.html',
        link: function (scope, element) {

            scope.pin = { "lat": 5, "lng": 5 }
            scope.mapPosition = { "lat": 5, "lng": 5, "zoom": 5 };
            scope.result = '';
            scope.address = '';
            scope.icons = [];
            scope.selectedIcon = false;

            scope.googleMap = {
                useMap: false,

                lat: 5,
                lng: 5,
                zoom: 5,

                title: "",
                description: "",
                useTitle: false,
                useDescription: false,
                icon:false
            };

            scope.getIconPath = function (iconPath) {
                return mainSettings.mainAppURL + iconPath;
            }

            scope.selectIcon = function (icon) {
                scope.googleMap.icon = mainSettings.mainAppURL+icon;
                scope.selectedIcon = icon;
            }

            scope.$watch('id', function () {
                if (scope.id > 0) {
                    $http.get(mainSettings.mainURL + '?page=articles&task=getGoogleMapData&id=' + scope.id)
                        .success(function (response) {
                            if (response.data !== false) {
                                console.log('prosao!');
                                scope.googleMap = response.data;
                                scope.googleMap.useTitle = true;
                                scope.googleMap.useMap = true;
                                scope.googleMap.useDescription = true;

                                scope.mapPosition.lat = scope.googleMap.lat;
                                scope.mapPosition.lng = scope.googleMap.lng;
                                scope.mapPosition.zoom = scope.googleMap.zoom;
                            }
                            else {
                                scope.googleMap = {
                                    useMap: false,

                                    lat: 5,
                                    lng: 5,
                                    zoom: 5,

                                    title: "",
                                    description: "",
                                    useTitle: false,
                                    useDescription: false
                                };
                            }

                        }).error(function () {
                            
                        })
                }
            });

            
            element.find("#addr_finder").bind('keyup', function (e) {
                window.stop(); // Works in all browsers but IE    
                document.execCommand("Stop"); // Works in IE
                if (e.keyCode==13){
                    scope.search();
                }
            });

            

            // map
            scope.$on('mapInitialized', function (event, map) {

                var geocoder = new google.maps.Geocoder();

                // map = event;
                scope.placeMarker = function (e) {

                }

                scope.draged = function (e) {
                    scope.googleMap.lat = scope.map.markers[0].getPosition().lat();
                    scope.googleMap.lng = scope.map.markers[0].getPosition().lng();
                    scope.$apply();
                }

                scope.search = function () {
                    scope.result = "Searching...";
                    geocoder.geocode({ 'address': scope.address },
                        function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                scope.result = "Found!";

                                scope.googleMap.lat = results[0].geometry.location.lat();
                                scope.googleMap.lng = results[0].geometry.location.lng();
                                scope.googleMap.zoom = 15;

                                scope.mapPosition.zoom = 15;
                                scope.mapPosition.lat = results[0].geometry.location.lat();
                                scope.mapPosition.lng = results[0].geometry.location.lng();

                                scope.$apply();

                                map.setCenter(results[0].geometry.location);
                            } else {
                                scope.result = "Not Found!";
                            }
                        });
                }

            });

            // Load map Icons
            loadMapIcons();


            function loadMapIcons() {
                $http.get(mainSettings.mainURL + '?page=articles&task=getMapIcons')
                    .success(function (response) {

                        // Icons
                        scope.icons = response.data;
                        
                        console.log('icons', scope.icons);
                    })
                    .error(function (response) {
                        console.log('error', response);
                    });
            }
        },
        controller: function ($scope, $timeout) {

        }
    }
});
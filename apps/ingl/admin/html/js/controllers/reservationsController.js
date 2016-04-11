app.controller("reservationsController", function($scope, $http, $rootScope, $modal) {

    $scope.accommodationsURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=accommodations&task=getMyAccommodationList&option=accommodation&token=" + $rootScope.token;
    $scope.reservationsURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=reservations&task=getReservations&token=" + $rootScope.token + "&accommodation_id=";
    $scope.reservationsPendingURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=reservations&task=getPendingReservations&&token=" + $rootScope.token + "accommodation_id=";
    $scope.reservationsApprovedURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=reservations&task=getApprovedReservations&&token=" + $rootScope.token + "accommodation_id=";
    $scope.approveReservationURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=reservations&task=AcceptReservation&token=" + $rootScope.token + "&accommodation_id=";
    $scope.rejectReservationURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=reservations&task=rejectReservation&token=" + $rootScope.token + "&accommodation_id=";
    $scope.lockDateURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=calendar&task=add&token=" + $rootScope.token + "&rel_id=";
    $scope.unlockDateURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=calendar&task=deleteDates&token=" + $rootScope.token + "&rel_id=";

    $scope.lockedDatesURL = "http://149.154.152.228/showroom/reservations/cms/rest/?page=calendar&task=getDatesForMonth&token=" + $rootScope.token + "&rel_id=";

    $scope.accommodations = {};
    $scope.reservations = {};
    $scope.reservation = {};

    $scope.lockedDates = {};
    $scope.day = moment();
    $scope.m = 0;
    $scope.y = 0;

    $scope.activeAccommodationID = 0;

    $scope.check = function(c, m, d) {

        for (var i = 0; i < $scope.lockedDates.length; i++) {

            var da = new Date($scope.lockedDates[i].date);

            if (da.getDate() == c && (da.getMonth() + 1) == d && m == true) {
                return true;
            }
        }
    }

    $scope.getLockedDates = function(accommodationID, month, year) {
        $http.get($scope.lockedDatesURL + accommodationID + "&rel_page=reservations&month=" + month + "&year=" + year)
            .success(function(data) {
                if (typeof data.data == "undefined") {
                    $scope.lockedDates = {};
                } else {
                    $scope.lockedDates = data.data;
                }
                console.log($scope.lockedDates);
            });
    }

    $scope.getMyAccommodations = function() {
        $http.get($scope.accommodationsURL)
            .success(function(data) {
                $scope.accommodations = data.data;
            })
            .error(function(data) {

            });
    }

    $scope.getMyReservations = function(accommodationID) {

        $scope.activeAccommodationID = accommodationID;

        $http.get($scope.reservationsURL + accommodationID)
            .success(function(data) {
                $scope.reservations = data.data.reservations;
                $scope.getLockedDates(accommodationID, new Date().getMonth() + 1, new Date().getFullYear());
            })
            .error(function(data) {

            });
    }

    $scope.getMyPendingReservations = function(accommodationID) {
        $http.get($scope.reservationsPendingURL + accommodationID)
            .success(function(data) {
                $scope.reservations = data.data;
            })
            .error(function(data) {

            });
    }

    $scope.getMyApprovedReservations = function(accommodationID) {
        $http.get($scope.reservationsApprovedURL + accommodationID)
            .success(function(data) {
                $scope.reservations = data.data;
            })
            .error(function(data) {

            });
    }

    $scope.confirmPanel = function(key) {
        $scope.reservation = $scope.reservations[key];
        var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'approveTpl',
            controller: "approveReservation",
            resolve: {
                reservation: function() {
                    return {
                        "key": key,
                        "data": $scope.reservations[key]
                    };
                }
            }
        });

        modalInstance.result.then(function(data) {
            console.log(data, $scope);
            $scope.confirm(data);
        }, function() {
            console.log('dismissed');
        });
    }

    $scope.rejectPanel = function(key) {
        $scope.reservation = $scope.reservations[key];
        var modalInstance = $modal.open({
            animation: true,
            templateUrl: 'rejectTpl',
            controller: "rejectReservation",
            resolve: {
                reservation: function() {
                    return {
                        "key": key,
                        "data": $scope.reservations[key]
                    };
                }
            }
        });

        modalInstance.result.then(function(data) {
            $scope.removeConfirmation(data);
        }, function() {
            console.log('dismissed');
        });
    }

    $scope.confirm = function(data) {
        $scope.reservations[data.key].confirmed = 1;

        $http.get($scope.approveReservationURL + $scope.reservations[data.key].accommodation_id + "&id=" + $scope.reservations[data.key].id).success(function(d) {
            if (data.lockCalendar) {
                $scope.lockDate($scope.reservations[data.key].start_date, $scope.reservations[data.key].end_date, $scope.reservations[data.key].accommodation_id);
            }
        });
    }

    $scope.removeConfirmation = function(data) {
        $scope.reservations[data.key].confirmed = 0;
        $http.get($scope.rejectReservationURL + $scope.reservations[data.key].accommodation_id + "&id=" + $scope.reservations[data.key].id).success(function(d) {

            if (data.unlockCalendar) {
                $scope.unlockDate($scope.reservations[data.key].start_date, $scope.reservations[data.key].end_date, $scope.reservations[data.key].accommodation_id);
            }
        });
    }

    $scope.lockDate = function(start_date, end_date, accommodationID) {
        $http.get($scope.lockDateURL + accommodationID + "&start_date=" + start_date + "&end_date=" + end_date + "&rel_page=reservations")
            .success(function(data) {
                $scope.getLockedDates($scope.activeAccommodationID, $scope.m, $scope.y);
            });
    }

    $scope.unlockDate = function(start_date, end_date, accommodationID) {
        $http.get($scope.unlockDateURL + accommodationID + "&start_date=" + start_date + "&end_date=" + end_date + "&rel_page=reservations")
            .success(function(data) {
                $scope.getLockedDates($scope.activeAccommodationID, $scope.m, $scope.y);
            });
    }

    $scope.sendEmail = function(email) {
        var link = "mailto:" + email;

        window.location.href = link;
    };

    $scope.getMyAccommodations();


});


app.controller('approveReservation', function($scope, $modalInstance, reservation) {

    $scope.reservation = reservation.data;
    $scope.form = {};

    $scope.ok = function() {
        var data = {
            "key": reservation.key,
            "lockCalendar": $scope.form.lockCalendar
        };

        $modalInstance.close(data);
    };

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };

});

app.controller('rejectReservation', function($scope, $modalInstance, reservation) {

    $scope.reservation = reservation.data;
    $scope.form = {};

    $scope.from = '';
    $scope.to = '';

    $scope.ok = function() {
        var data = {
            "key": reservation.key,
            "unlockCalendar": $scope.form.lockCalendar
        };

        $modalInstance.close(data);
    };

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };

});

app.controller('Datepicker', function($scope) {

    $scope.datepickers = {
        from: false,
        to: false
    }
    
    $scope.unlock = function(f,t,a){
        $scope.unlockDate((new Date(f)).toString().split(' ').splice(1,3).join(' '),(new Date(t)).toString().split(' ').splice(1,3).join(' '),a);
    }
    
    $scope.lock = function(f,t,a){
        $scope.lockDate((new Date(f)).toString().split(' ').splice(1,3).join(' '),(new Date(t)).toString().split(' ').splice(1,3).join(' '),a);
    }

    $scope.fromDate = new Date();
    $scope.toDate = new Date();

    $scope.today = function() {

    };
    $scope.today();

    $scope.clear = function() {
        $scope.dt = null;
    };

    $scope.$watch('fromDate', function() {
        console.log($scope.fromDate);
    });
    $scope.$watch('toDate', function() {
        console.log($scope.toDate);
    });

    // Disable weekend selection
    $scope.disabled = function(date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.toggleMin = function() {
        $scope.minDate = $scope.minDate ? null : new Date();
    };
    $scope.toggleMin();

    $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();

        $scope.opened = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.formats = ['dd.MM.yyyy'];
    $scope.format = $scope.formats[0];

    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    var afterTomorrow = new Date();
    afterTomorrow.setDate(tomorrow.getDate() + 2);
    $scope.events = [{
        date: tomorrow,
        status: 'full'
    }, {
        date: afterTomorrow,
        status: 'partially'
    }];

    $scope.getDayClass = function(date, mode) {
        if (mode === 'day') {
            var dayToCheck = new Date(date).setHours(0, 0, 0, 0);

            for (var i = 0; i < $scope.events.length; i++) {
                var currentDay = new Date($scope.events[i].date).setHours(0, 0, 0, 0);

                if (dayToCheck === currentDay) {
                    return $scope.events[i].status;
                }
            }
        }

        return '';
    };
});
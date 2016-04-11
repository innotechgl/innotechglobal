(function () {
    'use strict';
    app.controller('menuArticleLinkController', ["$location", "$scope","$rootScope","$http",
        function ($location, $scope, $rootScope,$http) {

            // URL definitions
            $scope.getTasksAndOptionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getTasksAndOptions";

            // Data
            $scope.tasksAndOptions = "";
            $scope.task = "";
            
            // Load tasks and options
            $scope.loadTaskAndOptions = function () {

                $http.get($scope.getTasksAndOptionsURL).success(function (data) {

                    $scope.tasksAndOptions = data.data;

                }).error(function (data) {
                    console.log("ERROR loading.");
                });
            }


            $scope.loadTaskAndOptions();
            

        }]);
})();

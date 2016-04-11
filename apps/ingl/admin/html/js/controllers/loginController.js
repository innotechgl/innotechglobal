app.controller("loginController", ["$scope", "$http","$location","$rootScope","$cookies","user",
        function($scope, $http, $location,$rootScope,$cookies, user) {

            $scope.loginURL = $rootScope.mainURL+"?page=users&task=login";
            $scope.checkTokenURL = $rootScope.mainURL+"?page=users&task=checkToken&token=";

            $scope.loginForm = {};
            $rootScope.token = null;
            $scope.loginError = false;

            $scope.login = function () {
                user
                    .login($scope.loginForm)
                    .then
                    (function(response) {
                    
                        if (response.data.STATUS_CODE == 200) {
                            user.setToken(response.data.data.token);
                            user.setLogedIn(true);

                            $rootScope.token = response.data.data.token;
                            $cookies["token"] = response.data.data.token;
                            $location.path("/");
                        }
                        else {
                            $scope.loginError=true;
                        }
                    }, function (error) {
                        console.log('login error!', error);
                    })
                    
            }

            $scope.checkToken = function() {
                $http.get($scope.checkTokenURL + $rootScope.token);
            }

        }
    ]);
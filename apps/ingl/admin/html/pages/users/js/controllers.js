app.controller("usersListController", ["usersService", "$http", "$scope", function (usersService, $http, $scope) {

    // List of users
    $scope.users = [];

    // Activate user
    $scope.activate = function (id) {
        usersService.activate(id).then(function () {
            $scope.users[findUserByID(id)].active = 1;
        });
    }

    // Deactivate user
    $scope.deactivate = function (id) {
        usersService.activate(id).then(function () {
            $scope.users[findUserByID(id)].active = 0;
        });
    }

    $scope.delete = function (id) {
        console.log('delete',id);
    }
    
    // Load
    usersService.loadAll().then(function (response) {
        $scope.users = response.data.data;
    });

    function findUserByID(id) {
        for (var i = 0; i < $scope.users.length; i++) {
            if ($scope.users[i].id == id) {
                return i;
            }
        }
    }
    
    
}]);

app.controller("usersRegisterController", ["usersService", "$http", "$scope","$timeout", function (usersService, $http, $scope,$timeout) {

    // List of users
    $scope.userForm = {};
    $scope.ok = false;
    $scope.errors = [];

    $scope.register = function () {
        // Reset errors
        $scope.errors = [];

        usersService
            .register($scope.userForm)
            .then(function (response) {
                if (Object.keys(response.data.data).length > 0) {

                    // Show errors
                    $scope.errors = response.data.data;
                }
                else {
                    // Remove button
                    $scope.ok = true;
                    
                }
            })
    }

}]);
optimus.controller('usersAddController', ["$scope","$modal","$log", function($scope, $modal, $log) {
 $scope.ime= "Peconi";
 $scope.addGroup = function (addGroup) {
     var modalInstance = $modal.open({
         animation: true,
         templateUrl: 'pages/users/user_groups/add.html',
         controller: "groupAddController",

     });
 }






}]);

optimus.controller('groupAddController', function($scope) {

  $scope.items = ['Group1', 'Group2', 'Group3'];
  $scope.formData = {name : 'Ime grupe', parent_id:0, type:0}
  $scope.operators = [
        {value: 'admin', displayName: 'admin', id:0 },
        {value: 'moderator', displayName: 'moderator',id:1},
        {value: 'standard', displayName: 'standard', id:2}
     ]
  $scope.parents = [
        {value: 'Root', displayName: 'Root', id:0},
        {value: 'Group1', displayName: 'Group1', id:1},
        {value: 'Group2', displayName: 'Group2', id:2}
      ]



});

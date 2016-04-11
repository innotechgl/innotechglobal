optimus.controller('usersListController', function($scope, users) {

   $scope.data = [
    {
      parent:"0",
     username:"John",
     loged:"loged out",
     created:"01.12.2012",
     member:"Group a3"
    },

    {
    parent:"1",
    username:"MartyMcFly",
    loged:"loged in",
    created:"04.09.2014",
    member:"Group a1"
    },

    {
    parent:"2",
    username:"oTwist",
    loged:"loged in",
    created:"04.09.2015",
    member:"Group a2"
    }
]

 });

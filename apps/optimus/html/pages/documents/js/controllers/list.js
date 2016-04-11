optimus.controller('documentsListController', function($scope, documents) {


  $scope.listData= [
    {
      id:"1",
       category:"category1",
       name:"Something.file",
       description:"This is fake file from fake json"
     },
     {
         id:"2",
        category:"category2",
        name:"Something.file",
        description:"This is fake file from fake json"
      },
      {
          id:"3",
         category:"category3",
         name:"Something.file",
         description:"This is fake file from fake json"
       }
   ]
});

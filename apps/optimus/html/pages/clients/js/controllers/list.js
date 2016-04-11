optimus.controller('clientListController', function($scope,clients) {

  this.delete = function (id) {
    clients.delete(id).then(function(response){

    })
  };

  $scope.listData= [
    {
       id:"1",
       name:"TrueComercz",
       state:"Srbija",
       place:"Beograd",
       adress:"Skadarska",
       email:"office@truecomercz.com",
       phone:"062123123",
       pib:"12222678",
       mb:"12345678"
     },
     {
       id:"2",
       name:"NekaFirma",
       state:"Srbija",
       place:"Beograd",
       adress:"Topolska",
       email:"office@nekafirma.rs",
       phone:"062123123",
       pib:"12344378",
       mb:"12345678"
      },
     {
       id:"3",
       name:"FirmaTrade",
       state:"Srbija",
       place:"Nis",
       adress:"Nova Ulica",
       email:"contact@firmatrade.com",
       phone:"062123123",
       pib:"11145678",
       mb:"12345678"
      }
   ]
});

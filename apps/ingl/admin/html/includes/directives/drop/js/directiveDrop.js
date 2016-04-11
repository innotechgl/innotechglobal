app.directive('optDrop', function () {
    return{
        strict:"E",
        scope:{
            opt: "=ngModel",
            title: "@title",
            defaultValue: "@default",
            dropOptions: "=dropOptions"
        },
        templateUrl:'includes/directives/drop/index.html',
        link: function (scope) {

            // set default if undefined
            if (angular.isUndefined(scope.opt)){
                scope.opt = scope.defaultValue;
            }

            // Select option
            scope.selectOption = function (index) {
                scope.opt = scope.dropOptions[index];
            };
        }
    }
});
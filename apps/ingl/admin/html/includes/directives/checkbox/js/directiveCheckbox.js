app.directive('optCheckbox', function () {
    return{
        strict:"E",
        scope:{
            opt: "=ngModel",
            title: "@title",
            defaultValue: "@default"
        },
        templateUrl:'includes/directives/checkbox/index.html',
        link: function (scope) {
            // set default if undefined
            if (angular.isUndefined(scope.opt)) {
                scope.opt = scope.defaultValue;
            }
        }
    }
});
app.directive('multipleText', ["$modal","mainSettings","$timeout","$compile",function ($modal, mainSettings, $timeout, $compile) {

    return {
        scope: {
            texts: "=texts",
            showPhotos: "&showPhotos"
        },
        replace: false,
        priority:1001,
        templateUrl:"pages/articles/includes/directives/multiple-text/index.html",
        link: function (scope, element, attrs) {

            if (angular.isUndefined(scope.texts)) {
                scope.texts = [{
                    title: "",
                    text: ""
                }];
            }

            // Tinymce
            scope.tinymceOptions = {
                width: 800,
                height: 520,
                plugins: 'textcolor media image code link paste link',
                toolbar: "link undo redo styleselect bold italic print forecolor backcolor media picture image",
                content_css: "pages/articles/includes/utils/tinymce/preview.css",
                convert_urls: false
            };

            scope.addField = function (k) {
                scope.texts.splice((k+1),0,{
                    title: "",
                    text: ""
                });
            }
            scope.removeField = function (index) {
                    scope.texts.splice(index, 1);
            }

            scope.showPhotos = function (where) {

               // scope.$parent.insertImageToTinyMCE(data);

                var modalInstance = $modal.open({
                    animation: true,
                    backdrop: 'static',
                    size: "lg",
                    templateUrl: 'pages/articlePhotos/index.html',
                    controller: "articlePhotosController"
                });

                modalInstance.result.then(function (data) {
                    switch (where) {
                        case "img_list":
                            $scope.insertImageToList(data);
                            break;
                        case "tinymce":
                            scope.$parent.insertImageToTinyMCE(data);
                            break;
                    }
                }, function () {
                    console.log('dismissed');
                });
            }
        },
        controller: function($scope, $element) {
            $timeout(function () {

            }, 1000);
        }
    }
}]);
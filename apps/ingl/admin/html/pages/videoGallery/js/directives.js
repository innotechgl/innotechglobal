app.directive("videoGalleryCategories", function () {
    return {
        strict: "E",
        scope: {
            categorySelected: "=categorySelected",
            categories: "=categories",
            edit: "&edit",
            delete: "&delete"
        },
        templateUrl:"pages/videoGallery/includes/categorySelect.html",
        link: function (scope) {
            scope.selectCategory = function (category) {
                scope.categorySelected = category;
            }
        }
    }
});
app.directive("videoGalleryCategoriesDrop", function () {
    return {
        strict: "E",
        scope: {
            categorySelected: "=categorySelected",
            categories: "=categories",
            title:"@title"
        },
        templateUrl: "pages/videoGallery/includes/drop.html",
        link: function (scope) {
            scope.rootCat = true;
            scope.selectCategory = function (category) {
                scope.categorySelected = category;
            }
        }
    }
});
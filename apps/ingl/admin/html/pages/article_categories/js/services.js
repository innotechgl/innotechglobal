app.service('articleCategoriesService', ['$rootScope', '$http','mainSettings', function ($rootScope, $http, mainSettings) {

    var getAllCategoriesURL       = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=getAll",
        getAllLanguagesURL        = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=getAllLanguages",
        getAllCategorieOptionsURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=getOptions",
        addURL                    = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=add",
        updateURL                 = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=update",
        deleteURL                 = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=delete",
        loadItemURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=article_categories&task=load&id=";


    // Load all menus
    this.loadAll = function () {
        var promise = $http.get(getAllCategoriesURL);
        return promise;
    }

    // Load all menus
    this.loadLanguages = function () {
        var promise = $http.get(getAllLanguagesURL);
        return promise;
    }

    // Load only 1 menu item
    this.loadCategoryItem = function (id) {
        var promise = $http.get(loadItemURL+id);
        return promise;
    }

    // load options for menus
    this.loadOptions = function () {
        var promise = $http.get(getAllCategorieOptionsURL);
        return promise;
    }

    // Update menu
    this.updateCategoryItem = function (categoryItem) {
        var promise = $http.post(updateURL, {
            data: categoryItem
        });

        return promise;
    }

    // Delete menu
    this.deleteCategoryItem = function (id) {
        var promise = $http.post(deleteURL, {
            data: { id: id }
        });

        return promise;
    }

    this.addNewItem = function (categoryItem) {
        var promise = $http.post(addURL, {
            data: categoryItem
        });

        return promise;
    }

}]);
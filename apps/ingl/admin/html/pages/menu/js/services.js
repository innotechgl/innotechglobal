app.service('menusService', ['$rootScope', '$http','mainSettings', function ($rootScope, $http, mainSettings) {

    // Define URL
    var getListURL            = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getAll",
        getOptionsURL         = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getOptions",
        getPagesURL           = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getPages",
        getTasksAndOptionsURL = mainSettings.mainURL + "?token=" + $rootScope.token,

        sortURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=sort",

        addURL                = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=add",
        updateURL             = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=update",
        deleteURL             = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=delete",
        loadItemURL           = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=menu&task=load&id=";

    // Load all menus
    this.loadAll = function () {
        var promise = $http.get(getListURL);
        return promise;
    }

    // Load only 1 menu item
    this.loadMenuItem = function (id) {
        var promise = $http.get(loadItemURL+id);
        return promise;
    }

    this.loadPages = function () {
        var promise = $http.get(getPagesURL);
        return promise;
    }

    // load options for menus
    this.loadMenusOptions = function () {
        var promise = $http.get(getOptionsURL);
        return promise;
    }

    // load options for menus
    this.loadTasksAndOptions = function (page) {
        var promise = $http.get(getTasksAndOptionsURL + "&page=" + page + "&task=getTasksAndOptions");
        return promise;
    }

    // Update menu
    this.updateMenuItem = function (menuItem) {
        var promise = $http.post(updateURL, {
            data: menuItem
        });

        return promise;
    }

    // Delete menu
    this.deleteMenuItem = function (id) {
        var promise = $http.post(deleteURL, {
            data:{id: id}
        });

        return promise;
    }

    this.addNewItem = function (menuItem) {
        var promise = $http.post(addURL, {
            data: menuItem
        });

        return promise;
    }

    this.updateSort = function (menusToSort) {

        var sortedMenus = [];

        angular.forEach(menusToSort, function (val, key) {
            sortedMenus.push(val.id);
        });

        $http.post(sortURL,
            { menus: sortedMenus }
        ).then(function (response) {


        });

    }

}]);
app.service("usersService", ["$http","$rootScope","mainSettings", function ($http, $rootScope, mainSettings) {

    var getListURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=getAll",
        getOptionsURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=getOptions",
        addURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=add",
        updateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=update",
        deleteURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=delete",
        activateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=activate",
        deactivateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=deactivate",
        loadItemURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=users&task=load&id=";

    // Load all menus
    this.loadAll = function () {
        var promise = $http.get(getListURL);
        return promise;
    }

    // load options for menus
    this.loadOptions = function () {
        var promise = $http.get(getOptionsURL);
        return promise;
    }

    this.register = function (userItem) {
        var promise = $http.post(addURL, {
            data: userItem
        });

        return promise;
    }

    this.update = function (userItem) {
        var promise = $http.post(updateURL, {
            data: userItem
        });

        return promise;
    }

    // Delete menu
    this.delete = function (id) {
        var promise = $http.post(deleteURL, {
            id: id
        });

        return promise;
    }

    // Delete menu
    this.activate = function (id) {
        var promise = $http.post(activateURL, {
            id: id
        });

        return promise;
    }

    // Delete menu
    this.deactivate = function (id) {
        var promise = $http.post(deactivateURL, {
            id: id
        });

        return promise;
    }


    this.load = function (id) {
        var promise = $http.get(loadItemURL+id);
        return promise;
    }

}]);
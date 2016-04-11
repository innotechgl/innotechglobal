app.service("videoGalleryService", ["$http", "mainSettings", "$rootScope", function ($http, mainSettings, $rootScope) {
    
    var getListURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=getAll",
        getOptionsURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=getOptions",
        addURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=add",
        updateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=update",
        deleteURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=delete",
        activateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=activate",
        deactivateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=deactivate",
        loadItemURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery&task=load&id=";

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

    this.add = function (data) {
        var promise = $http.post(addURL, {
            data: data
        });

        return promise;
    }

    this.update = function (data) {
        var promise = $http.post(updateURL, {
            data: data
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
        var promise = $http.get(loadItemURL + id);
        return promise;
    }

}]);


app.service("videoGalleryCategoriesService", ["$http", "mainSettings", "$rootScope", function ($http, mainSettings, $rootScope) {

    var getListURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=getAll",
        getOptionsURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=getOptions",
        addURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=add",
        updateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=update",
        deleteURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=delete",
        activateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=activate",
        deactivateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=deactivate",
        loadItemURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=videoGallery_categories&task=load&id=";

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

    this.add = function (data) {
        var promise = $http.post(addURL, {
            data: data
        });

        return promise;
    }

    this.update = function (data) {
        var promise = $http.post(updateURL, {
            data: data
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
        var promise = $http.get(loadItemURL + id);
        return promise;
    }

}]);

app.filter('trusted', ['$sce', function ($sce) {
    return function (url) {
        return $sce.trustAsResourceUrl(url);
    };
}]);
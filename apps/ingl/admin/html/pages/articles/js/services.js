app.service('articles', 
['$rootScope', '$http','mainSettings',
    function ($rootScope, $http, mainSettings) 
    {

    // Define URL
    var getListURL            = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getAll",
        getOptionsURL         = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getOptions",
        addURL                = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=add",
        updateURL             = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=update",
        deleteURL             = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=delete",

        multipleDeleteURL     = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=multipleDelete",

        sortURL               = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=sort",

        loadItemURL           = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=load&id=",
        activateURL           = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=activate",
        deactivateURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=articles&task=deactivate";



    // Load all menus
    this.loadAll = function () {
        var promise = $http.get(getListURL);
        return promise;
    }

    // Load only 1 menu item
    this.load = function (id) {
        var promise = $http.get(loadItemURL+id);
        return promise;
    }

    // load options for menus
    this.loadArticleOptions = function () {
        var promise = $http.get(getOptionsURL);
        return promise;
    }

    // Update menu
    this.update = function (articleItem) {
        var promise = $http.post(updateURL, {
            data: articleItem
        });

        return promise;
    }

    // Delete article
    this.delete = function (id) {
        var promise = $http.post(deleteURL, {
            data:{id: id}
        });

        return promise;
    }

    this.add = function (articleItem) {
        var promise = $http.post(addURL, {
            data: articleItem
        });

        return promise;
    }

    this.updateSort = function(articlesToSort){

        var sortedArticles = [];

        angular.forEach(articlesToSort, function(val,key){
            sortedArticles.push(val.id);
        });

        $http.post(sortURL,
            {articles:sortedArticles}
        )
            .then(function(response){

            });
    }

        this.activate = function(id){
            var promise = $http.post(activateURL, {id:id});
            return promise;
        }

        this.deactivate = function(id){
            var promise = $http.post(deactivateURL, {id:id});
            return promise;
        }

}]);
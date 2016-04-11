optimus.service("documents", ["mainSettings", "$http",
        function (mainSettings, $http) {

            var getAllURL = mainSettings.mainURL + "?page=clients&task=getAll",
                addURL = mainSettings.mainURL + "?page=clients&task=add",
                updateURL = mainSettings.mainURL + "?page=clients&task=update",
                deleteURL = mainSettings.mainURL + "?page=clients&task=delete",
                getTotalURL = mainSettings.mainURL + "?page=clients&task=getTotal",
                getUserInfoURL = mainSettings.mainURL + "?page=clients&task=getUserInfo&id=",
                searchURL = mainSettings.mainURL + "?page=clients&task=search",
                exportURL = mainSettings.mainURL + "?page=clients&task=export",
                saveDataURL = mainSettings.mainURL + "?page=clients&task=saveData";

            this.getAll = function () {
                var promise = $http.get(getAllURL);
                return promise;
            };

            this.add = function (item) {
                var promise = $http.post(addURL, { data: item });
                return promise;
            };

            this.update = function (item) {
                var promise = $http.post(updateURL,  item);
                return promise;
            };

            this.delete = function (id) {
                var promise = $http.post(deleteURL, { data: id });
                return promise;
            };

            this.getTotal = function () {
                var promise = $http.get(getTotalURL);
                return promise;
            };

            this.getUserInfo = function(id){
                var promise = $http.get(getUserInfoURL+id);
                return promise;
            };

            this.search = function (data) {
                    var promise = $http.post(searchURL,data);
                return promise;
            };

            this.saveData = function (data) {
                var promise = $http.post(saveDataURL,data);
                return promise;
            };

            this.export = function (data) {
                var promise = $http.post(exportURL, data);
                return promise;
            };



        }]);

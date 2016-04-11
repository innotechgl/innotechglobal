optimus.service("clients",
    ['$rootScope', '$http', 'mainSettings',
        function ($rootScope, $http, mainSettings) {

            var getListURL = mainSettings.mainURL + "?token=" + $rootScope.token + "&page=lan&task=getAll";
            var languages = [];

            var selectedLangauge = { short_name: "ALL", name: "Svi jezici" };


            // get lang list
            this.getLanguages = function(){
                return languages;
            }

            // Load all menus
            this.loadAll = function () {
                var promise = $http.get(getListURL);
                return promise;
            }

            // Set lang
            this.setLanguage = function (lang) {
                selectedLangauge = lang;
            }

            // Return lang
            this.getLanguage = function () {
                return selectedLangauge;
            }

            this.findLanguageByName = function (shortName) {
                for (var i = 0; i < languages.length; i++){
                    if (languages[i].short_name == shortName){
                        return languages[i];
                    }
                }
            }

            // Preload
            this.loadAll().then(function (response) {
                languages = response.data.data;
                languages.unshift({ short_name: "ALL", name: "Svi jezici" });
                $rootScope.$broadcast('languages:loaded');
            });

        }]);

optimus.service("user",
            ['$rootScope', '$http', 'mainSettings',
                function ($rootScope, $http, mainSettings) {

                    var token = null;
                    var logedIn = false;
                    var m = this;

                    this.logout = function () {
                        var promise = $http.get(mainSettings.mainURL + "?token=" + m.getToken() + "&page=users&task=logout");
                        return promise;
                    }

                    this.login = function (loginData) {
                        var promise = $http.post(mainSettings.mainURL + "?page=users&task=login", {
                            data:loginData
                        });
                        return promise;
                    }

                    this.setLogedIn = function (val) {
                        logedIn = val;
                    }

                    this.getLogedIn = function () {
                        return logedIn;
                    }

                    this.setToken = function (t) {
                        token = t;
                    }

                    this.getToken = function () {
                        return token;
                    }

            }]);

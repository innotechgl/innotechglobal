app.controller("menuListController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$routeParams", "$route",
    "menusService", "$filter",
    function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, menusService, $filter) {

        // URLS
        $scope.getListURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getAll";

        $scope.updateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=update";
        $scope.deleteURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=delete";
        $scope.activateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=activate";
        $scope.deactivateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=deactivate";

        // Menu
        $scope.menus = {};
        $scope.allMenus = {};
        $scope.parentMenuID = $routeParams.parent_id;
        $scope.path = [];
        $scope.orderedBy = 'no';
        $scope.orderDirection = '';
        
        $scope.sorting = false;

        // Single menu item
        $scope.menu = {};
        $scope.menuOptions = {};

        // Load menus
        $scope.loadMenus = function () {

            $http.get($scope.getListURL).success(function (data) {

                $scope.allMenus = data.data;
                $scope.menus = data.data;
                $scope.getPath($scope.parentMenuID);

            }).error(function (data) {
                console.log("ERROR loading.");
            });
        };

        $scope.delete = function (id) {
            if (confirm("Delete menu?")) {
                menusService.deleteMenuItem(id)
                    .then(function () {
                        var i = $scope.findMenuByID(id);
                        $scope.allMenus.splice(i, 1);
                    });
            }
        };

        $scope.$watchCollection('allMenus', function () {
            $scope.menus = $filter('orderBy')(filterMenus(), $scope.orderedBy, $scope.orderDirection);
        });

        function filterMenus() {

            var filtered = [];

            angular.forEach($scope.allMenus, function (val, key) {
                if (val.parent_id == $scope.parentMenuID) {
                    filtered.push(val);
                }
            });


            return filtered;
        }

        $scope.countChildMenus = function (id) {
            var c = 0;
            for (var i = 0; i < $scope.allMenus.length; i++) {
                if ($scope.allMenus[i].parent_id == id) {
                    c++;
                }
            }
            return c;
        };

        $scope.setOrderBy = function (val) {
            $scope.orderedBy = val;
        };

        $scope.changeParentID = function (id) {
            $scope.parentMenuID = id;
            $route.updateParams({parent_id: id});
            $scope.getPath(id);
        };

        $scope.getPath = function (id) {
            $scope.path = [];
            $scope._goThroughMenus(id);
            $scope.path.reverse();
        };

        $scope.findMenuByID = function (id) {
            for (var i = 0; i < $scope.allMenus.length; i++) {
                if ($scope.allMenus[i].id == id) {
                    return i;
                }
            }
        };
        
        $scope.toggleSorting = function () {
            if ($scope.sorting) {
                $scope.sorting = false;
            } else {
                $scope.sorting = true;
            }

        }

        $scope._goThroughMenus = function (id) {
            var index = 0;

            for (var i = 0; i < $scope.allMenus.length; i++) {

                console.log($scope.allMenus[i].id == id, $scope.allMenus[i].parent_id !== 0);

                if ($scope.allMenus[i].id == id) {
                    if ($scope.allMenus[i].parent_id !== 0) {

                        // Get index of parent menu
                        index = $scope.findMenuByID($scope.allMenus[i].parent_id);
                        console.log(i, index, id);
                        // Path
                        $scope.path.push({"id": $scope.allMenus[index].id, "name": $scope.allMenus[index].name});

                        // loop
                        $scope._goThroughMenus($scope.allMenus[index].id);
                    }
                    else {
                        return false;
                    }
                }

            }
        };

        $scope.activate = function (aid) {
            var index = $scope.findMenuByID(aid);
            $http.post($scope.activateURL, {id: aid}).success(function (data) {
                $scope.allMenus[index].active = 1;
            }).error(function (data) {

            })
        };

        $scope.deactivate = function (aid) {
            var index = $scope.findMenuByID(aid);
            $http.put($scope.deactivateURL, {id: aid, m: "a"}).success(function (data) {
                $scope.allMenus[index].active = 0;
            }).error(function (data) {

            })
        };


        $scope.saveSort = function () {
            // Articles
            menusService.updateSort($scope.menus);

            console.log('update sort');
        }

        $scope.loadMenus();

    }
]);
app.controller("menuFormController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$routeParams",
    "$route", "menusService", "languages", "$window",
    function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, menusService, languages, $window) {

        // Define URL
        $scope.getListURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getAll";
        $scope.getOptionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getOptions";
        $scope.getPagesURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getPages";
        $scope.saveURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=save";

        // URL definitions
        $scope.getTasksAndOptionsURL = $rootScope.mainURL + "?token=" + $rootScope.token;

        $scope.addURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=add";

        // menu object
        $scope.allMenus = {};
        $scope.parentMenuID = $routeParams.parent_id;
        $scope.options = {};
        $scope.pages = {};
        $scope.pageSelectedName = "";
        $scope.taskSelectedName = "";
        $scope.taskSelectedIndex = "";
        $scope.languages = {};

        console.log('ROUTE PARAMS:', $routeParams);
        $scope.editorTask = 'add';

        $scope.languageSelected = {short_name: "ALL", name: "Svi jezici"};

        $scope.generatorVisible = false;

        // Data
        $scope.tasksAndOptions = "";
        $scope.task = "";
        $scope.linkFields = {};
        $scope.fieldValues = {};

        // Define form
        $scope.menuForm = {parent_id: $routeParams.parent_id, name: ""};
        $scope.canEditAlias = true;


        $scope.$watch("languageSelected", function () {
            // change lang
            $scope.menuForm.language = $scope.languageSelected.short_name;
        });

        // Load menus
        $scope.loadMenus = function () {

            $http.get($scope.getListURL).success(function (data) {
                $scope.allMenus = data.data;

            }).error(function (data) {
                console.log("ERROR loading.");
            });
        };

        $scope.loadPages = function () {
            $http.get($scope.getPagesURL).success(function (data) {
                $scope.pages = data.data;
            }).error(function (data) {
                console.log("ERROR loading.");
            });
        };

        $scope.loadOptions = function () {
            $http.get($scope.getOptionsURL).success(function (data) {
                $scope.options = data.data.options.option;
                console.log($scope.options);

            }).error(function (data) {
                console.log("ERROR loading.");
            });
        };

        $scope.pageSelectedLinkCreator = function () {

            return "pages/menu/includes/menuCreator/menuCreator.html";
        };

        $scope.pageSelected = function () {
            $scope.loadTaskAndOptions();
        };

        // Include element
        $scope.includeElement = function (option) {
            switch (option.type) {
                case "drop":
                    return "pages/menu/includes/dropOption.html";
                    break;
                case "text":
                    return "pages/menu/includes/text.html";
                    break;
            }
        };

        $scope.$watch('menuForm.name', function () {
            var name = '';
            if ($scope.canEditAlias) {
                if (!angular.isUndefined($scope.menuForm.name)) {
                    name = $scope.menuForm.name;
                    $scope.menuForm.alias = name.replace(/ /g, '-').replace(/[^\w-]+/g, '');
                }

            }
            else {

            }
        });

        $scope.findMenuByID = function (id) {
            for (var i = 0; i < $scope.allMenus.length; i++) {
                if ($scope.allMenus[i].id == id) {
                    return i;
                }
            }
        };

        $scope.changed = function (field) {
            if (!angular.isUndefined(field.onChange)) {
                if (!angular.isUndefined(field.onChange.load)) {
                    $scope.loadFieldDataOnDemand(field.onChange.load, field.fieldName);
                }
            }
        };

        $scope.selectLanguage = function (lang) {
            $scope.languageSelected = lang;
        };

        $scope.showMenus = function () {
            console.log($scope.menuForm);
        };

        // Load tasks and options
        $scope.loadTaskAndOptions = function () {

            $http.get($scope.getTasksAndOptionsURL + "&page=" + $scope.pageSelectedName + "&task=getTasksAndOptions").success(function (data) {

                $scope.tasksAndOptions = data.data;

            }).error(function (data) {
                console.log("ERROR loading.");
            });
        };

        $scope.loadField = function (fieldType, f) {
            console.log(fieldType, "pages/menu/includes/menuCreator/" + fieldType + ".html", f);
            return "pages/menu/includes/menuCreator/" + fieldType + ".html";
        };

        $scope.loadFieldData = function (field, firstLoad) {

            console.log((firstLoad && field.autoLoad == "true"), firstLoad, field.autoLoad == "true", field.autoLoad, field);

            if (firstLoad && field.autoLoad == "true") {
                $http.get($rootScope.mainURL + "?token=" + $rootScope.token + "&" + field.urlData).success(function (data) {
                    $scope.fieldValues[field.fieldName] = data.data;
                }).error(function () {
                    console.log("ERROR loading.");
                });
            }
        };

        $scope.loadFieldDataOnDemand = function (fieldNameToFill, relatedField) {

            var url = '';

            angular.forEach($scope.tasksAndOptions.tasks.task[$scope.taskSelectedIndex], function (val, key) {
                angular.forEach(val.field, function (v, k) {
                    if (v.fieldName == fieldNameToFill) {

                        url = $rootScope.mainURL + "?token=" + $rootScope.token + "&" + v.urlData + "&" +
                            relatedField + "=" + $scope.linkFields[relatedField];

                    }
                });
            });

            $http.get(url)
                .success(function (data) {
                    $scope.fieldValues[fieldNameToFill] = data.data;
                }).error(function () {
                console.log("ERROR loading.");
            });

        };

        $scope.taskChanged = function (t) {
            $scope.taskSelectedName = $scope.tasksAndOptions.tasks.task[t].name;
            $scope.taskSelectedIndex = t;
        };

        $scope.generateLink = function () {
            var linkString = "page=" + $scope.pageSelectedName + "&task=" + $scope.taskSelectedName;

            angular.forEach($scope.linkFields, function (value, key) {
                linkString += "&" + key + "=" + value;
            });

            $scope.menuForm.link = linkString;

            // hide generator
            $scope.generatorVisible = false;
        };

        $scope.testArray = function (ar) {
            if (angular.isArray(ar)) {
                return true;
            }
            else {
                return false;
            }
        };


        // Saving data
        $scope.save = function () {
            $http.post($scope.saveURL, {data: $scope.menuForm}).success(function (data) {
                $window.history.back();
            }).error(function (data) {
                alert('error ' + data);
            });
        };

        // Load everything we need
        languages
            .loadAll()
            .then(function (response) {
                $scope.languages = response.data.data;
            });

        $scope.loadMenus();
        $scope.loadOptions();
        $scope.loadPages();

    }]);

app.controller("menuEditFormController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$route",
    "$routeParams", "menusService", "categorise", "languages", "$window",
    function ($scope, $http, $location, $rootScope, $cookies, $route,
              $routeParams, menusService, categorise, languages, $window) {

        // menu object
        $scope.allMenus = {};
        $scope.parentMenuID = 0;
        $scope.options = {};
        $scope.pages = {};
        $scope.pageSelectedName = "";
        $scope.taskSelectedName = "";
        $scope.taskSelectedIndex = "";

        $scope.generatorVisible = false;

        // Data
        $scope.tasksAndOptions = "";
        $scope.task = "";
        $scope.linkFields = {};
        $scope.fieldValues = {};

        // Define form
        $scope.menuForm = {};
        $scope.canEditAlias = true;

        $scope.categoriesNested = [];

        $scope.parentSelected = {};

        $scope.editorTask = 'edit';

        /***
         Load
         ***/
            // All menus
        menusService.loadAll()
            .then(function (response) {

                // Set menus
                $scope.allMenus = response.data.data;
                $scope.allMenus.unshift({name: "ROOT", id: 0, parent_id: -1});
                $scope.categoriesNested = categorise.do($scope.allMenus, -1);

                // All options for menus
                menusService.loadMenusOptions()
                    .then(function (response) {

                        $scope.options = response.data.data.options.option;

                        // All available pages
                        menusService.loadPages()
                            .then(function (response) {

                                $scope.pages = response.data.data;

                                // Load menu item for editing
                                menusService.loadMenuItem($routeParams.id)
                                    .then(function (response) {

                                        // set menu form
                                        $scope.menuForm = response.data.data;

                                        // Get parent id
                                        var index = $scope.findMenuByID($scope.menuForm.parent_id);
                                        $scope.selectParent($scope.allMenus[index]);

                                        $scope.selectLanguage(languages.findLanguageByName($scope.menuForm.language))

                                    });
                            })
                    })
            });


        $scope.$watch('menuForm.name', function () {
            var title = '';
            if ($scope.canEditAlias) {
                title = $scope.menuForm.name;

                $scope.menuForm.alias = fixAlias(title);
            }
        });

        // String
        function fixAlias(str) {

            var from = "čćšđžàáäâèéëêìíïîòóöôùúüûñç·/_,:;";
            var to = "ccsdzaaaaeeeeiiiioooouuuunc------";

            str = str.toLowerCase();

            for (var i = 0, l = from.length; i < l; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/ /g, '-').replace(/[^\w-]+/g, '');

            return str;
        }

        $scope.pageSelectedLinkCreator = function () {
            return "pages/menu/includes/menuCreator/menuCreator.html";
        };

        $scope.selectParent = function (menu) {
            $scope.parentMenuID = menu.id;
            $scope.menuForm.parent_id = menu.id;
            $scope.parentSelected = menu;
        };

        $scope.checkParentID = function (id) {
            if (id == $routeParams.id && $routeParams.id > 0) {
                return true;
            }
            return false;
        };

        $scope.testArray = function (ar) {
            if (angular.isArray(ar)) {
                return true;
            }
            else {
                return false;
            }
        };


        $scope.pageSelected = function () {
            menusService.loadTasksAndOptions($scope.pageSelectedName)
                .then(function (response) {

                    $scope.tasksAndOptions = response.data.data;
                })
        };

        // Include element
        $scope.includeElement = function (option) {
//            console.log('option', option.type);
            switch (option.type) {
                case "drop":
                    return "pages/menu/includes/dropOption.html";
                    break;
                case "text":
                    return "pages/menu/includes/text.html";
                    break;
            }
        };

        $scope.loadField = function (fieldType, f) {
            //   console.log(fieldType, "pages/menu/includes/menuCreator/" + fieldType + ".html",f);
            return "pages/menu/includes/menuCreator/" + fieldType + ".html";
        };

        $scope.loadFieldData = function (field, firstLoad) {

            //     console.log((firstLoad && field.autoLoad == "true"), firstLoad, field.autoLoad == "true", field.autoLoad, field);

            if (firstLoad && field.autoLoad == "true") {
                $http.get($rootScope.mainURL + "?token=" + $rootScope.token + "&" + field.urlData).success(function (data) {
                    $scope.fieldValues[field.fieldName] = data.data;
                }).error(function () {
                    console.log("ERROR loading.");
                });
            }
        };

        $scope.loadFieldDataOnDemand = function (fieldNameToFill, relatedField) {

            console.log('LOAD', fieldNameToFill, relatedField);

            var url = '';

            angular.forEach($scope.tasksAndOptions.tasks.task[$scope.taskSelectedIndex], function (val, key) {
                angular.forEach(val.field, function (v, k) {
                    if (v.fieldName == fieldNameToFill) {

                        url = $rootScope.mainURL + "?token=" + $rootScope.token + "&" + v.urlData + "&" +
                            relatedField + "=" + $scope.linkFields[relatedField];

                    }
                });
            });

            $http.get(url)
                .success(function (data) {
                    $scope.fieldValues[fieldNameToFill] = data.data;
                }).error(function () {
                console.log("ERROR loading.");
            });

        };

        $scope.selectLanguage = function (lang) {
            $scope.languageSelected = lang;
            $scope.menuForm.language = lang.short_name;
        };

        $scope.loadFieldDataOnDemand = function (fieldNameToFill, relatedField) {

            var url = '';

            console.log('foreach', $scope.tasksAndOptions.tasks.task[$scope.taskSelectedIndex]);

            angular.forEach($scope.tasksAndOptions.tasks.task[$scope.taskSelectedIndex], function (val, key) {

                angular.forEach(val.field, function (v, k) {
                    if (v.fieldName == fieldNameToFill) {

                        url = $rootScope.mainURL + "?token=" + $rootScope.token + "&" + v.urlData + "&" +
                            relatedField + "=" + $scope.linkFields[relatedField];

                    }
                });
            });

            $http.get(url)
                .success(function (data) {
                    $scope.fieldValues[fieldNameToFill] = data.data;
                }).error(function () {
                console.log("ERROR loading.");
            });

        };

        $scope.changed = function (field) {
            console.log('field', field);
            if (!angular.isUndefined(field.onChange)) {
                if (!angular.isUndefined(field.onChange.load)) {
                    console.log('load', field.onChange.load);
                    $scope.loadFieldDataOnDemand(field.onChange.load, field.fieldName);
                }
            }
        };

        $scope.taskChanged = function (t) {
            $scope.taskSelectedName = $scope.tasksAndOptions.tasks.task[t].name;
            $scope.taskSelectedIndex = t;
        };


        $scope.save = function () {
            menusService.updateMenuItem($scope.menuForm)
                .then(function (response) {
                    $window.history.back();
                }, function (error) {
                    console.log('menu save error: ', error);
                });
        };

        $scope.findMenuByID = function (id) {
            //console.log('find ',id);
            for (var i = 0; i < $scope.allMenus.length; i++) {
                if ($scope.allMenus[i].id == id) {
                    return i;
                }
            }
            return -1;
        }

        $scope.generateLink = function () {
            var linkString = "page=" + $scope.pageSelectedName + "&task=" + $scope.taskSelectedName;

            angular.forEach($scope.linkFields, function (value, key) {
                linkString += "&" + key + "=" + value;
            });

            $scope.menuForm.link = linkString;

            // hide generator
            $scope.generatorVisible = false;
        };


    }]);
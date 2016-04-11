/*List controller*/
app.controller("widgetsListController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$routeParams", "$route",
        function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route) {

            // URLS
            $scope.getListURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getAll";
            $scope.getAllAvailableWidgetsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getAllAvailableWidgets";

            $scope.updateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=update";
            $scope.deleteURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=delete";
            $scope.multipleDeleteURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=multipleDelete";
            $scope.activateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=activate";
            $scope.deactivateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=deactivate";
            $scope.getPositionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getPositions";

            // widgets
            $scope.widgets = {};
            $scope.allwidgets = [];
            $scope.categories = {};
            $scope.categoriesNested = {};
            $scope.path = [];

            $scope.allAvailableWidgets = {};

            $scope.search = "";

            $scope.positions = {};

            $scope.orderedBy = 'id';
            $scope.orderDirection = "desc";

            $scope.categorySelected = { id: 0, parent_id: -1, name: "" }

            // pagination
            $scope.totalItems = 0;
            $scope.itemsPerPage = 15;
            $scope.currentPage = 1;

            $scope.multipleSelect = {};


            $scope.$watchCollection('allwidgets', function () {
                //$scope.currentPage = 1;
                $scope.totalItems = $scope.allwidgets.length;
                var begin = (($scope.currentPage - 1) * $scope.itemsPerPage),
                          end = begin + $scope.itemsPerPage;

                $scope.widgets = $scope.allwidgets.slice(begin, end);
            });



            /*******************
            CATEGORIES FUNCTIONS 
            ********************/
            function getNestedChildren(arr, parent) {
                var out = []
                for (var i in arr) {
                    if (arr[i].parent_id == parent) {
                        var children = getNestedChildren(arr, arr[i].id)

                        if (children.length) {
                            arr[i].children = children
                        }
                        out.push(arr[i])
                    }
                }
                return out
            }

            // Load widgets
            $scope.loadPositions = function () {

                var promise = $http.get($scope.getPositionsURL).success(function (data) {
                    $scope.positions = data.data.positions;
                    console.log($scope.positions);
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }

            // Load widgets
            $scope.getAllAvailableWidgets = function () {

                var promise = $http.get($scope.getAllAvailableWidgetsURL).success(function (data) {
                    $scope.allAvailableWidgets = data.data;
                    console.log($scope.allAvailableWidgets);
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }
            
            
            // Load widgets
            $scope.loadwidgets = function () {

                $http.get($scope.getListURL).success(function (data) {

                    $scope.allwidgets = data.data;
                    $scope.totalItems = $scope.allwidgets.length;

                    //$scope.widgets = $scope.allwidgets.slice(0, $scope.maxSize);

                    /* 
                    PAGINATION FUNCTIONS
                    */
                    $scope.$watch('currentPage + itemsPerPage', function () {
                        var begin = (($scope.currentPage - 1) * $scope.itemsPerPage),
                          end = begin + $scope.itemsPerPage;

                        $scope.widgets = $scope.allwidgets.slice(begin, end);
                        console.log('widgets length first', $scope.widgets.length);
                    });

                }).error(function (data) {
                    console.log("ERROR loading.");
                });
            }

            $scope.delete = function (id) {
                if (confirm("Are you shure that you want to delete widget?")) {
                    $http.post($scope.deleteURL, { data: { id: id } })
                    .success(function (data) {
                        deleteWidgetFromArray(id);
                    })
                    .error(function (data) {

                    });
                }
            }

            $scope.multipleDelete = function () {
                if (confirm("Do you realy want to delete selected widgets?")) {

                    var ids = [];
                    angular.forEach($scope.multipleSelect, function (v, k) {
                        ids.push($scope.widgets[k].id);
                    });

                    $http.post($scope.multipleDeleteURL, { data: { ids: ids } })
                        .success(function (data) {
                            for (var i = 0; i < ids.length; i++) {
                                $scope.deleteArticleFromArray(ids[i]);
                            }
                        })
                        .error(function (data) {

                        });
                }
            }

            function deleteWidgetFromArray (id) {
                var index = $scope.findWidgetByID(id);
                $scope.allwidgets.splice(index, 1);
            }

            $scope.setOrderBy = function (val) {
               

                if ($scope.orderDirection=="desc"){
                    $scope.orderedBy = val;
                    $scope.orderDirection = "asc";
                }
                else {
                    $scope.orderedBy = -val;
                    $scope.orderDirection = "desc";
                }
            }

            $scope.findWidgetByID = function (id) {
                for (var i = 0; i < $scope.allwidgets.length; i++) {
                    if ($scope.allwidgets[i].id == id) {
                        return i;
                    }
                }
                return false;
            }

            $scope.activate = function (aid) {
                var index = $scope.findWidgetByID(aid);
                $http.post($scope.activateURL, { id: aid }).success(function (data) {
                    $scope.allwidgets[index].active = 1;
                }).error(function (data) {

                })
            }

            $scope.deactivate = function (aid) {
                var index = $scope.findWidgetByID(aid);
                $http.post($scope.deactivateURL, { id: aid }).success(function (data) {
                    $scope.allwidgets[index].active = 0;
                }).error(function (data) {

                })
            }
            
            $scope.loadPositions();
            $scope.getAllAvailableWidgets();
            $scope.loadwidgets();
        }
]);

/* Add widget controller */
app.controller("widgetAddFormController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$routeParams", "$route",
    "$modal", "$filter", "$window","categorise",
        function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, $modal, $filter, $window, categorise) {

            // Define URL
            $scope.getAllAvailableWidgetsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getAllAvailableWidgets";
            $scope.getAllMenusURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getAll";
            $scope.saveURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=save";

            $scope.getPositionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getPositions";

            $scope.positions = {};

            // Data
            $scope.allAvailableWidgets = {};

            // Menus
            $scope.menus = {};
            $scope.menuSelect = {};
            $scope.categorisedMenus = [];

            $scope.selectedPosition = {};

            $scope.visibleAt = [];

            // Define form
            $scope.widgetForm = {};

            $scope.name = '';
            $scope.active = 0;
            
            $scope.m = "";

            $scope.selectedWidget = {name:"-select widget-",options:{}};

            // Tinymce
            $scope.tinymceOptions = {
                height: 300,
                plugins: 'textcolor media image code link paste',
                toolbar: "undo redo styleselect bold italic print forecolor backcolor"
            };

            $scope.$watchCollection('widgetForm', function(){
                console.log($scope.widgetForm);
            });

            // Load widgets
            $scope.loadPositions = function () {

                var promise = $http.get($scope.getPositionsURL).success(function (data) {
                    $scope.positions = data.data.positions;
                    console.log($scope.positions);
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }

            $scope.menuSelectClicked = function (el, id) {

                if (el[id] == true) {
                    $scope.visibleAt.push(id);
                }
                else {
                    removeField(id);
                }

                function removeField(id) {
                    for (var i = 0; i < $scope.visibleAt.length; i++) {
                        if ($scope.visibleAt[i] == id) {
                            $scope.visibleAt.splice(i, 1);
                            return;
                        }
                    }
                }
            }

            $scope.$watch('selectedWidget', function () {
                var val = '';

                $scope.widgetForm = {};

                angular.forEach($scope.selectedWidget.options.option, function (v, k) {
                    val = '';
                    if (!angular.isObject(v.defaultValue)) {
                        val = v.defaultValue;
                    }
                    $scope.widgetForm[v.name] = val;
                });

            });

            $scope.setSelectedWidget = function (widget) {
                $scope.selectedWidget = widget;
            }

            $scope.setSelectedOption = function (field, value) {
                $scope.widgetForm[field] = value;
                console.log(field, value);
            }

            $scope.loadField = function (f) {
                var field = false;
                switch (f.fieldType) {
                    
                    default:
                    case "text":
                        field = 'text';
                        break;
                    case "select":
                        field = 'drop';
                        break;
                    case "advanced_select":
                        field = 'advanced_select';
                        break;
                    case "advanced_article_select":
                        field = "advanced_article_select";
                        break;
                        
                }
                return "pages/widgets/form/includes/" + field + ".html";
            }

            $scope.selectPosition = function (position) {
                $scope.selectedPosition = position;
            }

            // Load widgets
            $scope.getAllAvailableWidgets = function () {

                var promise = $http.get($scope.getAllAvailableWidgetsURL).success(function (data) {
                    $scope.allAvailableWidgets = data.data;
                    console.log($scope.allAvailableWidgets);
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }

            $scope.getAllMenus = function () {
                var promise = $http.get($scope.getAllMenusURL).success(function (data) {
                    $scope.menus = data.data;
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;
            }

            $scope.loadFieldData = function (field, firstLoad) {

                console.log((firstLoad && field.autoLoad == "true"), firstLoad, field.autoLoad == "true", field.autoLoad, field);

                if (firstLoad && field.autoLoad == "true") {
                    $http.get($rootScope.mainURL + "?token=" + $rootScope.token + "&" + field.urlData).success(function (data) {
                        $scope.fieldValues[field.fieldName] = data.data;
                    }).error(function () {
                        console.log("ERROR loading.");
                    });
                }
            }

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

            }

            // Saving data
            $scope.save = function () {

                console.log('save');

                $http.post($scope.saveURL, {
                    data: {
                        widget: $scope.widgetForm,
                        position: $scope.selectedPosition.position,
                        visible_at: $scope.visibleAt,
                        name:$scope.name,
                        native_name: $scope.selectedWidget.name,
                        type: "standard",
                        active:$scope.active
                    }
                }).success(function (data) {
                    console.log(data);
                    $window.history.back();
                }).error(function (data) {

                });
            }

            $scope.getAllAvailableWidgets()
                .then(function () {
                    $scope.getAllMenus()
                    .then(function () {
                        $scope.categorisedMenus = categorise.do($scope.menus);
                        $scope.loadPositions();
                    });
                });

        }]);

/* Edit widget controller */
app.controller("widgetEditFormController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$routeParams", "$route",
    "$modal", "$filter", "$window", "categorise",
        function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, $modal, $filter, $window, categorise) {

            // Define URL
            $scope.getAllAvailableWidgetsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getAllAvailableWidgets";
            $scope.getAllMenusURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=menu&task=getAll";

            $scope.loadWidgetURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=loadWidget&id=" + $routeParams.id;

            $scope.saveURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=update";

            $scope.getPositionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getPositions";

            $scope.positions = {};

            // Data
            $scope.allAvailableWidgets = {};

            // Menus
            $scope.menus = {};
            $scope.menuSelect = {};
            $scope.categorisedMenus = [];

            $scope.selectedPosition = {};

            

            $scope.visibleAt = [];

            // Define form
            $scope.widgetForm = {};

            $scope.name = '';
            $scope.active = 0;

            $scope.m = {};

            $scope.selectedWidget = { name: "-select widget-", options: {} };

            // Tinymce
            $scope.tinymceOptions = {
                height: 300,
                plugins: 'textcolor media image code link paste',
                toolbar: "undo redo styleselect bold italic print forecolor backcolor"
            };

            // Load positions
            $scope.loadWidget = function () {

                var promise = $http.get($scope.loadWidgetURL)
                    .success(function (data) {

                        $scope.selectedWidget = findNativeNameWidget(data.data.native_name);

                        // Set init values
                        angular.forEach($scope.selectedWidget.options.option, function (v, k) {
                            val = '';
                            if (!angular.isObject(v.defaultValue)) {
                                val = v.defaultValue;
                            }
                            $scope.widgetForm[v.name] = val;
                        });

                        // Change init values with data from DB
                        $scope.widgetForm = data.data.widget;

                        // Select position
                        $scope.selectedPosition = findPosition(data.data.position);

                        // Set visible data
                        $scope.visibleAt = data.data.visible_at;

                        // Select menu 
                        setMenuSelect($scope.visibleAt);

                        // Select checkboxes with loaded data
                        selectCheckboxes();

                        $scope.type = data.data.type;
                        $scope.name = data.data.name;
                        $scope.active = data.data.active;

                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;
            }

            function selectCheckboxes() {
                angular.forEach($scope.selectedWidget.options.option, function (v, k) {
                    if (v.fieldType == "advanced_select") {
                        setSelectedOptions(v.name);
                    }
                    
                });
            }

            function findNativeNameWidget(nativeName) {
                console.log('find',nativeName);
                for (var i = 0; i < $scope.allAvailableWidgets.length; i++) {
                    if (nativeName == $scope.allAvailableWidgets[i].name) {
                        console.log('found', $scope.allAvailableWidgets[i]);
                        return $scope.allAvailableWidgets[i];
                    }
                }
            }

            function findPosition(position) {
                console.log('find', position);
                for (var i = 0; i < $scope.positions.length; i++) {
                    console.log('find', position, $scope.positions[i].position);
                    if (position == $scope.positions[i].position) {
                        console.log('found', $scope.positions[i]);
                        return $scope.positions[i];
                    }
                }
            }

            function setMenuSelect(menus) {
                var m = '';
                for (var i = 0; i < menus.length; i++) {
                    m = menus[i].toString();
                    $scope.menuSelect[m] = true;
                }
            }

            function setSelectedOptions(optionName) {
                var m = '';
                $scope.m[optionName] = {};
                for (var i = 0; i < $scope.widgetForm[optionName].length; i++) {
                    m = $scope.widgetForm[optionName][i].toString();
                    $scope.m[optionName][m] = true;
                }
            }

            // Load positions
            $scope.loadPositions = function () {

                var promise = $http.get($scope.getPositionsURL).success(function (data) {
                    $scope.positions = data.data.positions;
                    console.log($scope.positions);
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }

            $scope.menuSelectClicked = function (el, id) {

                if (el[id] == true) {
                    $scope.visibleAt.push(id);
                }
                else {
                    removeField(id);
                }

                function removeField(id) {
                    for (var i = 0; i < $scope.visibleAt.length; i++) {
                        if ($scope.visibleAt[i] == id) {
                            $scope.visibleAt.splice(i, 1);
                            return;
                        }
                    }
                }
            }

            $scope.setSelectedWidget = function (widget) {
                $scope.selectedWidget = widget;
            }

            $scope.setSelectedOption = function (field, value) {
                $scope.widgetForm[field] = value;
            }

            $scope.loadField = function (f) {
                var field = false;
                switch (f.fieldType) {

                    default:
                    case "text":
                        field = 'text';
                        break;
                    case "select":
                        field = 'drop';
                        break;
                    case "advanced_select":
                        field = 'advanced_select';
                        break;

                }
                return "pages/widgets/form/includes/" + field + ".html";
            }

            $scope.selectPosition = function (position) {
                $scope.selectedPosition = position;
            }

            // Load widgets
            $scope.getAllAvailableWidgets = function () {

                var promise = $http.get($scope.getAllAvailableWidgetsURL).success(function (data) {
                    $scope.allAvailableWidgets = data.data;
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }

            $scope.getAllMenus = function () {
                var promise = $http.get($scope.getAllMenusURL).success(function (data) {
                    $scope.menus = data.data;
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;
            }

            $scope.loadFieldData = function (field, firstLoad) {

                console.log((firstLoad && field.autoLoad == "true"), firstLoad, field.autoLoad == "true", field.autoLoad, field);

                if (firstLoad && field.autoLoad == "true") {
                    $http.get($rootScope.mainURL + "?token=" + $rootScope.token + "&" + field.urlData).success(function (data) {
                        $scope.fieldValues[field.fieldName] = data.data;
                    }).error(function () {
                        console.log("ERROR loading.");
                    });
                }
            }

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

            }

            // Saving data
            $scope.save = function () {
                $http.post($scope.saveURL, {
                    data: {
                        widget: $scope.widgetForm,
                        position: $scope.selectedPosition.position,
                        visible_at: $scope.visibleAt,
                        name: $scope.name,
                        native_name: $scope.selectedWidget.name,
                        type: "standard",
                        active: $scope.active,
                        id:$routeParams.id
                    }
                }).success(function (data) {
                    $window.location.href = "#/pages/widgets/list/";
                }).error(function (data) {

                });
            }

            $scope.getAllAvailableWidgets()
                .then(function () {
                    $scope.getAllMenus()
                    .then(function () {
                        $scope.categorisedMenus = categorise.do($scope.menus);
                        $scope.loadPositions()
                            .then(function () {
                                $scope.loadWidget();
                            });
                    })
                });

        }]);

app.controller("widgetSortController", ["$scope", "$http", "$location", "$rootScope", "$cookies","$filter",
        function ($scope, $http, $location, $rootScope, $cookies, $filter) {
            
            $scope.getListURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getAll";
            $scope.saveSortURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=sort";

            $scope.getPositionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=widgets&task=getPositions";
            
            $scope.allWidgets = {};
            $scope.selectedWidgets = {};
            $scope.sorted = {};

            $scope.alerts = [];

            $scope.closeAlert = function (index) {
                $scope.alerts.splice(index, 1);
            };

            $scope.selectedPosition = { name: "-select position-", position: "", description: "" };

            $scope.$watch('selectedPosition', function () {
                $scope.selectedWidgets = $filter('filter')($scope.allWidgets, { position: $scope.selectedPosition.position });
                console.log('changed', $scope.allWidgets, $scope.selectedWidgets);
            });

            $scope.$watchCollection('selectedWidgets', function () {
                
                console.log('changed sort', $scope.selectedWidgets);
            });

            $scope.selectPosition = function (position) {
                $scope.selectedPosition = position;
            }


            // Load positions
            $scope.loadPositions = function () {

                var promise = $http.get($scope.getPositionsURL).success(function (data) {
                    $scope.positions = data.data.positions;
                }).error(function (data) {
                    console.log("ERROR loading.");
                });

                return promise;

            }

            // Load widgets
            $scope.loadWidgets = function () {

                $http.get($scope.getListURL)
                    .success(function (data) {
                    $scope.allWidgets = data.data;
                }).error(function (data) {
                    console.log("ERROR loading.");
                });
            }



            $scope.save = function () {
                $http.post($scope.saveSortURL, {
                    widgets: prepareData()
                }).success(function () {
                    $scope.alerts.push({ msg: "Saved", type: "success" });
                }).error(function () {

                });
            }


            function prepareData() {

                var data = [], obj = {};

                angular.forEach($scope.selectedWidgets, function (v,k) {
                    obj = {};
                    obj.id = v.id;
                    obj.no = k;
                    data.push(obj);
                });

                return data;
            }

            // LOAD ALL
            var promise = $scope.loadPositions();

            promise.then(function () {
                $scope.loadWidgets();
            });

        }]);

/* Advanced select directive for Widgets */
app.directive("widget", ["$http", "$rootScope", "$compile", function ($http, $rootScope, $compile) {

    return {
        restrict: "AEC",
        replace:false,
        scope: {
            setSelectedOption: "&",
            option: "=opt",
            m: "=m",
            fields:"=fields"
        },
        templateUrl: 'pages/widgets/form/includes/advanced_selectD.html',
        link: function (scope, elm, attrs, ctrl) {
            // pre: {
            this.option = scope.option;
            if (!angular.isObject(scope.$parent.widgetForm[scope.option.name])){
                scope.$parent.widgetForm[scope.option.name] = [];
            }
            this.fields = scope.$parent.widgetForm[scope.option.name];
           
                $http.get($rootScope.mainURL + "?token=" + $rootScope.token + "&" + scope.option.selectFrom)
                    .success(function (data) {
                        scope.categorised = getNestedChildren(data.data, 0);
                    })
                    .error(function (data) {

                    });
                
                scope.clicked = function (el, id) {
                    
                    if (el[id] == true){
                        this.fields.push(id);
                    }
                    else {
                        removeField(this.fields, id);
                    }
                    scope.$parent.widgetForm[scope.option.name] = this.fields;
                }.bind(this)
          
        }
    };

    function removeField(fields,id){
        for (var i = 0; i < fields.length; i++) {
            if (fields[i] == id) {
                fields.splice(i, 1);
                return;
            }
        }
    }

    /*******************
            CATEGORIES FUNCTIONS 
            ********************/
    function getNestedChildren(arr, parent) {
        var out = []
        for (var i in arr) {
            if (arr[i].parent_id == parent) {
                var children = getNestedChildren(arr, arr[i].id)

                if (children.length) {
                    arr[i].children = children
                }
                out.push(arr[i])
            }
        }
        return out
    }
}]);
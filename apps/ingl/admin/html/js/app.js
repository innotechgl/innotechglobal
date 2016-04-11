var app = angular.module("anSCMS", ["ngRoute", "ngCookies", "ui.bootstrap", "ui.tinymce", "ngFileUpload", "ngMap", "ui"]);

var POPOVER_SHOW = 'popoverToggleShow';
var POPOVER_HIDE = 'popoverToggleHide';

app.config(function ($routeProvider, $locationProvider, $controllerProvider) {

    // remember mentioned function for later use
    app.registerCtrl = $controllerProvider.register;

    $routeProvider
        .when('/login', {
            templateUrl: 'pages/login.html',
            controller: 'loginController'
        })

        /* MENU */
        .when('/pages/menu/list/:parent_id', {
            templateUrl: 'pages/menu/list/index.html',
            controller: 'menuListController'
        })
        .when('/pages/menu/add/:parent_id', {
            templateUrl: 'pages/menu/form/index.html',
            controller: 'menuFormController'
        })
        .when('/pages/menu/edit/:id', {
            templateUrl: 'pages/menu/form/index.html',
            controller: 'menuEditFormController'
        })

        /* ARTICLES */
        .when('/pages/articles/list/', {
            templateUrl: 'pages/articles/list/index.html',
            controller: 'articleListController'
        })
        .when('/pages/articles/form/:task/:id?', {
            templateUrl: 'pages/articles/form/index.html',
            controller: 'articleFormController'
        })

        /* BANNERS */
        .when('/pages/banners/list/', {
            templateUrl: 'pages/banners/list/index.html',
            controller: 'bannersListController'
        })

        /* VIDEO GALLERY */
        .when('/pages/videoGallery/list/', {
            templateUrl: 'pages/videoGallery/list/index.html',
            controller: 'videoGalleryListController'
        })
        /* VIDEO GALLERY */
        .when('/pages/videoGallery/form/:task/:id?', {
            templateUrl: 'pages/videoGallery/form/index.html',
            controller: 'videoGalleryFormController'
        })

        /* WIDGETS */
        .when('/pages/widgets/list/', {
            templateUrl: 'pages/widgets/list/index.html',
            controller: 'widgetsListController'
        })
        .when('/pages/widgets/form/add/', {
            templateUrl: 'pages/widgets/form/add.html',
            controller: 'widgetAddFormController'
        })
        .when('/pages/widgets/form/edit/:id', {
            templateUrl: 'pages/widgets/form/edit.html',
            controller: 'widgetEditFormController'
        })
        .when('/pages/widgets/sort/', {
            templateUrl: 'pages/widgets/sort/index.html',
            controller: 'widgetSortController'
        })


        /* USERS */
        .when('/pages/users/list/', {
            templateUrl: 'pages/users/list/index.html',
            controller: 'usersListController'
        })
        .when('/pages/users/register/', {
            templateUrl: 'pages/users/form/register.html',
            controller: 'usersRegisterController'
        })

})
    .run(function ($rootScope, $location, $cookies, $http, user) {

        // Define rootScope
        //$rootScope.mainURL = "http://192.168.0.21/scms/apps/izlog/zlatibor/admin/rest/";
        //$rootScope.mainAppURL = "http://192.168.0.21/scms/apps/izlog/zlatibor/interaktivni/";

        $rootScope.mainURL = "http://www.innotechgl.com/dev/apps/ingl/admin/rest/";
        $rootScope.mainAppURL = "http://www.innotechgl.com/dev/apps/ingl/app/";
        $rootScope.showColors = 0;

        if (!angular.isUndefined($cookies["token"])) {
            $rootScope.token = $cookies["token"];
            user.setToken($cookies["token"]);
            user.setLogedIn(true);
            console.log('token', user.getLogedIn());
        }

        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            console.log('token', $cookies["token"]);

            if (!angular.isUndefined($cookies["token"])) {
                $rootScope.token = $cookies["token"];
                user.setToken($cookies["token"]);
                user.setLogedIn(true);
                console.log('token', user.getLogedIn());
            }
            if ($rootScope.token == null) {
                // no logged user, redirect to /login
                if (next.templateUrl === "pages/login.html") {
                    //user.setLogedIn(false);
                }
                else {
                    $location.path("/login");
                    user.setLogedIn(false);
                }
            }
            else {
                $http.get($rootScope.mainURL + "?page=users&task=checkToken&token=" + $rootScope.token)
                    .success(function (data) {
                        if (data.data == "EXISTS") {
                            console.log('TOKEN OK!');
                            user.setLogedIn(true);
                        }
                        else {
                            user.setLogedIn(false);
                            $location.path("/login");
                        }
                    });
            }
        });

    });
// Main Settings
app.factory('mainSettings', function () {

    // Settings
    var settings = {
        //mainURL: "http://192.168.0.21/scms/apps/izlog/zlatibor/admin/rest/",
        //mainAppURL: "http://192.168.0.21/scms/apps/izlog/zlatibor/interaktivni/",
        mainURL: "http://www.innotechgl.com/dev/apps/ingl/admin/rest/",
        mainAppURL: "http://www.innotechgl.com/dev/apps/ingl/app/",
        showColors: 0
    }

    return settings;

});
app.controller("scmsController", ['$scope', '$route', '$routeParams', '$compile', 'user', '$cookies', '$cookieStore', '$rootScope', '$location', 'languages', 'mainSettings',
    function ($scope, $route, $routeParams, $compile, user, $cookies, $cookieStore, $rootScope, $location, languages, mainSettings) {

        $scope.languages = [];
        $scope.selectedLanguage = '';

        $scope.isLogedIn = function () {
            return user.getLogedIn();
        };

        $scope.userLogout = function () {
            user.logout()
                .then(function (response) {
                    $cookieStore.remove("token");
                    user.setLogedIn(false);
                    $rootScope.token = null;
                    $location.path("/login");
                    $scope.hiddenAll
                });
        };

        $scope.selectLanguage = function (lang) {
            languages.setLanguage(lang);
            $scope.selectedLanguage = lang;
        }

        $rootScope.$on('languages:loaded', function () {
            console.log('scope loaded');
            $scope.languages = languages.getLanguages();
            $scope.selectedLanguage = languages.getLanguage();
        });

    }]);

app.directive("calendar", function () {
    return {
        restrict: "E",
        templateUrl: "pages/calendar.html",
        scope: false,
        link: function (scope) {
            scope.selected = _removeTime(scope.selected || moment());
            scope.month = scope.selected.clone();

            var start = scope.selected.clone();

            start.date(1);
            _removeTime(start.day(0));

            _buildMonth(scope, start, scope.month);

            scope.select = function (day) {
                scope.selected = day.date;

            };

            scope.next = function () {
                var next = scope.month.clone();
                _removeTime(next.month(next.month() + 1).date(1));
                scope.month.month(scope.month.month() + 1);
                _buildMonth(scope, next, scope.month);
                scope.setDate();
                scope.getLockedDates(scope.activeAccommodationID, scope.month.format("M"), scope.month.format("YYYY"));
            };

            scope.previous = function () {
                var previous = scope.month.clone();
                _removeTime(previous.month(previous.month() - 1).date(1));
                scope.month.month(scope.month.month() - 1);
                _buildMonth(scope, previous, scope.month);
                scope.setDate();
                scope.getLockedDates(scope.activeAccommodationID, scope.month.format("M"), scope.month.format("YYYY"));
            };

            scope.setDate = function () {
                scope.m = scope.month.format("M");
                scope.y = scope.month.format("YYYY");
            }

            scope.$watch("lockedDates", function (newValue, oldValue) {
                console.log('locked');
                scope.setDate();
            });
        }
    };

    function _removeTime(date) {
        return date.day(0).hour(0).minute(0).second(0).millisecond(0);
    }

    function _buildMonth(scope, start, month) {
        scope.weeks = [];
        var done = false, date = start.clone(), monthIndex = date.month(), count = 0;
        while (!done) {
            scope.weeks.push({ days: _buildWeek(date.clone(), month) });
            date.add(1, "w");
            done = count++ > 2 && monthIndex !== date.month();
            monthIndex = date.month();
        }

    }

    function _buildWeek(date, month) {
        var days = [];
        for (var i = 0; i < 7; i++) {
            days.push({
                name: date.format("dd").substring(0, 1),
                number: date.date(),
                isCurrentMonth: date.month() === month.month(),
                isToday: date.isSame(new Date(), "day"),
                date: date
            });
            date = date.clone();
            date.add(1, "d");
        }
        return days;
    }
});

app.directive('tinymce', function () {
    return {
        restrict: 'C',
        require: 'ngModel',
        link: function (scope, element, attrs, modelCtrl) {
            element.tinymce({
                setup: function (e) {
                    e.on("change", function () {
                        modelCtrl.$setViewValue(element.val());
                        scope.$apply();
                    })
                }
            });
        }
    }
})




app.factory('categorise', function () {

    var nested = [];

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

    return {
        do: function (categories, parent_id) {
            var p = 0;
            if (!angular.isUndefined(parent_id)) {
                p = parent_id;
            }
            var nested = getNestedChildren(categories, p);
            return nested;
        }
    }
});
var optimus = angular.module("optimus", ["ngRoute","ui.router","ngSanitize","oc.lazyLoad", "ngCookies", "ui.bootstrap", "ui.tinymce", "ngFileUpload", "ngMap"]);

optimus.config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {
    $ocLazyLoadProvider.config({
        // global configs go here
    });
}]);

optimus.config(['$controllerProvider', function($controllerProvider) {
    // this option might be handy for migrating old apps, but please don't use it
    // in new ones!
    $controllerProvider.allowGlobals();
}]);

optimus.factory('settings', ['$rootScope', function($rootScope) {
    // supported languages
    var settings = {
        layout: {
            pageSidebarClosed: false, // sidebar menu state
            pageBodySolid: false, // solid body color state
            pageAutoScrollOnLoad: 1000 // auto scroll to top on page load
        },
        layoutImgPath: Metronic.getAssetsPath() + 'admin/layout/img/',
        layoutCssPath: Metronic.getAssetsPath() + 'admin/layout/css/'
    };

    $rootScope.settings = settings;

    return settings;
}]);



optimus.config(function ($routeProvider, $locationProvider, $controllerProvider) {

    // remember mentioned function for later use
    optimus.registerCtrl = $controllerProvider.register;

    $routeProvider
          /* clients */
        .when('/pages/clients/add',{
          templateUrl: 'pages/clients/html/add.html',
          controller: 'clientAddController'
          })

        .when('/pages/clients/edit',{
          templateUrl: 'pages/clients/html/edit.html',
          controller: 'clientEditController'
          })

        .when('/pages/clients/list',{
          templateUrl: 'pages/clients/html/list.html',
          controller: 'clientListController'
          })

          /* documents */

        .when('/pages/documents/add',{
          templateUrl: 'pages/documents/html/add.html',
          controller: 'documentsAddController'
          })

        .when('/pages/documents/edit',{
          templateUrl: 'pages/documents/html/edit.html',
          controller: 'documentsEditController'
          })

        .when('/pages/documents/list',{
          templateUrl: 'pages/documents/html/list.html',
          controller: 'documentsListController'
          })

            /* documents categories */

        .when('/pages/documents-categories/add',{
          templateUrl: 'pages/documents_categories/html/add.html',
          controller: 'documentsCategoriesAddController'
          })

        .when('/pages/documents-categories/list',{
          templateUrl: 'pages/documents_categories/html/list.html',
          controller: 'documentsCategoriesListController'
          })

          /* factory */
        .when('/pages/factory/add',{
          templateUrl: 'pages/factory/html/add.html',
          controller: 'factoryAddController'
          })

        .when('/pages/factory/edit',{
          templateUrl: 'pages/factory/html/edit.html',
          controller: 'factoryEditController'
          })

        .when('/pages/factory/list',{
          templateUrl: 'pages/factory/html/list.html',
          controller: 'factoryListController'
          })

          /* users */
        .when('/pages/users/add',{
          templateUrl: 'pages/users/html/add.html',
          controller: 'usersAddController'
          })

        .when('/pages/users/edit',{
          templateUrl: 'pages/users/html/edit.html',
          controller: 'usersEditController'
          })

        .when('/pages/users/list',{
          templateUrl: 'pages/users/html/list.html',
          controller: 'usersListController'
          })

          /* user group */



        /* pages */
        .when('/pages/menu/list/', {
            templateUrl: 'pages/menu/list/index.html',
            controller: 'menuListController'
        })

    /*start */
        .when('/', {
            templateUrl: 'pages/start/start.html',
            controller: 'startCtrl'
        })

})
  /*  .run(function ($rootScope, $location, $cookies, $http, user) {

        // Define rootScope
        //$rootScope.mainURL = "http://192.168.0.21/scms/apps/izlog/zlatibor/admin/rest/";
        //$rootScope.mainAppURL = "http://192.168.0.21/scms/apps/izlog/zlatibor/interaktivni/";

        $rootScope.mainURL = "http://test.lovekopaonik.com/admin-temp/rest/";
        $rootScope.mainAppURL = "http://www.lovekopaonik.com/";
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

    }); */
// Main Settings
optimus.factory('mainSettings', function () {

    // Settings
    var settings = {
        //mainURL: "http://192.168.0.21/scms/apps/izlog/zlatibor/admin/rest/",
        //mainAppURL: "http://192.168.0.21/scms/apps/izlog/zlatibor/interaktivni/",
        mainURL: "http://test.lovekopaonik.com/admin-temp/rest/",
        mainAppURL: "http://www.lovekopaonik.com/",
        showColors: 0
    }

    return settings;

});

optimus.factory('categorise', function () {

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

optimus.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    // Redirect any unmatched url
    $urlRouterProvider.otherwise("/index.html");
$stateProvider
// UI Select
.state('uiselect', {
    url: "/ui_select.html",
    templateUrl: "views/ui_select.html",
    data: {pageTitle: 'AngularJS Ui Select'},
    controller: "UISelectController",
    resolve: {
        deps: ['$ocLazyLoad', function($ocLazyLoad) {
            return $ocLazyLoad.load([{
                name: 'ui.select',
                insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                files: [
                    'js/angular/ui-select/select.min.css',
                    'js/angular/ui-select/select.min.js'
                ]
            }, {
                name: 'optimus',
                files: [
                    'js/UISelectController.js'
                ]
            }]);
        }]
    }
})

 .state("profile.dashboard", {
     url: "/dashboard",
     templateUrl: "views/profile/dashboard.html",
     data: {pageTitle: 'User Profile'}
    })

}]);
/* app.controller("scmsController", ['$scope', '$route', '$routeParams', '$compile', 'user', '$cookies', '$cookieStore', '$rootScope', '$location', 'languages', 'mainSettings',
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

    }]);  */

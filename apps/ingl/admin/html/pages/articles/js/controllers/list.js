app.controller("articleListController", ["$scope", "$http", "$location", "$rootScope",
    "$cookies", "$routeParams", "$route", "$filter",
    "$modal", "articleCategoriesService", "articles",

    function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, $filter,
        $modal, articleCategoriesService, articles) {

        // URLS
        $scope.getListURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getAll";
        $scope.getAllCategoriesURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getAllArticleCategories";

        $scope.fixAliasURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=fixAlias";

        // Articles
        $scope.articles = {};
        $scope.allArticles = [];
        $scope.filteredArticles = [];
        $scope.categories = [];
        $scope.categoriesNested = {};
        $scope.path = [];
        $scope.sortingArticles = [];

        $scope.search = "";

        $scope.orderedBy = 'id';
        $scope.orderDirection = false;

        $scope.categorySelected = {id: 0, parent_id: -1, name: ""}

        // pagination
        $scope.totalItems = 0;
        $scope.itemsPerPage = 30;
        $scope.currentPage = 1;
        
        // Sorting
        $scope.sorting = false;

        $scope.multipleSelect = {};

        $scope.$watchCollection('allArticles', function () {
            $scope.filteredArticles = $filter('orderBy')(filterArticles(), $scope.orderedBy, $scope.orderDirection);
        });

        $scope.$watchCollection('filteredArticles', function () {

            $scope.articles = [];
            $scope.currentPage = 1;

            // check undefined
            if (!angular.isUndefined($scope.filteredArticles)) {
                $scope.totalItems = $scope.filteredArticles.length;
            } else {
                $scope.totalItems = 0;
            }

            var begin = (($scope.currentPage - 1) * $scope.itemsPerPage),
                    end = begin + $scope.itemsPerPage;
            if ($scope.totalItems > 0) {
                $scope.articles = $scope.filteredArticles.slice(begin, end);
            }

        });

        $scope.$watchCollection('categories', function () {
            console.log('changed');
            $scope.categoriesNested = getNestedChildren($scope.categories, "0");
        });

        /*
         PAGINATION FUNCTIONS
         */
        $scope.$watch('currentPage + itemsPerPage', function () {
            var begin = (($scope.currentPage - 1) * $scope.itemsPerPage),
                    end = begin + $scope.itemsPerPage;

            $scope.articles = $scope.filteredArticles.slice(begin, end);
        });

        function filterArticles() {

            var filtered = [];
            if ($scope.categorySelected.id > 0) {
                angular.forEach($scope.allArticles, function (val, key) {
                    if (val.categorie_id == $scope.categorySelected.id) {
                        filtered.push(val);
                    }
                });
            } else {
                filtered = $scope.allArticles;
            }

            return filtered;
        }

        $scope.$watch('categorySelected', function () {
            if ($scope.categorySelected.id > 0) {
                $scope.filteredArticles =
                        $filter('orderBy')(filterArticles(), $scope.orderedBy, $scope.orderDirection);
            } else {
                $scope.filteredArticles =
                        $filter('orderBy')($scope.allArticles, $scope.orderedBy, $scope.orderDirection);
            }
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

        // Load articles
        $scope.loadArticleCategories = function () {

            var promise = $http.get($scope.getAllCategoriesURL).success(function (data) {
                $scope.categories = data.data;
            }).error(function (data) {
                console.log("ERROR loading.");
            });

            return promise;

        }

        $scope.articleFilter = function (item) {
            if ($scope.categorySelected.id > 0) {
                if (item.categorie_id == $scope.categorySelected.id) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }

        // Select category for filter
        $scope.selectCategory = function (category) {
            $scope.categorySelected = category;
            $scope.getPath(category.id);
        }

        $scope.selectCategoryByID = function (id) {
            var index = $scope.findCategoryByID(id);
            console.log(id, index, $scope.categories[index]);
            $scope.selectCategory($scope.categories[index]);
        }

        $scope.getPath = function (id) {
            $scope.path = [];
            $scope._goThroughCategories(id);
            $scope.path = $scope.path.reverse();
            console.log($scope.path);
        }


        $scope._goThroughCategories = function (id) {
            var index = 0;

            for (var i = 0; i < $scope.categories.length; i++) {

                console.log($scope.categories[i].id == id, $scope.categories[i].parent_id !== 0);

                if ($scope.categories[i].id == id) {
                    if ($scope.categories[i].parent_id !== 0) {

                        // Get index of parent menu
                        index = $scope.findCategoryByID($scope.categories[i].parent_id);
                        console.log(i, index, id);

                        // Path
                        if (index) {
                            $scope.path.push({
                                "id": $scope.categories[index].id,
                                "name": $scope.categories[index].name
                            });
                        }

                        // loop
                        if (index) {
                            $scope._goThroughCategories($scope.categories[index].id);
                        }

                    } else {
                        return false;
                    }
                }

            }
        }


        // Single article item
        $scope.article = {};
        $scope.articleOptions = {};

        // Load articles
        $scope.loadArticles = function () {

            $http.get($scope.getListURL).success(function (data) {

                $scope.allArticles = data.data;
                //$scope.totalItems = $scope.allArticles.length;

                //$scope.articles = $scope.allArticles.slice(0, $scope.maxSize);


            }).error(function (data) {
                console.log("ERROR loading.");
            });
        }

        $scope.delete = function (id) {
            if (confirm("Are you shure that you want to delete article?")) {
                articles.delete(id).then(function(){
                        $scope.deleteArticleFromArray(id);
                },
                    function(response){
                    console.log('Error deleting article',response);
                });
            }
        }

        $scope.multipleDelete = function () {
            if (confirm("Do you realy want to delete selected articles?")) {

                var ids = [];
                angular.forEach($scope.multipleSelect, function (v, k) {
                    ids.push($scope.articles[k].id);
                });

                $http.post($scope.multipleDeleteURL, {data: {ids: ids}})
                        .success(function (data) {
                            for (var i = 0; i < ids.length; i++) {
                                $scope.deleteArticleFromArray(ids[i]);
                            }
                        })
                        .error(function (data) {

                        });
            }
        }

        $scope.deleteArticleFromArray = function (id) {
            var index = $scope.findArticleByID(id);
            $scope.allArticles.splice(index, 1);
        }

        $scope.setOrderBy = function (val) {
            if ($scope.orderedBy == val) {
                if (!$scope.orderDirection) {
                    $scope.orderDirection = true;
                } else {
                    $scope.orderDirection = false;
                }
            } else {
                $scope.orderedBy = val;
                $scope.orderDirection = false;
            }

            $scope.filteredArticles = $filter('orderBy')(filterArticles(), $scope.orderedBy, $scope.orderDirection);

        }

        $scope.createAlias = function (articleID) {
            var index = $scope.findArticleByID(articleID);
            var title = $scope.articles[index].title;
            var alias = fixAlias(title);

            $http.post($scope.fixAliasURL, {
                alias: alias,
                id: $scope.articles[index].id

            }).success(function () {
                $scope.articles[index].alias = alias;
            });
        }

        $scope.toggleSorting = function () {
            if ($scope.sorting) {
                $scope.sorting = false;
            } else {
                $scope.sorting = true;
            }

        }

        $scope.saveSort = function() {
            // Articles
            articles.updateSort($scope.articles);

            console.log('update sort');
        }

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

        $scope.findArticleByID = function (id) {
            for (var i = 0; i < $scope.allArticles.length; i++) {
                if ($scope.allArticles[i].id == id) {
                    return i;
                }
            }
            return false;
        }

        $scope.findCategoryByID = function (id) {
            //console.log('find ',id);
            for (var i = 0; i < $scope.categories.length; i++) {
                if ($scope.categories[i].id == id) {
                    return i;
                }
            }
            return -1;
        };

        // Delete category
        $scope.deleteCategory = function (id) {

            var index = $scope.findCategoryByID(id);

            if (confirm("Do You really want to delete category " + $scope.categories[index].name + "?")) {
                articleCategoriesService.deleteCategoryItem(id)
                        .then(function () {
                            $scope.categories.splice(index, 1);
                        });
            }
        };

        // Get category name
        $scope.getCategorieName = function (id) {

            var found = $scope.findCategoryByID(id);

            if (found >= 0) {
                return $scope.categories[found].name;
            } else {
                return "Unknown";
            }
        };

        $scope.activate = function (aid) {
            var index = $scope.findArticleByID(aid);
            articles.activate(aid).then(function(data){
                $scope.allArticles[index].active = 1;
            },
            function(response){
                console.log('Error activating article');
            });

        };

        $scope.deactivate = function (aid) {
            var index = $scope.findArticleByID(aid);

            articles.deactivate(aid).then(function(data){
                    $scope.allArticles[index].active = 1;
                },
                function(response){
                    console.log('Error deactivating article');
                });
        };

        $scope.editCategory = function (cat) {

            var modalInstance = $modal.open({
                animation: true,
                templateUrl: 'pages/article_categories/form/index.html',
                controller: "articleCategories",
                resolve: {
                    category: function () {
                        return cat;
                    }
                }
            });

            modalInstance.result.then(function (data) {
                $scope.loadArticleCategories();
            }, function () {
                console.log('dismissed');
            });
        };

        var promise = $scope.loadArticleCategories();
        promise.then(function () {
            $scope.loadArticles();
        });


        $scope.addCategory = function () {

            var modalInstance = $modal.open({
                animation: true,
                templateUrl: 'pages/article_categories/form/index.html',
                controller: "articleCategories",
                resolve: {
                    category: function () {
                        return {id: 0, parent_id: 0};
                    }
                }
            });

            modalInstance.result.then(function (data) {
                $scope.loadArticleCategories();
            }, function () {
                console.log('dismissed');
            });
        };

        var promise = $scope.loadArticleCategories();
        promise.then(function () {
            $scope.loadArticles();
        });

    }


]);



app.directive("selectMultipleArticlePhoto", ['$modal', function ($modal) {
        return {
            strict: "E",
            scope: {
                selected: "=ngModel",
            },
            templateUrl: 'pages/articles/includes/selectPhoto.html',
            link: function (scope, element, attrs, ngModel) {

                scope.activePhotos = [];

                if (angular.isUndefined(scope.selected)) {
                    scope.selected = [];
                }

                scope.addPhoto = function () {
                    scope.selected.push('');
                }

                scope.selectPhoto = function (index) {
                    scope.showPhotos(index);
                }

                scope.showPhotos = function (where) {
                    console.log(scope.option);
                    var modalInstance = $modal.open({
                        animation: true,
                        backdrop: 'static',
                        size: "lg",
                        templateUrl: 'pages/articlePhotos/index.html',
                        controller: "articlePhotosController"
                    });

                    modalInstance.result.then(function (data) {
                        scope.selected[where] = data.imageURL;
                        // ngModel.$setViewValue(scope.selected);
                    }, function () {
                        console.log('dismissed');
                    });
                }

                scope.remove = function (index) {
                    scope.selected.splice(index, 1);
                }
            }
        }
    }]);

app.controller("articleFormController", ["$scope", "$http", "$location", "$rootScope", "$cookies", "$routeParams", "$route", "$modal", "$filter", "$window", "languages", "$timeout", "$log",
    function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, $modal, $filter, $window, languages, $timeout, $log) {

        // Define URL
        //$scope.getOptionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getOptions";
        $scope.getAllCategoriesURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getAllArticleCategories";
        $scope.getAllLanguagesURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getAllLanguages";
        $scope.saveURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=save";
        $scope.getOptionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getArticleSettings";

        // Edit article URLS
        $scope.getArticleURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=getArticle";
        $scope.saveEditURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=articles&task=edit";

        // categories object
        $scope.categories = {};
        $scope.categoriesNested = {};
        $scope.languages = languages.getLanguages();
        $scope.options = {};

        //
        $scope.categorySelected = { id: 0, name: "- Select category -" };
        $scope.languageSelected = languages.getLanguage();

        // Data
        $scope.fieldValues = {};

        // Define form
        $scope.articleForm = {
            id: 0,
            categorie_id: 0,
            title: "",
            meta: "",
            description: "",
            text: "",
            language: $scope.languageSelected.short_name,
            date: $filter('date')(new Date(), "dd.MM.yyyy. HH:mm"),
            options: {}
        };
        if ($routeParams.task !== "edit") {
            $scope.canEditAlias = true;
        } else {
            $scope.canEditAlias = false;
        }

        $scope.tinymceOptions = {
            width: 800,
            height: 520,
            plugins: 'textcolor media image code link paste link',
            toolbar: "link undo redo styleselect bold italic print forecolor backcolor media picture image",
            //content_css: "pages/articles/includes/utils/tinymce/preview.css",
            convert_urls: false,
            style_formats: [
                {
                    title: 'Image Left',
                    selector: 'img',
                    styles: {
                        'float': 'left',
                        'margin': '0 10px 10px 0'
                    }
                },
                {
                    title: 'Image Right',
                    selector: 'img',
                    styles: {
                        'float': 'right',
                        'margin': '0 0 10px 10px'
                    }
                }
            ]
        };


        $scope.$watch('articleForm', function () {
            console.log($scope.articleForm);
        });

        function findItemByObjectKey(items, objKey, value) {
            for (var i = 0; i < items.length; i++) {
                if (items[i][objKey] == value) {
                    return i;
                }
            }
            return false;
        }


        // Load article for editing
        $scope.loadArticle = function (id) {
            var promise = $http.get($scope.getArticleURL + "&id=" + id)
                    .success(function (data) {
                        // Fill article Values
                        $scope.articleForm = data.data;

                        // selectCategory
                        var index = $scope.findCategoryByID($scope.articleForm.categorie_id);
                        $scope.categorySelected = $scope.categories[index];

                        index = findItemByObjectKey($scope.languages, "short_name", $scope.articleForm.language);
                        $scope.languageSelected = $scope.languages[index];

                    }).error(function (data) {

                    });

            return promise;
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

        // Load articles
        $scope.loadArticleCategories = function () {

            var promise = $http.get($scope.getAllCategoriesURL).success(function (data) {
                $scope.categories = data.data;
                // Nested
                $scope.categoriesNested = getNestedChildren($scope.categories, 0);
                console.log($scope.categoriesNested);

            }).error(function (data) {
                console.log("ERROR loading.");
            });
            return promise;
        }

        $scope.findCategoryByID = function (id) {
            for (var i = 0; i < $scope.categories.length; i++) {
                if ($scope.categories[i].id == id) {
                    return i;
                }
            }
        }

        // Load languages
        $scope.loadLanguages = function () {

            $http.get($scope.getAllLanguagesURL).success(function (data) {
                $scope.languages = data.data;
                $scope.languages.push({ short_name: "ALL", name: "Any language" });
            }).error(function (data) {
                console.log("ERROR loading.");
            });
        }


        $scope.loadOptions = function () {
            $http.get($scope.getOptionsURL).success(function (data) {
                $scope.options = data.data.options.option;
                console.log($scope.options);

            }).error(function (data) {
                console.log("ERROR loading.");
            });
        }

        $scope.changed = function (category) {
            $scope.categorySelected = category;
            $scope.articleForm.categorie_id = category.id;
        }

        $scope.changedLanguage = function (language) {
            $scope.languageSelected = language;
            $scope.articleForm.language = language.short_name;
        }

        $scope.insertcode = function () {
            tinymce.activeEditor.execCommand('mceInsertContent',
                    false,
                    $scope.articleForm.text_test);
        }

        $scope.insertImageToList = function (data) {
            console.log(data);
            $scope.articleForm.options.default_img_list = data.imageURL;
        }

        $scope.insertImageToTinyMCE = function (data) {

            var imageInside = "<img src='" + data.imageURL + "' />";
            var Link = "<a href='" + data.dirPath + data.image + "' rel='thumb[]'>" + imageInside + "</a>";
            var image = imageInside;

            if (data.clickable == 1) {
                image = Link;
            }

            tinymce.activeEditor.execCommand('mceInsertContent', false, image);
        }

        $scope.showPhotos = function (where) {

            var modalInstance = $modal.open({
                animation: true,
                backdrop: 'static',
                size: "lg",
                templateUrl: 'pages/articlePhotos/index.html',
                controller: "articlePhotosController"
            });

            modalInstance.result.then(function (data) {
                switch (where) {
                    case "img_list":
                        $scope.insertImageToList(data);
                        break;
                    case "tinymce":
                        $scope.insertImageToTinyMCE(data);
                        break;
                }
            }, function () {
                console.log('dismissed');
            });
        }


        // Include element
        /*
         @todo: proveriti zasto ova polja na klik reaguju kao da je nesto novo ucitano od opcija
         */
        $scope.includeElement = function (option) {
            //console.log(option.fieldType);
            switch (option.fieldType) {
                case "drop":
                    return "pages/articles/includes/dropOption.html";
                    break;

                case "text":
                    return "pages/articles/includes/text.html";
                    break;

                case "checkbox":
                    return "pages/articles/includes/checkbox.html";
                    break;

                case "addOnPhotoSelect":
                    //console.log('ima');
                    return "pages/articles/includes/addOnPhotoSelect.html";
                    break;
            }
        };

        $scope.$watch('articleForm.title', function () {
            var title = '';
            if ($scope.canEditAlias) {
                title = $scope.articleForm.title;

                $scope.articleForm.alias = fixAlias(title);
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

        $scope.findCategoryByID = function (id) {
            for (var i = 0; i < $scope.categories.length; i++) {
                if ($scope.categories[i].id == id) {
                    return i;
                }
            }
            return false;
        }

        $scope.testArray = function (ar) {
            if (angular.isArray(ar)) {
                return true;
            } else {
                return false;
            }
        }


        // Saving data
        $scope.save = function () {

            var url = '';

            switch ($routeParams.task) {
                case "add":
                    url = $scope.saveURL;
                    break;
                case "edit":
                    url = $scope.saveEditURL;
                    break;
            }

            $http.post(url, { data: $scope.articleForm }).success(function (data) {
                console.log(data);
                $window.history.back();
            }).error(function (data) {

            });
        }

        /************
         EDIT ARTICLE
         ************/
        if ($routeParams.task == "edit") {
            var promise = $scope.loadArticleCategories();
            promise.then(function () {
                $scope.loadArticle($routeParams.id);
            });
        } else {
            $scope.loadArticleCategories();
        }

        $scope.loadLanguages();
        $scope.loadOptions();


    }]);
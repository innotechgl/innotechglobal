app.controller('articleCategories', ["$scope", "articleCategoriesService", "$modalInstance",
    "category", "categorise", "articleCategoriesService",
    function ($scope, articleCategoriesService, $modalInstance, category, categorise, articleCategoriesService) {

        $scope.categories = {};
        $scope.categoryItem = {name: "", alias: "", parent_id: 0, language: "ALL"};
        $scope.options = {};

        $scope.languages = {};
        $scope.languageSelected = {short_name: "ALL", name: "Any language"};

        $scope.categoriesNested = {};

        $scope.categorySelected = {id: 0, name: "root"};

        $scope.canEditAlias = true;

        $scope.$watch('categoryItem.name', function () {
            var title = '';
            if ($scope.canEditAlias) {
                title = $scope.categoryItem.name;

                $scope.categoryItem.alias = fixAlias(title);
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

        $scope.task = "Add";
        if (category.id > 0) {
            $scope.task = "Edit";
        }

        $scope.selectCategory = function (cat) {
            $scope.categoryItem.parent_id = cat.id;
            $scope.categorySelected = cat;
        };

        $scope.selectLanguage = function (language) {
            $scope.languageSelected = language;
            $scope.categoryItem.language = language.short_name;
        }

        $scope.checkParentID = function (id) {
            if (id == category.id && category.id > 0) {
                return true;
            }
            return false;
        }

        // Save
        $scope.save = function () {
            if (category.id > 0) {
                articleCategoriesService.updateCategoryItem($scope.categoryItem)
                    .then(function (response) {
                        $modalInstance.close(response.data.data);
                    });
            }
            else {
                articleCategoriesService.addNewItem($scope.categoryItem)
                    .then(function (response) {
                        $modalInstance.close(response.data.data);
                    });
            }
        };

        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };

        function findItemByObjectKey(items, objKey, value) {
            for (var i = 0; i < items.length; i++) {
                if (items[i][objKey] == value) {
                    return i;
                }
            }
            return false;
        }

        // Load what we need
        articleCategoriesService
            .loadAll()
            .then(function (response) {

                // fill categories
                $scope.categories = response.data.data;

                // add root element
                $scope.categories.unshift({id: 0, name: "Root", parent_id: -1});

                $scope.categoriesNested = categorise.do($scope.categories, -1);

                // load available options for categories
                articleCategoriesService.loadOptions()
                    .then(function (response) {

                        // set options
                        $scope.options = response.data.data.options.option;

                        // Load languages
                        articleCategoriesService.loadLanguages().then(function (response) {

                            $scope.languages = response.data.data;

                            // Load item in case that we are editing categoryItem
                            if (category.id > 0) {
                                articleCategoriesService.loadCategoryItem(category.id)
                                    .then(function (response) {

                                        $scope.categoryItem = response.data.data;

                                        if (category.parent_id > 0) {
                                            // Select category
                                            var index = findItemByObjectKey($scope.categories, "id", category.parent_id);
                                            $scope.selectCategory($scope.categories[index]);
                                        }

                                        // Select language
                                        index = findItemByObjectKey($scope.languages, "short_name", $scope.categoryItem.language);
                                        $scope.selectLanguage($scope.languages[index]);

                                    });
                            }
                        });
                    });
            });

    }]);
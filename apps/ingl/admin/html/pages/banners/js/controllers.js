app.controller("bannersListController", ["$scope", "$http", "$location", "$rootScope", "$cookies",
    "$routeParams", "$route",
    "$modal", "Upload", '$timeout', "bannerCategoriesService",
    function ($scope, $http, $location, $rootScope, $cookies, $routeParams, $route, $modal, Upload, $timeout, bannerCategoriesService) {

        // Define URL
        $scope.getOptionsURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=getOptions";
        $scope.getAllCategoriesURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=getAllCategories";
        $scope.getAllPhotosURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=getAllPhotos";
        $scope.uploadURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=upload";
        $scope.editURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=edit";
        $scope.deleteURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=delete";

        $scope.sortURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=sort";

        $scope.activateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=activate";
        $scope.deactivateURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=deactivate";

        // Category URLs
        $scope.categoryAddURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=addCategory";
        $scope.categoryEditURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=editCategory";
        $scope.categoryDeleteURL = $rootScope.mainURL + "?token=" + $rootScope.token + "&page=banners&task=deleteCategory";

        // categories object
        $scope.categories = {};
        $scope.categoriesNested = {};
        $scope.photos = {};
        $scope.options = {};
        $scope.newCat = {};

        $scope.path = [];

        // Loading/Saving
        $scope.loading = false;
        $scope.saving  = false;

        // photo
        $scope.photoForm = {};
        $scope.allBanners = [];

        $scope.newBanners = {};

        $scope.categorySelected = {id: 0, name: "- Select category -"};
        $scope.photoSelected = {};
        $scope.insertPhoto = {};

        // Upload
        $scope.photosForUpload = [];
        $scope.uploadedPhotos = {};
        $scope.fileToUpload = [];
        $scope.previews = [];

        $scope.isOpen = true;

        // Data
        $scope.fieldValues = {};

        $scope.insertCategoryPopover = {
            title: ''
        };

        // Sorting
        $scope.sorting = false;

        // pagination
        $scope.totalItems = 0;
        $scope.itemsPerPage = 15;
        $scope.currentPage = 1;

        $scope.multipleSelect = {};

        $scope.$watchCollection('allBanners', function () {
            //$scope.currentPage = 1;
            $scope.totalItems = $scope.allBanners.length;
            var begin = (($scope.currentPage - 1) * $scope.itemsPerPage),
                    end = begin + $scope.itemsPerPage;

            $scope.articles = $scope.allBanners.slice(begin, end);
        });

        // Upload
        $scope.addPhotoInput = function () {
            console.log('add photos input');
            $scope.photosForUpload.push({});
        }

        $scope.readyForUpload = function (el) {

            for (var i = 0; i < el.files.length; i++) {
                $scope.fileToUpload.push(el.files[i]);
                $scope.previews.push("");
                $scope.readURL(el.files[i], $scope.fileToUpload.length - 1);
            }

            console.log('file to upload', $scope.fileToUpload, el.files[0]);
        }

        $scope.toggleSorting = function () {
            if ($scope.sorting) {
                $scope.sorting = false;
            } else {
                $scope.sorting = true;
            }

        }

        $scope.saveSort = function () {
         
            var sortedBanners = [];

            angular.forEach($scope.photos, function (val, key) {
                if (val.categorie_id == $scope.categorySelected.id) {
                    sortedBanners.push(val.id);
                }
            });

            // 
            $http.post($scope.sortURL,{banners: sortedBanners})
                    .then(function (response) {
                        
                    });

        }

        $scope.readURL = function (url, index) {

            var reader = new FileReader();

            reader.onload = function (e) {
                $scope.previews[index] = e.target.result;
                $scope.$apply();
                console.log('done');

            }

            reader.readAsDataURL(url);
        }

        $scope.finishedUpload = function (indexOfFile, photo) {
            // Remove file from uploads
            $scope.fileToUpload.splice(indexOfFile, 1);
            //$scope.photosForUpload.splice(indexOfFile, 1);

            // Add photo to array
            $scope.photos.push(photo);
            console.log($scope.fileToUpload.length, $scope.photosForUpload.length);

        }

        $scope.uploadFiles = function () {
            for (var i = 0; i < $scope.fileToUpload.length; i++) {
                uploadUsingUpload($scope.fileToUpload[i], i);
            }
        }

        function uploadUsingUpload(file, indexOfFile) {
            file.upload = Upload.upload({
                url: $scope.uploadURL,
                method: 'POST',
                fields: {
                    category_id: $scope.categorySelected.id,
                    title: $scope.newBanners[indexOfFile].title,
                    description: $scope.newBanners[indexOfFile].description
                },
                file: file,
                fileFormDataName: 'photo'
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                    console.log(response.data);
                    $scope.finishedUpload(indexOfFile, response.data.data.photos[0]);

                });
            }, function (response) {
                if (response.status > 0) {
                    $scope.finishedUpload(indexOfFile, response.data.data.photos[0]);
                    $scope.errorMsg = response.status + ': ' + response.data;
                }
            });

            file.upload.progress(function (evt) {
                // Math.min is to fix IE which reports 200% sometimes
                file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));

            });

            file.upload.xhr(function (xhr) {
                // xhr.upload.addEventListener('abort', function(){console.log('abort complete')}, false);
            });
        }

        $scope.$watch('fileToUpload', function () {
            console.log('file to upload', $scope.fileToUpload);
        });


        $scope.setPopoverImage = function (name) {
            $scope.insertPopover.imageName = name;
        }

        $scope.insertPhotoReturn = function () {

            var data = {
                "dirPath": $rootScope.mainAppURL + "media/images/banners/" + $scope.categorySelected.id + "/",
                "imageURL": $rootScope.mainAppURL + "media/images/banners/" + $scope.categorySelected.id + "/" + $scope.insertPhoto.prefix + $scope.insertPopover.imageName,
                "prefix": $scope.insertPhoto.prefix,
                "image": $scope.insertPopover.imageName,
                "clickable": $scope.insertPhoto.clickable
            };
            $modalInstance.close(data);
        }

        // Load Caegories
        $scope.loadAllCategories = function () {

            $http.get($scope.getAllCategoriesURL).success(function (data) {

                $scope.categories = data.data;
                $scope.categoriesNested = getNestedChildren($scope.categories, "0");

            }).error(function (data) {
                console.log("ERROR loading.");
            });
        }

        $scope.childCats = function (parentID) {
            var cats = [];
            for (var i = 0; i < $scope.categories.length; i++) {
                if (parentID == $scope.categories[i].parent_id) {
                    cats.push($scope.categories[i]);
                }
            }

            return cats;
        }

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

        $scope.res = [];
        $scope.nested_array_stingified = '';

        $scope.addNewCategory = function () {
            $http.post($scope.categoryAddURL, {
                data: {
                    title: $scope.newCat.title,
                    parent_id: $scope.categorySelected.id
                }
            }).success(function (data) {
                // add new category to categories
                $scope.loadAllCategories();

                $scope.isOpen = false;
            }).error(function (data) {

            });
        }
        $scope.deleteCategory = function (id) {
            if (confirm("Do You really want to delete category? You will also delete all photos in category.")) {
                $http.post($scope.categoryDeleteURL, {
                    data: {
                        id: id
                    }
                }).success(function (data) {

                    var index = -1;

                    for (var i = 0; i < data.data.removed_categories.length; i++) {

                        // Get category index
                        index = $scope.findCategoryByID(data.data.removed_categories[i]);

                        // check if we deleted selected category
                        if ($scope.categories[index].id == $scope.categorySelected.id) {
                            // Reset selected category to 0
                            $scope.categorySelected = {id: 0, name: ""};
                        }

                        // Remove category from categories
                        $scope.categories.splice(index, 1);
                    }

                    // Reset nested view
                    $scope.categoriesNested = getNestedChildren($scope.categories, "0");

                }).error(function (data) {

                });

            }
        }

        $scope.getPath = function (id) {
            $scope.path = [];
            $scope._goThroughCategories(id);
            $scope.path = $scope.path.reverse();
            console.log($scope.path);
        }

        $scope.findCategoryByID = function (id) {
            for (var i = 0; i < $scope.categories.length; i++) {
                if ($scope.categories[i].id == id) {
                    return i;
                }
            }
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
                            $scope.path.push({"id": $scope.categories[index].id, "name": $scope.categories[index].name});
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

        // Find photos 
        $scope.findPhotoByID = function (id) {
            for (var i = 0; i < $scope.photos.length; i++) {
                if ($scope.photos[i].id == id) {
                    return i;
                }
            }
        }



        // Load languages
        $scope.loadPhotos = function () {

            $http.get($scope.getAllPhotosURL).success(function (data) {
                $scope.photos = data.data;
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

        $scope.getPhotoURL = function (photo, prefix) {
            return $rootScope.mainAppURL + "media/images/banners/" + photo.categorie_id + "/" + prefix + photo.name;
        }

        /* Delete photo */
        $scope.deletePhoto = function (photoID) {
            if (confirm("Delete image?")) {
                $http.post($scope.deleteURL, {data: {id: photoID}})
                        .success(function (data) {
                            var index = $scope.findPhotoByID(photoID);
                            if (index) {
                                $scope.photos.splice(index, 1);
                            }
                        }).error(function (data) {

                });
            }
        }

        $scope.selectCategory = function (category) {
            $scope.categorySelected = category;
            $scope.getPath(category.id);
        }

        $scope.insertcode = function () {
            tinymce.activeEditor.execCommand('mceInsertContent', false, $scope.articleForm.text_test);
        }

        // Include element
        $scope.includeElement = function (option) {
            switch (option.type) {
                case "drop":
                    return "pages/articles/includes/dropOption.html";
                    break;
                case "text":
                    return "pages/articles/includes/text.html";
                    break;
            }
        };

        $scope.$watch('articleForm.title', function () {
            var title = '';
            if ($scope.canEditAlias) {
                title = $scope.articleForm.title;

                $scope.articleForm.alias = title.replace(/ /g, '-').replace(/[^\w-]+/g, '');
            } else {

            }
        });

        $scope.$watch('uploadedPhotos', function () {
            console.log($scope.uploadedPhotos);
        });

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
            $http.post($scope.saveURL, {data: $scope.photoForm}).success(function (data) {

            }).error(function (data) {

            });
        }

        $scope.update = function (id) {
            var index = $scope.findPhotoByID(id);
            $http.post($scope.editURL, {
                data: {
                    banner: $scope.photos[index]
                }
            })
                    .success(function (data) {
                    }).error(function (data) {

            });
        }

        $scope.activate = function (id) {
            $http.post($scope.activateURL, {data: {id: id}})
                    .success(function (data) {
                        var index = $scope.findPhotoByID(id);
                        $scope.photos[index].active = 1;
                    }).error(function (data) {

            });
        }

        $scope.deactivate = function (id) {
            $http.post($scope.deactivateURL, {data: {id: id}})
                    .success(function (data) {
                        var index = $scope.findPhotoByID(id);
                        $scope.photos[index].active = 0;
                    }).error(function (data) {

            });
        }



        $scope.edit = function (id) {
            var index = $scope.findPhotoByID(id);

            var banner = $scope.photos[index];

            var modalInstance = $modal.open({
                animation: true,
                templateUrl: 'pages/banners/form/edit.html',
                controller: "editBanner",
                resolve: {
                    d: function () {
                        return {
                            "index": index,
                            "banner": banner
                        };
                    }
                }
            });

            modalInstance.result.then(function (data) {
                $scope.update(data.banner.id);
            }, function () {
                console.log('dismissed');
            });
        }



        // Load everything we need
        $scope.loadAllCategories();
        $scope.loadPhotos();

    }]);


app.controller('editBanner', function ($scope, $modalInstance, d) {

    console.log(d);

    $scope.banner = d.banner;
    $scope.form = {};

    $scope.save = function () {
        var data = {
            "index": d.index,
            "banner": $scope.banner
        };

        $modalInstance.close(data);
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

});

app.directive("uploadBannerPhotos", function () {
    return {
        restrict: 'EA',
        //replace: true,
        scope: false,
        transclude: false,
        templateUrl: 'pages/banners/includes/uploadPhotosTemplate.html',
        link: function ($scope, upload) {

            $scope.addToReadyForUpload = function (el) {
                console.log('selected', $scope.readyForUpload);
                $scope.readyForUpload(el);
            }
        }
    }
});

app.filter('bytes', function () {
    return function (bytes, precision) {
        if (isNaN(parseFloat(bytes)) || !isFinite(bytes))
            return '-';
        if (typeof precision === 'undefined')
            precision = 1;
        var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
                number = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + ' ' + units[number];
    }
});
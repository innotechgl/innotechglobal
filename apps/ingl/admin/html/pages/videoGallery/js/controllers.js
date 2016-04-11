app.controller("videoGalleryListController",
    ["$scope", "$modal", "videoGalleryService", "videoGalleryCategoriesService", "categorise",
    function ($scope, $modal, videoGalleryService, videoGalleryCategoriesService, categorise) {

    $scope.videoItem  = {};
    $scope.categories = [];
    $scope.categorised = [];
    $scope.categorySelected = {id:0, name:"Root"};
    $scope.videos = [];


    $scope.edit = function (id) {
        console.log('edit',id)
    }

    $scope.delete = function (id) {
        console.log('delete',id)
    }

    $scope.activate = function (id) {
        videoGalleryService.activate(id)
            .then(function () {
                $scope.categories[findCategoryByID(id)].active = 1;
            })
    }

    $scope.deactivate = function (id) {
        videoGalleryService.deactivate(id)
            .then(function () {
                $scope.categories[findCategoryByID(id)].active = 0;
            })
    }

    $scope.getCategoryName = function(id){
        return $scope.categories[findCategoryByID(id)].name;
    }
    
    // Load categories
    videoGalleryCategoriesService.loadAll()
        .then(function (response) {

            // Fill categories
            $scope.categories = response.data.data;
            $scope.categorised = categorise.do(response.data.data, 0);

            console.log($scope.categorised);

            // Load all videos
            videoGalleryService
                .loadAll()
                    .then(function (response) {
                        $scope.videos = response.data.data;
                    });
        });



    function findCategoryByID(id) {
        for (var i = 0; i < $scope.categories.length; i++) {
            if ($scope.categories[i].id == id) {
                return i;
            }
        }
        return false;
    }

    }]);

app.controller("videoGalleryFormController",
    ["$scope", "$modal", "videoGalleryService", "videoGalleryCategoriesService", "categorise", "$routeParams",
    function ($scope, $modal, videoGalleryService, videoGalleryCategoriesService, categorise, $routeParams) {

        $scope.videoItem = {};
        $scope.categories = [];
        $scope.categorised = [];
        $scope.categorySelected = { id: 0, name: "Root" };

        $scope.previewLink = "";

        $scope.task = $routeParams.task;

        // 

        $scope.$watch('categorySelected', function () {
            $scope.videoItem.category_id = $scope.categorySelected.id;
        });

        $scope.previewVideo = function () {
            var videoid = $scope.videoItem.link.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
            if (videoid != null) {
                $scope.previewLink = "https://www.youtube.com/embed/" + videoid[1] + "?rel=0";
                console.log("video id = ", videoid[1]);
            } else {
                console.log("The youtube url is not valid.");
            }
        }

        // Load categories
        videoGalleryCategoriesService.loadAll()
            .then(function (response) {

                // Fill categoriesou
                $scope.categories = response.data.data;
                $scope.categorised = categorise.do(response.data.data, 0);

                if ($routeParams.task == "edit"){
                
                    // Load all videos
                    videoGalleryService
                        .load($routeParams.id)
                            .then(function (response) {
                                $scope.videoItem = response.data.data;
                        });
                }
            });

        function findCategoryByID(id) {
            for (var i = 0; i < $scope.categories.length; i++) {
                if ($scope.categories[i].id == id) {
                    return i;
                }
            }
            return false;
        }
    }]);
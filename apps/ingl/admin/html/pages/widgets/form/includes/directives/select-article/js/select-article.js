app.directive('selectArticle', function(articles){
    return{
        templateUrl:'pages/widgets/form/includes/directives/select-article/html/select-article.html',
        scope:{
            selected:"=articleId"
        },
        link:function(scope,element,attrs){

            console.log('started directive');

            scope.articles = [];
            scope.searchBy = "";

            scope.$watch('searchBy',function(){
                console.log('changed search: '+scope.searchBy);
            });

            scope.selectArticle = function(id){
                scope.selected = id;
                console.log(scope.selected);
            }

            articles
                .loadAll()
                .then(function(response){
                    scope.articles = response.data.data;
                    console.log(scope.articles);
                });

        }
    }
});
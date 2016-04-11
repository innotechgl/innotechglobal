<?php
class viewController extends controller
{
    /**
     * @param modelViewArticle $model
     */
    public function __construct($model)
    {
        parent::__construct($model);
        $this->resolve();
    }

    protected function view()
    {

        $view = new articleView();


        // Get article
        if (isset($this->engine->sef->sef_params["route_more"])){
            // Get article alias
            $alias = $this->engine->sef->readAlias();
            $article = $this->model->getArticleByAlias($alias);
        }
        else if ($this->engine->sef->sef_params['task'] == 'view' && $this->engine->sef->sef_params['id'] > 0){
            $article = $this->model->getArticleByID($this->engine->sef->sef_params['id']);
        }
        else {
            // Get article alias
            $alias = $this->engine->sef->readAlias();
            $article = $this->model->getArticleByAlias($alias);
        }

        $category = $article->getElementFromData("category");
        $viewType = "view";
        $cw = $category->get_option_by_key("use_template_view");

        if ($cw){
            $viewType = $category->get_option_by_key("use_template_view");
            $view->setViewType($viewType);
            $view->setData($article);
            $view->view();
        }

        if ($viewType=="view-recepti"){
            $articles = $this->model->getRecentArticles(array($article->getID()), $article->getCategorieID(), 6);
            $view->setData(array("article"=>$article,"articles"=>$articles));
            $view->viewRecept();
        }
        else {
            $view->setData($article);
            $view->view();
        }
    }

    protected function viewRecept(){
        // Get article alias
        $alias = $this->engine->sef->readAlias();

        // Get article
        $article = $this->model->getArticleByAlias($alias);

        $view = new articleView();
        $view->setData($article);
        $view->view();
    }
}
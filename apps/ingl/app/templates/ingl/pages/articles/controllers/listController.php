<?php

/**
 * Created by PhpStorm.
 * User: sutija
 * Date: 19.8.2015.
 * Time: 18.54
 */
class listController extends controller
{
    /**
     * @param modelListArticles $model
     */
    public function __construct($model)
    {
        parent::__construct($model);
        $this->resolve();
    }

    protected function listAll()
    {
        $pnum = 1;

        if (isset($this->engine->sef->sef_params["pnum"])) {
            $pnum = $this->engine->sef->sef_params["pnum"];
        }

        $articles = $this->model->listArticles($this->engine->sef->sef_params["id"], $pnum);
        $view = new articlesListView();
        $view->setData($articles);

        $articleCategory = $this->model->getCategory($this->engine->sef->sef_params["id"]);
        if ($articleCategory->get_option_by_key("use_template_list_view")){
            $view->view($articleCategory->get_option_by_key("use_template_list_view"));
        }
        else {
            $view->view();
        }


    }
}
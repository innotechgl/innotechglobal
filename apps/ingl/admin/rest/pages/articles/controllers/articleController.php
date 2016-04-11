<?php

/**
 * Class articleController
 */
class articleController extends controller
{

    /**
     * @var rest_class
     */
    protected $rest;
    protected $view;


    /**
     * @param articleModel $model
     * @param articleView $view
     */
    public function __construct(articleModel $model, $view)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->view = $view;



        $this->rest = new rest_class();
        $this->resolve();


    }

    protected function getArticleByCategorieID()
    {
        $this->model->loadArticlesByCategorieID(
            $this->engine->sef->sef_params["id"],
            true,
            0,
            "no",
            "ASC"
        );
        $articles = $this->model->convertArticlesToArray();
        $this->view->setData($articles);
        $this->view->show();
    }

    protected function getAll(){
        $articles  = $this->model->getAll();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $articles);
    }

    /**
     * @todo make load options
     */
    protected function getOptions(){
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("options"=>array()));
    }

    protected function save()
    {
        $data = $this->getRequestData();

        $article = $this->model->add($data["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("article" => $article->__getArray()));
    }

    protected function edit(){
        $data = $this->getRequestData();

        $res = $this->model->edit($data["data"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("updated"=>$res));
    }

    protected function getArticle(){
        $id = (int)$_GET["id"];
        $article = $this->model->loadArticle($id);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $article->__getArray());
    }

    protected function update()
    {
        $res = $this->model->update();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("result" => $res));
    }

    protected function delete()
    {
        $rawData = $this->getRequestData();
        $data = $rawData["data"];
        $res = $this->model->delete((int)$data['id']);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }

    protected function multipleDelete(){
        $data = $this->getRequestData();

        $res  = $this->model->deleteMultiple($data["data"]["ids"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array($res));
    }

    protected function activate()
    {
        $data = $this->getRequestData();

        $res = $this->model->activate($data["id"]);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("result" => $res));
    }

    protected function deactivate()
    {
        $data = $this->getRequestData();

        $res = $this->model->deactivate($data["id"]);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("result" => $res));
    }

    protected function getAllArticleCategories(){
        $data = $this->model->getAllArticleCategories();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getAllForMenuCreator(){
        $id = filter_input(INPUT_GET,"categorie_id",FILTER_SANITIZE_NUMBER_INT);
        $data = $this->model->getAllForMenuCreator($id);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getTasksAndOptions(){
        $data = $this->model->getTasksAndOptions();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getArticleSettings(){
        $data = $this->model->getArticleSettings();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getAllLanguages(){
        $data = $this->model->getAllLanguages();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    /**
     * @return array
     */
    protected function getMapIcons(){
        $data = $this->model->getMapIcons();

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function getGoogleMapData(){

        $id = filter_input(INPUT_GET,"id", FILTER_SANITIZE_NUMBER_INT);

        $data = $this->model->getGoogleMapData($id);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function fixAlias(){
        $data = $this->getRequestData();
        $this->model->updateAlias($data["alias"],$data["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }
    
    protected function sort(){
        $rawData = $this->getRequestData();
        $this->model->_sort($rawData["articles"]);
    }
}
<?php

class controllerUsers extends controller
{
    /**
     *
     * @var modelUsers
     */
    protected $model;
    protected $rest;

    public function __construct($model)
    {
        parent::__construct($model);
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function login()
    {
        $data = $this->getRequestData();
        $token = $this->model->login($data["data"]["mail"], $data["data"]["password"]);
        if ($token !== null) {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                array(
                    "id" => $this->model->active_user->get_id(),
                    "token" => $token
                ));
        } else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_ERROR, array("msg" => "Unknown user."));
        }
    }

    protected function logout()
    {
        $token = filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING);
        $this->model->logoutUser($token);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }

    protected function checkToken()
    {
        $r = $this->model->getUserIDByToken($_GET['token']);
        if ($r !== false) {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK,
                "EXISTS");
        }
    }

    protected function load(){
        $id = filter_input(INPUT_GET,FILTER_SANITIZE_NUMBER_INT);
        $user = $this->model->loadUser($id);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $user);
    }

    protected function getAll(){
        $users = $this->model->getAllUsers();
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $users);
    }

    protected function add(){
        $data = $this->getRequestData();
        $res = $this->model->register($data["data"]);

        $errorData = array();
        if (!$res){
            $errorData = $this->engine->log->error_log['user'];
        }

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK, $errorData);
    }

    protected function update(){
        $this->model;
    }

    protected function delete(){
        $data = $this->getRequestData();
        $this->model->delete($data["data"]["id"]);

        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }

}
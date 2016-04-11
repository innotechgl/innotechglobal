<?php

class calendarController extends controller
{

    /**
     * @var calendarModel
     */
    protected $model;
    protected $rest;

    public function __construct($model)
    {
        parent::__construct($model);
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function add()
    {
        $startDate = filter_input(INPUT_GET, "start_date", FILTER_SANITIZE_STRING);
        $endDate = filter_input(INPUT_GET, "end_date", FILTER_SANITIZE_STRING);
        $relPage = filter_input(INPUT_GET, "rel_page", FILTER_SANITIZE_STRING);
        $relID = filter_input(INPUT_GET, "rel_id", FILTER_SANITIZE_STRING);
        $this->model->addCalendarItems($startDate, $endDate, $relPage, $relID);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }

    protected function getDatesForMonth()
    {
        $month = filter_input(INPUT_GET, "month", FILTER_SANITIZE_STRING);
        $year = filter_input(INPUT_GET, "year", FILTER_SANITIZE_STRING);
        $relPage = filter_input(INPUT_GET, "rel_page", FILTER_SANITIZE_STRING);
        $relID = filter_input(INPUT_GET, "rel_id", FILTER_SANITIZE_STRING);
        $data = $this->model->getDatesForMonth($month, $year, $relPage, $relID);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            $data);
    }

    protected function deleteDates()
    {
        $startDate = filter_input(INPUT_GET, "start_date", FILTER_SANITIZE_STRING);
        $endDate = filter_input(INPUT_GET, "end_date", FILTER_SANITIZE_STRING);
        $relPage = filter_input(INPUT_GET, "rel_page", FILTER_SANITIZE_STRING);
        $relID = filter_input(INPUT_GET, "rel_id", FILTER_SANITIZE_STRING);
        $this->model->deleteDates($startDate, $endDate, $relPage, $relID);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }

    protected function delete()
    {
        $data = $this->getRequestData();
        $this->model->deleteMultiple($data["ids"]);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK);
    }
}
<?php

class controllerAccommodationReservation extends controller
{

    /**
     * @var rest_class
     */
    protected $rest;

    /**
     * @var modelAccommodationReservations
     */
    protected $model;

    public function __construct($model)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->rest = new rest_class();
        $this->resolve();
    }

    protected function getReservations()
    {
        $accommodationID = filter_input(INPUT_GET, "accommodation_id", FILTER_SANITIZE_NUMBER_INT);
        $data = $this->model->getReservations($accommodationID);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("reservations" => $data));
    }

    protected function getPendingReservations()
    {
        $accommodationID = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $data = $this->model->getPendingReservations($accommodationID);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("reservations" => $data));
    }

    protected function getAcceptedReservations()
    {
        $accommodationID = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $data = $this->model->getAcceptedReservations($accommodationID);
        $this->rest->createResponse(
            rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK,
            array("reservations" => $data));
    }

    protected function addReservation()
    {
    }

    protected function AcceptReservation()
    {
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $result = $this->model->acceptReservation($id);
        if ($result) {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK);
        } else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_ERROR,
                rest_class::REST_STATUS_ERROR);
        }
    }

    protected function rejectReservation()
    {
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
        $result = $this->model->rejectReservation($id);
        if ($result) {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_OK,
                rest_class::REST_STATUS_OK);
        } else {
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_ERROR,
                rest_class::REST_STATUS_ERROR);
        }
    }
}
<?php
// Disable direct access to script
if (!defined("_scms_rest_")) {
    die("Error: Not allowed");
}
$option = '';
if (isset($engine->sef->sef_params["option"])) {
    $option = $engine->sef->sef_params["option"];
}
$controller;
switch ($option) {
    case "accommodation":
        // Include controller
        require_once 'pages/accommodations/controllers/'
            . 'accommodation/accommodation_controller.php';
        $engine->import(array("pages.accommodations.classes.accommodation.accommodation"));
        require_once 'pages/accommodations/models/'
            . 'accommodation/accommodation_model.php';
        $model = new accommodation_model();
        $controller = new accommodation_controller($model);
        $controller->resolve();
        break;
    case "room":
        require_once 'pages/accommodations/controllers/'
            . 'room/room_controller.php';
        $engine->import(array("pages.accommodations.classes.room.accommodationRooms"));
        $model = new accommodation_class;
        $controller = new accommodation_controller($model);
        $controller->resolve();
        break;
    case "bed":
        require_once 'pages/accommodations/controllers/'
            . 'bed/accommodation_controller.php';
        break;
}
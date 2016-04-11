<?php

// Disable direct access to script
if (!defined("_scms_rest_")) {
    die("Error: Not allowed");
}
require_once "../pages/accommodations/interfaces/accommodationReservation.php";
require_once "../pages/accommodations/classes/accommodation/items/accommodationReservation_item.php";
$engine->import(array("pages.accommodations.classes.accommodation.accommodationReservations"));
require_once "pages/reservations/controllers/controllerAccommodationReservation.php";
require_once "pages/reservations/models/modelAccommodationReservations.php";
$model = new modelAccommodationReservations();
$controller = new controllerAccommodationReservation($model);
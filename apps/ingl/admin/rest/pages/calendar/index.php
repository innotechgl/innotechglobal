<?php


$engine->load_page("calendar");
require_once "pages/calendar/controllers/controller.php";
require_once "pages/calendar/models/model.php";
$model = new calendarModel();
$controller = new calendarController($model);
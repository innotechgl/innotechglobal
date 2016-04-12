<?php

$this->engine->pages->load_page("booking","updater");

require_once 'controllers/bookingController.php';
require_once 'models/bookingModel.php';

$model = new bookingModel();
$controller = new bookingController($model);
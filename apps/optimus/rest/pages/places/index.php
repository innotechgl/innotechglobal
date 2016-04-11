<?php

require_once _APP_DIR_.'pages/places/controllers/placesController.php';
require_once _APP_DIR_.'pages/places/models/placesModel.php';

$model = new placesModel();
$controller = new placesController($model);
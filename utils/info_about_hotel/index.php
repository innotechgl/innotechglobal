<?php

$this->engine->pages->load_page("about_hotel","updater");

require_once 'controllers/infoAboutHotelController.php';
require_once 'models/infoAboutHotelModel.php';

$model = new infoAboutHotelModel();
$controller = new infoAboutHotelController($model);
<?php
$engine->pages->load_pages("banners","banner_categories");
// Load required util
$engine->load_util("photos");

require_once 'pages/banners/controllers/bannersController.php';

require_once 'pages/banners/models/bannersModel.php';
require_once 'pages/banners/models/modelCategory.php';

// Load banner object
require_once _ROOT_.'pages/banners/classes/bannerObject.php';

$model = new bannersModel();
$controller = new bannersController($model);
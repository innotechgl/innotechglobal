<?php

$this->engine->load_page("videoGallery_categories");

require_once 'pages/videoGallery_categories/controllers/videoCategoriesController.php';
require_once 'pages/videoGallery_categories/models/videoCategoriesModel.php';

$model = new videoCategoriesModel();
$controller = new videoCategoriesController($model);
<?php

require_once _ROOT_.'pages/article_photos/photoItem/photoItem_class.php';

// Load required pages
$engine->pages->load_pages( "article_photo_categories", "article_photos", "updater" );

// Load required util
$engine->load_util("photos");

require_once 'pages/article_photos/models/model.php';
require_once 'pages/article_photos/models/modelCategory.php';
require_once 'pages/article_photos/controllers/controller.php';

$model = new modelArticlePhotos();
$controller = new articlePhotosController($model);
<?php
$engine->pages->load_pages('articles, article_categories','updater');
$engine->load_util('googleMaps');

require_once 'pages/map/controllers/mapController.php';
require_once 'pages/map/models/mapModel.php';

$model = new mapModel();
$controller = new mapController($model);
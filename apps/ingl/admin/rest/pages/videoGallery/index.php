<?php

// Load pages
$engine->pages->load_pages("videoGallery","videoGallery_categories");
$engine->load_util("photos");

require_once "pages/videoGallery/controllers/controller.php";
require_once "pages/videoGallery/models/modelVideoGallery.php";

$modelVideos = new modelVideoGallery();
$controller = new controllerVideoGallery($modelVideos);
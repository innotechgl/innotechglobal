<?php

// Load pages
$engine->pages->load_pages("gallery","gallery_categories");
$engine->load_util("photos");

require_once "pages/photoGallery/controllers/controller.php";
require_once "pages/photoGallery/models/modelPhotoGallery.php";

$modelPhotos = new modelPhotoGallery();

$controller = new controllerGallery($modelPhotos);
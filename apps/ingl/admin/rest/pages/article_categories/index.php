<?php

$this->engine->pages->load_pages("article_categories", "updater");

require_once 'pages/article_categories/controllers/articleCategoriesController.php';
require_once 'pages/article_categories/models/articleCategoriesModel.php';

$model = new articleCategoriesModel();
$controller = new articleCategoriesController($model);
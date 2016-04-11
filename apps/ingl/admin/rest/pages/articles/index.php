<?php
$engine->pages->load_pages("articles","article_categories", "updater");

require_once 'pages/articles/objects/articleObjectInteractive.php';
require_once 'pages/articles/controllers/articleController.php';
require_once 'pages/articles/models/articleModel.php';
require_once 'pages/articles/view/articleView.php';

$model = new articleModel();
$view = new articleView();
$controller = new articleController($model, $view);
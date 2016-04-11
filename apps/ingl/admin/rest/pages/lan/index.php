<?php

require_once 'pages/lan/controllers/controller.php';
require_once 'pages/lan/models/model.php';

$model = new modelLang();
$controller = new controllerLang($model);
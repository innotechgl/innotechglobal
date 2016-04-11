<?php

require_once 'pages/widgets/controllers/controller.php';
require_once 'pages/widgets/models/model.php';

// Load banner object

$model = new modelWidgets();
$controller = new controllerWidgets($model);
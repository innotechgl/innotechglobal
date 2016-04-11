<?php

$this->engine->pages->load_page("product_groups","updater");

require_once 'controllers/productGroupsController.php';
require_once 'models/productGroupsModel.php';

$model = new productGroupsModel();
$controller = new productGroupsController($model);
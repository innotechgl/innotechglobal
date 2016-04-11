<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:22 PM
 */

$this->engine->pages->load_page("product_models","updater");

require_once 'controllers/productModelsController.php';
require_once 'models/productModelsModel.php';

$model = new productModelsModel();
$controller = new productModelsController($model);
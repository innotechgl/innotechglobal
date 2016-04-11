<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:35 PM
 */

$this->engine->pages->load_page("product_models","updater");

require_once 'controllers/imeiController.php';
require_once 'models/imeiModel.php';

$model = new imeiModel();
$controller = new imeiController($model);
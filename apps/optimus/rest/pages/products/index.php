<?php

require_once _APP_DIR_.'pages/products/controllers/productsController.php';
require_once _APP_DIR_.'pages/products/models/productsModel.php';
require_once _APP_DIR_.'pages/products/models/imeiModel.php';
require_once _APP_DIR_.'pages/products/models/imeiObject.php';

$model = new productsModel();
$controller = new productsController($model);
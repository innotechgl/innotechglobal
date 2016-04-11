<?php
/**
 * Created by PhpStorm.
 * User: Dajana
 * Date: 2/24/2016
 * Time: 1:18 PM
 */
require_once _APP_DIR_.'pages/customer_orders/controllers/customerOrdersController.php';
require_once _APP_DIR_.'pages/customer_orders/models/customerOrdersModel.php';

$model = new customerOrdersModel();
$controller = new customerOrdersController($model);
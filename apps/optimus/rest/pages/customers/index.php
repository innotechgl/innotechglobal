<?php
/**
 * Created by PhpStorm.
 * User: Dajana
 * Date: 2/24/2016
 * Time: 12:43 PM
 */

require_once _APP_DIR_.'pages/customers/controllers/customersController.php';
require_once _APP_DIR_.'pages/customers/models/customersModel.php';

$model = new customersModel();
$controller = new customersController($model);
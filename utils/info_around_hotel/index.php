<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:35 PM
 */

$this->engine->pages->load_page("around_hotel","updater");

require_once 'controllers/infoAroundHotelController.php';
require_once 'models/infoAroundHotelModel.php';

$model = new infoAroundHotelModel();
$controller = new infoAroundHotelController($model);
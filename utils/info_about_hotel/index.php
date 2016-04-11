<?php
/**
 * Created by PhpStorm.
 * User: L502M
 * Date: 2/23/2016
 * Time: 4:35 PM
 */

$this->engine->pages->load_page("about_hotel","updater");

require_once 'controllers/infoAboutHotelController.php';
require_once 'models/infoAboutHotelModel.php';

$model = new infoAboutHotelModel();
$controller = new infoAboutHotelController($model);
<?php
if (!defined("_scms_rest_")) {
    die("Error: Not allowed");
}

$engine->pages->load_pages("menu","updater");

require_once "pages/menu/controllers/controller.php";
require_once "pages/menu/models/modelMenu.php";

$model = new modelMenu();
$controller = new controllerMenu($model);
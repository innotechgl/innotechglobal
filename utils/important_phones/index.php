<?php

$this->engine->pages->load_page("important_phones","updater");

require_once 'controllers/importantPhonesController.php';
require_once 'models/importantPhonesModel.php';

$model = new importantPhonesModel();
$controller = new importantPhonesController($model);
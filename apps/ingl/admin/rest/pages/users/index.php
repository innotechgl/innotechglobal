<?php
if (!defined("_scms_rest_")) {
    die("Error: Not allowed");
}
require_once "pages/users/models/modelUsers.php";
require_once "pages/users/controllers/controller.php";

require_once _ROOT_."core/users/user_item/abstract/userInfo.php";

$model = new modelUsers();
$controllerUsers = new controllerUsers($model);
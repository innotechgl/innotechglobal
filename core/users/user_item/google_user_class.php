<?php
global $engine;
require_once($engine->path . 'core/users/user_item/abstract/user_item_class.php');

final class google_user_class extends user_item
{

    function __construct()
    {
        $this->set_user_type(self::USER_TYPE_GOOGLE_USER);
    }
}

?>
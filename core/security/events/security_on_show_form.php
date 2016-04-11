<?php
// Require
require_once('core/abstract/event_class.php');
require_once('core/user_groups/classes/classes.php');
require_once('core/security/security_items/security_usergroup_item.php');
require_once('core/security/security_items/security_user_item.php');

class event_on_show_form extends event_class
{

    public function fire()
    {
        global $engine;
        // Get groups
        $user_groups = $engine->user_groups->get_array(false);
        $query = "";
    }

    protected function get_attached_groups()
    {
    }

    protected function get_attached_users()
    {
    }
}

?>
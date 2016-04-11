<?php
// Events
global $engine;

// Define event
class event_show_form extends event_class
{
    /**
     * Fire event
     * @
     */
    public function fire()
    {
        global $engine;
        // Load
        $engine->load_util('ownership');
        $engine->load_page('user_profile');
        // Set data
        $tasks = array();
        $users = array();
        $user_groups = array();
        // get users
        $users_raw = $engine->users->get_users();
        $user_profiles = $engine->user_profile->get_array(false);
        $i = 0;
        foreach ($users_raw as $key => $val) {
            $users[$i]['id'] = $val['id'];
            $users[$i]['username'] = $val['username'];
            foreach ($user_profiles as $key_p => $val_p) {
                if ($val_p['user_id'] == $val_p['user_id']) {
                    $users[$i]['first_name'] = $val_p['first_name'];
                    $users[$i]['last_name'] = $val_p['last_name'];
                    $users[$i]['group_name'] = 'grupa';
                    break;
                }
            }
            $i++;
        }
        // Get user groups
        $groups = $engine->user_groups->get_user_groups(false);
        $user_groups = $groups;
        // Get allowed users
        $allowed = $engine->util_ownership->get_array(false, '*', 'WHERE rel_page LIKE "' . $this->fired_at . '" AND rel_id=' . $this->data['id']);
        // show forms
        $engine->util_ownership->load_html('view.php', $tasks, $users, $user_groups, $allowed);
    }
}

// Create event
$event = new event_show_form();
$event->name = 'Ownership show form';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_SHOW_FORM;
// Register event
$engine->events->addEvent($event);
// destroy vars
unset($event);
?>
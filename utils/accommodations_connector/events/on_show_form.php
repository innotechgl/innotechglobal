<?php
// Events
global $engine;

// Define event
class accommodations_connector_event_show_form extends event_class
{
    /**
     * Fire event
     * @
     */
    public function fire()
    {
        global $engine;
        // Load
        $engine->load_util('accommodations_connector');
        // Set data
        $tasks = array();
        $users = array();
        $user_groups = array();
        // get markers
        $room_id = $engine->util_accommodations_connector->getConnectionForRelated($this->fired_at, $this->data['id']);
        // show forms
        $engine->util_accommodations_connector->load_html('select_list.php', array("room_id" => $room_id));
    }
}

// Create event
$event = new accommodations_connector_event_show_form();
$event->name = 'Accommodation connector select';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_SHOW_FORM;
// Register event
$engine->events->addEvent($event);
// destroy vars
unset($event);
?>
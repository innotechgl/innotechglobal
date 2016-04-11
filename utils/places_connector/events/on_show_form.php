<?php
// Events
global $engine;

// Define event
class places_connector_event_show_form extends event_class
{
    /**
     * Fire event
     * @
     */
    public function fire()
    {
        global $engine;
        // Load
        $engine->load_util('places_connector');
        // Set data
        $tasks = array();
        $users = array();
        $user_groups = array();
        // get markers
        $places_array = $engine->util_places_connector->get_array(false, 'place_id', 'WHERE rel_page LIKE "' . $this->fired_at . '" AND rel_id=' . $this->data['id']);
        // Define gallery connection
        $places_collection = array();
        // Go through gals
        foreach ($places_array as $key_p => $val_p) {
            $places_collection[] = $val_p['place_id'];
        }
        // show forms
        $engine->util_places_connector->load_html('select_list.php', array("places_connection" => $places_collection));
    }
}

// Create event
$event = new places_connector_event_show_form();
$event->name = 'Places connector select';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_SHOW_FORM;
// Register event
$engine->events->addEvent($event);
// destroy vars
unset($event);
?>
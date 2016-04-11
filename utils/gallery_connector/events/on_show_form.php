<?php

// Events
global $engine;

// Define event
class gallery_connector_event_show_form extends event_class
{
    /**
     * Fire event
     * @
     */
    public function fire()
    {
        global $engine;
        // Load
        $engine->load_util('gallery_connector');
        // Set data
        $tasks = array();
        $users = array();
        $user_groups = array();
        // get markers
        $gals_array = $engine->util_gallery_connector->get_array(false, 'gal_id', 'WHERE rel_page LIKE "' . $this->fired_at . '" AND rel_id=' . $this->data['id']);
        // Define gallery connection
        $gal_collection = array();
        // Go through gals
        foreach ($gals_array as $key_g => $val_g) {
            $gal_collection[] = $val_g['gal_id'];
        }
        // show forms
        $engine->util_gallery_connector->load_html('select_list.php', array("gal_connection" => $gal_collection));
    }
}

// Create event
$event = new gallery_connector_event_show_form();
$event->name = 'Gallery connector select';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_SHOW_FORM;
// Register event
$engine->events->addEvent($event);
// destroy vars
unset($event);
?>
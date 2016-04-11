<?php
// Events
global $engine;

// Define event
class category_connector_event_show_form extends event_class
{
    /**
     * Fire event
     * @
     */
    public function fire()
    {
        global $engine;
        // Load
        $engine->load_util('category_connector');
        // Set data
        $tasks = array();
        $users = array();
        $user_groups = array();
        // get markers
        $categories_array = $engine->util_category_connector->get_array(false, 'place_id', 'WHERE rel_page LIKE "' . $this->fired_at . '" AND rel_id=' . $this->data['id']);
        // Define gallery connection
        $categories_collection = array();
        // Go through gals
        foreach ($categories__array as $key_p => $val_c) {
            $categories_collection[] = $val_c['categorie_id'];
        }
        // show forms
        $engine->util_category_connector->load_html('select_list.php', array("categories_connection" => $categories_collection));
    }
}

// Create event
$event = new category_connector_event_show_form();
$event->name = 'Places connector select';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_SHOW_FORM;
// Register event
$engine->events->addEvent($event);
// destroy vars
unset($event);
?>
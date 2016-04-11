<?php
// Events
global $engine;

// Define event
class gmap_event_show_form extends event_class
{
    /**
     * Fire event
     * @
     */
    public function fire()
    {
        global $engine;
        // Load
        $engine->load_util('googleMaps');
        // Set data
        $tasks = array();
        $users = array();
        $user_groups = array();
        // get markers
        $tags = $engine->util_googleMaps->get_array(false, '*', 'WHERE rel_page LIKE "' . $this->fired_at . '" AND rel_id=' . $this->data['id']);
        $lat = 0;
        $lng = 0;
        $zoom = 0;
        $title = '';
        $text = '';
        $url = '';
        if (count($tags) > 0) {
            $lat = $tags[0]['lat'];
            $lng = $tags[0]['lng'];
            $zoom = $tags[0]['zoom'];
            $title = $tags[0]['rel_title'];
            $text = $tags[0]['rel_text'];
            $url = $tags[0]['rel_url'];
        }
        // show forms
        $engine->util_googleMaps->load_html('editView.php', array("lng" => $lng, "lat" => $lat, "zoom" => $zoom));
    }
}

// Create event
$event = new gmap_event_show_form();
$event->name = 'googleMaps show form';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_SHOW_FORM;
// Register event
$engine->events->addEvent($event);
unset($event);
?>
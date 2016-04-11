<?php

// Define event
class places_connector_event_saved extends event_class
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
        // Set vals
        $vals = $engine->security->get_vals(array("place_id"));
        // Connect
        $engine->util_places_connector->add($this->fired_at, $this->data['id'], $vals['place_id']);
    }
}

// Create event
$event = new places_connector_event_saved();
$event->name = 'Connect places on save';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_UPDATE;
// Register event
$engine->events->addEvent($event);
?>
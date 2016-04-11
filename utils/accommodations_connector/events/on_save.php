<?php

// Define event
class accommodations_connector_event_saved extends event_class
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
        // Set vals
        $vals = $engine->security->get_vals(array("room_id"));
        // Connect
        $engine->util_accommodations_connector->updateConnection($this->fired_at, $this->data['id'], $vals['room_id']);
    }
}

// Create event
$event = new accommodations_connector_event_saved();
$event->name = 'Connect rooms on save';
$event->fire_in_pages = array('articles', 'addressbook', 'cars');
$event->fire_type = 'admin';
$event->event_type = events::ON_UPDATE;
// Register event
$engine->events->addEvent($event);
?>
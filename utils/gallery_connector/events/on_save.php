<?php

// Define event
class gallery_connector_event_saved extends event_class
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
        // Set vals
        $vals = $engine->security->get_vals(array("gal_id"));
        // Connect
        $engine->util_gallery_connector->add($this->fired_at, $this->data['id'], $vals['gal_id']);
    }
}

// Create event
$event = new gallery_connector_event_saved();
$event->name = 'Connect galeries on save';
$event->fire_in_pages = array('articles', 'addressbook', 'cars');
$event->fire_type = 'admin';
$event->event_type = events::ON_UPDATE;
// Register event
$engine->events->addEvent($event);
?>
<?php

// Define event
class category_connector_event_saved extends event_class
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
        // Set vals
        $vals = $engine->security->get_vals(array("categorie_id"));
        // Connect
        $engine->util_category_connector->add($this->fired_at, $this->data['id'], $vals['categorie_id']);
    }
}

// Create event
$event = new category_connector_event_saved();
$event->name = 'Connect category on save';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_UPDATE;
// Register event
$engine->events->addEvent($event);
?>
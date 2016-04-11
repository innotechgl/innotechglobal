<?php

// Define event
class event_deleted extends event_class
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
        // Delete
        $engine->util_googleMaps->delete($this->fired_at, $this->data['id']);
    }
}

// Create event
$event = new event_deleted();
$event->name = 'Deleted';
$event->fire_in_pages = array('articles');
$event->fire_type = 'admin';
$event->event_type = events::ON_DELETE;
// Register event
$engine->events->addEvent($event);
// destroy vars
unset($event);

?>
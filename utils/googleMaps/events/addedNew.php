<?php

// Define event
class event_addedNew_article extends event_class
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
        $vals = $engine->security->get_vals(array("description", "title", "lat", "lng", "zoom"));
        // add new point
        if ((double)$vals['lat'] > 0 && (double)$vals['lng'] > 0) {
            $engine->util_googleMaps->add($this->fired_at, $this->data['id'], $vals['lat'],
                $vals['lng'], $vals['zoom'], $this->data['url'], $vals['title'], $vals['description']);
        } else {
            $engine->util_googleMaps->delete($this->fired_at, $this->data['id']);
        }
    }
}

// Create event
# $event = new event_addedNew_article();
# $event->name = 'Saved new article';
# $event->fire_in_pages = array('articles');
# $event->fire_type = 'admin';
# $event->event_type = events::ON_SAVE;
// Register event
# $engine->events->addEvent($event);
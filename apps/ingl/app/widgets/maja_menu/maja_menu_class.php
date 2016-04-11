<?php

/**
 * @name Banner
 * @package Widget, sCMS
 */
final class widget_maja_menu_class extends widget_class
{

    protected $menu;
    protected $menu_children;
    protected $children;

    public function __construct()
    {
        parent::__construct();
        /**
         * Get engine
         * @global Object $engine
         */
        global $engine;
        /**
         * Load required pages for this widget
         *
         */
        $engine->pages->load_pages("menu");
    }

    /**
     * Prepare me
     *
     * @global Object $engine
     * @name prepare_me
     */
    public function prepare_me()
    {
        global $engine;
        $get_lang = 'AND language LIKE "' . $engine->get_lang() . '"';
        // Check language
        if ($engine->get_lang() == "cir" || $engine->get_lang() == "lat") {
            $get_lang = 'AND (language LIKE "cir" OR language LIKE "lat")';
        }

        //var_dump($this->settings['categorie_id']);

        $this->menu = $engine->menu->get_array(false, '*', 'WHERE parent_id IN (' . implode(",",$this->settings['categorie_id']) . ') ' . $get_lang . ' AND active=1', 'no', 'ASC');
        if (count($this->menu) > 0) {
            $menus = array();
            // Get root menus
            foreach ($this->menu as $val_m) {
                # add menu
                $menus[] = $val_m['id'];
            }
            // Child
            $this->menu_children = $engine->menu->get_array(false, '*', 'WHERE active=1 AND parent_id IN (' . implode(",", $menus) . ')', 'no', 'ASC');
            $this->children = array();
            foreach ($this->menu_children as $key_c => $val_c) {
                $this->children[$val_c['parent_id']][] = $val_c;
            }
            // Set view
            $viewType = 'view';
            if (isset($this->settings['viewType'])) {
                $viewType = $this->settings['viewType'];
            }
            $this->set_view($viewType);
        } else {
            // Don' t display widget!
            $this->set_view(NULL);
        }
    }
}
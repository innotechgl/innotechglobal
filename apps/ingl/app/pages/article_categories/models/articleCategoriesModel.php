<?php

class articleCategoriesModel extends article_categories_class
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param DateTime $sinceDate
     * @return array
     */
    public function getAll(DateTime $sinceDate){
        $categories = array();
        $data = $this->get_array(false, "*", "WHERE modified > '".$sinceDate->format("Y-m-d h:i:s")."'");

        foreach($data as $val){
            $category = new category_item();
            $category->fillMe($val);
            $category->set_name($this->engine->language->cirToLat($category->get_name(),true));
            $category->set_options(json_decode($category->get_options(),true));
            $categories[] = $category->__toArray();
        }

        return $categories;
    }

}
<?php

/**
 *
 * @author dajana
 *
 */
class category_item
{

    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @var parent_id
     */
    protected $parent_id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $alias;

    /**
     *
     * @var int
     */
    protected $active;

    /**
     * @var Str
     */
    protected $language;

    /**
     *
     * @var int
     */
    protected $no;
    protected $creator;
    protected $created;
    protected $modifier;
    protected $modified;
    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $options = array();
    protected $meta;

    // Id
    public function set_id($id)
    {
        $this->id = (int)$id;
    }

    public function get_id()
    {
        return $this->id;
    }

    // Parent id
    public function set_parent_id($parent_id)
    {
        $this->parent_id = (int)$parent_id;
    }

    public function get_parent_id()
    {
        return $this->parent_id;
    }

    /**
     *
     * @param string $name
     */
    public function set_name($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     *
     * @param string $description
     */
    public function set_description($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @return description
     */
    public function get_description()
    {
        return $this->description;
    }

    // Name
    public function set_alias($alias)
    {
        $this->alias = $alias;
    }

    public function get_alias()
    {
        return $this->alias;
    }

    // Active
    public function set_active($active)
    {
        $this->active = (int)$active;
    }

    public function get_active()
    {
        return $this->active;
    }

    public function set_lang($lang){
        $this->language = $lang;
    }

    public function get_lang(){
        return $this->language;
    }

    // Active
    public function set_no($no)
    {
        $this->no = (int)$no;
    }

    public function get_no()
    {
        return $this->no;
    }

    // Created
    public function set_created($created)
    {
        global $engine;
        $this->created = $engine->security->get_val($created, 'date');
    }

    public function get_created($format = 'Y-m-d h:m:s')
    {
        return date($format, strtotime($this->created));
    }

    // Modified
    public function set_creator($creator)
    {
        $this->creator = (int)$creator;
    }

    public function get_creator()
    {
        return $this->creator;
    }

    // Modified
    public function set_modified($modified)
    {
        global $engine;
        $this->modified = $engine->security->get_val($modified, 'date');
    }

    public function get_modified($format = 'Y-m-d h:m:s')
    {
        return date($format, strtotime($this->modified));
    }

    // Modified
    public function set_modifier($modifier)
    {
        $this->modifier = (int)$modifier;
    }

    /**
     *
     * @return string
     */
    public function get_modifier()
    {
        return $this->modifier;
    }

    /**
     *
     * @param array $options
     */
    public function set_options($options)
    {
        $this->options = $options;
    }

    public function get_options()
    {
        return $this->options;
    }

    public function set_meta($meta)
    {
        $this->meta = $meta;
    }

    public function get_meta()
    {
        return $this->meta;
    }

    public function get_option_by_key($key){
        if (isset($this->options[$key])){
            return $this->options[$key];
        }
        else{
            return false;
        }

    }


    /**
     *
     * @param data $data
     */
    public function fillMe($data)
    {
        if (isset($data["id"])){
            $this->set_id($data['id']);
        }
        if (isset($data['active'])) {
            $this->set_active($data['active']);
        }
        if (isset($data['language'])) {
            $this->set_lang($data['language']);
        }
        if (isset($data['parent_id'])) {
            $this->set_parent_id($data['parent_id']);
        }
        if (isset($data['name'])) {
            $this->set_name($data['name']);
        }
        if (isset($data['alias'])) {
            $this->set_alias($data['alias']);
        }
        if (isset($data['options'])) {
            $this->set_options($data['options']);
        }
        if (isset($data['description'])) {
            $this->set_description($data['description']);
        }
        if (isset($data['meta'])) {
            $this->set_meta($data['meta']);
        }
        if (isset($data['creator'])) {
            $this->set_creator($data['creator']);
        }
        if (isset($data['created'])) {
            $this->set_created($data['created']);
        }
        if (isset($data['modified'])) {
            $this->set_modified($data['modified']);
        }
        if (isset($data['modifier'])) {
            $this->set_modifier($data['modifier']);
        }
    }

    public function __toArray(){
        $data['id'] = $this->id;
        $data['active'] = $this->active;
        $data['parent_id'] = $this->parent_id;
        $data["name"] = $this->name;
        $data["alias"] = $this->alias;
        $data["options"] =  $this->options;
        $data["language"] = $this->language;
        $data["description"] = $this->description;
        $data["meta"] = $this->meta;
        $data["creator"] = $this->creator;
        $data["created"] = $this->created;
        $data["modified"] = $this->modified;
        $data["modifier"] = $this->modifier;

        return $data;
    }

    public function __toJSON(){
        $json = json_encode($this->__toArray());
        return $json;
    }
}

?>
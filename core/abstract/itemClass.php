<?php

class itemClass
{

    protected $id;
    protected $info;

    protected $created;
    protected $creator;

    protected $modified;
    protected $modifier;

    protected $active;
    protected $language;

    /**
     *
     * @param string $key
     * @param mixed $val
     */
    public function addInfoElement($key, $val)
    {
        $this->info[$key] = $val;
    }

    /**
     *
     * @param string $key
     */
    public function removeInfoElement($key)
    {
        unset($this->info[$key]);
    }

    public function fillMeFromDB(Array $vars)
    {
        $this->fillMe($vars);
        if (isset($vars["info"])) {
            $this->setInfo(json_decode($vars["info"], true));
        }
    }

    /**
     *
     * @param array $vars
     */
    public function fillMe(Array $vars)
    {
        if (isset($vars["id"])) {
            $this->setId($vars['id']);
        }
        if (isset($vars["info"])) {
            $this->setInfo($vars['info']);
        }
        if (isset($vars["creator"])) {
            $this->setCreator($vars['creator']);
        }
        if (isset($vars["created"])) {
            $this->setCreated($vars['created']);
        }
        if (isset($vars["modifier"])) {
            $this->setModifier($vars['modifier']);
        }
        if (isset($vars["modified"])) {
            $this->setModified($vars['modified']);
        }
        if (isset($vars["active"])) {
            $this->setActive($vars['active']);
        }
        if (isset($vars["language"])) {
            $this->setModified($vars['language']);
        }
    }

    /**
     *
     * @param array $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->info[$key];
    }

    /**
     *
     * @return string
     */
    public function __toJSON()
    {
        $arr = $this->__toArray();
        return json_encode($arr);
    }

    /**
     *
     * @return array
     */
    public function __toArray()
    {
        $array = array();
        $array['id'] = $this->getId();
        $array['info'] = $this->getAllInfo();
        $array['created'] = $this->getCreated();
        $array['creator'] = $this->getCreator();
        $array['modifier'] = $this->getModifier();
        $array['modified'] = $this->getModified();
        $array["language"] = $this->getLanguage();
        $array["active"] = $this->getActive();
        return $array;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     *
     * @return array
     */
    public function getAllInfo()
    {
        return $this->info;
    }

    /**
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     *
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     *
     * @return int
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     *
     * @param int $creator
     */
    public function setCreator($creator)
    {
        $this->creator = (int)$creator;
    }

    /**
     *
     * @return string
     */
    public function getModifier()
    {
        return $this->modifier;
    }

    /**
     *
     * @param int $modifier
     */
    public function setModifier($modifier)
    {
        $this->modifier = (int)$modifier;
    }

    /**
     *
     * @return string
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     *
     * @param string $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     *
     * @return String
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = (int)$language;
    }

    /**
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     *
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = (int)$active;
    }
}
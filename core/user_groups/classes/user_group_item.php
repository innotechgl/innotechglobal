<?php

class user_group_item
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $active;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $members;

    const USER_GROUP_TYPE_SUPER_ADMIN = "USER_GROUP_TYPE_SUPER_ADMIN";
    const USER_GROUP_TYPE_ADMIN = "USER_GROUP_TYPE_ADMIN";
    const USER_GROUP_TYPE_MODERATOR = "USER_GROUP_TYPE_MODERATOR";
    const USER_GROUP_TYPE_USER = "USER_GROUP_TYPE_USER";
    const USER_GROUP_TYPE_GUEST = "USER_GROUP_TYPE_GUEST";

    public function __construct()
    {
        $this->type = self::USER_GROUP_TYPE_GUEST;
    }

    /**
     * Set group type
     * @param string $type
     *
     */
    public function set_type($type)
    {
        switch ($type) {
            default:
                return false;
                break;
            case self::USER_GROUP_TYPE_ADMIN:
                $this->type = self::USER_GROUP_TYPE_ADMIN;
                break;
            case self::USER_GROUP_TYPE_GUEST:
                $this->type = self::USER_GROUP_TYPE_GUEST;
                break;
            case self::USER_GROUP_TYPE_MODERATOR:
                $this->type = self::USER_GROUP_TYPE_MODERATOR;
                break;
            case self::USER_GROUP_TYPE_SUPER_ADMIN:
                $this->type = self::USER_GROUP_TYPE_SUPER_ADMIN;
                break;
            case self::USER_GROUP_TYPE_USER:
                $this->type = self::USER_GROUP_TYPE_USER;
                break;
        }
        return true;
    }

    /**
     * @param int $id
     */
    public function setID($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get type
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     *
     * @param Object $user_item
     */
    public function add_member($user_item)
    {
        // Add user to members
        $this->members[] = $user_item;
    }

    /**
     *
     * @param int $index
     */
    public function get_member($index)
    {
        return $this->members[$index];
    }

    /**
     *
     * @param int $index
     */
    public function remove_member($index)
    {
        // Slice array
        array_slice($this->members, $index, 1);
    }

    /**
     * return int
     */
    public function get_active()
    {
        return $this->active;
    }

    /**
     *
     * @param int $active
     */
    public function set_active($active)
    {
        $this->active = (int)$active;
    }

    /**
     *
     * @param int $user_id
     */
    public function is_member($user_id)
    {
        $found = false;
        for ($i = 0; $i < count($this->members); $i++) {
            if ($this->members[$i] == (int)$user_id) {
                $found = true;
                break;
            }
        }
        return $found;
    }
}

?>
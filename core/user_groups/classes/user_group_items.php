<?php

/**
 * Class user_group_items
 */
class user_group_items
{

    protected $groups = array();

    /**
     * @param user_group_item $userGroup
     */
    public function addGroup(user_group_item $userGroup)
    {
        $this->groups[] = $userGroup;
    }

    /**
     * @param string $groupName
     * @return user_group_item
     */
    public function getGroupByName($groupName)
    {
        foreach ($this->groups as $key => $val) {
            if ($val->getName() == $groupName) {
                return $val;
            }
        }
        return false;
    }

    /**
     * @param string $groupName
     * @return bool
     */
    public function checkGroupExistance($groupName)
    {
        foreach ($this->groups as $key => $val) {
            if ($val->getName() == $groupName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $groupID
     * @return user_group_item
     */
    public function getGroupByID($groupID)
    {
        foreach ($this->groups as $key => $val) {
            if ($val->get_did() == $groupID) {
                return $val;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getGroupIDS()
    {
        $ids = array();
        foreach ($this->groups as $val) {
            $ids[] = $val->getID();
        }
        return $ids;
    }
}
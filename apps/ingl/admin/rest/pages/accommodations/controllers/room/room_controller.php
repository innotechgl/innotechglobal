<?php

class room_controller extends controller
{

    /**
     *
     * @var accommodationRooms_class
     */
    protected $model;
    protected $rest;

    public function __construct(accommodation_class $model)
    {
        parent::__construct($model);
        $this->rest = new rest_class();
    }

    public function update()
    {
        /**
         * Room Class
         */
        $this->engine->import(array("pages.accommodations.classes.room"
            . ".items.room_item"));
        $room = new accommodationRoom_item_class();
        $requested = array("title", "type_id", "accommodation_id", "description",
            "alias", "meta", "text", "seo_description",
            "categorisation", "alias");
        $vars = $this->engine->security->get_vals($requested);
        $room->fillMe($vars);
        $room->setId($this->engine->sef->sef_params["id"]);
        /**
         * @todo Add multilanguage option (translation)
         */
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        /**
         * Check if user is owner of accommodation record
         */
        if ($this->engine->security->owner_check(
            // set type
                accommodationRooms_class::_DEF_TYPE_,
                $room->getId(),
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            // Update room
            $res = $this->model->update($room);
            if ($res) {
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodationRooms_class::_DEF_TYPE_)),
                    events::ON_UPDATE,
                    array("room" => $room));
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
            } else {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_ERROR,
                    rest_class::REST_STATUS_ERROR);
            }
        } else {
            // Create and print response
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_FORBIDDEN,
                rest_class::REST_STATUS_FORBIDDEN);
        }
    }

    public function delete()
    {
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        /**
         * Check if user is owner of accommodation record
         */
        if ($this->engine->security->owner_check(
            // set type
                accommodationRooms_class::_DEF_TYPE_,
                $this->engine->sef->sef_params["id"],
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            // Update room
            $res = $this->model->delete($this->engine->sef->sef_params["id"]);
            if ($res) {
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodationRooms_class::_DEF_TYPE_)),
                    events::ON_DELETE,
                    array("id" => $this->engine->sef->sef_params["id"]));
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
            } else {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_ERROR,
                    rest_class::REST_STATUS_ERROR);
            }
        } else {
            // Create and print response
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_FORBIDDEN,
                rest_class::REST_STATUS_FORBIDDEN);
        }
    }

    public function activate()
    {
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        /**
         * Check if user is owner of accommodation record
         */
        if ($this->engine->security->owner_check(
            // set type
                accommodationRooms_class::_DEF_TYPE_,
                $this->engine->sef->sef_params["id"],
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            // Update room
            $res = $this->model->activate($this->engine->sef->sef_params["id"]);
            if ($res) {
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodationRooms_class::_DEF_TYPE_)),
                    events::ON_ACTIVATE,
                    array("id" => $this->engine->sef->sef_params["id"]));
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
            } else {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_ERROR,
                    rest_class::REST_STATUS_ERROR);
            }
        } else {
            // Create and print response
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_FORBIDDEN,
                rest_class::REST_STATUS_FORBIDDEN);
        }
    }

    protected function add()
    {
        /**
         * Room Class
         */
        $this->engine->import(array("pages.accommodations.classes.room"
            . ".items.room_item"));
        $room = new accommodationRoom_item_class();
        $requested = array("title", "type_id", "accommodation_id", "description",
            "alias", "meta", "text", "seo_description",
            "categorisation", "alias");
        $vars = $this->engine->security->get_vals($requested);
        $room->fillMe($vars);
        /**
         * @todo Add multilanguage option (translation)
         */
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        /**
         * Check if user is owner of accommodation record
         */
        if ($this->engine->security->owner_check(
            // set type
                accommodation_class::_DEF_TYPE_,
                $room->getaccommodationId(),
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            $id = $this->model->add($room);
            if ($id > 0) {
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodationRooms_class::_DEF_TYPE_)),
                    events::ON_SAVE,
                    array("room" => $room));
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
            } else {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_ERROR,
                    rest_class::REST_STATUS_ERROR);
            }
        } else {
            // Create and print response
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_FORBIDDEN,
                rest_class::REST_STATUS_FORBIDDEN);
        }
    }

    protected function deactivate()
    {
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        /**
         * Check if user is owner of accommodation record
         */
        if ($this->engine->security->owner_check(
            // set type
                accommodationRooms_class::_DEF_TYPE_,
                $this->engine->sef->sef_params["id"],
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            // Update room
            $res = $this->model->deactivate($this->engine->sef->sef_params["id"]);
            if ($res) {
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodationRooms_class::_DEF_TYPE_)),
                    events::ON_DEACTIVATE,
                    array("id" => $this->engine->sef->sef_params["id"]));
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
            } else {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_ERROR,
                    rest_class::REST_STATUS_ERROR);
            }
        } else {
            // Create and print response
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_FORBIDDEN,
                rest_class::REST_STATUS_FORBIDDEN);
        }
    }

    protected function getRoomsForMyAccommodation()
    {
        $list = $this->model->getRoomsForMyAccommodation($this->engine->sef->sef_params["id"]);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $list[$i]->__toArray();
        }
        $this->rest->createResponse(rest_class::REST_MESSAGE_OK, rest_class::REST_STATUS_OK, $list);
    }

    protected function getRoomsForAccommodation()
    {
        $list = $this->model->getRoomsByAccommodation($this->engine->sef->sef_params["id"]);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $list[$i]->__toArray();
        }
        $this->rest->createResponse(rest_class::REST_MESSAGE_OK, rest_class::REST_STATUS_OK, $list);
    }

    protected function getMyRooms()
    {
        $list = $this->model->getAllMyRooms();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $list[$i]->__toArray();
        }
        $this->rest->createResponse(rest_class::REST_MESSAGE_OK, rest_class::REST_STATUS_OK, $list);
    }
}
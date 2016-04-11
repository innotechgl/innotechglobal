<?php

/**
 * Description of accommodation_controller
 *
 * @author Dajana Nestorovic
 */
class accommodation_controller extends controller
{

    /**
     *
     * @var accommodation_class
     */
    protected $model;
    protected $rest;

    public function __construct(accommodation_class $model)
    {
        parent::__construct($model);
        $this->rest = new rest_class();
    }

    /**
     * @throws string
     */
    protected function add()
    {
        $this->engine->import(array("pages.accommodations.classes.accommodation"
            . ".items.accommodation_item"));
        $accommodation = new accommodation_item_class;
        $requested = array("title", "category_id", "place_id", "title",
            "alias", "desription", "meta", "text", "seo_description",
            "categorisation", "alias");
        $vars = $this->engine->security->get_vals($requested);
        $accommodation->fillMe($vars);
        /**
         * @todo Add multilanguage option (translation)
         */
        $id = $this->model->add($accommodation);
        if ($id > 0) {
            /**
             * Fire event
             */
            $this->engine->events->fireEvent(
                $this->engine->events->defineEventPage(array(
                    accommodations_class::_DEF_TYPE_,
                    accommodation_class::_DEF_TYPE_)),
                events::ON_SAVE,
                array("accommondation" => $accommodation));
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
    }

    /**
     * Update accommodation
     */
    protected function update()
    {
        $this->engine->import(array("pages.accommodations.classes.accommodation"
            . ".items.accommodation_item"));
        $accommodation = new accommodation_item_class;
        $requested = array("id", "title", "category_id", "place_id", "title",
            "alias", "desription", "meta", "text", "seo_description",
            "categorisation", "alias");
        $vars = $this->engine->security->get_vals($requested);
        $accommodation->fillMe($vars);
        $userGroups = array();
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        if ($this->engine->security->owner_check(
            // set type
                accommodation_class::_DEF_TYPE_,
                $accommodation->getId(),
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            // Update
            $res = $this->model->update($accommodation);
            if ($res) {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodation_class::_DEF_TYPE_)),
                    events::ON_UPDATE,
                    array("id" => $this->engine->sef->sef_params["id"]));
            } else {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_ERROR,
                    rest_class::REST_STATUS_ERROR);
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodation_class::_DEF_TYPE_)),
                    events::ON_UPDATE,
                    array("id" => $this->engine->sef->sef_params["id"]));
            }
        } else {
            // Create and print response
            $this->rest->createResponse(
                rest_class::REST_MESSAGE_FORBIDDEN,
                rest_class::REST_STATUS_FORBIDDEN);
        }
        /**
         * @todo Add multilanguage option (translation)
         */
    }

    /**
     * Delete record
     */
    protected function delete()
    {
        $userGroups = array();
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        if ($this->engine->security->owner_check(
            // set type
                accommodation_class::_DEF_TYPE_,
                $this->engine->sef->sef_params["id"],
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            $res = $this->model->delete($this->engine->sef->sef_params["id"]);
            if ($res) {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodation_class::_DEF_TYPE_)),
                    events::ON_DELETE,
                    array("id" => $this->engine->sef->sef_params["id"]));
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

    protected function activate()
    {
        $userGroups = array();
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        if ($this->engine->security->owner_check(
            // set type
                accommodation_class::_DEF_TYPE_,
                $this->engine->sef->sef_params["id"],
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            $res = $this->model->activate($this->engine->sef->sef_params["id"]);
            if ($res) {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodation_class::_DEF_TYPE_)),
                    events::ON_ACTIVATE,
                    array("id" => $this->engine->sef->sef_params["id"]));
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
        $userGroups = array();
        $userGroups = $this->engine->user_groups->
        get_groups_related_to_user($this->engine->users->active_user->get_id());
        if ($this->engine->security->owner_check(
            // set type
                accommodation_class::_DEF_TYPE_,
                $this->engine->sef->sef_params["id"],
                $userGroups->getGroupIDS()) ||
            $this->engine->user_groups->
            checkUserBelongsToAdminGroup($this->engine->users->active_user->get_id())
        ) {
            $res = $this->model->deactivate($this->engine->sef->sef_params["id"]);
            if ($res) {
                // Create and print response
                $this->rest->createResponse(
                    rest_class::REST_MESSAGE_OK,
                    rest_class::REST_STATUS_OK);
                /**
                 * Fire event
                 */
                $this->engine->events->fireEvent(
                    $this->engine->events->defineEventPage(array(
                        accommodations_class::_DEF_TYPE_,
                        accommodation_class::_DEF_TYPE_)),
                    events::ON_DEACTIVATE,
                    array("id" => $this->engine->sef->sef_params["id"]));
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

    protected function getAccommodationListByUser()
    {
        $list = $this->model->getListByUser($this->engine->sef->sef_params["id"]);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $list[$i]->__toArray();
        }
        $this->rest->createResponse(rest_class::REST_MESSAGE_OK, rest_class::REST_STATUS_OK, $list);
    }

    protected function getAccommodationList()
    {
        $list = $this->model->getList();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $list[$i]->__toArray();
        }
        $this->rest->createResponse(rest_class::REST_MESSAGE_OK, rest_class::REST_STATUS_OK, $list);
    }

    protected function getMyAccommodationList()
    {
        $list = $this->model->getMyList();
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $list[$i]->__toArray();
        }
        $this->rest->createResponse(rest_class::REST_MESSAGE_OK,
            rest_class::REST_STATUS_OK, $list);
    }
}
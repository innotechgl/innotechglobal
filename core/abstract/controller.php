<?php

/**
 * Class controller
 * @author Dajana Nestorovic
 *
 */
abstract class controller implements interfaceController
{

    // Use mainEngineTrait
//    use mainEngine;

    protected $model;

    public function __construct($model)
    {
        global $engine;
        $this->engine =& $engine;
        $this->model = $model;
    }

    public function getRequestData()
    {
        $postData = file_get_contents("php://input");
        $request = json_decode($postData, true);
        return $request;
    }

    public function resolve()
    {
        if (isset($this->engine->sef->sef_params["task"])) {
            $name = $this->engine->sef->sef_params["task"];
            if ($name == "list") {
                $name = "listAll";
            }
            if (method_exists($this, $name)) {
                $this->$name();
            } else {
                die("UNKNOWN:: " . $this->engine->sef->sef_params["task"]);
            }
        } else {
            die("UNKNOWN:: " . $this->engine->sef->sef_params["task"]);
        }
    }


}
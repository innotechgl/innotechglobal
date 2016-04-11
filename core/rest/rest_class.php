<?php

class rest_class
{

    const REST_MESSAGE_OK = "OK";
    const REST_STATUS_OK = "200";

    const REST_MESSAGE_FORBIDDEN = "FORBIDDEN";
    const REST_STATUS_FORBIDDEN = "300";

    const REST_MESSAGE_ERROR = "ERROR";
    const REST_STATUS_ERROR = "100";

    protected $response;

    /**
     * @param string $message
     * @param string $code
     * @param array $data
     */
    public function createResponse($message, $code, $data = array())
    {
        $this->response = array();
        $this->response["STATUS_CODE"] = $code;
        $this->response["MESSAGE"] = $message;
        //if (count($data) > 0) {
            $this->response["data"] = $data;
        //}
        $this->sendResponse();
    }

    protected function sendResponse()
    {
        echo json_encode($this->response,JSON_NUMERIC_CHECK);
    }
}
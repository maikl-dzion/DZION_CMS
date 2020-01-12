<?php

namespace Core\Services;

use Core\AbstractCore;

class Response extends AbstractCore {

    public $responseData;

    public function setData($data) {
        $this->responseData = $data;
    }

    public function response($data = null, $responseName = RESPONSE_RESULT_NAME, $code = 200) {
        header("HTTP/1.1 " . $code);
        if(empty($data))
          $data = $this->responseData;
        $data = json_encode(array($responseName => $data));
        return $data;
    }

    public function responseError($data = null, $responseName = RESPONSE_ERROR_NAME, $code = 200) {
        header("HTTP/1.1 " . $code);
        if(empty($data))
            $data = $this->responseData;
        $data = json_encode(array($responseName => $data));
        return $data;
    }

}
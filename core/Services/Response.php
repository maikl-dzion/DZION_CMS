<?php

namespace Core\Services;

use Core\AbstractCore;

class Response extends AbstractCore {

    protected $response;

    public function setResponse($response) {
        $this->response = $response;
    }

//    public function response($data, $status = 200) {
//        header("HTTP/1.1 " . $status . " " . $this->statusCodeList($status));
//        return json_encode($data);
//    }

    public function response($responseName = 'result', $code = 200) {
        header("HTTP/1.1 " . $code);
        $data = $this->response;
        $data = json_encode(array($responseName => $data));
        return $data;
    }

}
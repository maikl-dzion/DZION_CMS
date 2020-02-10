<?php

namespace Core\Kernel;

// use Core\AbstractCore;

class Response extends AbstractCore {

    public $data;

    /**
     * @param $data
     */
    public function setData($data) {
        $this->responseData = $data;
    }

    /**
     * @param null $data
     * @param string $responseName
     * @param int $code
     * @return string
     */
    public function response($data = null, string $responseName = RESPONSE_RESULT_NAME, int $code = 200) : string {
        header("HTTP/1.1 " . $code);
        if(empty($data))
          $data = $this->data;
        $data = json_encode(array($responseName => $data));
        return $data;
    }

    public function responseError($data = null, $responseName = RESPONSE_ERROR_NAME, $code = 200) : string {
        header("HTTP/1.1 " . $code);
        if(empty($data))
            $data = $this->data;
        $data = json_encode(array($responseName => $data));
        return $data;
    }

}
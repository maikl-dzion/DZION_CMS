<?php

namespace Core\Services;

use Core\AbstractCore;

class Response extends AbstractCore {

<<<<<<< HEAD
    public $data;
=======
    public $responseData;
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685

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
<<<<<<< HEAD
          $data = $this->data;
=======
          $data = $this->responseData;
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
        $data = json_encode(array($responseName => $data));
        return $data;
    }

    public function responseError($data = null, $responseName = RESPONSE_ERROR_NAME, $code = 200) : string {
        header("HTTP/1.1 " . $code);
        if(empty($data))
<<<<<<< HEAD
            $data = $this->data;
=======
            $data = $this->responseData;
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
        $data = json_encode(array($responseName => $data));
        return $data;
    }

}
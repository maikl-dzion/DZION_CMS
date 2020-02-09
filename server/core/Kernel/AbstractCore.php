<?php

namespace Core\Kernel;

use Core\Interfaces\ILogger;
use Core\Services\FileLogger;
use Core\Services\Logger;

abstract class AbstractCore {

    protected $logger;

    public function __construct(){
        $this->logger  = new FileLogger(LOG_PATH);
    }

    protected function setLogger(ILogger $logger){
        $this->logger  = $logger;
    }

    protected function statusCodeList($code = '') {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status;
    }

    protected function responseCode($code) {
        http_response_code($code);
    }
}
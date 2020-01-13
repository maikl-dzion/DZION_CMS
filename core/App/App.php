<?php

namespace Core\App;

abstract class App
{
    protected $di;
    protected $db;
    protected $logger;

    public function __construct() {

    }

    protected function responseCode($code) {
        http_response_code($code);
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
}
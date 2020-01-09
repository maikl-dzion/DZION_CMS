<?php

namespace Core\App;

abstract class App
{
    protected $di;
    protected $db;
    protected $logger;

    protected function responseCode($code) {
        http_response_code($code);
    }
}
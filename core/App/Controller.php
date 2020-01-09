<?php

namespace Core\App;

use Core\Services\DI;
// use Core\App\App;

class Controller extends App {

    protected $di;
    protected $db;
    protected $config;
    protected $request;
    protected $parameters = array();

    public function __construct(DI $di, $parameters = array()) {

        $this->di = $di;
        $this->parameters = $parameters;

        $this->db     = $this->di->get('db');
        $this->logger = $this->di->get('logger');

    }

    protected function getParam($index = '') {
        if(!empty($this->parameters[$index]))
            return $this->parameters[$index];
        return false;
    }

    protected function getParams() {
        return $this->parameters;
    }

    public function __get($key) {
        return $this->di->get($key);
    }

    protected function fetchPost($cast = 'array') {
        $data = json_decode(file_get_contents("php://input"));
        switch ($cast) {
            case 'array' : $data = (array)$data; break;
        }
        return $data;
    }
}
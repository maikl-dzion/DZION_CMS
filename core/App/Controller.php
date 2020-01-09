<?php

namespace Core\App;

use Core\Services\DI;
use Core\App\App;

class Controller extends App {

    protected $di;
    protected $db;
    protected $config;
    protected $request;
    //protected $load;
    protected $parameters = array();

    public function __construct(DI $di, $parameters = array()) {

        $this->di = $di;
        $this->parameters = $parameters;

//        $this->db      = $this->di->get('db');
//        $this->view    = $this->di->get('view');
//        $this->config  = $this->di->get('config');
//        $this->request = $this->di->get('request');
//        $this->load    = $this->di->get('load');
//
//        $this->initVars();
    }

    public function __get($key) {
        return $this->di->get($key);
    }

//    public function initVars() {
//        $vars = array_keys(get_object_vars($this));
//
//        foreach ($vars as $var) {
//            if ($this->di->has($var)) {
//                $this->{$var} = $this->di->get($var);
//            }
//        }
//
//        return $this;
//    }
}
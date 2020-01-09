<?php

namespace Core\App;

use Core\App\App;

class Model extends App {

    protected $di;
    protected $db;
    protected $config;
    public $builder;

    public function __construct() {
//        $this->di      = $di;
//        $this->db      = $this->di->get('db');
//        $this->config  = $this->di->get('config');
//        $this->queryBuilder = new QueryBuilder();
    }
}
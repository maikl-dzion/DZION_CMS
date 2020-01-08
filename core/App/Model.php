<?php

namespace Core\App;

// use Core\DI\DI;

class Model extends Core {

    protected $di;
    protected $db;
    protected $config;
    public $queryBuilder;

    public function __construct() {
//        $this->di      = $di;
//        $this->db      = $this->di->get('db');
//        $this->config  = $this->di->get('config');
//        $this->queryBuilder = new QueryBuilder();
    }
}
<?php

namespace Core\Services\DB;

use \PDO;
// use Engine\Core\Config\Config;

class DB
{
    private $pdo;

    public function __construct(){
        $this->connect();
    }

    private function connect() {
//        $config = Config::file('database');
//        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
//        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
//        return $this;
    }


    public function execute($query, $data = []){
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($data);
    }

    public function query($query, $data = [], $statement = PDO::FETCH_OBJ) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
        $result = $stmt->fetchAll($statement);
        if($result === false)
            return [];
        return $result;
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
}
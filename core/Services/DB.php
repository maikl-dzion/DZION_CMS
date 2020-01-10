<?php

namespace Core\Services;

use Doctrine\DBAL\Driver\PDOException;
use \PDO;

use Core\Services\Logger;
use Core\AbstractCore;

class DB extends AbstractCore
{
    private $pdo;
    private $config;
    protected $logger;

    public function __construct(array $config){

        // parent::__construct();
        $this->logger  = new Logger(LOG_PATH);
        $this->config = $config;
        $this->connect($this->config);

    }

    public function reConnect($config) {
        $this->config = $config;
        return $this->connect($this->config);
    }

    private function connect($config) {

        $driver = $config['driver'];
        $dbname = $config['dbname'];
        $host   = $config['host'];
        $port   = $config['port'];
        $user   = $config['user'];
        $password = $config['password'];

        try {
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
            $dsn = "{$driver}:dbname={$dbname};host={$host};port={$port}";
            $this -> pdo = new PDO($dsn, $user, $password, $options);

        } catch (PDOException $e) {
            $title = "Не удалось подключиться к базе данных";
            exceptionHandler($e, $title);
            exit;
        }

        return $this;
    }

    public function insertSqlForm($fields, $data) {
        $_fields = $_alias = $_values = array();

        foreach ($fields as $fieldName => $fieldValue) {
            if(!empty($data[$fieldName])) {

                $value = $data[$fieldName];
                $alias = ":{$fieldName}";

                $_fields[] = $fieldName;
                $_alias[]  = $alias;
                $_values[$fieldName] = $value;
            }
        }

        $fieldsStr = implode(',', $_fields);
        $aliasStr  = implode(',' , $_alias);
        $query    = "({$fieldsStr})  VALUES({$aliasStr})";
        return array( 'query' => $query,
                      'values' => $_values);
    }

    public function updateSqlForm($fields, $data){
        $queryArray = $_values = array();
        foreach($fields as $fieldName => $fieldValue) {
            if(!empty($data[$fieldName])) {
                $alias = ":{$fieldName}";
                $value = $data[$fieldName];
                $queryArray[] = "{$fieldName} = {$alias}";
                $_values[$fieldName]  = $value;
            }
        }

        $query = implode(',', $queryArray);
        return array('query'  => $query,
                     'values' => $_values);
    }

    public function debugStmt($stmt) {
        $debug = $stmt->debugDumpParams();
        return $debug;
    }

    public function bindExec($query, $data) {
        $stmt   = $this->pdo->prepare($query);
        foreach ($data as $fieldName => $value) {
            $value = htmlspecialchars(strip_tags($value));
            $stmt->bindValue(":{$fieldName}", $value);
        }
        $status = $stmt->execute();
        $error  = $stmt->errorInfo();
        return $this->saveInfo($error, $status, $stmt);
    }


    // Создание записи
    public function createPrepare($fields, $data, $tableName) {
        $builder  = $this->insertSqlForm($fields, $data);
        $query  = $builder['query'];
        $values = $builder['values'];
        $query  = "INSERT INTO {$tableName} {$query}";
        return $this->bindExec($query, $values);
    }

    // Обновить запись
    public function updatePrepare($fields, $data, $tableName, $fieldName, $id){
        $builder  = $this->updateSqlForm($fields, $data);
        $query  = $builder['query'];
        $values = $builder['values'];
        $values[$fieldName]  = $id;
        $query  = "UPDATE {$tableName} SET {$query} WHERE {$fieldName} = :{$fieldName}";
        return $this->bindExec($query, $values);
    }

    public function execute($query, $data = []){
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($data);
    }

    public function fetch($query, $data = [], $statement = PDO::FETCH_ASSOC) {
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

    public function log($data, $name = '') {
        lg($data);
    }

    public function saveInfo($error, $status, $stmt = array()) {

        $info = new \stdClass();

        $info->query   = '';
        $info->code    = '';
        $info->num     = '';
        $info->message = '';
        $info->status  = $status;
        $info->stmt    = $stmt;

        if(!empty($error[0]))
            $info->code = $error[0];
        if(!empty($error[1]))
            $info->num = $error[1];
        if(!empty($error[2]))
            $info->message = $error[2];
        if(!empty($stmt->queryString))
            $info->query = $stmt->queryString;

        return $info;
    }

    public function exceptionHandler($e, $title = '') {
        $error = array( 'title' => $title,
                        'file'  => $e->getFile(),
                        'line'  => $e->getLine(),
                        'message' => $e->getMessage(),
                        'code' => $e->getCode());
        $this->log($error);
    }

    public function truncateTable($tableName) {
        $stmt = $this->pdo->prepare("TRUNCATE TABLE  {$tableName}");
        $result = $stmt->execute();
        return $result;
    }

    public function createTable($tableName, $fileds) {
        $query = "CREATE TABLE IF NOT EXISTS {$tableName} ({$fileds})";
        $result = $this->pdo->exec($query);
        return $result;
    }

    public function deleteTable($tableName) {
        $query = "DROP TABLE {$tableName}";
        $result = $this->pdo->exec($query);
        return $result;
    }

    public function delete($tableName, $fName, $fValue) {
        $query = "DELETE FROM {$tableName} WHERE {$fName} = {$fValue} ";
        $result = $this->pdo->exec($query);
        return $result;
    }

    public function setConfig(array $config) {
        $this->config = $config;
    }

    protected function config() {
        $conf = new \stdClass();
        $conf->host     = '185.63.191.96';
        $conf->dbname   = 'maikldb';
        $conf->user     = 'w1user';
        $conf->password = 'w1password';
        $conf->driver   = 'pgsql';
        $conf->port     = 5432;
        return $conf;
    }

    public function getTableFields($tableName, $allFields = false) {
        $selectFields = ($allFields) ? '*' : 'column_name, column_default, data_type';
        $query = "SELECT {$selectFields} 
                  FROM INFORMATION_SCHEMA.COLUMNS 
                  WHERE table_name = '{$tableName}'";
        return $this->fetch($query);
    }
}
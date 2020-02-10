<?php

namespace Core\Services;

use Core\Interfaces\IDatabase;
// use Doctrine\DBAL\Driver\PDOException;
use \PDO;

use Core\Kernel\AbstractCore;

class DB extends AbstractCore implements IDatabase
{
    private $pdo;
    private $config;

    public function __construct(array $config){
        parent::__construct();
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

        } catch (\PDOException $e) {
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

    // Пример использования
    // $arr = array('login' => 'Maikl', 'passwd' => '343535');
    // $data = $db->prepareInsertBuilder($arr);
    // $item  = $data['data'];
    // $query = $data['query'];
    // $res = $db->prepareExecSimple($query, $item);
    protected function prepareInsertBuilder(array $items) :array {

        $data = $fields = $values = array();

        foreach ($items as $fname => $value) {
            $fields[] = $fname;
            $values[] = '?';
            $data[]   = $value;
        }

        $fieldsSql = '(' . implode(',', $fields) . ') ';
        $valuesSql = 'VALUES(' . implode(',', $values) . ')';
        $query = $fieldsSql . $valuesSql;

        return array(
            'data'  => $data,
            'query' => $query
        );
    }

    protected function prepareExecSimple(string $query, array $data, $getInsertId = false) :array {
        $lastInsertId = 0;
        $stmt    = $this->pdo->prepare($query);
        $result  = $stmt->execute($data);
        if($getInsertId)
            $lastInsertId = $this->pdo->lastInsertId();
        return array($result, $lastInsertId);
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

        try {
             $status = $stmt->execute();
        } catch (Exception $e) {
            $title = "Не удалось выполнить запрос : {$query}";
            exceptionHandler($e, $title);
            exit;
        }

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

    public function exec($query){
        return $this->pdo->exec($query);
    }

    public function query($query){
        return $this->pdo->query($query);
    }

    public function getPdo(){
        return $this->pdo;
    }

    public function fetch(string $query, $data = [], $statement = PDO::FETCH_ASSOC) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
        $result = $stmt->fetchAll($statement);
        if($result === false)
            return [];
        return $result;
    }

    public function fetchRow($query) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result[0]))
           return $result[0];
        return [];
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }

    public function log($data, $name = 'db_log') {
        $this->logger->log($data, $name);
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

    public function selectItem($tableName, $fieldName, $value, $select = "*", $where = '') {
        $value = "'{$value}'";
        if (is_numeric($value))
            $value = $value;
        $query = "SELECT {$select} FROM {$tableName} WHERE {$fieldName} = " . $value . $where;
        return $this->fetchRow($query);
    }

    public function getTableListSheme($shemeName = '') {
        $shemeName = (!$shemeName) ? 'public' : $shemeName;
        $query = "SELECT * FROM information_schema.columns
                  WHERE table_schema='{$shemeName}'";
        $list = $this->fetch($query);
        $result = array();

        foreach ($list as $key => $values) {

            $tableName = $values['table_name'];
            $fieldName = $values['column_name'];
            $auto      = $values['column_default'];
            $fieldType = $values['data_type'];
            if($auto) $auto = true;

            $field = array(
                'name' => $fieldName,
                'auto' => $auto,
                'type' => $fieldType,
            );

            foreach($field as $fk => $fval)
                $values[$fk] = $fval;

            $result[$tableName]['name'] = $tableName;
            $result[$tableName]['fields'][] = $values;
        }

        return $result;
    }


}


// $sql = "INSERT INTO books (title,author) VALUES (:title,:author)";
// $q = $conn->prepare($sql);
// $q->execute(array(':author'=>$author,':title'=>$title));

// $sql = "UPDATE books  SET title=?, author=? WHERE id=?";
// $q = $conn->prepare($sql);
// $q->execute(array($title, $author, $id));


//$this->pdo->beginTransaction();
//
///* Вставка множества записей по принципу "все или ничего" */
//$sql = 'INSERT INTO fruit
//       (name, colour, calories)
//       VALUES (?, ?, ?)';
//
//$stmt = $this->pdo->prepare($sql);
//
//foreach ($items as $item) {
//    $stmt->execute(array(
//        $item->name,
//        $item->colour,
//        $item->calories,
//    ));
//}
//
///* Фиксация изменений */
// $this->pdo->commit();
<?php

namespace App\Controllers;

use Core\App\Controller;

class UserController extends Controller{

    protected $tableName = 'users';

    public function getUsers(){
        $query = "SELECT * FROM {$this->tableName}";
        $users = $this->db->fetch($query);
        return $users;
    }

    public function getUser($args) {
        $userId = (is_array($args)) ? $args[0] : $args;
        $query = "SELECT * FROM {$this->tableName} WHERE user_id = {$userId}";
        $user = $this->db->fetch($query);
        if(!empty($user[0]))
            $user = $user[0];
        return $user;
    }

    private function getTestData() {

        $salt = rand();

        $data = array(
            "login"    => "test_user" . $salt,
            "password" => "test_user" . $salt,
            "username" => "test_user" . $salt,
            "lastname" => "test_user" . $salt,
            "email"    => "test_mail{$salt}@mail.ru"
        );

        return $data;
    }

    public function createUser() {

        $data  = $this->fetchPost();
        $error = '';

        $data = $this->getTestData();

        // Проверка на обязательные поля
        if( !empty($data['login'])    &&
            !empty($data['password']) &&
            !empty($data['username']) &&
            !empty($data['email'])) {

            $fields = $this->getFields('create');
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $response = $this->db->createPrepare($fields, $data, $this->tableName);
            if($response->status) {
                $userId = $this->db->lastInsertId();
                $user   = $this->getUser($userId);
                return array("message" => "Пользователь успешно создан",
                             "user_id" => $userId, 'user' => $user);
            }

            $error = $response->message . '(' . $response->status . ')';
        } else {
            $error = " отсутствуют обязательные поля ";
        }

        $message = 'Невозможно создать пользователя, ' . $error;
        return $this->saveErrorHandler($message, $response, 'create_user');
    }

    public function updateUser() {

        $data = $this->fetchPost();
        $userId = $this->getParam(0);
        $error = '';

        $data = $this->getTestData();

        // Проверка на обязательные поля
        if( !empty($data['login'])    &&
            !empty($data['username']) &&
            !empty($data['email'])) {

            $fields = $this->getFields('update');
            $response = $this->db->updatePrepare($fields, $data, $this->tableName, 'user_id', $userId);
            if($response->status) {
                $user = $this->getUser($userId);
                return array("message" => "Данные успешно сохранены",
                             "user_id" => $userId, "user" => $user);
            }

            $error = $response->message . '(' . $response->status . ')';
        } else {
            $error = " отсутствуют обязательные поля";
        }

        $message = 'Невозможно создать пользователя, ' . $error;
        return $this->saveErrorHandler($message, $response, 'update_user');
    }

    protected function saveErrorHandler($message, $response, $logFile = 'update_user'){
        $this->logger->log(array($message, $response), $logFile);
        $this->logger->log(array($message, $response), 'log');
        $this->responseCode(400);
        return array("message" => $message);
    }

    public function deleteUser() {
        $userId = $this->getParam(0);
        $status = $this->db->delete($this->tableName, 'user_id', $userId);
        if(!$status)
            return array("message" => "Не удалось удалить пользователя");
        return array("message" => "Пользователь удален");
    }

    public function getFields($optional = '') {

        $fields = array(
            'user_id'    => array( 'type' => 'serial',     'size' => 0,  'param' => 'PRIMARY KEY',   'optional' => 1),
            'created_dt' => array( 'type' => 'TIMESTAMP',  'size' => 0,  'param' => 'DEFAULT NOW()', 'optional' => 1),

            'login'      => array( 'type' => 'varchar',    'size' =>250, 'param' => 'UNIQUE NOT NULL', 'optional' => 2),
            'email'      => array( 'type' => 'varchar',    'size' =>300, 'param' => 'UNIQUE NOT NULL', 'optional' => 2),
            'username'   => array( 'type' => 'varchar',    'size' =>250, 'param' => 'DEFAULT NULL',    'optional' => 2),
            'lastname'   => array( 'type' => 'varchar',    'size' =>250, 'param' => 'DEFAULT NULL', 'optional' => 2),
            'password'   => array( 'type' => 'varchar',    'size' =>250, 'param' => 'DEFAULT NULL', 'optional' => 2),
            'note'       => array( 'type' => 'text',       'size' => 0,  'param' => 'DEFAULT NULL', 'optional' => 2),
            'phone'      => array( 'type' => 'varchar',    'size' =>250, 'param' => 'DEFAULT NULL', 'optional' => 2),
            'mobile'     => array( 'type' => 'varchar',    'size' =>250, 'param' => 'DEFAULT NULL', 'optional' => 2),
            'sex'         => array( 'type' => 'integer',   'size' => 0,  'param' => 'DEFAULT 1',    'optional' => 2),
            'age'         => array( 'type' => 'integer',   'size' => 0,  'param' => 'DEFAULT NULL', 'optional' => 2),
            'address'     => array( 'type' => 'text',      'size' => 0, 'param' => 'DEFAULT NULL',  'optional' => 2),

            'role'       => array( 'type' => 'varchar',     'size' => 0,  'param' => 'DEFAULT 2',     'optional' => 3),
            'verify'     => array( 'type' => 'varchar',     'size' => 0,  'param' => 'DEFAULT 0',     'optional' => 3),
            'update_dt'  => array( 'type' => 'TIMESTAMP',   'size' => 0,  'param' => 'DEFAULT NOW()', 'optional' => 3),

            'last_login' => array( 'type' => 'TIMESTAMP',   'size' => 0,  'param' => 'NULL',          'optional' => 4),
            'active'      => array( 'type' => 'varchar',    'size' =>250, 'param' => 'DEFAULT NULL',  'optional' => 4),
            'status'      => array( 'type' => 'varchar',    'size' => 0,  'param' => 'DEFAULT 0'   ,  'optional' => 4),
            'last_active' => array( 'type' => 'TIMESTAMP',  'size' => 0,  'param' => 'DEFAULT NULL',  'optional' => 4),
        );

        $result = array();

        switch($optional) {
            case 'create' :
            case 'update' :
                foreach ($fields as $fieldName => $values) {
                    if($values['optional'] == 2)
                        $result[$fieldName] = $values;
                }
                break;

            default : $result = $fields; break;
        }

        return $result;
    }
}
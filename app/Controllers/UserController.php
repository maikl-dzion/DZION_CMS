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
        $users = $this->db->fetch($query);
        return $users;
    }

    public function createUser() {

        $data = $this->fetchPost();

        $data = array(
              "login"    => "vlad",
              "password" => "vlad",
              "username" => "vlad",
              "lastname" => "vlad",
              "email"    => "vlad@mail.ru"
        );

        $message = "Невозможно создать пользователя ";

        // Проверка на обязательные поля
        if( empty($data['login'])    ||
            empty($data['password']) ||
            empty($data['username']) ||
            empty($data['email'])) {

            $message .= ", отсутствуют обязательные поля";
            $this->logger->log($message, 'create_user');
            $this->responseCode(400);
            return array("message" => $message);
        }

        $fields = $this->getFields('create');
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $resp = $this->db->createPrepare($fields, $data, $this->tableName);
        $userId = $this->db->lastInsertId();

        if(!$resp->status) {
            $message .= $resp->message . '(' . $resp->status . ')';
            $this->logger->log(array($message, $resp), 'create_user');
            $this->responseCode(400);
            return array("message" => $message);
        }

        $newUser = array();

        return array("message" => "Пользователь успешно создан",
                     "user_id" => $userId, 'user' => $newUser);

    }

    public function updateUser() {

        $data = $this->fetchPost();
        $userId = $this->getParam(0);

        $data = array(
            "login"    => "stas222_maikl",
            "username" => "stas22_999",
            "lastname" => "stas22_999",
            "email" => "dzion676222788222we@mail.ru"
        );

        $message = "Не удалось сохранить данные ";

        // Проверка на обязательные поля
        if( empty($data['login'])    ||
            empty($data['username']) ||
            empty($data['email'])) {

            $message .= ", отсутствуют обязательные поля";
            $this->logger->log($message, 'update_user');
            $this->responseCode(400);
            return array("message" => $message);
        }

        $fields = $this->getFields('update');
        $resp = $this->db->updatePrepare($fields, $data, $this->tableName, 'user_id', $userId);

        if(!$resp->status) {
            $message .= $resp->message . '(' . $resp->status . ')';
            $this->logger->log(array($message, $resp), 'update_user');
            $this->responseCode(400);
            return array("message" => $message);
        }

        $user = array();

        return array("message" => "Данные успешно сохранены",
                     "user_id" => $userId, "user" => $user);
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
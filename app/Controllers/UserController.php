<?php

namespace App\Controllers;

use Core\App\Controller;


class UserController extends Controller{

    protected $tableName = 'users';
    protected $access;

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

    protected function hasValue($fieldName, $value, $tableName = '') {
        $item = $this->db->selectItem($this->tableName, $fieldName, $value);
        return $item;
    }

    public function hasLogin($login) {
        $user = $this->hasValue('login', $login);
        return (!empty($user['user_id'])) ? $user['user_id'] : false;
    }

    public function hasEmail($email) {
        $user = $this->hasValue('email', $email);
        return (!empty($user['user_id'])) ? $user['user_id'] : false;
    }

    public function createUser() {

        $data  = $this->fetchPost();
        $this->db->truncateTable($this->tableName);
        $data = $this->getTestData(21);

        if( !empty($data['login'])    &&
            !empty($data['password']) &&
            !empty($data['username']) &&
            !empty($data['email'])) {

            if($userId = $this->hasLogin($data['login']))
                return array("message" => 'Такой логин уже существует - ' . $data['login']);

            if($userId = $this->hasEmail($data['email']))
                return array("message" => 'Такой email уже существует - ' . $data['email']);

            $fields = $this->getFields('create');
            $data['password']= $this->passwordHash($data['password']);
            $response = $this->db->createPrepare($fields, $data, $this->tableName);
            if($response->status) {
                $userId = $this->db->lastInsertId();
                $user   = $this->getUser($userId);

                $mailHeader  = 'Проверка email';
                $linkMessage = 'Пройдите по адресу для подтверждения почты';
                $serviceUrl  = 'http://home.ru/DZION_CMS/user/verify_email/' . $userId;
                $verifyEmailUrl = '<a href="'. $serviceUrl .'" >' .$linkMessage. '</a>';
                $this->sendMail($data['email'], $verifyEmailUrl, $mailHeader);

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


    public function login() {

        $data = $this->fetchPost();
        $result = $this->verifyPwd($data);
        $status = false;
        $sessionId = $jwt = $username = '';

        if(!empty($result['verify_pwd'])) {
            $sessionId = session_id();
            $username  = $result['username'];
            $account = array(
                'session_id' => $sessionId,
                'role'       => $result['role'],
                'user_id'    => $result['user_id'],
                'login'      => $result['login'],
                'password'   => $result['password'],
                'username'   => $username,
                'verify'     => $result['verify'],
                'status'     => $result['status'],
                'user'       => $result,
                'auth_dt'    => date('Y-m-d')
            );

            $jwt = $this->authJwt($account);
            $status = true;
        } else {
            $this->authClose();
        }

        $response = array(
            'status'      => $status,
            'session_id'  => $sessionId,
            'username'    => $username,
            'jwt'         => $jwt
        );

        $serialize = serialize($response);
        $response['serialize'] = $serialize;
        return $response;
    }

    protected function md5($value) {
        return md5($value);
    }

    protected function authJwt($data) {
        $token = $this->jwt->encode($data['login'],
                                    $data['password'],
                                    $data['role']);
        return $token;
    }

    protected function authClose() {

    }

    protected function logout() {
        $this->authClose();
    }

    private function verifyPwd($data) {
        $login    = $data['login'];
        $password = $data['password'];
        $query = "SELECT * FROM {$this->tableName} WHERE login='{$login}' ";
        $user = $this->db->fetch($query);
        if(!empty($user[0]))
            $user = $user[0];
        $hash = $user['password'];
        $status = $this->passwordVerify($password, $hash);
        if($status) {
            $user['verify_pwd'] = $status;
            return $user;
        }
        return array('verify_pwd' => $status);
    }

    private function passwordHash($data) {
        return password_hash($data, PASSWORD_BCRYPT);
    }

    private function passwordVerify($value, $hash) {
        return password_verify($value, $hash);
    }

    protected function verifyJwtToken($token = '') {
        ($token) ? $jwtToken = $token :
                   $jwtToken = end($this->getParams());

        $access = $this->jwt->decode($jwtToken);
        ($access) ? $status = true : $status = false;
        $this->access = $status;
        return $status;
    }

    public function access() {
        $accessStatus = $this->verifyJwtToken();
        return array('access_status' => $accessStatus);
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

    private function getTestData($value = 0) {

        // $salt = rand();
        if($value)
           $salt = $value;
        else
           $salt = rand();

        $data = array(
            "login"    => "testuser_" . $salt,
            "password" => "testuser_" . $salt,
            "username" => "testuser_" . $salt,
            "lastname" => "testuser_" . $salt,
            "email"    => "testmail_{$salt}@mail.ru"
        );

        $data = array(
            "login"    => "maikl",
            "password" => "1234",
            "username" => "maikl",
            "lastname" => "abasov",
            "email"    => "dzion67@mail.ru"
        );

        return $data;
    }
}
<?php

namespace Core\Services;

use Core\Interfaces\IAuthController;
use Core\Interfaces\IDatabase;
use Core\Interfaces\JwtAuthInterface;
use Core\Kernel\AbstractCore;

class AuthController extends AbstractCore implements IAuthController
{
    protected $db;
    protected $tableName;
    protected $jwt;

    public function __construct(IDatabase $db, JwtAuthInterface $jwt, string $tableName = 'users') {
        parent::__construct();
        $this->db  = $db;
        $this->jwt = $jwt;
        $this->tableName = $tableName;
    }

    public function auth(string $login, string $password,
                         string $fLogin = 'login', string $fPassword = 'password') :array {

        $query = "SELECT * FROM {$this->tableName} WHERE {$fLogin}='{$login}' ";
        $user = $this->db->fetchRow($query);
        if(empty($user))
            return array('status' => false,
                         'msg'    => "Пользователь {$login} не найден");

        if(!isset($user[$fPassword]))
            return array('status' => false,
                         'msg'    => "Не найдено поле $fPassword");

        $passwordHash = $user[$fPassword];
        $verify = password_verify($password, $passwordHash);

        if(!$verify)
            return array('status' => false,
                         'msg'    => "Логин или пароль неправильные,попробуйте еще раз");

        $jwtToken = $this->getJwtToken($user);

        return array('status' => $verify,
                     'jwt'    => $jwtToken,
                     'msg'    => "Аутентификация прошла успешно");
    }

    protected function getJwtToken(array $data) : string {
        return $this->jwt->encode($data['login'],
                                  $data['password'],
                                  $data['role']);
    }
}
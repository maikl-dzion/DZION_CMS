<?php

namespace Core\Tests;

use Core\AbstractCore;

class TestAppController extends AbstractCore {
    public $di;
    public function __construct($di = array()){
        parent::__construct();
        if(empty($di))
            $di = new \Core\Services\DI();
        $this->di = $di;
    }

    public function testMail() {
        $mail = $this->di->get('mail');
        $email  = 'dzion67@mail.ru';
        $body   = 'Тестовое письмо';
        $header = 'Заголовок письма';
        $res = $mail->send($email, $body, $header);
        return $res;
    }

    public function testAppClass($className, $funcName = '', $params = array()) {
        $controller = new $className($this->di, $params);
        if(!$funcName)
            return $controller;
        return $controller->$funcName($params);
    }

}
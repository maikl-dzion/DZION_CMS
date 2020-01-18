<?php

namespace Core\Tests;

use Core\AbstractCore;

class TestAppController extends AbstractCore {
    public $di;
<<<<<<< HEAD
    public function __construct($di = array()){
        parent::__construct();
        if(empty($di))
            $di = new \Core\Services\DI();
=======
    public function __construct($di){
        parent::__construct();
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
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

<<<<<<< HEAD
    public function testAppClass($className, $funcName = '', $params = array()) {
        $controller = new $className($this->di, $params);
        if(!$funcName)
            return $controller;
        return $controller->$funcName($params);
    }

=======
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
}
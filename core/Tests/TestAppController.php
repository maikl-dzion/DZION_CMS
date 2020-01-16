<?php

namespace Core\Tests;

use Core\AbstractCore;

class TestAppController extends AbstractCore {
    public $di;
    public function __construct($di){
        parent::__construct();
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

}
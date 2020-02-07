<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Core\AppKernel;
use Core\Services\StringProcessingController;

// vendor/bin/phpunit --bootstrap vendor/autoload.php tests/EmailTest

class SendMailTest extends TestCase
{
    public function testStringProcessingTrue(){

        $source = 'return gdfteu';
        $findValue = 'tur';

        $str = new StringProcessingController();
        $r = $str->find($source, $findValue);

        $this->assertTrue($r);

    }


//    public function testSendMailTrue(){
//
//        $email  = 'dzion67@mail.ru';
//        $body   = 'Тестовое письмо';
//        $header = 'Заголовок письма';
//
//        $mail = new \Core\Services\SendMailer();
//        $send = $mail->send($email, $body, $header);
//
//        $this->assertTrue($send);
//    }
}
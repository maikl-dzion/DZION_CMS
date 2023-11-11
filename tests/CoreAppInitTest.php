<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Core\Services\StringProcessingController;

// ./vendor/bin/phpunit tests

class CoreAppKernelInitTest extends TestCase {

    public function testRequestPathInfo(){

        $_SERVER['PATH_INFO'] = 'user/get_users';

        $requestResult = new \stdClass();
        $requestResult->url    = 'user/get_users';
        $requestResult->type   = 'PATH_INFO 11';
        $requestResult->class  = 'user';
        $requestResult->action = 'get_users';
        $requestResult->url_key   = 'user/get_users';
        $requestResult->arguments = array();
        $requestResult->error     = '';
        $requestResult->warning   = '';

        $request = new \Core\Kernel\Request();
        $_result = $request->getRequest();
        $this->assertSame($requestResult->action, $_result->action);

    }

}

class StringProcessingControllerTest extends TestCase
{
    public function testStringProcessingTrue(){

        $source = 'my name fill';
        $findValue = 'il';
        $str = new StringProcessingController();
        $r = $str->find($source, $findValue);
        $this->assertTrue($r);
    }

    public function testStringLenTrue(){

        $source = 'my name fill';
        $str = new StringProcessingController();
        $len = $str->len($source);
        $this->assertSame(12, $len);
    }

}
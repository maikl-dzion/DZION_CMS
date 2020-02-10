<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Core\Services\StringProcessingController;

// vendor/bin/phpunit tests

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
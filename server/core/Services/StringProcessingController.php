<?php
/**
 * Created by PhpStorm.
 * User: abasovm
 * Date: 14.01.2020
 * Time: 12:21
 */

namespace Core\Services;

use Core\Kernel\AbstractCore;

class StringProcessingController extends AbstractCore {

    public function __construct() {
        parent::__construct();
    }

    public function replace($source, $replaceValue, $pattern) {
        return str_replace($pattern, $replaceValue, $source);
    }

    public function find($source, $findValue) {
        $pos = strrpos($source, $findValue);
        if($pos === false)
            return false;
        return true;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: abasovm
 * Date: 14.01.2020
 * Time: 12:21
 */

namespace Core\Services;


use Core\AbstractCore;

class StringProcessingController extends AbstractCore {
<<<<<<< HEAD

=======
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
    public function __construct() {
        parent::__construct();
    }

<<<<<<< HEAD
    public function replace($source, $replaceValue, $pattern) {
        return str_replace($pattern, $replaceValue, $source);
    }

    public function find($source, $findValue) {
        $pos = strrpos($source, $findValue);
        if($pos === false)
            return false;
        return true;
    }

=======
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
}
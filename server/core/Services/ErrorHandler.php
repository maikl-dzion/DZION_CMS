<?php

namespace Core\Services;

use Core\AbstractCore;

class ErrorHandler extends  AbstractCore {

    public function __construct(){
        parent::__construct();
    }


    public function exceptionProcess(\Exception $e, $fileName = '') {

        $message = $e->getMessage();
        $code    = $e->getCode();
        $trace   = $e->getTrace();
        $traceString = $e->getTraceAsString();
        $toString = $e->__toString();
        $file =  $e->getFile();
        $line =  $e->getLine();

        switch($code) {
            case  23505 :
                $findString = 'SQLSTATE[23505]';
                if($res = $this->findText($message, $findString)) {
                    $data = explode(':', $message);
                    $data[] = 'Ошибка:в базе дубликат поля , должны быть уникальное значение';
                    lg($message);
                    $this->response($data);
                }
                break;
        }
        return array();
    }

    private function findText($text, $search) {
        $pos = strrpos($text, $search);
        if($pos === false)
            return false;
        return substr($text, $pos);
    }

    public function response($data, $responseName = 'message', $code = 400) {
        header("HTTP/1.1 " . $code);
        $data = json_encode(array($responseName => $data));
        // $this->responseCode($code);
        die($data);
    }
}
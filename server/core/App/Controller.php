<?php

namespace Core\App;

use Core\Services\DI;

class Controller extends App {

    protected $di;
    protected $db;
    protected $jwt;
    protected $model;
    protected $handl;
    protected $config;
    protected $request;
    protected $response;
    protected $parameters = array();

    public function __construct(DI $di, $parameters = array()) {
        parent::__construct();

        $this->di = $di;
        $this->parameters = $parameters;

        foreach ($parameters as $name => $value)
            $this->$name = $value;

        $this->db  = $this->di->init('db');
        $this->jwt = $this->di->get('jwt');
        $this->response = $this->di->get('response');
        $this->handl = $this->di->get('handl');

    }

    protected function getParam($index = '') {
        if(!empty($this->parameters[$index]))
            return $this->parameters[$index];
        return false;
    }

    protected function getParams() {
        return $this->parameters;
    }

    public function __get($key) {
        return $this->di->get($key);
    }

    protected function sendMail($email, $message, $header) {
        $mail   = $this->di->get('mail');
        $result = $mail->send($email, $message, $header);
        return $result;
    }

    protected function loaderModel($modelName, $params = array()) {
        $modelClass = 'App\Models\\' . $modelName;
        if(class_exists($modelClass)) {
            if(!empty($params))
                $this->model = new $modelClass($params);
            else
                $this->model = new $modelClass();
        }

        return $this->model;
    }

    // пример использования
    //  $className = 'Core\Services\FileUploads';
    //  $obj = $this->loaderClass($className, array());
    protected function loaderClass($className, $params = array()) {
        $class = null;
        if(class_exists($className)) {
            if(!empty($params))
                $class = new $className($params);
            else
                $class = new $className();
        }
        return $class;
    }

    protected function fetchPost($cast = 'array') {
        $data = json_decode(file_get_contents("php://input"));
        switch ($cast) {
            case 'array' : $data = (array)$data; break;
        }
        return $data;
    }
}
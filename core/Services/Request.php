<?php

namespace Core\Services;

use Core\AbstractCore;

class Request extends AbstractCore
{
    public $get     = [];
    public $post    = [];
    public $request = [];
    public $cookie  = [];
    public $files   = [];
    public $server  = [];

    // public function __construct() {}

    public function init() {
        $this->get     = $_GET;
        $this->post    = $_POST;
        $this->request = $_REQUEST;
        $this->cookie  = $_COOKIE;
        $this->files   = $_FILES;
        $this->server  = $_SERVER;
    }

    public static function isPost(): bool {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            return true;
        return false;
    }

    public static function getMethod():string {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getUrl(string $type): string {
        if(!empty($_SERVER[$type]))
            return $_SERVER[$type];
        return '';
    }

    public static function getUrlParam(string $type = REQUEST_URL_NAME): \stdClass {
        $routeObject = null;
        $url = self::getUrl($type);
        switch ($type) {
            case 'PATH_INFO'  :
                  $routeObject = self::pathInfo($url, $type);
                  break;
            case 'REQUEST_URI' :
                  $routeObject = self::requestUri($url, $type);
                  break;
        }

        return $routeObject;
    }

    public static function pathInfo(string $url, string $type = ''): \stdClass {

        $url    = trim($url, '/');
        $route  = explode('/', $url);
        $class  = $action = '';
        $arguments   = array();
        $error = $warning = '';

        foreach ($route as $key => $value) {
            switch ($key) {
                case 0  : $class  = $value; break;
                case 1  : $action = $value; break;
                default : $arguments[] = $value; break;
            }
        }

        if(!$class || !$action) {
            $warning = "Неопределенный маршрут - {$class} / {$action}";
            $class  = DEFAULT_URL_NAME;
            $action = DEFAULT_ACTION_NAME;
        }

        $resp = new \stdClass();
        $resp->url    = $url;
        $resp->type   = $type;
        $resp->class  = $class;
        $resp->action = $action;
        $resp->url_key = $class . '/' . $action;
        $resp->arguments = $arguments;
        $resp->error = $error;
        $resp->warning = $warning;

        return $resp;
    }


    public static function requestUri(string $url, string $type = '') {
        print_r($url); die;
    }

}
<?php

namespace Core\Kernel;

use Core\Interfaces\IRequest;

class Request extends AbstractCore implements IRequest
{
    public $get     = [];
    public $post    = [];
    public $request = [];
    public $cookie  = [];
    public $files   = [];
    public $server  = [];
    protected $urlType;
    protected $urlString = null;
    protected $requestResult;

    public function __construct() {
        parent::__construct();
        $this->init();
        $this->requestResult = new \stdClass;
    }

    public function isPost(): bool {
        if($this->server['REQUEST_METHOD'] == 'POST')
            return true;
        return false;
    }

    public function getRequestMethod():string {
        return $this->server['REQUEST_METHOD'];
    }

    public function getRequest():\stdClass {

        // ? QUERY_STRING    / PATH_INFO  / REQUEST_URI
        $urlString = $this->getUrlInfoString();

        switch ($this->urlType) {
            case 'PATH_INFO'  :
                  $state = $this->getPathInfo($urlString, $this->urlType);
                  break;

            case 'QUERY_STRING' :
                  $state = $this->getQueryString($urlString, $this->urlType);
                  break;

            default :
                  $state = $this->getRequestUri($urlString, $this->urlType);
                  break;
        }

        if($state) {
            return $this->requestResult;
        } else {
            throw new \Exception('Ошибка в классе Request');
            // exit;
        }
    }

    protected  function getUrlInfoString():string {

        if(!empty($this->server['PATH_INFO'])) {
            $this->urlType = 'PATH_INFO';
            $this->urlString = $this->server['PATH_INFO'];
        } elseif(!empty($this->server['QUERY_STRING'])) {
            $this->urlType = 'QUERY_STRING';
            $this->urlString = $this->server['QUERY_STRING'];
        } elseif(!empty($this->server['REQUEST_URI'])) {
            $this->urlType = 'REQUEST_URI';
            $this->urlString = $this->server['REQUEST_URI'];
        }

        return $this->urlString;
    }

    protected function init() {
        $this->get     = $_GET;
        $this->post    = $_POST;
        $this->request = $_REQUEST;
        $this->cookie  = $_COOKIE;
        $this->files   = $_FILES;
        $this->server  = $_SERVER;
    }

    // -- /DZION_CMS/api/user/get_user/103/r5
    // -- /user/get_user/103/rev
    protected function getPathInfo(string $url, string $type = '') : bool {
        $_result = true;
        $url     = trim($url, '/');
        $route   = explode('/', $url);
        $class   = $action = '';
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
            $warning = " Неопределенный маршрут - {$class} / {$action}";
            //$class = ConstContainer::DEFAULT_CONROLLER_NAME;
            //$action = ConstContainer::DEFAULT_ACTION_NAME;
        }

        $this->requestResult->url    = $url;
        $this->requestResult->type   = $type;
        $this->requestResult->class  = $class;
        $this->requestResult->action = $action;
        $this->requestResult->url_key   = $class . '/' . $action;
        $this->requestResult->arguments = $arguments;
        $this->requestResult->error     = $error;
        $this->requestResult->warning   = $warning;

        return $_result;
    }

    // -- /DZION_CMS/api/?class=user&method_name=get_user&p=103
    // -- class=user&method_name=get_user&p=103
    protected function getQueryString(string $url, string $type = '') : bool {

        $_result = true;
        $r1 = $this->findText($url, '=');
        $r2 = $this->findText($url, '&');

        $url    = trim($url, '/');
        $class  = $action = '';
        $arguments   = array();
        $error = $warning = '';

        // -- тип url: class=user&method=get_user&user_id=12
        if($r1 && $r2) {
            $route  = explode('&', $url);
            foreach ($route as $key => $value) {
                switch ($key) {
                    case 0 : $item  = explode('=', $value);
                             $class = $item[1];
                             break;

                    case 1 : $item  = explode('=', $value);
                             $action = $item[1];
                             break;

                    default :
                            $item  = explode('=', $value);
                            $fname = $item[0];
                            $param = $item[1];
                            $arguments[] = $param;
                            break;
                }
            }
        } else { // -- тип url: user/get_user/12

            $route  = explode('/', $url);
            foreach ($route as $key => $value) {
                switch ($key) {
                    case 0  : $class  = $value; break;
                    case 1  : $action = $value; break;
                    default : $arguments[] = $value; break;
                }
            }
        }
        //--------------------

        $this->requestResult->url    = $url;
        $this->requestResult->type   = $type;
        $this->requestResult->class  = $class;
        $this->requestResult->action = $action;
        $this->requestResult->url_key   = $class . '/' . $action;
        $this->requestResult->arguments = $arguments;
        $this->requestResult->error     = $error;
        $this->requestResult->warning   = $warning;

        // print_r( $this->requestResult); die;

        return $_result;
    }

    protected function getRequestUri(string $url, string $type = '') : bool  {

        //print_r($url . __FUNCTION__); die;

        $_result = true;
        $url    = trim($url, '/');
        $class  = $action = '';
        $arguments   = array();
        $error = $warning = '';

        //--------------------

        $this->requestResult->url    = $url;
        $this->requestResult->type   = $type;
        $this->requestResult->class  = $class;
        $this->requestResult->action = $action;
        $this->requestResult->url_key   = $class . '/' . $action;
        $this->requestResult->arguments = $arguments;
        $this->requestResult->error     = $error;
        $this->requestResult->warning   = $warning;

        return $_result;
    }

    protected function findText($source, $findValue) {
        $pos = strrpos($source, $findValue);
        if($pos === false)
            return false;
        return true;
    }

}
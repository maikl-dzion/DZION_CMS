<?php

namespace Core\Services;

use Core\AbstractCore;
use Core\Services\RequestResult;

class Request extends AbstractCore
{
    public $get     = [];
    public $post    = [];
    public $request = [];
    public $cookie  = [];
    public $files   = [];
    public $server  = [];
    protected $urlType;
    protected $urlString;
    protected $requestResult;

    public function __construct() {
        parent::__construct();
        $this->init();
        $this->requestResult = new RequestResult();
    }



    public function isPost(): bool {
        if($this->server['REQUEST_METHOD'] == 'POST')
            return true;
        return false;
    }

    public function getRequestMethod():string {
        return $this->server['REQUEST_METHOD'];
    }

    public function getUrlParam(): RequestResult {

        // ? QUERY_STRING    / PATH_INFO  / REQUEST_URI
        $urlString = $this->getUrlInfoString();

        switch ($this->urlType) {
            case 'PATH_INFO'  :
                  $requestResult = $this->getPathInfo($urlString, $this->urlType);
                  break;

            case 'QUERY_STRING' :
                  $requestResult = $this->getQueryString($urlString, $this->urlType);
                  break;

            default :
                  $requestResult = $this->getRequestUri($urlString, $this->urlType);
                  break;
        }

        return $requestResult;
    }

    protected  function getUrlInfoString() {

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
    protected function getPathInfo(string $url, string $type = '') : RequestResult {

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
            $warning = " Неопределенный маршрут - {$class} / {$action}";
            $class  = DEFAULT_URL_NAME;
            $action = DEFAULT_ACTION_NAME;
        }

        $this->requestResult->url    = $url;
        $this->requestResult->type   = $type;
        $this->requestResult->class  = $class;
        $this->requestResult->action = $action;
        $this->requestResult->url_key   = $class . '/' . $action;
        $this->requestResult->arguments = $arguments;
        $this->requestResult->error     = $error;
        $this->requestResult->warning   = $warning;

        return $this->requestResult;
    }

    // -- /DZION_CMS/api/?class=user&method_name=get_user&p=103
    // -- class=user&method_name=get_user&p=103
    protected function getQueryString(string $url, string $type = '') {
        print_r($url); die;
    }

    protected function getRequestUri(string $url, string $type = '') {
        print_r($url); die;
    }

}
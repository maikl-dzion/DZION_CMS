<?php

namespace Core;

use Core\Services\Request;

class Router
{
    private $routes  = [];
    private $request;
    private $route;
    private $routeUrl;
    private $method;

    public function __construct(array $routes){
        $this->routes = $routes;
    }

    public function getReguest(){
        // $this->route = Request::getRouteParam('REQUEST_URI');
        $request = Request::getRouteParam(REQUEST_URL_NAME);
        return $request;
    }

    public function findRoute($routes, $urlKey) {
        foreach($routes as $routeKey => $routeServer) {
            if($this->findText($routeKey, $urlKey))
                return array(
                    'front'  => $routeKey,
                    'server' => $routeServer
                );
        }
        return false;
    }

    protected function argumentsFormatted($routeUrl, $arguments, $urlKey) {
        $routeUrl = str_replace($urlKey, "", $routeUrl);
        $routeUrl = str_replace(':', "", $routeUrl);
        $paramsName = explode('/', $routeUrl);
        $result = array();
        foreach($arguments as $key => $value) {
            if(!empty($paramsName[$key])) {
                $name = $paramsName[$key];
                $result[$name] = $value;
            }
        }
        return $result;
    }

    protected function routeProcessing($request, $route) {

        $delimiter = '::';

        //$class    = $request->class;
        //$funcName = $request->action;
        //$url      = $request->url;
        $arguments = $request->arguments;
        $urlKey    = $request->url_key;

        $routeUrl   = $route['front'];
        $serverLink = $route['server'];

        $server = explode($delimiter, $serverLink);

        $error = 0;
        $className = $funcName = $argsStr = $method = '';
        // $arguments = $argNames = array();

        list($className,
             $funcName
             //$argsStr,
             //$method
             ) = $server;

//        $argNames = explode(',', $args);
//
//        foreach($parameters as $key => $value) {
//            if(!empty($argNames[$key])) {
//                $name = $argNames[$key];
//                $arguments[$name] = $value;
//            }
//        }
        $parameters = $this->argumentsFormatted($routeUrl, $arguments, $urlKey . '/');
        // lg($parameters);

        // Тестирование пользовательского контроллера
//        $test = new \Core\Tests\TestAppController();
//        $testResult = $test->testAppClass($className, $funcName, $arguments);
//        lg($testResult);


        $std = new \stdClass();
        $std->class       = $className;
        $std->action      = $funcName;
        $std->parameters  = $parameters;
        $std->arguments   = $arguments;
        // $std->args  = $parameters;

        $std->route       = $route;
        $std->resquest    = $request;

        //$resp->routeUrl    = $routeUrl;
        //$resp->actionParams = $methodParams;



//        $urlKey   = $class . '/' . $funcName;
//        foreach($this->routes as $routeKey => $routeServer) {
//             if($this->find($routeKey, $urlKey)) {
//                 lg($routeServer, $realUrl); die;
//             }
//        }

        // lg($request); die;
//        if(!empty($this->routes[$class])) {
//            $this->route = $this->routes[$class];
//            return $routeUrl;
//        }

        //$message = "Не найден маршрут (Router-{$class})";
        //throw new \Exception($message);

        return $std;
    }



    public function init() {

        $this->request = $this->getReguest();
        $urlKey = $this->request->url_key;
        $this->route   = $this->findRoute($this->routes, $urlKey);
        $result = $this->routeProcessing($this->request, $this->route);



//        $routeUrl   = $this->routeProcessing();
//        $action     = $routeUrl->action;  // Имя метода для фронта : get_users
//        $parameters = $routeUrl->parameters;
//
//        /**  Пример $this->route
//        [class] => App\Controllers\DefaultController
//        [public] => Array(
//            [get_users] => Array([func_name] => getUsers, [args] => 'id', [method] => GET)
//        ) **/
//
//        if(empty($this->route[ROUTE_METHODS_INDEX][$action])) {
//            $message = "Не найден метод класса (Router) ({$action})";
//            throw new \Exception($message);
//        }
//
//        $className    = $this->route[ROUTE_CLASS_NAME];     // Получаем имя класса : App\Controllers\DefaultController
//        $methodParams = $this->route[ROUTE_METHODS_INDEX][$action]; // Получаем массив метода : Array([func_name] => getUsers, [args] => 'id', [method] => GET)
//        $methodName   = $methodParams[ROUTE_METHOD_FNAME];  // Получаем имя метода : getUsers
//
//        $resp = new \stdClass();
//        $resp->class       = $className;
//        $resp->action      = $methodName;
//        $resp->parameters  = $parameters;
//        $resp->route       = $this->route;
//        $resp->routeUrl    = $routeUrl;
//        $resp->actionParams = $methodParams;

        return $result;
    }


    public function findText($source, $findValue) {
        $pos = strrpos($source, $findValue);
        if($pos === false)
            return false;
        return true;
    }

    protected function exception($message) {
        throw new \Exception($message);
    }
}
<?php

namespace Core;

use Core\Services\Request;

class Router
{
    private $routes = [];
    private $route;
    private $routeUrl;
    private $method;

    public function __construct(array $routes){
        $this->routes = $routes;
    }

    public function getRoute() {
        $routeUrl = Request::getRouteParam(REQUEST_URL_NAME);
        // $this->route = Request::getRouteParam('REQUEST_URI');
        $class   = $routeUrl->class;
        if(!empty($this->routes[$class])) {
            $this->route = $this->routes[$class];
            return $routeUrl;
        }

        $message = "Не найден маршрут (Router-{$class})";
        throw new \Exception($message);
    }

    public function init() {

        $routeUrl = $this->getRoute();
        $action   = $routeUrl->action;  // Имя метода для фронта : get_users
        $parameters  = $routeUrl->parameters;

        /**  Пример $this->route
        [class] => App\Controllers\DefaultController
        [public] => Array(
            [get_users] => Array([func_name] => getUsers, [args] => 'id', [method] => GET)
        ) **/

        if(empty($this->route[ROUTE_METHODS_INDEX][$action])) {
            $message = "Не найден метод класса (Router) ({$action})";
            throw new \Exception($message);
        }

        $className    = $this->route[ROUTE_CLASS_NAME];     // Получаем имя класса : App\Controllers\DefaultController
        $methodParams = $this->route[ROUTE_METHODS_INDEX][$action]; // Получаем массив метода : Array([func_name] => getUsers, [args] => 'id', [method] => GET)
        $methodName   = $methodParams[ROUTE_METHOD_FNAME];  // Получаем имя метода : getUsers

        $resp = new \stdClass();
        $resp->class       = $className;
        $resp->action      = $methodName;
        $resp->parameters  = $parameters;
        $resp->route       = $this->route;
        $resp->routeUrl    = $routeUrl;
        $resp->actionParams = $methodParams;

        return $resp;
    }

    protected function exception($message) {
        throw new \Exception($message);
    }
}
<?php

namespace Core;

use Core\Services\Request;

class Router
{
    private $routes = [];
    private $route;
    private $method;

    public function __construct(array $routes){
        $this->routes = $routes;
    }

    public function getRoute() {
        $this->route = Request::getRouteParam(REQUEST_URL_NAME);
        // $this->route = Request::getRouteParam('REQUEST_URI');
        $class   = $this->route->class;
        if(isset($this->routes[$class]))
            return $this->routes[$class];

        $message = "Не найден маршрут (Router-{$class})";
        throw new \Exception($message);
    }

    public function init() {

        $route = $this->getRoute();
        $action = $this->route->action;

        if(!isset($route[ROUTE_CLASS_METHODS][$action])) {
            $message = "Не найден метод класса (Router) ({$action})";
            throw new \Exception($message);
        }

        $className   = $route[ROUTE_CLASS_NAME];
        $methodParam = $route[ROUTE_CLASS_METHODS][$action];
        $actionName  = $methodParam[ROUTE_METHOD_FIELD];
        $parameters  = $this->route->parameters;

        $resp = new \stdClass();
        $resp->class       = $className;
        $resp->action      = $actionName;
        $resp->parameters  = $parameters;
        $resp->route       = $route;
        $resp->actionParam = $methodParam;

        return $resp;
    }

    protected function exception($message) {
        throw new \Exception($message);
    }
}
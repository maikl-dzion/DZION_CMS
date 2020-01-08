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
        $class   = $this->route->class;
        if(isset($this->routes[$class])) {
            return $this->routes[$class];
        } else {
            $message = 'Не найден маршрут';
            throw new \Exception($message);
        }
    }

    public function run(DI $di) {

        $currentRoute = $this->getRoute();
        $class  = $this->route->class;
        $action = $this->route->action;
        $parameters = $this->route->parameters;

        if(isset($currentRoute[ROUTE_CLASS_METHODS][$action])) {

            $className = $currentRoute[ROUTE_CLASS_NAME];
            $methodParam = $currentRoute[ROUTE_CLASS_METHODS][$action];
            $actionName  = $methodParam[ROUTE_METHOD_FIELD];

            $controller = new $className($di, $parameters);
            $response   = $controller->$actionName($parameters);

        } else {
            $message = 'Не найден метод класса';
            throw new \Exception($message);
        }

        return $response;
    }

}
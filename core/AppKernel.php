<?php

namespace Core;

//use Engine\Core\Router\DispatchedRoute;
//use Engine\Helper\Common;
use Core\DI;
use Core\Router;

class AppKernel
{

    private $di;
    protected $router;
    protected $controller;

    public function __construct(array $routes){

        $this->di = new DI();
        $this->router = new Router($routes);
        $this->init();
    }

    protected function init() {
        $this->controller = $this->router->init();
    }

    public function run() {
        $className  = $this->controller->class;
        $actionName = $this->controller->action;
        $parameters = $this->controller->parameters;

        if(class_exists($className)) {
            $controller = new $className($this->di, $parameters);
            if(method_exists($controller, $actionName))  {
                $response = $controller->$actionName($parameters);
            }
        }
        return $response;
    }

}
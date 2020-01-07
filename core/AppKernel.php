<?php

namespace Core;

//use Engine\Core\Router\DispatchedRoute;
//use Engine\Helper\Common;
use Core\DI;

class AppKernel
{

    private $di;
    public $router;

    public function __construct(){

        $this->di = new DI();

        $this->run();

        // $this->router = $this->di->get('router');
    }

    private function init() {

    }

    public function run() :void {


            print_r($this); die;

            echo 'ok';
//            require_once __DIR__ . '/../' . mb_strtolower(ENV) . '/Route.php';
//
//            $routerDispatch = $this->router->dispatch(Common::getMethod(), Common::getPathUrl());
//
//            if ($routerDispatch == null) {
//                $routerDispatch = new DispatchedRoute('ErrorController:page404');
//            }
//
//            list($class, $action) = explode(':', $routerDispatch->getController(), 2);
//
//            $controller = '\\' . ENV . '\\Controller\\' . $class;
//            $parameters = $routerDispatch->getParameters();
//            call_user_func_array([new $controller($this->di), $action], $parameters);
            // return true;

    }
}
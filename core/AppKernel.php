<?php

namespace Core;

//use Engine\Core\Router\DispatchedRoute;
//use Engine\Helper\Common;

class AppKernel
{

    private $di;
    public $router;

    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->router = $this->di->get('router');
    }

    /**
     * Run cms
     */
    public function run() :void
    {
        try {

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
        } catch(\Exception $e){

            echo $e->getMessage();
            exit;

        }
    }
}
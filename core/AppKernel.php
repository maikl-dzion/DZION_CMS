<?php

namespace Core;

//use Engine\Core\Router\DispatchedRoute;
//use Engine\Helper\Common;
use Core\DI;
use Core\Router;

class AppKernel
{

    private $di;
    public  $router;

    public function __construct(array $routes){

        $this->di = new DI();

        $this->router = new Router($routes);

        $this->run();

    }

    private function init() {

    }

    public function run() {
        $response = $this->router->run($this->di);
        print_r($response); die;
    }
}
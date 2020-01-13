<?php

namespace Core;

use Core\Services\DI;
use Core\Services\Response;
use Core\Tests\TestAppController;


class AppKernel
{

    private   $di;
    protected $router;
    protected $dbconfig;
    protected $services;
    protected $logger;
    protected $controller;
    public    $response;

    /**
     * AppKernel constructor.
     * @param array $routes
     * @param array $dbconfig
     * @throws \Exception
     */
    public function __construct(array $routes, array $dbconfig){

        $this->dbconfig = $dbconfig;
        $this->di       = new DI();
        $this->router   = new Router($routes);

        $this->services = new ServicesRegister();
        $this->services->servicesInit($this->di, $this->dbconfig);
        $this->logger   = $this->di->get('logger');
        $this->response = $this->di->get('response');

        $this->db = $this->di->get('db');
        $this->migrate = $this->di->get('migrate');
        $this->migrate->migrateLoader($this->db);

        //$test = new TestAppController($this->di);
        //$test->testMail();
        // $this->di->set('test', $test);

        $this->routerInit();
    }

//    protected function initialize() {
//        $this->servicesInit();
//        $this->routerInit();
//    }

    /**
     * @throws \Exception
     */
    protected function routerInit() {
        $this->controller = $this->router->init();
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run() : Response {

        $className  = $this->controller->class;
        $actionName = $this->controller->action;
        $parameters = $this->controller->parameters;

        $message    = false;

        if(class_exists($className)) {
            $controller = new $className($this->di, $parameters);
            if(method_exists($controller, $actionName))  {
                if(!empty($parameters)) {
                    $response = $controller->$actionName($parameters);
                } else {
                    $response = $controller->$actionName();
                }
            } else {
                $message = "Не существует метод класса - {$className}->{$actionName}";
            }
        } else {
            $message = "Не существует класс - {$className}";
        }

        if($message) {
            $this->logger->log($message, 'app_kernel');
            $this->logger->log($message, 'log');
            throw new \Exception($message);
            exit;
        }

        $this->response->responseData = $response;
        return $this->response;
    }

}
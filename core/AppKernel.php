<?php

namespace Core;

use Core\Services\DI;
use Core\Services\Logger;

class AppKernel
{

    private   $di;
    protected $router;
    protected $dbconfig;
    protected $services;
    protected $logger;
    protected $controller;

    public function __construct(array $routes, array $dbconfig){
        $this->dbconfig = $dbconfig;
        $this->di       = new DI();
        $this->router   = new Router($routes);
        $this->logger   = new Logger(LOG_PATH);
        $this->initialize();
    }

    protected function initialize() {
        $this->initServices();
        $this->initRouter();
    }

    protected function initRouter() {
        $this->controller = $this->router->init();
    }

    public function run() {

        $className  = $this->controller->class;
        $actionName = $this->controller->action;
        $parameters = $this->controller->parameters;

        $message    = false;

        if(class_exists($className)) {
            $controller = new $className($this->di, $parameters);
            if(method_exists($controller, $actionName))  {
                if(!empty($parameters))
                    $response = $controller->$actionName($parameters);
                 else
                    $response = $controller->$actionName();
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

        return $response;
    }

    public function loadServices() {
        return array(
            Services\Logger::class => array('name' => 'logger', 'params' => LOG_PATH),
            Services\DB::class     => array('name' => 'db'    , 'params' => $this->dbconfig)
        );
    }

    public function initServices() {

        $services = $this->loadServices();

        foreach ($services as $serviceClass => $values) {

            $serviceName  = $values['name'];
            $params = $values['params'];

            if(!empty($params))
              $service  = new $serviceClass($params);
            else
              $service  = new $serviceClass();

            $this->di->set($serviceName, $service);
        }
    }

}
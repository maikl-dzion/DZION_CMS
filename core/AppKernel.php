<?php

namespace Core;

use Core\Services\DI;
use Core\Services\Response;
<<<<<<< HEAD
use Core\Services\ConfigController;
use Core\Tests\TestAppController;
=======
use Core\Tests\TestAppController;
use Core\Services\ConfigController;
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685


class AppKernel
{

    private   $di;
    protected $app;
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
    public function __construct(){

        $this->di = new DI(); // Создаем контейнер зависисимостей
        $config   = new ConfigController(); // Получаем конфиги приложения (из папки config)
        $routes   = $config->get('routes');
        $this->dbconfig = $config->get('dbconfig');
        $this->router   = new Router($routes);

        // Инициализируем общие сервисы
        $this->services = new ServicesRegister();
        $this->services->servicesInit($this->di, $this->dbconfig);
        $this->logger   = $this->di->get('logger');
        $this->response = $this->di->get('response');
<<<<<<< HEAD

        // Подготавливаем миграции
        $this->db = $this->di->get('db');
        $this->migrate = $this->di->get('migrate');
        $this->migrate->migrateLoader($this->db);

        // Тестирование компонентов
        // $test = new \Core\Tests\TestAppController($this->di);
        // $test->testMail();
        // $this->di->set('test', $test);

        // Запускаем обработку роута
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
        $this->app = $this->router->init();
    }
=======

        // Подготавливаем миграции
        $this->db = $this->di->get('db');
        $this->migrate = $this->di->get('migrate');
        $this->migrate->migrateLoader($this->db);

        // Тестирование компонентов
        // $test = new TestAppController($this->di);
        // $test->testMail();
        // $this->di->set('test', $test);

        // Запускаем обработку роута
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
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685

    /**
     * @return Response
     * @throws \Exception
     */
    public function run() : Response {

        $className  = $this->app->class;
        $actionName = $this->app->action;
        $parameters = $this->app->parameters; // ассоциативный массив
        $arguments  = $this->app->arguments;  // массив с цифровыми индексами
        $message    = false;
<<<<<<< HEAD
        // lg($className);

        $controller = new $className($this->di, $parameters);
        if(!empty($arguments))
            $response = $controller->$actionName(...$arguments);
        else
            $response = $controller->$actionName();


//        if(class_exists($className)) {
//            $controller = new $className($this->di, $parameters);
//            if(method_exists($controller, $actionName))  {
//                if(!empty($parameters)) {
//
//                    $response = $controller->$actionName($parameters);
//                } else {
//                    $response = $controller->$actionName();
//                }
//            } else {
//                $message = "Не существует метод класса - {$className}->{$actionName}";
//            }
//        } else {
//            $message = "Не существует класс - {$className}";
//        }
=======

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
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685

        if($message) {
            $this->logger->log($message, 'app_kernel');
            $this->logger->log($message, 'log');
            throw new \Exception($message);
            exit;
        }

<<<<<<< HEAD
        $this->response->data = $response;
=======
        $this->response->responseData = $response;
>>>>>>> d8e115ebd1c0e451b3ef0def7b1e80db89cce685
        return $this->response;
    }

}
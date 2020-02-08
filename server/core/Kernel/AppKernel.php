<?php

namespace Core;

use Core\Services\DI;
use Core\Services\Response;
use Core\Services\ConfigController;
use Core\Tests\TestAppController;


class AppKernel {

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
        $this->services = new ServicesProvider();
        $this->services->servicesInit(array($this->di, $this->dbconfig));
        $this->logger   = $this->di->get('logger');
        $this->response = $this->di->get('response');

        // Подготавливаем миграции
        $this->db = $this->di->get('db');
        $this->migrate = $this->di->get('migrate');
        $this->migrate->migrateLoader($this->db);

        // lg($this);
        // Тестирование компонентов
         // $test = new \Core\Tests\TestAppController($this->di);
         // $test->testMail();
         // $this->di->set('test', $test);

        $this->routerInit(); // Запускаем обработку роута
    }

    /**
     * @throws \Exception
     */
    protected function routerInit() {
        $this->app = $this->router->init();
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run() : Response {

        $className  = $this->app->class;
        $actionName = $this->app->action;
        $parameters = $this->app->parameters; // ассоциативный массив
        $arguments  = $this->app->arguments;  // массив с цифровыми индексами
        $errorMessage = '';

        if(!class_exists($className)) {
            $errorMessage = "Не найден класс - {$className}";
            $this->error($errorMessage);
            return false;
        }

        // Создаем объект класса (App)
        $controller = new $className($this->di, $parameters);

        if(!method_exists($controller, $actionName))  {
            $errorMessage = "Не найден метод класса - {$className}->{$actionName}";
            $this->error($errorMessage);
            return false;
        }

        // Вызываем метод класса (App)
        if(!empty($arguments))
            $response = $controller->$actionName(...$arguments);
        else
            $response = $controller->$actionName();

        $this->response->data = $response;
        return $this->response;
    }

    protected function error($errorMessage) {
        $this->logger->log($errorMessage, 'AppKernel');
        $this->logger->log($errorMessage, 'log');
        throw new \Exception($errorMessage);
        exit;
    }

}
<?php

namespace Core\Kernel;

use Core\Services\DI;
use Core\Services\ConfigController;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class AppKernel {

    private   $di;
    protected $app;
    protected $router;
    protected $dbconfig;
    protected $services;
    protected $logger;
    protected $controller;
    public    $response;

    protected $route;
    protected $routes;
    protected $config;

    public function __construct(){

        // Получаем конфиги
        $configPath = ConstContainer::CONFIG_DIR;
        $this->config = new \Core\Kernel\ConfigController($configPath);
        $routes   = $this->config->getConfig('routes');
        $dbconfig = $this->config->getConfig('dbconfig');

        // Запускаем router
        $this->routerStart($routes);

        // Создаем DI container
        $this->di = new DI(); // container = инициализированные объекты , instances = только регистрация

        // Загружаем сервисы
        new ServicesProvider($this->di, array('dbconfig' =>$dbconfig));

        // Загружаем компоненты
        new ComponentsProvider($this->di, array());

        $this->response = new Response();

        // $this->di->get('db', array(), 'save');
        // lg($this->di);

//        $logger = new Logger('my_logger');
//        $logger->pushHandler(new StreamHandler('logs/debug.log', Logger::DEBUG, false));
//        $logger->pushHandler(new StreamHandler('logs/info.log', Logger::INFO, false));
//        $logger->pushHandler(new StreamHandler('logs/error.log', Logger::WARNING, false));
//
//        $logger->debug(array('debug' => 'tryrtr'));
//        $logger->info('info');
//        $logger->err('warn');
//        $logger->err('error');
//        echo "----------------------ERROR:\n";
//
//        print_r(file_get_contents('logs/error.log'));
//
//        echo "---------------------INFO:\n";
//
//        print_r(file_get_contents('logs/info.log'));
//
//        echo "----------------------DEBUG:\n";
//        print_r(file_get_contents('logs/debug.log'));
//
//        lg($logger);


        // lg($this->di->init('curl'));

//        $this->di = new DI(); // Создаем контейнер зависисимостей
//        $config   = new ConfigController(); // Получаем конфиги приложения (из папки config)
//        $routes   = $config->get('routes');
//        $this->dbconfig = $config->get('dbconfig');
//        $this->router   = new Router($routes);
//
//        // Инициализируем общие сервисы
//        $this->services = new ServicesProvider();
//        $this->services->servicesInit(array($this->di, $this->dbconfig));
//        $this->logger   = $this->di->get('logger');
//        $this->response = $this->di->get('response');
//
//        // Подготавливаем миграции
//        $this->db = $this->di->get('db');
//        $this->migrate = $this->di->get('migrate');
//        $this->migrate->migrateLoader($this->db);

        // lg($this);
        // Тестирование компонентов
         // $test = new \Core\Tests\TestAppController($this->di);
         // $test->testMail();
         // $this->di->set('test', $test);

    }

    protected function routerStart($routes) {

        $request = new \Core\Kernel\Request();
        //lg($request->getRequest());

        $router = new \Core\Kernel\Router($request, $routes);
        $this->route = $router->init();
    }

    public function run() : Response {

        $className  = $this->route->class;
        $actionName = $this->route->action;
        $parameters = $this->route->parameters; // ассоциативный массив
        $arguments  = $this->route->arguments;  // массив с цифровыми индексами
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

        // lg($response);

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
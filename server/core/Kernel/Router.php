<?php

namespace Core\Kernel;

// use Core\Services\Request;
use Core\Interfaces\IRequest;
use Core\Interfaces\IRouter;

class Router extends AbstractCore implements IRouter {

    private $routes = [];
    private $request;
    private $route;
    private $routeUrl;
    private $method;

    public function __construct(IRequest $request, array $routes){
        parent::__construct();
        $this->routes = $routes;
        $this->request = $request->getRequest();
        // lg($this->request);
    }

    public function init() : \stdClass {
        // $this->request = $this->getReguest();
        $urlKey        = $this->request->url_key;
        $this->route   = $this->findRoute($this->routes, $urlKey);
        $result        = $this->routeProcessing($this->request, $this->route);
        return $result;
    }

    public function findRoute(array $routes, string $requestUrlKey): array {

        foreach($routes as $frontRoute => $serverRoute) {
            $route  = explode('/', $frontRoute);
            $className  = $route[0];
            $actionName = $route[1];
            $routeUrl   =  $className . '/' . $actionName;
            if($requestUrlKey != $routeUrl) continue;

            return array(
                'front'  => $frontRoute,
                'server' => $serverRoute
            );
        }

        $warning = "Неопределенный маршрут - {$requestUrlKey}";
        $this->error($warning);

        return array(
            'front'  => DEFAULT_FRONT_ROUTE,
            'server' => DEFAULT_SERVER_ROUTE
        );
    }

    protected function argumentsFormatted(string $routeUrl, array $arguments, string $urlKey): array {
        $routeUrl = str_replace($urlKey, "", $routeUrl);
        $routeUrl = str_replace(':', "", $routeUrl);
        $paramsName = explode('/', $routeUrl);
        $result = array();
        foreach($arguments as $key => $value) {
            if(!empty($paramsName[$key])) {
                $name = $paramsName[$key];
                $result[$name] = $value;
            }
        }
        return $result;
    }

    protected function routeProcessing(\stdClass $request, array $route): \stdClass {

        $arguments = $request->arguments;
        $urlKey    = $request->url_key;
        $warning   = $request->warning;
        $error     = $request->error;

        if($warning)
            $this->error($warning);
        if($error)
            $this->error($error, true);

        $frontRouteUrl  = $route['front'];
        $serverRouteUrl = $route['server'];

        $srvRouteArr = $this->serverRouteFormatted($serverRouteUrl);
        $parameters  = $this->argumentsFormatted($frontRouteUrl, $arguments, $urlKey . '/');

        //        Тестирование пользовательского контроллера
        //        $test = new \Core\Tests\TestAppController();
        //        $testResult = $test->testAppClass($className, $funcName, $arguments);
        //        lg($testResult);

        $std = new \stdClass();
        $std->class       = $srvRouteArr['className'];
        $std->action      = $srvRouteArr['actionName'];
        $std->parameters  = $parameters; // Параметры в ассоциативном массиве (передаем в конструктор)
        $std->arguments   = $arguments;  // Параметры в числовом массиве (передаем в аргументы метода)
        $std->route       = $route;
        $std->resquest    = $request;

        return $std;
    }

    protected function serverRouteFormatted(string $serverRouteUrl): array {
        // пример "App\Controllers\UserController::getUser::GET",
        $server = explode(ROUTE_PARAM_DELIMITER, $serverRouteUrl);
        list($className,    // Имя класса         - App\Controllers\UserController
             $actionName,   // Имя метода класса  - getUser
             $requestMethod // Имя метода запроса - GET
            ) = $server;
        return array(
            'className'     => $className,
            'actionName'    => $actionName,
            'requestMethod' => $requestMethod
        );
    }


    protected function findText(string $source, string $findValue) : bool {
        $pos = strrpos($source, $findValue);
        if($pos === false)
            return false;
        return true;
    }

    protected function error($errorMessage, $fatalError = false) {
        $this->logger->log($errorMessage, 'Router');
        $this->logger->log($errorMessage, 'log');
        if($fatalError) {
            throw new \Exception($errorMessage);
            exit;
        }
    }
}
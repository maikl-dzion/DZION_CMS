<?php

declare(strict_types=1);

define('ROOT_DIR',  dirname(__DIR__));

require_once 'bootstrap.php';

try {

    $configPath = CONFIG_DIR;

    $config = new \Core\Kernel\ConfigController($configPath);
    $routes = $config->getConfig('routes');
    // lg($config->getConfig('routes'));

    $request = new \Core\Kernel\Request();
    //lg($request->getRequest());

    $router = new \Core\Kernel\Router($request, $routes);
    lg($router->init());


//    $visit = new Core\Services\PequestVisitsController();
//    $visitHtml = $visit->printVisits();
//
//    $app = new Core\AppKernel();
//    $response = $app->run()->response();
//    die($response);


} catch(\Exception $e){

    $fileName = __FILE__;
    $title = "Ошибка в файле index - {$fileName}";
    $message = $e->getMessage();
    $code    = $e->getCode();
    $trace   = $e->getTrace();
    $traceString = $e->getTraceAsString();
    $toString = $e->__toString();
    // die($toString);

    $error = array(
        'Try-Catch-Api-Index' => 'try-catch-api-index',
        'title' => $title,
        'file'  => $e->getFile(),
        'line'  => $e->getLine(),
        'message' => $message,
        'code'    => $code,
        'to_string' => $toString,
        'trace_string' => $traceStr,
        'trace'   => $trace,
        'exception'  => $e
    );

    $logger = new Core\Services\Logger(LOG_PATH);
    $logger->log($error, 'index');
    $logger->log('index.php = ' . $message, 'log');

    $errHandl = new Core\Services\ErrorHandler();
    $errHandl->exceptionProcess($e, $fileName);

    lg($error);

    exit;

}
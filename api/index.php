<?php

declare(strict_types=1);

define('ROOT_DIR',  dirname(__DIR__));

require_once 'bootstrap.php';

try {

    $app = new Core\AppKernel($routes, $dbconfig);
    $response = $app->run();
    print_r($response->response());

} catch(\Exception $e){

    $fileName = __FILE__;
    $title = "Ошибка в файле index-{$fileName}";

    $error = array(
        'title' => $title,
        'file'  => $e->getFile(),
        'line'  => $e->getLine(),
        'message' => $e->getMessage(),
        'code'    => $e->getCode(),
        'exception'  => $e
    );

    $logger = new Core\Services\Logger(LOG_PATH);
    $logger->log($error, 'index');
    $logger->log('index.php = ' . $e->getMessage(), 'log');
    lg($error);
    exit;

}

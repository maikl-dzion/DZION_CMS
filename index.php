<?php

declare(strict_types=1);

header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, X-HTTP-Method-Override, Origin, Content-Type, Cookie, Accept');

header('Content-Type: text/html; charset=utf-8');

set_error_handler('error_log_handler');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

session_start();  // Запускаем сессию

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');

require_once ROOT_DIR . '/core/bootstrap.php';
require_once ROOT_DIR . '/vendor/autoload.php';

$routes   = require_once ROOT_DIR . '/config/routes.php';
$dbconfig = require_once ROOT_DIR . '/config/dbconfig.php';

try {

    $app = new Core\AppKernel($routes, $dbconfig);
    $response = $app->run();
    print_r($response);

} catch(\Exception $e){

    $fileName = __FILE__;
    $title = "Ошибка в файле index-{$fileName}";

    $error = array(
        'title' => "Ошибка в файле index-{$fileName}",
        'file'  => $e->getFile(),
        'line'  => $e->getLine(),
        'message' => $e->getMessage(),
        'code'    => $e->getCode(),
        '$e'      => $e
    );

    $logger = new Core\Services\Logger(LOG_PATH);
    $logger->log($error, 'index');
    lg($error);
    exit;

}


function error_log_handler($errno, $message, $filename, $line) {
    $date = date('Y-m-d H:i:s (T)');
    $fp   = fopen('error.txt', 'a');
    if (!empty($fp)) {
        $filename  =str_replace(LOG_PATH,'', $filename);
        $err  = " $message = $filename = $line\r\n ";
        fwrite($fp, $err);
        fclose($fp);
    }
}

function lg() {

    $debugTrace = debug_backtrace();
    $args = func_get_args();

    $get = false;
    $output = $traceStr = '';

    $style = 'margin:10px; padding:10px; border:3px red solid;';

    foreach ($args as $key => $value) {
        $itemArr = array();
        $itemStr = '';
        is_array($value) ? $itemArr = $value : $itemStr = $value;
        if ($itemStr == 'get') $get = true;
        $line = print_r($value, true);
        $output .= '<div style="' . $style . '" ><pre>' . $line . '</pre></div>';
    }

    foreach ($debugTrace as $key => $value) {
        // if($key == 'args') continue;
        $itemArr = array();
        $itemStr = '';
        is_array($value) ? $itemArr = $value : $itemStr = $value;
        if ($itemStr == 'get') $get = true;
        $line = print_r($value, true);
        $output .= '<div style="' . $style . '" ><pre>' . $line . '</pre></div>';
    }

    if ($get)  return $output;

    print $output;
    die ;
}
<?php

declare(strict_types=1);

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');

use Core\AppKernel;

require_once ROOT_DIR . '/vendor/autoload.php';

require_once ROOT_DIR . '/core/bootstrap.php';

$routes = require_once ROOT_DIR . '/config/routes.php';

//$className  = 'user';
//$methodName = 'get_users';
//$result = Router($routes, $className, $methodName);
//print_r($result);

try {

    new AppKernel($routes);

} catch(\Exception $e){

    echo $e->getMessage();
    exit;

}

//function Router($routes, $className, $methodName) {
//    $response = array();
//    if(isset($routes[$className])) {
//        $route = $routes[$className];
//        if(isset($route[ROUTE_CLASS_METHODS][$methodName])) {
//
//            $class    = $route[ROUTE_CLASS_NAME];
//            $method   = $route[ROUTE_CLASS_METHODS][$methodName];
//            $funcName = $method[ROUTE_METHOD_NAME];
//
//            $controller = new $class();
//            $response = $controller->$funcName();
//        }
//    }
//
//    return $response;
//}
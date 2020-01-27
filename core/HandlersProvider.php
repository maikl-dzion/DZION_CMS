<?php
/**
 * Created by PhpStorm.
 * User: abasovm
 * Date: 19.01.2020
 * Time: 18:46
 */

namespace Core;

class HandlersProvider extends AbstractCore {

    public function handlerList($params) {
        return array(
            'curl' => array('Core\Handlers\CurlDownloader', array()),
        );
    }

    public function servicesInit() {

//        $di       = $args[0];
//        $dbconfig = $args[1];
//
//        $services = $this->servicesList($dbconfig);
//
//        foreach ($services as $serviceClass => $values) {
//            $serviceName  = $values['name'];
//            $params = $values['params'];
//            if(!empty($params))
//                $service  = new $serviceClass($params);
//            else
//                $service  = new $serviceClass();
//
//            if(!$di->has($serviceName))
//                $di->set($serviceName, $service);
//        }
    }
}
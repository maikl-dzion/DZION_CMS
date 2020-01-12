<?php

namespace Core;

class ServicesRegister extends AbstractCore {

    public function servicesList($dbconfig) {
        return array(
            Services\Logger::class            => array('name' => 'logger'       , 'params' => LOG_PATH),
            Services\DB::class                => array('name' => 'db'           , 'params' => $dbconfig),
            Services\JwtAuthController::class => array('name' => 'jwt'          , 'params' => ''),
            Services\FileUploads::class       => array('name' => 'files_loader' , 'params' => ''),
            Services\Response::class          => array('name' => 'response'     , 'params' => '')
        );
    }

    public function servicesInit(...$args) {

        $di       = $args[0];
        $dbconfig = $args[1];

        $services = $this->servicesList($dbconfig);

        foreach ($services as $serviceClass => $values) {
            $serviceName  = $values['name'];
            $params = $values['params'];
            if(!empty($params))
                $service  = new $serviceClass($params);
            else
                $service  = new $serviceClass();

            if(!$di->has($serviceName))
               $di->set($serviceName, $service);
        }

        // return $di;
    }

}
<?php

namespace Core;

class ServicesRegister {

    public function __construct() {
         print_r($this->loadServices()); die;
    }


    public function loadServices() {

        $services = array(
            Core\Service\DB::class,
            Core\Service\Logger::class,
        );

        return $services;
    }

}
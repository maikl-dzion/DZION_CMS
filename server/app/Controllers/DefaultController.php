<?php

namespace App\Controllers;

use Core\Services\ConfigController;

class DefaultController {

    public function index() {
        $message = 'Default controller (404 not found)';
        return $message;
    }

    public function routesShow() {
        $config   = new ConfigController();
        $result   = $config->routesShow();
        return $result;
    }

    public function page404() {
        $message = '(404 notyyyy found)';
        return $message;
    }

}
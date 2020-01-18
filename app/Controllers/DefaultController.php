<?php

namespace App\Controllers;

use Core\Services\ConfigController;

class DefaultController {
    public function index() {
        $message = 'Default controller';
        return $message;
    }

    public function routesShow() {
        $config   = new ConfigController();
        $result   = $config->routesShow();
        return $result;
    }
}
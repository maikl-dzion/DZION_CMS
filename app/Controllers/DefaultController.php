<?php

namespace App\Controllers;

class DefaultController {
    public function index() {
        $message = 'Дефолтный контроллер';
        return $message;
    }
}
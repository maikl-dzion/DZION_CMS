<?php

namespace App\Controllers;

use Core\App\Controller;

class UserController extends Controller{

    // public function __construct(){}

    public function getUsers(){
        return 'get users f1';
    }


    public function getUser(...$params) {
        print_r($params); die;
        return $params;
    }
}
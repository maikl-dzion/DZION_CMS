<?php

return array(

//   'default_uri' => array(
//       ROUTE_CLASS_NAME  => App\Controllers\DefaultController::class,
//       ROUTE_METHODS_INDEX => array(
//            'index'       => array(ROUTE_METHOD_FNAME =>'index', 'args' => '', 'method' => 'GET'),
//            'routes_show' => array(ROUTE_METHOD_FNAME =>'routesShow', 'args' => '', 'method' => 'GET')
//       ),
//   ),
//
//   'user' => array(
//       ROUTE_CLASS_NAME  => App\Controllers\UserController::class,
//       ROUTE_METHODS_INDEX => array(
//           'get_users'   => array(ROUTE_METHOD_FNAME =>'getUsers',   'args' => '', 'method' => 'GET'),
//           'get_user'    => array(ROUTE_METHOD_FNAME =>'getUser' ,   'args' => '', 'method' => 'GET'),
//           'create_user' => array(ROUTE_METHOD_FNAME =>'createUser', 'args' => '', 'method' => 'POST'),
//           'update_user' => array(ROUTE_METHOD_FNAME =>'updateUser', 'args' => 'user_id', 'method' => 'PUT'),
//           'delete_user' => array(ROUTE_METHOD_FNAME =>'deleteUser', 'args' => 'user_id', 'method' => 'DELETE'),
//           'login'       => array(ROUTE_METHOD_FNAME =>'login',      'args' => 'login,password,user_id', 'method' => 'POST'),
//           'logout'      => array(ROUTE_METHOD_FNAME =>'logout',     'args' => 'user_id', 'method' => 'POST'),
//           'access'      => array(ROUTE_METHOD_FNAME =>'access',     'args' => 'token', 'method' => 'GET'),
//           'verify_email'     => array(ROUTE_METHOD_FNAME =>'verifyEmail', 'args' => '', 'method' => 'GET'),
//           'change_password'  => array(ROUTE_METHOD_FNAME =>'changePassword', 'args' => 'user_id', 'method' => 'PUT'),
//           'forgot_your_password'  => array(ROUTE_METHOD_FNAME =>'forgotYourPassword', 'args' => 'email', 'method' => 'GET'),
//       ),
//   )

    "default_url/index"         => "App\Controllers\DefaultController::index::GET",
    "default_url/404"           => "App\Controllers\DefaultController::page404::GET",
    "default_url/routes_show"   => "App\Controllers\DefaultController::routesShow::GET",

    "user/get_user/:user_id"    => "App\Controllers\UserController::getUser::GET",
    "user/get_users"            => "App\Controllers\UserController::getUsers::GET",
    "user/create_user"          => "App\Controllers\UserController::createUser::POST",
    "user/update_user/:user_id" => "App\Controllers\UserController::updateUser::PUT",
    "user/delete_user/:user_id" => "App\Controllers\UserController::deleteUser::DELETE",

    "user/login"                => "App\Controllers\UserController::login::POST",
    "user/logout/:user_id"      => "App\Controllers\UserController::logout::GET",

    "user/access"               => "App\Controllers\UserController::access::GET",
    "user/verify_email"         => "App\Controllers\UserController::verifyEmail::GET",
    "user/change_password"      => "App\Controllers\UserController::changePassword::POST",
    "user/forgot_your_password" => "App\Controllers\UserController::forgotYourPassword::POST",

);
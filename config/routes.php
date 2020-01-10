<?php

return array(

   'default_class' => array(
        ROUTE_CLASS_NAME  => App\Controllers\DefaultController::class,
        ROUTE_CLASS_METHODS => array(
            'index' => array(ROUTE_METHOD_FIELD =>'getUsers', 'args' => '', 'method' => 'GET')
        ),
   ),

   'user' => array(
       ROUTE_CLASS_NAME  => App\Controllers\UserController::class,
       ROUTE_CLASS_METHODS => array(
           'get_users'   => array(ROUTE_METHOD_FIELD =>'getUsers',   'args' => '', 'method' => 'GET'),
           'get_user'    => array(ROUTE_METHOD_FIELD =>'getUser' ,   'args' => '', 'method' => 'GET'),
           'create_user' => array(ROUTE_METHOD_FIELD =>'createUser', 'args' => '', 'method' => 'POST'),
           'update_user' => array(ROUTE_METHOD_FIELD =>'updateUser', 'args' => 'user_id', 'method' => 'PUT'),
           'delete_user' => array(ROUTE_METHOD_FIELD =>'deleteUser', 'args' => 'user_id', 'method' => 'DELETE'),
           'login'       => array(ROUTE_METHOD_FIELD =>'login',      'args' => 'login,password,user_id', 'method' => 'POST'),
           'logout'      => array(ROUTE_METHOD_FIELD =>'logout',     'args' => 'user_id', 'method' => 'POST'),
           'access'      => array(ROUTE_METHOD_FIELD =>'access',     'args' => 'token', 'method' => 'GET'),
       ),
   )

);
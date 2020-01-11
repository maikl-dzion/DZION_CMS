<?php

return array(

   'default_uri' => array(
       ROUTE_CLASS_NAME  => App\Controllers\DefaultController::class,
       ROUTE_METHODS_INDEX => array(
            'index' => array(ROUTE_METHOD_FNAME =>'index', 'args' => '', 'method' => 'GET')
       ),
   ),

   'user' => array(
       ROUTE_CLASS_NAME  => App\Controllers\UserController::class,
       ROUTE_METHODS_INDEX => array(
           'get_users'   => array(ROUTE_METHOD_FNAME =>'getUsers',   'args' => '', 'method' => 'GET'),
           'get_user'    => array(ROUTE_METHOD_FNAME =>'getUser' ,   'args' => '', 'method' => 'GET'),
           'create_user' => array(ROUTE_METHOD_FNAME =>'createUser', 'args' => '', 'method' => 'POST'),
           'update_user' => array(ROUTE_METHOD_FNAME =>'updateUser', 'args' => 'user_id', 'method' => 'PUT'),
           'delete_user' => array(ROUTE_METHOD_FNAME =>'deleteUser', 'args' => 'user_id', 'method' => 'DELETE'),
           'login'       => array(ROUTE_METHOD_FNAME =>'login',      'args' => 'login,password,user_id', 'method' => 'POST'),
           'logout'      => array(ROUTE_METHOD_FNAME =>'logout',     'args' => 'user_id', 'method' => 'POST'),
           'access'      => array(ROUTE_METHOD_FNAME =>'access',     'args' => 'token', 'method' => 'GET'),
       ),
   )

);
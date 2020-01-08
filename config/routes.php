<?php

return array(

   'default_class' => array(
        ROUTE_CLASS_NAME  => App\Controllers\DefaultController::class,
        ROUTE_CLASS_METHODS => array(
            'index' => array(ROUTE_METHOD_FIELD =>'getUsers', 'params' => '')
        ),
   ),

   'user' => array(
       ROUTE_CLASS_NAME  => App\Controllers\UserController::class,
       ROUTE_CLASS_METHODS => array(
           'get_users' => array(ROUTE_METHOD_FIELD =>'getUsers', 'params' => ''),
           'get_user'  => array(ROUTE_METHOD_FIELD =>'getUser' , 'params' => '')
       ),
   )

);
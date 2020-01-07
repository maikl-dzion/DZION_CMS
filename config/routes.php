<?php

return array(

   'user' => array(
       ROUTE_CLASS_NAME  => App\Controllers\UserController::class,
       ROUTE_CLASS_METHODS => array(
           'get_users' => array('name' =>'getUsers', 'params' => ''),
           'get_user'  => array('name' =>'getUser' , 'params' => '')
       ),
   )


);
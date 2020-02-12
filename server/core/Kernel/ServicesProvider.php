<?php

namespace Core\Kernel;

use Core\Interfaces\DIContainerInterface;

class ServicesProvider extends AbstractProvider {

    public function __construct(DIContainerInterface $di, $params = array()){
        parent::__construct();
        // $this->params = $params;
        $itemsList = $this->getItemsList($params);
        $this->dependencesRegister($di, $itemsList);
    }

    public function getItemsList($params = array()) : array {

        $dbconfig = $params['dbconfig'];

        return array(
            // Только регистрируем
            'db'   => array('class'  => "\\Core\\Services\\DB" ,             'params' => $dbconfig, 'init' => false),
            'mail' => array('class' => '\\Core\\Services\\SendMailer',       'params' => '',        'init' => false),

            // Сразу нициализируем
            'jwt'  => array('class' => \Core\Services\JwtAuthController::class, 'params' => '',       'init' => true),
        );


//        $servicesList = array(
//            Services\Logger::class            => array('name' => 'logger'       , 'params' => LOG_PATH),
//            Services\DB::class                => array('name' => 'db'           , 'params' => $dbconfig),
//            Services\JwtAuthController::class => array('name' => 'jwt'          , 'params' => ''),
//            Services\FileUploads::class       => array('name' => 'files_loader' , 'params' => ''),
//            Services\Response::class          => array('name' => 'response'     , 'params' => ''),
//            Services\SendMailer::class        => array('name' => 'mail'         , 'params' => ''),
//            Services\StringProcessingController::class => array('name' => 'string_helper', 'params' => ''),
//            Services\DbMigrateController::class => array('name' => 'migrate'    , 'params' => ''),
//            HandlersProvider::class           => array('name' => 'handl', 'params' => '')
//        );

        // return $servicesList;
    }

}
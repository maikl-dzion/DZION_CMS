<?php

namespace Core\Kernel;

use Core\Interfaces\DIContainerInterface;

class ServicesProvider extends AbstractCore {

    private $params;

    public function __construct($params = array()){
        parent::__construct();
        $this->params = $params;
    }

    protected function servicesList(array $dbconfig) : array {

        $servicesList = array(
            // Только регистрируем
            'db'   => array('class'  => "\\Core\\Services\\DB" ,             'params' => $dbconfig, 'init' => false),
            'mail' => array('class' => '\\Core\\Services\\SendMailer',       'params' => '',        'init' => false),

            // Сразу нициализируем
            'jwt'  => array('class' => '\\Core\\Services\\JwtAuthController', 'params' => '',       'init' => true),
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

        return $servicesList;
    }

    public function servicesRegister(DIContainerInterface $di, array $params) {

        $services = $this->servicesList(...$params);

        foreach ($services as $serviceName => $service) {

            $serviceClass  = $service['class'];
            $params        = $service['params'];
            $init          = $service['init'];

            if($init) {
                if(!empty($params))
                  $serviceObject  = new $serviceClass($params);
                else
                  $serviceObject  = new $serviceClass();

                $di->set($serviceName, $serviceObject);
            }
            else {
                $di->register($serviceName, $serviceClass, $params);
            }
        }
    }

}
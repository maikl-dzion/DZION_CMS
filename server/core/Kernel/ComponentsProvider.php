<?php

namespace Core\Kernel;

use Core\Interfaces\DIContainerInterface;

class ComponentsProvider extends AbstractProvider {

    public function __construct(DIContainerInterface $di, $params = array()){
        parent::__construct();
        // $this->params = $params;
        $itemsList = $this->getItemsList($params);
        $this->dependencesRegister($di, $itemsList);
    }

    public function getItemsList($params = array()) : array {

        return array(
            // Только регистрируем
            'curl'   => array('class'  => "\\Core\\Components\\CurlDownloader" ,  'params' => '' , 'init' => false),

            // Сразу нициализируем
            // 'jwt'  => array('class' => \Core\Services\JwtAuthController::class, 'params' => '',       'init' => true),
        );
    }

}
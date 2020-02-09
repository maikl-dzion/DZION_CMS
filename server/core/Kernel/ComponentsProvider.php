<?php

namespace Core\Kernel;

use Core\Interfaces\DIContainerInterface;

class ComponentsProvider extends AbstractCore {

    private $params;

    public function __construct($params = array()){
        parent::__construct();
        $this->params = $params;
    }

    protected function componentList() : array {

        $componentList = array(
            // Только регистрируем
            'curl'   => array('class'  => "\\Core\\Components\\CurlDownloader" ,  'params' => '' , 'init' => false),

            // Сразу нициализируем
            // 'jwt'  => array('class' => '\\Core\\Services\\JwtAuthController', 'params' => '',       'init' => true),
        );
        return $componentList;
    }

    public function componentsRegister(DIContainerInterface $di, array $params) {

        $components = $this->componentList(...$params);

        foreach ($components as $componentName => $item) {

            $itemClass  = $item['class'];
            $params        = $item['params'];
            $init          = $item['init'];

            if($init) {
                if(!empty($params))
                    $component  = new $itemClass($params);
                else
                    $component  = new $itemClass();

                $di->set($componentName, $component);
            }
            else {
                $di->register($componentName, $itemClass, $params);
            }
        }
    }

}
<?php

namespace Core\Kernel;

use Core\Interfaces\DIContainerInterface;

abstract class AbstractProvider extends AbstractCore
{
    protected $params;

    abstract public function getItemsList($params = array()) : array ;

    protected function dependencesRegister(DIContainerInterface $di, array $itemsList) {

        // $components = $this->componentList(...$params);

        foreach ($itemsList as $name => $item) {

            $itemClass  = $item['class'];
            $params     = $item['params'];
            $init       = $item['init'];

            if($init) {
                // Создаем объект класса
                if(!empty($params))
                    $controller  = new $itemClass($params);
                else
                    $controller  = new $itemClass();

                $di->set($name, $controller);
            }
            else {
                // Только регистрируем класс в контейнера (не инициализируем)
                $di->register($name, $itemClass, $params);
            }
        }
    }

}
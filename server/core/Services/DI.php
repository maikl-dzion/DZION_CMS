<?php

namespace Core\Services;

use Core\Interfaces\DIContainerInterface;

class DI implements DIContainerInterface
{
    private $container = array();
    private $instances = array();

    public function set($key, $value) {
        $this->container[$key] = $value;
        return $this;
    }

    public function get(string $key, array $params = array(), $save = false) {
        $object = $this->has($key);
        if(empty($object)) {
            $object = $this->init($key, $params);
            if(!empty($object)) {
                $this->set($key, $object);
            }
        }
        return $object;
    }

    public function has(string $key) {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }

    public function register(string $key, string $class, $parameters = array()) {
        $this->instances[$key]['class']  = $class;
        $this->instances[$key]['params'] = $parameters;
    }

    public function init(string $key, $parameters = array()) {
        if (!isset($this->instances[$key]))
           return null;

        if(!empty($parameters))
            $this->instances[$key]['params'] = $parameters;

        return $this->resolve($this->instances[$key]['class'],
                              $this->instances[$key]['params']);
    }


    public function resolve($concrete, $parameters) {

        $reflector = new \ReflectionClass($concrete);
        return new $reflector->name($parameters);

//        if (!$reflector->isInstantiable()) {
//            throw new Exception("Class {$concrete} is not instantiable");
//        }
//
//        $constructor = $reflector->getConstructor();
//
//        if (is_null($constructor)) {
//            return $reflector->newInstance();
//        }
//
//        $parameters   = $constructor->getParameters();
//        $dependencies = $this->getDependencies($parameters);
//
//        return $reflector->newInstanceArgs($parameters);
    }

    public function getDependencies($parameters) {
        $dependencies = [];
        lg($parameters);
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === NULL) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Can not resolve class dependency {$parameter->name}");
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}
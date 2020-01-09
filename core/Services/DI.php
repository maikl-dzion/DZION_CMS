<?php

namespace Core\Services;

class DI
{

    private $container = array();

    public function set($key, $value) {
        $this->container[$key] = $value;

        return $this;
    }

    public function get($key) {
        return $this->has($key);
    }

    public function has($key) {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }
}
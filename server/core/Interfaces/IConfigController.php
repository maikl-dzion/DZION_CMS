<?php

namespace Core\Interfaces;

interface IConfigController {
    public function getConfig(string $name);
}
<?php

namespace Core\Interfaces;

interface JwtAuthInterface {
    public function encode($login, $password, $role, $host = '');
    public function decode($token);
}
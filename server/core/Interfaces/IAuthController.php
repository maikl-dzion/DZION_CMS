<?php

namespace Core\Interfaces;

interface IAuthController
{
    public function auth(string $login, string $password);
}
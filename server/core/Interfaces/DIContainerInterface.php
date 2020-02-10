<?php
/**
 * Created by PhpStorm.
 * User: abasovm
 * Date: 09.02.2020
 * Time: 17:37
 */

namespace Core\Interfaces;


interface DIContainerInterface
{
    public function get(string $key);
    public function has(string $key);
}
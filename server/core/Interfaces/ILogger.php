<?php

namespace Core\Interfaces;


interface ILogger
{
   public function log($data, string $fileName);
}
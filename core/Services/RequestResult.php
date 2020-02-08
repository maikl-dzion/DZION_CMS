<?php

namespace Core\Services;

use Core\AbstractCore;

class RequestResult extends AbstractCore
{
    public $url;
    public $type;
    public $class;
    public $action;
    public $url_key;
    public $arguments;
    public $error;
    public $warning;
}
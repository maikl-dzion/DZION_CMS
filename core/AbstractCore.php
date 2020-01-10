<?php

namespace Core;

use Core\Services\Logger;

abstract class AbstractCore {

    protected $logger;

    public function __construct(){
        $this->logger  = new Logger(LOG_PATH);
    }
}
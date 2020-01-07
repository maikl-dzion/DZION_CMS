<?php

declare(strict_types=1);

define('ROOT_DIR', __DIR__);

use Core\AppKernel;

require_once ROOT_DIR . '/vendor/autoload.php';

require_once ROOT_DIR . '/core/bootstrap.php';


try {

    new AppKernel();

} catch(\Exception $e){

    echo $e->getMessage();
    exit;

}


<?php

header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, X-HTTP-Method-Override, Origin, Content-Type, Cookie, Accept');

header('Content-Type: text/html; charset=utf-8');

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

session_start();  // Запускаем сессию
$sessionId = session_id();

define('CONFIG_DIR' , ROOT_DIR . '/config');
define('APP_DIR'    , ROOT_DIR . '/app');
define('CORE_DIR'   , ROOT_DIR . '/core');
define('LOG_DIR'    , ROOT_DIR . '/log');
define('UPLOADS_DIR', ROOT_DIR . '/uploads');
define('PUBLIC_DIR' , ROOT_DIR . '/public');
define('ADMIN_DIR'  , ROOT_DIR . '/adnin');
define('API_DIR'    , ROOT_DIR . '/api');


require_once ROOT_DIR . '/vendor/autoload.php';

require_once 'constants.php';
require_once 'functions.php';

//$routes   = require_once ROOT_DIR . '/config/routes.php';
//$dbconfig = require_once ROOT_DIR . '/config/dbconfig.php';

set_error_handler('error_log_handler');
<?php
/**
 * Created by PhpStorm.
 * User: maikl
 * Date: 09.02.2020
 * Time: 12:05
 */

namespace Core\Kernel;


class ConstContainer
{

    const CONFIG_DIR  = ROOT_DIR . '/config';
    const APP_DIR     = ROOT_DIR . '/app';
    const CORE_DIR    = ROOT_DIR . '/core';
    const LOG_DIR     = ROOT_DIR . '/log';
    const ADMIN_DIR   = ROOT_DIR . '/admin';
    const UTILS_DIR   = ROOT_DIR . '/utils';
    const API_DIR     = ROOT_DIR . '/api';

    const UPLOADS_DIR = ROOT_DIR . '/../uploads';
    const PUBLIC_DIR  = ROOT_DIR . '/../public';
    // const LOG_PATH    = ROOT_DIR . '/log';

    const ROUTE_CLASS_NAME    = 'class';
    const ROUTE_METHODS_INDEX = 'public';
    const ROUTE_METHOD_FNAME  = 'func_name';

    const REQUEST_URL_NAME     = 'PATH_INFO';

    const RESPONSE_RESULT_NAME = 'result';
    const RESPONSE_ERROR_NAME  = 'error';

    const DEFAULT_FRONT_ROUTE   = 'default_url/index';
    const DEFAULT_SERVER_ROUTE  = 'App\Controllers\DefaultController::index::GET';

    const ROUTE_PARAM_DELIMITER  = '::';

    const SMTP_SERVER_NAME = 'ssl://smtp.beget.com';
    const SMPT_SERVER_PORT = 465; // 2525  25
    const SMTP_USER_NAME   = 'maikl_dzion@bolderfest.ru';
    const SMTP_USER_PASSWORD = 'Dzion1967';


}
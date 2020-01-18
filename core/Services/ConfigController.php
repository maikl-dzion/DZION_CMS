<?php
/**
 * Created by PhpStorm.
 * User: abasovm
 * Date: 14.01.2020
 * Time: 11:44
 */

namespace Core\Services;


use Core\AbstractCore;

class ConfigController extends AbstractCore
{
    protected $config;

    public function __construct() {
        parent::__construct();
        $this->configLoader();
    }

    public function set($name, $value) {
        $this->config[$name] = $value;
    }

    public function get($name) {
        return (!empty($this->config[$name])) ? $this->config[$name] : null;
    }

    protected function configLoader() {
        $configDirName = CONFIG_DIR;  // Директория где лежат все конфиги
        $configFiles = scandir($configDirName);
        foreach ($configFiles as $key => $fileName) {
            if($fileName == '.' || $fileName == '..') continue;
            $configFile = $configDirName . '/' .$fileName;
            if(!file_exists($configFile)) continue;

            $config = include $configFile;
            list($configName, $ext) = explode('.', $fileName);
            $this->set($configName, $config);
        }
    }

    public function printRoutes() {
        $routes = $this->get('routes');
    }

    public function routesShow() {
        $routes   = $this->get('routes');
        $result = array();
        foreach($routes as $classWebtUrl => $route) {
            $className = $route['class'];
            foreach($route['public'] as $funcWebUrl => $params) {
                $webUrl = '/' . $classWebtUrl . '/' . $funcWebUrl;
                $classUrl = $className . '::' . $params['func_name'];
                $args     = implode('@', $params);
                // list($fname, $args, $m)   = $params;
                $result[$webUrl] = $classUrl . ' (' . $args. ')';
            }
        }
        return $result;
    }

}
<?php

namespace Core\Kernel;

// use Core\AbstractCore;

use Core\Interfaces\IConfigController;

class ConfigController extends AbstractCore implements IConfigController
{
    protected $config;
    protected $configPath;

    public function __construct(string $configPath) {
        parent::__construct();
        $this->configPath = $configPath;
        $this->configLoader();
    }

    public function getConfig(string $name) : array  {
        return (!empty($this->config[$name])) ? $this->config[$name] : null;
    }

    protected function setConfig(string $name, array $conf) {
        $this->config[$name] = $conf;
    }

    protected function configLoader() {
        $configDirName = $this->configPath;  // Директория где лежат все конфиги
        $configFiles   = scandir($configDirName);
        foreach ($configFiles as $key => $fileName) {
            if($fileName == '.' || $fileName == '..') continue;
            $configFile = $configDirName . '/' .$fileName;
            if(!file_exists($configFile)) continue;

            $config = include $configFile;
            list($configName, $ext) = explode('.', $fileName);
            $this->setConfig($configName, $config);
        }
    }

//    public function printRoutes() {
//        $routes = $this->getConfig('routes');
//    }
//
//    public function routesShow() {
//
//        $result = array();
//        $routes = $this->getConfig('routes');
//
//        foreach($routes as $frontUrl => $serverRoute) {
//            $result[$frontUrl] = $serverRoute;
//        }
//        return $result;
//    }
}
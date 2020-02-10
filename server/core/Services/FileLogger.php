<?php

namespace Core\Services;

use Core\Interfaces\ILogger;

/***
 *
 * Пример использования
* $logger = new Core\Services\FileLogger(LOG_PATH);
* $error = array('err_name' => 'ошибка 33', 'data' => 'тестовый пример');
* $logger->log($error, 'db_error');
 *
 ***/

class FileLogger implements ILogger
{

    protected $logPath  = false;
    protected $ext      = '.log';
    public    $mode     = 'ON';
    public    $trace    = 'OFF';

    public function __construct($path){
        $this->logPath = $path;
    }

    public function log($data = '', string $fileName = 'log'){
        $log = $this->makeLog($data);
        if ($log && $this->logPath) {
            $this->saveLog($fileName, $log);
        } else {
            $message = "Не удалось записать в лог:не задан путь-{$this->logPath} или нет данных";
            lg($message, $log);
        }
    }

    protected function saveLog($fileName, $log) {
        $filePath = $this->getFilePath($fileName);
        $log .= file_get_contents($filePath);
        file_put_contents($filePath, $log, LOCK_EX);
    }

    protected function getFilePath($fileName) {
        return $this->logPath .'/'. $fileName . $this->ext;
    }

    protected function makeLog($data) {

        $log = false;

        if ($this->mode == 'ON') {
            $log = '=============================' . PHP_EOL;
            $log .= 'DateTime: ' . date('d.m.Y H:i:s') . PHP_EOL;
            $log .= 'Log data: ' . PHP_EOL;
            if (!empty($data)) {
                if (is_object($data) || is_array($data)) {
                    $log .= print_r($data, true);
                    // $log .= '<pre>' . print_r($data, true) .'<pre>';
                } else {
                    $log .= $data . PHP_EOL;
                }
            }

            if ($this->trace == 'ON') {
                $backtrace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                $log .= 'Trace data:' . PHP_EOL;
                foreach ($backtrace as $key => $el) {
                    if ($key > 0) {
                        $log .= '#' . $key . ' '
                            . $el['class'] . $el['type']
                            . $el['function'] . '() called at [' . $el['file']
                            . ':' . $el['line'] . ']' . PHP_EOL;
                    }
                }
            }

            $log .= '=============================' . PHP_EOL . PHP_EOL;
        }

        return $log;
    }


    public function consoleLog($data = '', $group = '') {
        $log = $this->makeLog($data);
        $result = '';
        if ($log) {
            $log = json_encode($log);
            $group_prefix = $group_postfix = '';
            if ($group) {
                $group_prefix  = 'console.group("' . $group . '");';
                $group_postfix = 'console.groupEnd();';
            }
            $result = "<script>
                        {$group_prefix} console.log({$log});
                        {$group_postfix}
                      </script>";

        }

        echo $result;
    }

    public  function pre($data = ''){
        $log = $this->makeLog($data);
        if ($log)
            $result ="<pre>$log</pre>";
        echo $result;
    }

}
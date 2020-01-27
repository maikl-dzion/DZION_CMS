<?php

function error_log_handler($errno, $message, $filename, $line) {
    $date = date('Y-m-d H:i:s (T)');
    $fp   = fopen('error.txt', 'a');
    if (!empty($fp)) {
        $filename  =str_replace(LOG_PATH,'', $filename);
        $err  = " $message = $filename = $line\r\n ";
        fwrite($fp, $err);
        fclose($fp);
    }
}

function lg() {

    $debugTrace = debug_backtrace();
    $args = func_get_args();

    $get = false;
    $output = $traceStr = '';

    $style = 'margin:10px; padding:10px; border:3px red solid;';

    foreach ($args as $key => $value) {
        $itemArr = array();
        $itemStr = '';
        is_array($value) ? $itemArr = $value : $itemStr = $value;
        if ($itemStr == 'get') $get = true;
        $line = print_r($value, true);
        $output .= '<div style="' . $style . '" ><pre>' . $line . '</pre></div>';
    }

    foreach ($debugTrace as $key => $value) {
        // if($key == 'args') continue;
        $itemArr = array();
        $itemStr = '';
        is_array($value) ? $itemArr = $value : $itemStr = $value;
        if ($itemStr == 'get') $get = true;
        $line = print_r($value, true);
        $output .= '<div style="' . $style . '" ><pre>' . $line . '</pre></div>';
    }

    // if ($get) return $output;

    print $output;
    die ;
}
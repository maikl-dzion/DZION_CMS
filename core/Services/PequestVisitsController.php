<?php

namespace Core\Services;

use Core\AbstractCore;

class PequestVisitsController extends AbstractCore {

    public function __construct(){
        parent::__construct();
        $this->saveVisit();
    }

    public function saveVisit() {

        $file = LOG_DIR . "/visits.log";   // куда пишем логи
        $col_zap = 4999;                   // строк в файле не более

        if (strstr($_SERVER['HTTP_USER_AGENT'], 'YandexBot')) {$bot='YandexBot';}
        elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Googlebot')) {$bot='Googlebot';}
        else { $bot=$_SERVER['HTTP_USER_AGENT']; }

        $ip = $this->getIpAddress();
        $date = date("H:i:s d.m.Y");        //дата события
        $home = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];    //какая страница сайта
        if(!file_exists($file))
            file_put_contents($file, "====== {$date} ===== ");
        $lines = file($file);
        while(count($lines) > $col_zap) array_shift($lines);
        $lines[] = $date."___bot:" . $bot. "___remote-ip:" . $ip . "___home:" . $home . "___\r\n";
        file_put_contents($file, $lines);
    }

    /**
     * @return string
     */
    protected function getIpAddress() : string {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))        // Определяем IP
        { $ip=$_SERVER['HTTP_CLIENT_IP']; }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  // Если IP идёт через прокси
        { $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; }
        else { $ip=$_SERVER['REMOTE_ADDR']; }
        return $ip;
    }

    public function printVisits() {
        $fileName = LOG_DIR . "/visits.log";
        $file = file($fileName);
        ob_start(); ?>

        <html>
        <head>
            <style type='text/css'>
                td.zz {padding-left: 3px; font-size: 9pt; padding-top: 2px; font-family: Arial; }
            </style>
        </head>
        <body>

        <table width="680" cellspacing="1" cellpadding="1" border="0" STYLE="table-layout:fixed">
            <tr bgcolor="#eeeeee">
                <td class="zz" width="100"><b>Время, дата</b></td>
                <td class="zz" width="200"><b>Кто посещал</b></td>
                <td class="zz" width="100"><b>IP, прокси</b></td>
                <td class="zz" width="280"><b>Посещенный URL</b></td>
            </tr>

        <?php

        $count = sizeof($file);

        foreach ($file as $si => $values) {

            $string = explode("___", $file[$si]);
            $q1[$si] = $string[0]; // дата и время
            $q2[$si] = $string[1]; // имя бота
            $q3[$si] = $string[2]; // ip бота
            $q4[$si] = $string[3]; // адрес посещения

            echo "<tr bgcolor='#eeeeee' >
                  <td class='zz' >{$q1[$si]}</td>
                  <td class='zz' >{$q2[$si]}</td>
                  <td class='zz' >{$q3[$si]}</td>
                  <td class='zz' >{$q4[$si]}</td>
              </tr>";
        }

        echo '</table></body></html>';
        $result = ob_get_clean();
        return $result;
    }
}
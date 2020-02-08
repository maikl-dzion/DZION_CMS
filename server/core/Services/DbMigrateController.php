<?php

namespace Core\Services;

use Core\AbstractCore;

class DbMigrateController extends AbstractCore {
    private $db;
    public function migrateLoader($db) {
        $this->db = $db;
        $list = $this->db->getTableListSheme();
        // lg($list);
    }

}
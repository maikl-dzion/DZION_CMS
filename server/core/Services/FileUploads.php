<?php

namespace Core\Services;

use Core\Interfaces\FileUploadsInterface;
use Core\Kernel\AbstractCore;

class FileUploads extends AbstractCore implements FileUploadsInterface {

    protected $params = array();
    protected $sourceFile;
    protected $newFilePath;
    protected $postData = array();
    protected $uploadsDir = UPLOADS_DIR;

    public function __construct($params = array()){
        parent::__construct();
        $this->params = $params;
    }

    public function fileLoading() {
        $this->init();
        return $this->save();
    }

    protected function init() {
        $this->postData = $this->fetchPostData();
        $file = $_FILES['image'];
        $uploadsDir = $this->uploadsDir;
        $this->sourceFile   = $file['tmp_name']; // Временный файл
        $this->newFilePath  = $uploadsDir. '/' . $file['name']; // Новый файл
    }

    protected function save() {
        $status = move_uploaded_file(
             $this->sourceFile   //  источник
            ,$this->newFilePath  //  новый файл
        );
        return $status;
    }

    protected function fetchPostData() {
        $result = (array)json_decode(file_get_contents("php://input"));
        if(empty($result))
            return array();
        return $result;
    }
}
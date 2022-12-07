<?php

namespace App\Helpers;

use Cache;
use File;
use Throwable;
use URL;

class ApplicationHelper
{
    private $DEFAULT_PATH;
    private $DEFAULT_FILE_NAME;
    public function __construct()
    {
        $this->DEFAULT_PATH = public_path(); // if you change this... you must change getUrl Function As Well
        $this->DEFAULT_FILE_NAME = 'wourki.apk';
    }
    public function upload($file){
        try{
            File::delete($this->getFullPath());
            $file->move($this->DEFAULT_PATH , $this->DEFAULT_FILE_NAME);
            return true;
        }
        catch(Throwable $e){
            return false;
        }
    }
    public function getFullPath(){
        return $this->DEFAULT_PATH . DIRECTORY_SEPARATOR . $this->DEFAULT_FILE_NAME;
    }
    public function getUrl(){
        return URL::to($this->DEFAULT_FILE_NAME);
    }
}

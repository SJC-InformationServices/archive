<?php 

use \R;
namespace sjcArchive\models;

class base{
    
    public $host = "sjc-content-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com";
    public $dbname = "archiveadmin";
    public $user = "sjcArchiveOld";
    public $pass = "5jcAdmin!";
    public $type = "";

        public $prop;
        public function __construct(){
            \R::setup("mysql:host=$this->host;dbname=$this->$dbname;",$this->$user,$this->$pass);
            \R::setAutoResolve( TRUE );        //Recommended as of version 4.2
            \R::useWriterCache(true);
            \R::useJSONFeatures(TRUE);
        }
        
    }


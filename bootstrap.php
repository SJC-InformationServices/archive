<?php

/**
 * BOOTSTRAP.PHP
 * 
 * @author    Kevin.Noseworthy <kevin.noseworthy@stjoseph.com>
 * @copyright 2015 St.Joseph
 * @license   Licence Name
 * @see       Link to project website
 * @package
 */

$root = $_SERVER["DOCUMENT_ROOT"];
require_once "$root"."/vendor/autoload.php";

define("sjcArchiveDB",array(
    "server"=>"sjc-archive-dev.cluster-cpi3jpipzm32.us-east-1.rds.amazonaws.com",
    "database"=>"sjcArchiveManager",
    "user"=>"sjcArchiveAdmin",
    "pwd"=>"5jcAdmin!",
    "port"=>"3306"    
));


?>
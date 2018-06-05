<?php
/** Define Root Path and Composer Autoloader */
$root = $_SERVER["DOCUMENT_ROOT"];
require_once "$root"."/vendor/autoload.php";

define("sjcArchiveDB",array(
    "server"=>"sjc-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com",
    "database"=>"sjcArchiveManager",
    "user"=>"sjcArchiveAdmin",
    "pwd"=>"5jcAdmin!",
    "port"=>"3306"    
));


?>
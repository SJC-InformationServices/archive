<?php
/** Define Root Path and Composer Autoloader */
$root = $_SERVER["DOCUMENT_ROOT"];
require_once "$root"."/vendor/autoload.php";
echo "$root"."/vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler($root."api.log", Logger::WARNING));

// add records to the log
$log->warning('Foo');
$log->error('Bar');



?>
<?php 
require_once "cfg.php";
echo "Test";

try{
$api = new \sjcArchive\api();
var_dump($api);
}catch(Exception $e){
    print_r($e);
}

try{
$ui = new \sjcArchive\ui();
var_dump($api);
}catch(Exception $e)
{
    print_r($e);
}

?>
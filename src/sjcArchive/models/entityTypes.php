<?php

namespace sjcArchive\models;

class entityType extends \RedBeanPHP\SimpleModel{
    
    public function __construct(array $dbconfig){

    }
    public function open(){}
    public function dispense(){}
    public function update(){}
    public function after_update(){}
    public function delete(){}
    public function after_delete(){}
    public function addEntityType(entityType $et){}
    public function deleteEntityType(entityType $et){} 
    public function addRelative(string $type, entityType $relative){}
}

?>
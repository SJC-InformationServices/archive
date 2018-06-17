<?php
/**
 * API.PHP
 *
 * For Any Archive Request Start Here
 *
 * PHP version 7
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Application
 * @package    Request
 * @author     Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
 * @copyright  1997-2018 St.Joseph Communication
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */ 
namespace sjcArchive\Models{
     /**
      * This is MainClass for All Requests
      * 
      * @category Application
      * @package  Request
      * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
      * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
      * @link     http://pear.php.net/package/Pa$ckageName
      */
    class EntityDefinitions 
    {
        use \sjcArchive\Modules\Archivedb;

        public $id;
        public $rawdata = [];
        private $_createdon;
        private $_updatedon;
        private $_tbl = "sjcarchiveentitymanager";
        /**
         * Construction function for Entity Definitions
         *
         * @param string $name Name of entity type unique
         */
        public function __construct(string $name=null)
        {
            $this->initdb(1, 1);
            if (!is_null($name)) {
                 $this->_rawdata = ['name'=>$name];
                 $this->_name = $name;
                 $this->loadByName();
            }
        }
        /**
         * GETTER function
         *
         * @param [string] $attrib attributed assigned to entity type
         * 
         * @return void
         */
        public function __get($attrib)
        {
            if (property_exists($this, $attrib)) {
                return $this->$attrib;
            }
            if (isset($this->_rawdata[$attrib])) {
                return $this->_rawdata[$attrib];
            }
                return false;
        }
       
        /**
         * Loads Values if EntityDefinition Already Exists
         *
         * @param string $name
         * 
         * @return void
         */
        public function loadByName($name=null)
        {
            $data = \R::findOne(
                $this->_tbl, 
                'name = ?', 
                [$this->_name]
            );
            if (count($data) == 1) {
                $this->_id = $data['id'];
                $this->_rawdata= $data['rawdata'];
                $this->_createdon = $data['createdon'];
                $this->_updatedon = $data['updatedon'];
            }
            return $this->getRecord();
        }
        public function loadById($id)
        

        
        
    }
}
?>
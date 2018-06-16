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
    use \sjcArchive\Modules\Archivedb;
    use \RedBeanPHP\R;

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
        private $_id;
        private $_tbl;
        private $_rawdata;
        private $_name;
        private $_attribs;
        private $_configs;
        private $_type;
        private $_relations;
        /**
         * Construction function for Entity Definitions
         *
         * @param string $name Name of entity type unique
         */
        public function __construct(string $name=null)
        {
            if (!is_null($name)) {
                 $this->_rawdata = ['name'=>$name];
                 $this->_name = $name;
                 $this->_load();
            }
        }
        /**
         * Loads Values if EntityDefinition Already Exists
         *
         * @return void
         */
        private function _load()
        {
            $data = \R::findOne(
                $this->_tbl, 
                'name = ?', 
                [$this->_name]
            );
            if (count($data) == 1) {
                $this->_rawdata= $data['rawdata'];
                $this->_name= $data['name'];
                $this->_attribs= $data['attribs'];
                $this->_configs= $data['configs'];
                $this->_type= $data['type'];
                $this->_relations= $data['relations'];
            }
        }
        /**
         * Getrecord returns current value
         *
         * @return array
         */
        public function getRecord()
        {
            return $this->_rawdata;
        }
        /**
         * Sets Current Records into the database 
         *
         * @return void
         */
        public function setRecord()
        {
            $t = $this->_tbl;
            $et = R::dispense($t);
            $et->rawdata = $this->_rawdata;
            $id = R::store($et); 
            $this->_id = $id;
            return $id;
        }

        
        
    }
}
?>
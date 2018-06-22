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
namespace sjcArchive\EntityManager{
    use \RedBeanPHP\R as R;
     /**
      * Base class for EntityManage requests
      * 
      * @category Application
      * @package  API
      * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
      * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
      * @link     http://url.com
      */ 
    class Base implements Control 
    {
        
        public $ed;

        private $_createTableSql = "CREATE TABLE `:name` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `rawdata` json DEFAULT NULL,
            `createdon` datetime DEFAULT CURRENT_TIMESTAMP,
            `updatedon` datetime DEFAULT 
            CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `uuid` varchar(36) COLLATE utf8mb4_unicode_ci 
            GENERATED ALWAYS AS 
            (json_unquote(json_extract(`rawdata`,'$.uuid'))) STORED,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uuid_UNIQUE` (`uuid`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 
          COLLATE=utf8mb4_unicode_ci";
        
        private $_createBeforeInsert = "CREATE TRIGGER 
        `:name_uuid_BEFORE_INSERT` BEFORE INSERT ON `:name` FOR EACH ROW
        BEGIN            
        if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
            set NEW.rawdata = JSON_SET(NEW.rawdata,'$.uuid',uuid());
        END IF;
        END";
        
        private $_createBeforeUpdate =  "CREATE TRIGGER 
        `:name_BEFORE_UPDATE` BEFORE UPDATE ON `:name` FOR EACH ROW
        BEGIN
        insert into `entity_history` 
        (`rawdata`) values 
        (json_set(old.rawdata,'$.entity_type',':name','$.entity_id',old.id));
        if JSON_EXTRACT(new.rawdata,'$.UUID') is null then
            set NEW.rawdata = JSON_SET(NEW.rawdata,'$.uuid',
            JSON_UNQUOTE(JSON_EXTRACT(OLD.rawdata,'$.UUID'))
            );
        END IF;
        END";
        private $_createBeforeDelete = "CREATE TRIGGER `:name_BEFORE_DELETE` 
        BEFORE DELETE ON `:name` FOR EACH ROW
        BEGIN insert into `entity_history` (`rawdata`) 
        values (json_set(old.rawdata,'$.entity_type',
        ':name','$.entity_id',old.id));
        END";
        
        /**
         * Undocumented function
         */
        public function __construct()
        {
            R::selectDatabase('default');
        }
        /**
         * Create
         *
         * @param array $rawdata json records of entitytypes
         * 
         * @return void
         */
        public function create(array $rawdata) 
        {            
            //R::fancyDebug(true);
            $this->read($rawdata['name']);
            
            if (is_null($this->ed) || count($this->ed) == 0) {
                $raw = json_encode($rawdata);
                R::begin();               
                try {
                    $b = R::exec(
                        'insert into `entitydefinitions` 
                        (`rawdata`) values (:raw)', [':raw'=>$raw]
                    );
                    $this->_createTable($rawdata['name']);
                    R::commit();
                }
                catch(Exception $e){
                    R::rollback();
                }
                $this->read($rawdata['name']);
            }
            
        }
        /**
         * Read
         *
         * @param string $name archive entity type
         * 
         * @return void
         */
        public function read(string $name=null) 
        {  
            if ($name === null || $name=="") {
                $results = R::getAll(
                    'select `id`,`rawdata`,`createdon`,`updatedon` 
                    from `entitydefinitions`'
                );
            } else {                
                $results = R::getAll(
                    'select `id`,`rawdata`,`createdon`,`updatedon` 
                    from `entitydefinitions` where name = :name', 
                    [':name'=>$name]
                );
            }
            if (!is_null($results)) {
                $tmp = [];
                foreach ($results as $r) {
                    $obj = array_merge($r, json_decode($r['rawdata'], true));
                    unset($obj['rawdata']);
                    $tmp[$obj['name']]=$obj;
                }
                $this->ed =$tmp;
            } else {
                $this->ed = null;
            }
        }
        /**
         * Undocumented function
         *
         * @param array $rawdata a array of attributes about entitytypes
         * 
         * @return void
         */
        public function update(array $rawdata)
        {

        }
        /**
         * Delete Functions
         * 
         * @param array $rawdata objects to delete and update
         * 
         * @return void
         */
        public function delete(array $rawdata) 
        {
            
        }
        /**
         * _CREATETABLE Corresponding Tables
         *
         * @return void
         */
        private function _createTable($name)
        {
            $name =strtolower($name);
            R::selectDatabase('datadb');
            R::begin();
            try{
                R::exec(
                    str_ireplace(':name', $name, $this->_createTableSql)
                );
                R::exec(
                    str_ireplace(':name', $name, $this->_createBeforeInsert)
                );
                R::exec(
                    str_ireplace(':name', $name, $this->_createBeforeUpdate)
                );
                R::exec(
                    str_ireplace(':name', $name, $this->_createBeforeDelete) 
                );
                R::commit();
            }
            catch(Exception $e)
            {
                //TODO: LOG
                R::rollback();
                return false;
            }
            R::selectDatabase('default');
        }
        
    }   
}
?>
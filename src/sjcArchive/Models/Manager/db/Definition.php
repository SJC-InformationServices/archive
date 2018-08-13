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

namespace sjcArchive\Models\Manager\db
{
    use \RedBeanPHP\R as R;
    /**
     * Abstract Base Archive Model 
     * 
     * @category Application
     * @package  API
     * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
     * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
     * @link     http://url.com
     */   
    trait Definition
    {
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
        private $_addIndex = "ALTER TABLE `:name` 
        ADD COLUMN `:col` INT(11) UNSIGNED NULL,
        ADD :idxtype `:idxname` (:fields);";
        /**
         * _CREATETABLE Corresponding Tables
         * 
         * @param string $name Name of table to create
         *
         * @return void
         */
        protected function createTable($name)
        {
            $name =strtolower($name);
            
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
                
            }
            catch(Exception $e)
            {
                //TODO: LOG
                return false;
            }
        
        }
        /**
         * Undocumented function
         *
         * @param [type] $name name of table
         * 
         * @return void
         */
        protected function deleteTable($name)
        {
            /**
             * TODO: Create a delete and archive function
             */
        }

    }
}
?>
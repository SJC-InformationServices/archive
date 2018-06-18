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
    
    class Base implements Control
    {
        /**
         * Create
         *
         * @param object $et      archive entity type
         * @param array  $rawdata json records of entitytypes
         * 
         * @return void
         */
        public function create(
            \sjcArchive\Models\Entitydefinition $et, 
            array $rawdata
        )
        {

        }
        /**
         * Read
         *
         * @param object $et archive entity type
         * 
         * @return void
         */
        public function read(
            \sjcArchive\Models\Entitydefinition $et
        ) {
            
        }
        /**
         * Undocumented function
         * 
         * @param object $et      archive entity type
         * @param array  $rawdata a array of attributes about entitytypes
         * 
         * @return void
         */
        public function update(
            \sjcArchive\Models\Entitydefinition $et, 
            array $rawdata
        ) {

        }
        /**
         * Undocumented function
         *
         * @param object $et archive entity type
         * 
         * @return void
         */
        public function delete(
            \sjcArchive\Models\Entitydefinition $et
        ) {
            
        } 
    }   
}
?>
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
      * Abstract base class for API requests
      * 
      * @category Application
      * @package  APIE
      * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
      * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
      * @link     http://url.com
      */
    class Entitydefinition
    {
        public $id;
        public $rawdata;
        public $createdon;
        public $updatedon;
        public $defition;
        
        /**
         * Undocumented function
         *
         * @param [type] $a attributes to fetch
         * 
         * @return void
         */
        public function __get($a) 
        {
            if (property_exists($this, $a)) {
                return $this->$a;
            } elseif (isset($this->rawdata[$a])) {
                return $this->rawdata[$a];
            } else {
                return null;
            }
        }
        /**
         * Undocumented function
         *
         * @param mixed $a attributes to fetch
         * @param mixed $v value of attribute
         * 
         * @return void
         */
        public function __set($a,$v) 
        {
            if (property_exists($this, $a)) {
                $this->$a=$v;
            } else {
                $this->rawdata[$a] = $v;
            }
        }
        

        
    }
}


?>
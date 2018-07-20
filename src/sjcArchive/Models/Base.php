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
    abstract class Base
    {
        protected $id;
        protected $rawdata=[];
        protected $createdon;
        protected $updatedon;
        /**
         * Inital Constructor 
         */
        public function __construct()
        {
            
        }
        /**
         * Undocumented function
         *
         * @param [type] $name  name of parm
         * @param [type] $value value of parm
         * 
         * @return void
         */
        public function __set($name,$value)
        {

        }
        /**
         * Undocumented function
         * 
         * @param mixed $name name of parm
         *
         * @return void
         */
        public function __get($name)
        {

        }
         /**
         * FIND function
         *
         * @param [array] $keyval int of id
         *
         * @return void
         */
        abstract public function find($keyval);
        /**
         * GETALL function
         *
         * @return void
         */
        abstract public function save();
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function delete();
        
    }
}
?>
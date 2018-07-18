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
namespace sjcArchive\Models\Manager{
    use \RedBeanPHP\R as R;
     /**
      * Abstract Base class for Management 
      * 
      * @category Application
      * @package  API
      * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
      * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
      * @link     http://url.com
      */ 
    abstract class Base
    {
        /**
         * Undocumented function
         */
        public function __construct()
        {
            try {
                R::setAutoResolve(true);
                R::useJSONFeatures(true);
                $db = ARCHIVEDB;

                $h = $db['server'];
                $d = $db['db'];
                $u = $db['uid'];
                $p = $db['pwd'];
                $f = $db['frozen'];
                R::setup(
                    "mysql:host=$h;dbname=$d",
                    $u,
                    $p,
                    $f
                );
                
                $db2 = DATADB;
                $h2 = $db2['server'];
                $d2 = $db2['db'];
                $u2 = $db2['uid'];
                $p2 = $db2['pwd'];
                $f2 = $db2['frozen'];
                R::addDatabase(
                    "datadb",
                    "mysql:host=$h2;dbname=$d2",
                    $u2,
                    $p2,
                    $f2
                );
                R::selectDatabase('default');
            }
            catch(Exception $e){
                $trace = debug_backtrace();
                trigger_error(
                    'DB Error:  ' . $e.message() . ' in ' . $trace[0]['file'] . 
                    ' on line ' . 
                    $trace[0]['line'], 
                    E_USER_NOTICE
                );
            }
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
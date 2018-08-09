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
    abstract class Base implements \JsonSerializable
    {
        protected $type;

        protected $id;
        protected $attributes=[];
        protected $rawdata=[];
        protected $createdon;
        protected $updatedon;
        
        /**
         * Parent Constsructor function
         *
         * @param sting $type type or name of models
         * 
         * @return void
         */
        public function __construct(string $type)
        {
            $this->type = $type;
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
         * Undocumented function
         *
         * @return void
         */
        public function jsonSerialize()
        {
            $r = $this->rawdata;
            $r['id'] = $this->id;
            $r['createdon']= $this->createdon;
            $r['updatedon']= $this->updatedon;
            return $r;
        }
        /**
         * Undocumented function
         *
         * @param [type] $k name of parm
         * @param [type] $v value of parm
         * 
         * @return void
         */
        public function __set($k, $v)
        {
            if (array_key_exists($k, $this->rawdata) || in_array($k, $this->attributes) ) {
                $this->rawdata[$k] = $value;
                return;
            }
            
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property  ' . $name . ' in ' . $trace[0]['file'] . 
                ' on line ' . 
                $trace[0]['line'], 
                E_USER_NOTICE
            );
        }
        /**
         * Undocumented function
         * 
         * @param mixed $k name of parm
         *
         * @return void
         */
        public function __get($k)
        {
            if (property_exists($this, $name)) {
                return $this->$name;
            }
            if (array_key_exists($name, $this->rawdata)) {
                return $this->rawdata[$name];
            }
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property  ' . $name . ' in ' . $trace[0]['file'] . 
                ' on line ' . 
                $trace[0]['line'], 
                E_USER_NOTICE
            );
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
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function getParents();
        /**
         * Undocumented function
         * 
         * @param object $parent parent object to assign
         *
         * @return void
         */
        abstract public function addParent($parent);
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function deleteParent($parent);
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function getSiblings();
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function addSibling();
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function deleteSibling();
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function getChildren();
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function addChild($child);
        /**
         * Undocumented function
         *
         * @return void
         */
        abstract public function deleteChild($child);
        
    }
}
?>
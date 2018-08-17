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
        protected $basetype;

        protected $id;
        protected $attributes=[];
        protected $rawdata=[];
        protected $allowedParents=[];
        protected $allowedChildren=[];
        protected $allowedSiblings=[];
        protected $createdon;
        protected $updatedon;
        
        /**
         * Parent Constsructor function
         *
         * @param sting $basetype type or name of models
         * 
         * @return void
         */
        public function __construct(string $basetype)
        {
            $this->basetype = $basetype;
              
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function jsonSerialize()
        {
            $r = $this->rawdata;
            
            if (!is_null($this->id)) {
                $r['id'] = $this->id;
            }
            if (!is_null($this->createdon)) {
                $r['createdon']= $this->createdon;
            }
            if (!is_null($this->updatedon)) {
                $r['updatedon']= $this->updatedon;
            }
            //print_r($r);
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
            if (array_key_exists($k, $this->rawdata) 
                || in_array($k, $this->attributes) 
            ) {
                $this->rawdata[$k] = $v;
                return;
            } 
            
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property  ' . $k . ' in ' . $trace[0]['file'] . 
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
            if (property_exists($this, $k)) {
                return $this->$name;
            }
            if (array_key_exists($k, $this->rawdata)) {
                return $this->rawdata[$k];
            }
            $trace = debug_backtrace();
            trigger_error(
                'Undefined property  ' . $k . ' in ' . $trace[0]['file'] . 
                ' on line ' . 
                $trace[0]['line'], 
                E_USER_NOTICE
            );
        }
        /**
         * FIND function
         *
         * @param [array] $keyval  array of all options
         * @param [array] $orderby sort order of results
         * @param [array] $groupby grouping order of results
         * @param [array] $limit   limit the results
         *
         * @return void
         */
        public function find(
            array $keyval=[], array $orderby=[],array $groupby=[],array $limit=[]
        ) {
            //TODO Add orderby and group options
            $basetype = $this->basetype;
            if (@count($keyval) > 0) { 
                $stmts = [];
                $slots = [];

                foreach ($keyval as $k=>$v) {
                    switch($k) {
                    case 'id':
                    case 'createdon':
                    case 'updatedon':
                        array_push($stmts, "`$k` ".$v[0]."':$k'");
                        $slots[":$k"] = $v[1];
                        break;
                    default :
                        array_push($stmts, "`rawdata`->>'$.$k' ".$v[0]." :$k ");
                        $slots[":$k"] = $v[1];
                        break;
                    }

                }
                $sql = "select 
                JSON_SET(`rawdata`,
                '$.id',`id`,
                '$.createdon',`createdon`,
                '$.updatedon',`updatedon`) as 'obj'
                from `$basetype` where ".implode($stmts, " and ");
                echo "<br><div>$sql</div><br>";
                $collection = R::getAll($sql, $slots);
                array_walk(
                    $collection, function (&$obj, $k) {
                        $obj = json_decode($obj['obj'], true);
                    }
                );
                return $collection;
            }
            return []; 
        }
        /**
         * GETALL function
         *
         * @return void
         */
        protected function save()
        {
            R::begin();         
            try {
                if ($this->id > 0) {
                    $bean = R::dispense($this->basetype, $this->id);
                } else {
                    $bean = R::dispense($this->basetype);
                }                
                $bean->rawdata = $this->rawdata;
                $this->id = R::store($bean);
                R::commit();
            }
            catch(Exception $e){
                R::rollback();
                return false;
            }
            return true;
        }
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
         * @param object $parent object to unassign
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
         * @param object $child to remove
         *
         * @return void
         */
        abstract public function addChild($child);
        /**
         * Undocumented function
         * 
         * @param object $child to remove
         *
         * @return void
         */
        abstract public function deleteChild($child);
        
    }
}
?>
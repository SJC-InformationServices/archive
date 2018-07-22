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
    use \sjcArchive\Models as Models;
    use \sjcArchive\Modules as Mods;
    use \sjcArchive\Repositories\Manager as EM;
    use \RedBeanPHP\R as R;
     /**
      * Definition class for API requests
      * 
      * @category Application
      * @package  APIE
      * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
      * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
      * @link     http://url.com
      */
    class Definition Extends Models\Base
    {
        /**
         * Undocumented function
         *
         * @param string $name name of defintion
         * 
         * @return void
         */
        public function __construct(string $name=null)
        {
            Parent::__construct(null, null, null);
            R::selectDatabase('default');
            
            if (!is_null($name)) {
                $rec = $this->find(["name"=>["=","$name"]]);
                
                $this->id = @$rec['id'];
                $this->rawdata = @$rec['rawdata'];
                $this->createdon = @$rec['createdon'];
                $this->updatedon = @$rec['updatedon'];
            }
        }
        /**
         * Undocumented function
         *
         * @param [type] $name  name of attribute
         * @param [type] $value value of attribute
         * 
         * @return mixed
         */
        public function __set($name, $value)
        {
            if (array_key_exists($name, $this->rawdata)) {
                $this->rawdata[$name] = $value;
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
         * @param [type] $name name of attribute to get
         * 
         * @return void
         */
        public function __get($name)
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
         * @param [array] $keyval array of key value pairs ["name"=>["=","test"],"]
         * 
         * @return void
         */
        public function find($keyval=[])
        {
            
            if (@count($keyval > 0)) {
                $stmts = [];
                $slots = [];
                foreach ($keyval as $k=>$v) {                    
                    array_push($stmts, "upper(`$k`) ".$v[0]."':$k'");
                    $slots[":$k"] = strtoupper($v[1]);
                }
                $test = R::findAll('entitydefinitions', ' name = ?', [$name]);
                $sql = "select `id`,`rawdata`,`createdon`,`updatedon` 
                from `entitydefinitions` where ".implode($stmts, " and ") .
                " order by `name`";
                $results = R::getAll($sql, $slots);
            } else {
                $sql = "select `id`,`rawdata`,`createdon`,`updatedon` 
                from `entitydefinitions` order by `name`";
                $results = R::getAll($sql);
            }
            
            /*array_walk(
                $results, 
                function (&$value, $key ) {
                    $value['rawdata'] = JSON_DECODE($value['rawdata']); 
                }
            );*/
            return $results;
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function save()
        {
            if ($this->id > 0) {
                $this->update();
            }
            R::selectDatabase('default');
            R::begin();            
            try {
                $b = R::exec(
                    'insert into `entitydefinitions` 
                    (`rawdata`) values (:raw)', [':raw'=>$this->rawdata]
                );
                R::selectDatabase('datadb');
                $this->createTable($rawdata['name']);
                R::commit();
                R::selectDatabase('default');
                $this->id = R::getInsertId();
                return $this->id;
            }
            catch(Exception $e){
                R::rollback();
                return false;
            }
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function delete()
        {
            //TODO: Enable delete and backup of existing data and relations
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        private function _update()
        {
            //TODO: Changes to EntityDefinition rename tables and relations
        }
        /**
         * Undocumented function
         *
         * @param \sjcArchive\Models\Manager\Definition $ed the 
         * 
         * @return void
         */
        public function addParent(\sjcArchive\Models\Manager\Definition $ed)
        {

        }
        /**
         * Undocumented function
         *
         * @param \sjcArchive\Models\Manager\Definition $ed the
         * 
         * @return void
         */
        public function addChild(\sjcArchive\Models\Manager\Definition $ed)
        {

        }
        /**
         * Undocumented function
         *
         * @param \sjcArchive\Models\Manager\Definition $ed the
         * 
         * @return void
         */
        public function addSibling(\sjcArchive\Models\Manager\Definition $ed)
        {

        }
        
        

    }

    
}
?>
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
        protected $attributes =  ['name'];
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
        public function getParents()
        {
            
        }
        /**
         * Undocumented function
         *
         * @param Defintion $parent - add parent defintions
         * 
         * @return void
         */
        public function addParent(sjcArchive\Models\Manager\Defintion $parent) 
        {

        }
        /**
         * Undocumented function
         * 
         * @param Defintion $parent - add parent defintions
         *
         * @return void
         */
        public function deleteParent(sjcArchive\Models\Manager\Defintion $parent) 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function getSiblings() 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function addSibling() 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function deleteSibling() 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function getChildren() 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function addChildren() 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function deleteChildren() 
        {

        }
        
        

    }

    
}
?>
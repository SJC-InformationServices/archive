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
            Parent::__construct("entitydefinitions");
            
            array_push($this->attributes, "name");
            $this->name = $name;
            //print_r($this->rawdata);
            if (!is_null($name)) {
                $rec = $this->find(["name"=>["=","$name"]]);
                $rec = $rec[0];
                foreach ($rec as $k=>$v) {
                    $this->$k = $v;
                }
            }
            echo $this->name;
            
        }
           
        
        /**
         * Undocumented function
         *
         * @return void
         */
        public function save()
        {
            $type = $this->type;
            $obj = JSON_ENCODE($this);
            if ($this->id > 0) {
                $this->_update();
            }
            R::selectDatabase('default');
            R::begin();         
            try {
                $b = R::exec(
                    "insert into `$type` 
                    (`rawdata`) values (:raw)", [':raw'=>$this->rawdata]
                );
                R::selectDatabase('datadb');
                $this->createTable($type);
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
        private function _update() 
        {

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
        public function addParent($parent) 
        {

        }
        /**
         * Undocumented function
         * 
         * @param Defintion $parent - add parent defintions
         *
         * @return void
         */
        public function deleteParent($parent) 
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
        public function addChild($child) 
        {

        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function deleteChild($child) 
        {

        }
        
        

    }

    
}
?>
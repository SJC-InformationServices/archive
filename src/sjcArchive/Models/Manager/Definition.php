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
        use db\Definition;
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
            $this->attributes = array_merge(
                $this->attributes, [
                    "name","type","configs","uuid","indexes"
                ]
            );
            
            if (!is_null($name)) {
                $this->name = $name;

                $rec = $this->find(["name"=>["=","$name"]]);
                if (count($rec) == 1) {
                    $rec = $rec[0];
                    foreach ($rec as $k=>$v) {
                        $this->$k = $v;
                    }
                }
            }
            
        }
           
        
        /**
         * Undocumented function
         *
         * @return void
         */
        public function save()
        {                   
            R::selectDatabase('default');
            if ($this->id > 0) {
                return $this->_update();
            }           
            try {
                Parent::save();
                $this->createTable($this->name);
            }
            catch(Exception $e)
            {
                return false;
            }
            return $this->id;
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        private function _update() 
        {
            $id = $this->id;
            $name = $this->name;
            $fr = R::findOne($this->basetype, ' id = ? ', [$this->id]);
            $total = R::count($this->basetype, ' name =? ', [$this->name]);   
            //Parent::save();
            if ($fr->name != $this->name ) {
                $this->renameTable($fr->name, $this->name);
            }
            echo $total;            
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
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
namespace sjcArchive\Modules
{
    use \sjcArchive\Models; 
    use \RedBeanPHP\R as R;

    /**
     * Abstract base class for API requests
     * 
     * @category Application
     * @package  APIE
     * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
     * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
     * @link     http://url.com
     */
    class Manager extends Base 
    {
        use Archivedb;
        public $results = [];
        /**
         * EntityManage Constructor Function
         *
         * @param [type] $request request url sepearted by /
         */
        public function __construct(string $request)
        {
            parent::__construct($request);
            
            //$em = new \sjcArchive\Models\Entitydefinitions();
            
            R::setAutoResolve(true);
            R::useJSONFeatures(true);
            $db = ARCHIVEDB;
                
            $h = $db['server'];
            $d = $db['db'];
            $u = $db['uid'];
            $p = $db['pwd'];
            R::setup(
                "mysql:host=$h;dbname=$d",
                $u,
                $p,
                0
            );
                
            $db2 = DATADB;
            $h2 = $db2['server'];
            $d2 = $db2['db'];
            $u2 = $db2['uid'];
            $p2 = $db2['pwd'];
          
            R::addDatabase(
                "datadb",
                "mysql:host=$h2;dbname=$d2",
                $u2,
                $p2,
                0
            );           
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function manage()
        {            
            switch($this->method)
            {
            case 'GET':
                $this->_handleGetRequest();
                break;
            case 'PUT':
                $this->_handlePutRequest();           
                break;
            case 'POST':
            case 'PATCH':
                
                break;
            case 'DELETE':
                
                break;
            } 
            
            return $this->results;                
           
            
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        private function _handleGetRequest()
        {
            $emName = is_null($this->verb) ? null:$this->verb;
            $emAttrib = isset($this->args[0]) ? $this->args[0] : null;
                
            $em = New Base();
            $em->read($emName);
            $emObj = $em->ed;

            if (!is_null($emAttrib)) {                    
                switch($emAttrib)
                {
                case 'relations':
                    if (isset($emObj[$emName]['relations'])) {
                            $this->results= $emObj[$emName]['relations'];
                    }
                    break;
                case 'parents':
                    if (isset($emObj[$emName]['relations']['parents'])) {
                        $this->results= $emObj[$emName]['relations']['parents']; 
                    }                       
                    break;
                case 'children':
                    if (isset($emObj[$emName]['relations']['children'])) {
                            $this->results= $emObj[$emName]['relations']['children'];
                    }
                    break;
                case 'siblings':
                    if (isset($emObj[$emName]['relations']['siblings'])) {
                        $this->results = $emObj[$emName]['relations']['siblings'];
                    }
                    break;
                case 'attributes':
                    if (isset($emObj[$emName]['attributes'])) {
                            $this->results= $emObj[$emName]['attributes'];
                    }
                    break;
                }
            } else {
                    $this->results=$emObj;
            }
            
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        private function _handlePutRequest()
        {
            $records =[];
            if (!is_null($this->verb) && $this->verb !== '') {
                    $data = json_decode($this->file) 
                    ? json_decode($this->file, true) : [[]];
                    /*$rec = $data['0'];
                    $rec['name'] = $this->verb;
                    $records = $rec;*/
                    return [];
            } else {
                $data = json_decode($this->file) 
                    ? json_decode($this->file, true) : [[]];
                foreach ($data as $k=>$v) {
                    $v['name']=$k;
                    array_push($records, $v);
                }
            }
            if (!isset($this->args[0])) {
                $defs = [];
                foreach ($records as $r) {
                    $edef = New Base();
                    $edef->create($r);
                    array_push($defs, $edef);
                    array_push($this->results, $edef->ed);
                }        
                foreach ($defs as $em) {
                    $parents = isset($em->ed['parents']) ? 
                    $this->_handleParents($em) : [];

                    $children = isset($em->ed['children']) ? 
                    $this->_handleChildren($em) : [];

                    $siblings = isset($em->ed['siblings']) ? 
                    $this->_handleAttribs($em) : [];

                    $attribs = isset($em->ed['attribs']) ? 
                    $this->_handleAttribs($em) : [];
                   
                    $indexes = isset($em->ed['indexes']) ? 
                    $this->_handleAttribs($em) : [];
                }
            } else {
                switch ($this->args[0]) {
                case 'parents':
                                       
                    break;
                case 'children':
                   
                    break;
                case 'siblings':
                   
                    break;
                case 'attributes':
                
                    break;
                case 'indexes':
                    break;
                default:
                    break;
                }
            }
            
        }
        /**
         * Undocumented function
         *
         * @param [object] $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleParents($ed) 
        {
            foreach ($ed['parents'] as $k=>$p) {
                $parents = New Parents();
                $parents->ed = $ed;
                $p['name'] = $k;
                $parents->create($p);
            }
        }   
        /**
         * Undocumented function
         *
         * @param [object] $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleChildren($ed) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [object] $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleSiblings($ed) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [object] $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleAttributes($ed) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [object] $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleIndexes($ed) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [array] $eda entity definition to update
         * @param [array] $edb new entity definition
         * 
         * @return void
         */
        private function _handleReplace($eda, $edb)
        {

        }
    }
}
?>
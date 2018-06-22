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
namespace sjcArchive\EntityManager 
{
    use \sjcArchive\{Modules,Modles}; 
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
    class Manager extends Modules\Base 
    {
        use Modules\Archivedb;
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
                $em = New Base();
                $em->read($this->verb);
                $emObj = $em->ed;
            if (isset($this->args[0])) {                    
                switch($this->args[0])
                {
                case 'parents':
                    if (isset($emObj[0]['relations']['parents'])) {
                        $this->results= $emObj[0]['relations']['parents']; 
                    }                       
                    break;
                case 'children':
                    if (isset($emObj[0]['relations']['children'])) {
                            $this->results= $emObj[0]['relations']['children'];
                    }
                    break;
                case 'siblings':
                    if (isset($emObj[0]['relations']['siblings'])) {
                        $this->results = $emObj[0]['relations']['siblings'];
                    }
                    break;
                case 'attributes':
                    if (isset($emObj[0]['attributes'])) {
                            $this->results= $emObj[0]['attributes'];
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
                    $rec = $data['0'];
                    $rec['name'] = $this->verb;
                    $records = $rec;
            } else {
                $data = json_decode($this->file) 
                    ? json_decode($this->file, true) : [[]];
                foreach ($data as $k=>$v) {
                    $v['name']=$k;
                    array_push($records, $v);
                }
            }
            foreach ($records as $r) {    
                if (!isset($this->args[0])) {
                    $em = New Base();
                    $em->create($r);
                    
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

                    array_push($results, $em->ed);
                    
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
                    }
                }
            }       
        }
        /**
         * Undocumented function
         *
         * @param [object]  $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleParents($ed) 
        {
            foreach($ed['parents'] as $k=>$p)
            {
                $parents = New Parents();
                $parents->ed = $ed;
                $p['name'] = $k;
                $parents->create($p);
            }
        }   
        /**
         * Undocumented function
         *
         * @param [object]  $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleChildren($ed) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [object]  $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleSiblings($ed) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [object]  $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleAttributes($ed, array $parents) 
        {

        }
        /**
         * Undocumented function
         *
         * @param [object]  $ed entity definition to assign parents
         * 
         * @return void
         */
        private function _handleIndexes($ed, array $parents) 
        {

        }
    }
}
?>
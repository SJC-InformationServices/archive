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
        public $em;
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
                1
            );           
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function manage()
        {            
                       
            $results = [];

            switch($this->method)
            {
            case 'GET':
                $em = New Base();
                $em->read($this->verb);
                $emObj = $em->ed;
                if (isset($this->args[0])) {                    
                    switch($this->args[0])
                    {
                    case 'parents':
                        if (isset($emObj[0]['relations']['parents'])) {
                            $results = $emObj[0]['relations']['parents']; 
                        }                       
                        break;
                    case 'children':
                        if (isset($emObj[0]['relations']['children'])) {
                            $results = $emObj[0]['relations']['children'];
                        }
                        break;
                    case 'siblings':
                        if (isset($emObj[0]['relations']['siblings'])) {
                            $results = $emObj[0]['relations']['siblings'];
                        }
                        break;
                    case 'attributes':
                        if (isset($emObj[0]['attributes'])) {
                            $results = $emObj[0]['attributes'];
                        }
                        break;
                    }
                } else {
                    $results=$emObj;
                }    
                break;
            case 'PUT':
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
                    switch($this->args[0]) {
                    case 'parents':
                                           
                        break;
                    case 'children':
                       
                        break;
                    case 'siblings':
                       
                        break;
                    case 'attributes':
                        
                        break;
                    default:
                        $em = New Base();
                        $em->read($r['name']);
                        if (is_null($em->eb)) {
                            $em->eb = $r;
                            $em->create($r);
                        } else {
                            array_push($results,$em->$eb);
                        }
                        break;
                    }   
                }
                
                break;
            case 'POST':
            case 'PATCH':
                
                break;
            case 'DELETE':
                
                break;
            } 
            
            return $results; 
                 
           
            
        }    
        
    }
}
?>
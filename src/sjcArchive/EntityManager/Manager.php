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
            
            $this->em = new \sjcArchive\Models\EntityDefinition();
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
            if (DEBUG) {
                R::fancyDebug(true);
            }           
            $name = $this->verb;
            $data = R::findOne(
                'entitydefinition', 
                ' `name` ',
                'seasons'
            );           
            print_r($data);
            print_r($this);
                     
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        public function manage()
        {
            
            if (!isset($this->args[0]) ) {
                $em = $this->em;
                $r = New Base();
            } else {
                switch($this->args[0]) 
                {
                case 'parents':
                    $r = new Parents();
                    break; 
                case 'children':
                    $r = new Children();
                    break;
                case 'sibling':
                    $r = new Siblings();
                    break;
                case 'attributes':
                    $r = new Attributes();
                    break;
                default:
                    $r = New Base();
                    break;
                }
            }
            $record=[$this->method];
            switch($this->method)
            {
            case 'GET':
                $record = $r->read($this->em);
                break;
            case 'PUT':
                $record = $r->create($this->em);
                break;
            case 'POST':
            case 'PATCH':
                $record = $r->update($this->em);
                break;
            case 'DELETE':
                $record = $r->delete($this->em);
                break;
            }   
            return $record;
        }    
        
    }
}
?>
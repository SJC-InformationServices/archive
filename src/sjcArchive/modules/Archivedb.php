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

namespace sjcArchive\Modules{
    use \RedBeanPHP\R as R;

    /**
     * This is MainClass for All DB Configs
     *
     * @category Application
     * @package  Request
     * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
     * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
     * @link     http://pear.php.net/package/PackageName
     */

    class Archivedb
    {

        private $_cfgconn;
        private $_dataconn;

        /**
         * Init database for Redbean ORM
         *
         * @param boolean $cfgfrozen  - lock the schema from changes
         * @param boolean $datafrozen - lock the schema from changes
         *
         * @return void
         */
        public function __CONSTRUCT(int $cfgfrozen=1, int $datafrozen=1)
        {
            try {
                $this->_cfgconn = $cfgconn;
                $this->_dataconn = $datadb;
                $db = ARCHIVEDB;
                $h = $db['server'];
                $d = $db['db'];
                $u = $db['uid'];
                $p = $db['pwd'];
                R::setup(
                    "mysql:host=$h;dbname=$d",
                    $u,
                    $p,
                    $cfgfrozen
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
                    true,
                    $datafrozen
                );
                return true;
            } catch (exception $e) {
                return $e->message();
            }
        }
    }
}

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
namespace sjcArchive\EntityManager {
    /**
     * Abstract base class for API requests
     * 
     * @category Application
     * @package  API
     * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
     * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
     * @link     http://url.com
     */
    class Manager extends Modules\Base 
    {
        private $_entityDefinition;
        
        /**
         * Entity __constructor function 
         *
         * @param string $request 
         */
        public function __construct($request)
        {

            parent::__construct($request);
        }
        /**
         * Undocumented function
         *
         * @param [type] $attrib fetchign which attribute
         * @return void
         */
        function __get($attrib) 
        {
            echo "get $attrib";
        }
        /**
         * Undocumented function
         *
         * @param [type] $attrib
         * @param [type] $value
         */
        public function __set($attrib, $value)
        {
            echo "set $attrib";
        }
        /**
         * Undocumented function
         *
         * @return void
         */
        function __call($method,$arguments)
        {
            echo "$method called";
            return [$method,$arguments];
        }


    }
}
?>
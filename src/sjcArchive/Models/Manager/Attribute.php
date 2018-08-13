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
    use \sjcArchive\Modules as Mods;
    use \sjcArchive\Repositories\Manager as EM;
    use \RedBeanPHP\R as R;
    /**
     * Attribute model for API requests
     * 
     * @category Application
     * @package  APIE
     * @author   Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
     * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
     * @link     http://url.com
     */
    class Attribute Extends EM\Config Implements EM\Contracts\Define 
    {
        private $_definition;
        private $_label;
        public $name;
        public $type="text";
        public $default=null;
        public $index;
        public $editable=true;
        public $visible=true;

        /**
         * Undocumented function
         *
         * @param Models\Definitions $def the entity definition to add an attribute 
         */
        public function __construct(Models\Definitions $def, string $name=null)
        {
            $this->_definition = $def;
            if (array_has_key($def)) {

            }
        }

    }
}
?>
<?php
/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Tool
 * @package    ExportingAlphaArchive
 * @author     Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */
header('Content-type: Text/HTML; Charset=UTF-8');
ini_set('memory_limit', '512M');
ini_set('default_charset', 'utf-8');
ini_set('max_execution_time', 0);

try {
    $prod = new MySqli(
        "sjcthearchive.cb1qb4plxjpf.us-east-1.rds.amazonaws.com",
        "SJCarchiveAdmin",
        '5jcAdmin!',
        'alphabrodermaster',
        '3306'
    );
    $dev = new MySqli(
        "sjc-archive-dev.cluster-cpi3jpipzm32.us-east-1.rds.amazonaws.com",
        "sjcArchiveAdmin",
        "5jcAdmin!",
        "sjcAlphaBroderArchive",
        '3306'
    );
    $prod->set_charset("utf8mb4");
    $dev->set_charset("utf8mb4");
    $prod->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    $dev->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");$
    $seasonProjectPage=[];

    /**
     * Create a season in archvive from old archive
     *
     * @param  array $rawdata contains array like ['name'=>'2015']
     * @return ID 
     */
    function createSeason(array $rawdata)
    {
        global $seasonProjectPage,$prod,$dev;
        $id = null;

        if (!isset($seasonProjectPage[$rawdata['name']])) {
            $name = $dev->real_escape_string($rawdata['name']);
            $sql = "select `id` from `seasons` where `name` = '$name'"; 
            $qry = $dev->qry($sql);
            $results = $qry->fetch_assoc();
            if (count($results) == 1 && $results[0]['id'] > 0 ) {
                $seasonProjectPage[$rawdata['name']]['id'] = $results[0]['id'];
            } else {
                $raw = $dev->real_escape_string(json_encode($rawdata));
                $insSql = "insert into `seasons` values ('$raw')";
                if (!$insQry = $dev->qry($insSql) ) {
                    return false;
                } else {
                    $seasonProjectPage[$rawdata['name']]['id'] = $dev->insert_id;
                }

            }
        } 
        return $seasonProjectPage[$rawdata['name']]['id'];
    }
    /**
     * CreateProject create project records in new archive db
     *
     * @param  integer $sid sid the season id for the project insert
     * @param  array $rawdata rawdata array of values to insert
     * @return void
     */
    function createProject(int $sid, array $rawdata) 
    {
        
    }
    /**
     * Undocumented function
     *
     * @param array $rawdata
     * @return void
     */
    function createPage(array $rawdata)
    {

    }
    /**
     * Undocumented function
     *
     * @param array $rawdata
     * @return void
     */
    function createOffer(array $rawdata)
    {

    }
    $getSql = "SELECT * FROM `masterdataview` where 
    `catalog_name` not like '%Hang%Tags%' and 
    `page` is not null and 
    `page` <> 'delete' 
    group by `season`, `catalog_name`, `page`, `style` 
    order by `season`, `catalog_name` limit 10";
    $getQry = $prod->query($getSql);
    while ($d = $getQry->fetch_assoc()) {
        $sid = createSeason(array('name'=>$d['season']));
        echo $sid."<br>";
        echo json_encode($d)."<br>";
         
    }

}
catch(execption $e)
{
    print_r($e);
}
?>
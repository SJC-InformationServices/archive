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
header('Content-type: application/json');
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
        global $seasonProjectPage,$prod,$dev,$output;
        $id = null;
        if (!isset($seasonProjectPage[$rawdata['name']])) {
            $name = $dev->real_escape_string($rawdata['name']);
            $sql = "select `id` from `seasons` where `name` = '$name'";
            //array_push($output, $sql); 
            $qry = $dev->query($sql);
            $results = $qry->fetch_assoc();
            //array_push($output, $results);
            if (count($results) == 1 && $results['id'] > 0 ) {
                $seasonProjectPage[$rawdata['name']]['id'] = $results['id'];
                $seasonProjectPage[$rawdata['name']]['projects']=[];
            } else {
                $raw = $dev->real_escape_string(json_encode($rawdata));
                $insSql = "insert into `seasons` (`rawdata`) values ('$raw')";
                //array_push($output, $insSql);
                if (!$insQry = $dev->query($insSql) ) {
                    return "FAILED";
                } else {
                    $seasonProjectPage[$rawdata['name']]['id'] = $dev->insert_id;
                    $seasonProjectPage[$rawdata['name']]['projects']=[];
                }

            }
        } 
        return $seasonProjectPage[$rawdata['name']]['id'];
    }
    /**
     * CreateProject create project records in new archive db
     *
     * @param  string $sid sid the season id for the project insert
     * @param  array $rawdata rawdata array of values to insert
     * @return void
     */
    function createProject(string $season, array $rawdata) 
    {
        global $seasonProjectPage,$prod,$dev,$output;

        $sid = $seasonProjectPage[$season]['id'];
        
        if (!isset($seasonProjectPage[$season]['projects'][$rawdata['name']])) {
            $name = $dev->real_escape_string($rawdata['name']);
            $sql = "select `id` from `projects` where 
            `name` = '$name' and 
            `seasons_id` = '$sid'";
             
            $qry = $dev->query($sql);
            $results = $qry->fetch_assoc();
            
            if (count($results) == 1 && $results['id'] > 0 ) {
                $seasonProjectPage
                [$season]['projects']
                [$rawdata['name']]['id'] = $results['id'];
                $seasonProjectPage
                [$season]['projects']
                [$rawdata['name']]['pages']=[];
                $seasonProjectPage
                    [$season]['projects']
                    [$rawdata['name']]['offers']=[];
            } else {
                $raw = $dev->real_escape_string(json_encode($rawdata));
                $insSql = "insert into `projects` 
                (`rawdata`,`seasons_id`) 
                values 
                ('$raw', '$sid')";
                
                if (!$insQry = $dev->query($insSql) ) {
                    array_push($output, $dev->error);
                    array_push($output, $insSql);
                    return false;
                } else {
                    $seasonProjectPage
                    [$season]['projects']
                    [$rawdata['name']]['id'] = $dev->insert_id;
                    $seasonProjectPage
                    [$season]['projects']
                    [$rawdata['name']]['pages']=[];
                    $seasonProjectPage
                    [$season]['projects']
                    [$rawdata['name']]['offers']=[];
                }

            }
        } 
        return  $seasonProjectPage[$season]['projects'][$rawdata['name']]['id'];
    }
    /**
     * Undocumented function
     *
     * @param array $rawdata
     * @return void
     */
    function createPage(string $season,string $project,array $rawdata)
    {
        global $seasonProjectPage,$prod,$dev,$output;

        $sid = $seasonProjectPage[$season]['id'];
        
        $projects = $seasonProjectPage[$season]['projects'][$project];
        
        $pid = $projects['id'];

        $pages = $projects['pages'];
        $pgfr = $rawdata['pagefrom'];
        $raw = $dev->real_escape_string(JSON_ENCODE($rawdata));

        $sql = "insert ignore into `pages` 
        (`rawdata`,`projects_id`) values ('$raw', '$pid' )";
        
        $ins = $dev->query($sql);
        if (!$ins) {
            return [$sql,$dev->error];
        } else {
            array_push( 
                $seasonProjectPage[$season]['projects'][$project]['pages'], 
                $rawdata['pagefrom'] 
            );
            return $dev->insert_id;
        }
    }
    /**
     * Undocumented function
     *
     * @param array $rawdata
     * @return void
     */
    function createOffer($season,$project,array $rawdata)
    {
        global $seasonProjectPage,$prod,$dev,$output;

        $sid = $seasonProjectPage[$season]['id'];
        
        $projects = $seasonProjectPage[$season]['projects'][$project];
        
        $pid = $projects['id'];

        $raw = $dev->real_escape_string(JSON_ENCODE($rawdata));

        $sql = "insert ignore into `offers` 
        (`rawdata`,`projects_id`) values ('$raw', '$pid' )";
        
        $ins = $dev->query($sql);
        if (!$ins) {
            return $dev->error;
        }return $dev->insert_id;
    }
    $output = [];
    $getSql = "SELECT * FROM `masterdataview` where 
    `catalog_name` not like '%Hang%Tags%' and 
    `page` is not null and 
    `page` <> 'delete' 
    group by `season`, `catalog_name`, `page`, `style` 
    order by `season`, `catalog_name` limit 100";
    $getQry = $prod->query($getSql);
    while ($d = $getQry->fetch_assoc()) {
        //array_push($output, json_encode($d));
        $s = $d['season'];
        $c = $d['catalog_name'];
        $p = $d['page'];
        $d['pagefrom'] = $p;
        $d['abstyle'] = $d['style'];
        $d['garmentfit'] = $d['womens_fit'];
        $d['category'] = $d['b2b_category'];
        $d['sizegroup'] = $d['size_group'];
        $d['sizerange'] = $d['b2b_size_group'];

        $sobj = array("name"=>$d['season']);
        $sid = createSeason(['name'=>$d['season']]);
        array_push($output, ["seasonid"=>$sid]);

        if ($sid) {
            $pid = createProject($s, ['name'=>$d['catalog_name']]);
            array_push($output, ["projectid"=>$pid]);
            if ($pid) {
                $pgid = createPage($s, $c, ['pagefrom'=>$p]);
                array_push($output, $pgid);
                $offerid = createOffer($s, $c, $d);
                array_push($output, $offerid);
            }
        }
        
        

        
         
    }
    array_push($output, $seasonProjectPage);
    echo json_encode($output);
    

}
catch(execption $e)
{
    print_r($e);
}
?>
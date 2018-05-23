<?php
require_once "vendor/autoload.php";
ini_set('memory_limit','512M');
use \RedBeanPHP\R as R;

 R::setup("mysql:host=sjc-content-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com;dbname=sjcWalmartArchive;",'sjcArchiveAdmin','5jcAdmin');
 R::freeze(TRUE);
 R::addDatabase("prod","mysql:host=10.2.1.142;dbname=archive",'kevin.noseworthy','Knoseworthy0808!',TRUE);

 
/**
 * Select Each Season From Current Archive
 */



R::selectDatabase('prod') ;
$qry = 'select `year` from `offers` where `year` is not null and `year` <> "" group by `year`';
$seasons = R::getAll($qry);
foreach($seasons as $s)
{
    $sobj = json_encode($s);
    $season = $s['year'];

    /**
     * Select Project/Campaign From Current Archive
     */
    R::selectDatabase('prod');
    $qry = "select `campaign` from `offers` where `year` = '$season' group by `campaign`";
    $projects = R::getAll($qry);
    foreach($projects as $p)
    {
        $project = $p['campaign'];

        R::selectDatabase('prod');
        $qry = "select `page` from `offers` where `year` = '$season' and `campaign` = '$project' group by `page`";
        $pages = R::getAll($qry);
            
            foreach($pages as $pg)
            {
                $page = $pg['page'];
                echo "$season $project $page<br>";

                R::selectDatabase('prod');
                $qry = "select * from `offers` where `year` = '$season' and `campaign` = '$project' group by `page`";
                $pages = R::getAll($qry);


            }          
        }
    }


?>
<?php
error_reporting(E_ALL & ~E_NOTICE); ini_set('display_errors', '1');
require_once "vendor/autoload.php";

header('Content-type: Text/HTML; Charset=UTF-8');
ini_set('memory_limit','512M');
ini_set('default_charset', 'utf-8');
ini_set('max_execution_time', 0);

?>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
</head>
<body>
<div>Start</div>
<?php
try {
    $prod = new MySqli("sjcthearchive.cb1qb4plxjpf.us-east-1.rds.amazonaws.com", "SJCarchiveAdmin", '5jcAdmin!', 'alphabrodermaster', '3306');
    $dev = new MySqli("sjc-archive-prod.cluster-cpi3jpipzm32.us-east-1.rds.amazonaws.com", "sjcArchiveAdmin", "5jcAdmin!", "sjcAlphaBroderArchive", '3306');
    $prod->set_charset("utf8mb4");
    $dev->set_charset("utf8mb4");
    $prod->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    $dev->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    $prodTable = "`masterdataview`";
    echo "<table class='table'><thead><tr><td>year</td><td>project</td><td>page</td><td>adblock</td></tr></thead><tbody>";
    $years_sql = 'select `season` from `offers` where `season` is not null and `year` <> "" group by `year`';
    $years_qry = $prod->query($years_sql);
    while($year = $years_qry->fetch_assoc())
    {
        $season = $year['year'];
        $year['name'] = $season;
        
        if($yearobj = $dev->real_escape_string(json_encode($year)))
        {
            $insert_year_sql = "insert into `seasons` (`rawdata`) values ('$yearobj')";

            if ($insert_year_qry = $dev->query($insert_year_sql)) 
                {
                $insert_year_id = $dev->insert_id;
                
                $campaigns_sql = "select `campaign` from `offers` where 
                `year` = '$season' and 
                `year` is not null and 
                `year` <> '' and `campaign` is not null and `campaign` <> '' group by `campaign`";
                $campaigns_qry = $prod->query($campaigns_sql);
                while ($campaign = $campaigns_qry->fetch_assoc()) 
                {
                    
                    $campaignname = $campaign['campaign'];
                    $campaign['name'] = $campaignname;
                    
                    if($campaignobj = $dev->real_escape_string(json_encode($campaign)))
                    {
                        $insert_campaign_sql = "insert into `projects` (`rawdata`,`seasons_id`) values ('$campaignobj','$insert_year_id')";
                        if($insert_campaign_qry = $dev->query($insert_campaign_sql))
                        {
                            $insert_campaign_id = $dev->insert_id;   

                            $page_sql = "select `page` from `offers` where `year` = '$season' and `campaign` = '$campaignname' group by `page` order by page";
                            $page_qry = $prod->query($page_sql);
                            while($pages=$page_qry->fetch_assoc())
                            {
                                $pages['pagefrom'] = $pages['page'];
                                $pg = $pages['page'];
                                
                                if($pageobj = $dev->real_escape_string(json_encode($pages)))
                                {                                    
                                    $insert_page_sql = "insert into `pages` (`rawdata`,`projects_id`) values ('$pageobj','$insert_campaign_id')";
                                    if ($insert_page_qry = $dev->query($insert_page_sql)) {
                                        $insert_page_id = $dev->insert_id;
                                        
                                        $offers_sql = "select * from `offers` where `year` = '$season' and `campaign` = '$campaignname' and `page` = '$pg' and `status` = 'active' and `record_type` = 'featured' group by `year`,`campaign`,`page`,`adblock`,`markets`";
                                        $offers_qry = $prod->query($offers_sql);
                                        while ($offers=$offers_qry->fetch_assoc())
                                        {
                                            $offers['project_id'] = $insert_campaign_id;
                                            $offers['pagefrom'] = $offers['page'];
                                            $adblock = $offers['adblock'];
                                            echo "<tr scope='row'><td>$season</td><td>$campaignname</td><td>$pg</td><td>$adblock</td></tr>";
                                            unset($offers['entity_id']);
                                            if ($offerobj = $dev->real_escape_string(json_encode($offers))) {
                                                $insert_offers_sql = "insert into `offers` (`rawdata`,`projects_id`) values ('$offerobj','$insert_campaign_id')";
                                                if ($insert_offer_qry = $dev->query($insert_offers_sql)) {
                                                    $insert_offer_id = $dev->insert_id;
                                                    echo "<tr scope='row'><td>$insert_year_id</td><td>$insert_campaign_id</td><td>$insert_page_id</td><td>$insert_offer_id</td></tr>";
                                                }else{
                                                    $error = $dev->error;
                                                    echo "<tr scope='row'><td>$insert_year_id</td><td>$insert_campaign_id</td><td>$insert_page_id</td><td>$error</td></tr>";
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        echo "Page Error".$dev->error;
                                    }
                                }
                            }
                        }else{
                            echo "Campaign Error ".$dev->error;
                        }                        
                    }
                }
            }
        }

        
    }
    echo "</tbody></table>";
    
    $prod->close();
    $dev->close();
}
catch(exception $e){
   print_r($e);
}





?>
<div>End</div>
</body>
</html>
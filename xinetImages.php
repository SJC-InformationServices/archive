<?php
error_reporting(E_ALL & ~E_NOTICE); ini_set('display_errors', '1');
require_once "vendor/autoload.php";

header('Content-type: Text/HTML; Charset=UTF-8');
ini_set('memory_limit','512M');
ini_set('default_charset', 'utf-8');
ini_set('max_execution_time', 0);
//use \RedBeanPHP\R as R;

 /* R::setup("mysql:host=sjc-content-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com;dbname=sjcWalmartArchive;",'sjcArchiveAdmin','5jcAdmin');
 R::freeze(TRUE);
 R::addDatabase("prod","mysql:host=10.2.1.142;dbname=archive",'kevin.noseworthy','Knoseworthy0808!',TRUE);
*/
try {
    
$prod = new MySqli("10.2.1.142","kevin.noseworthy",'Knoseworthy0808!','archive','3306');
$dev = new MySqli("sjc-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com","sjcArchiveAdmin","5jcAdmin!","sjcWalmartArchive",'3306');
$xinet = new MySqli("xinet-lesmill.stjosephcontent.com","kevin.noseworthy","Knoseworthy0808!","webnative","3306");

$prod->set_charset("utf8mb4");
$dev->set_charset("utf8mb4");
$xinet->set_charset("utf8");

$prod->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
$dev->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
$xinet->query("set names utf8 collate utf8_unicode_ci");

echo "Start<br>";

$imgSql  = "
select `file`.`FileID` as 'FileID',
`Field143` as 'Item_Number',
`FileName` as 'FileName',
`FileName` as 'name',
CAST(CONCAT(substr(substr(`path`.`Path`,2),locate('/',substr(`path`.`Path`,2))+1),'/',
`file`.`FileName`) as CHAR) as 'FilePath',
CAST(CONCAT(substr(substr(`path`.`Path`,2),locate('/',substr(`path`.`Path`,2))+1),'/',`file`.`FileName`) as CHAR) as 'fullpath',
`Field7` as 'Keywords',
`Field150` as 'UPC',
FROM_UNIXTIME(`file`.`CreateDate`) as `CreateDate`,
FROM_UNIXTIME(`file`.`ModifyDate`) as `ModifiedDate`,
FROM_UNIXTIME(`file`.`AccessDate`) as 'AccessedDate' From `file`,`path`,`keyword1` 
where (`file`.`PathID` = `path`.`PathID` and `file`.`FileID` = `keyword1`.`FileID`) and (`Path` like '%Walmart%' or `Path` like '%WM_Private%')";

if( $pqry = $xinet->query($imgSql))
{
 while($image = $pqry->fetch_assoc())
 {
     echo $image['name']."<br>";
     
    if($data = json_encode($image))
    {
        $data = $dev->real_escape_string($data);
        $insert_image_sql = "insert into `attachments` (`rawdata`) values ('$data')";
        $insert_image_qry = $dev->query($insert_image_sql);
        if(!$insert_image_qry)
        {
            file_put_contents("importrecords/".$image['FileID']."-".$image['name'].".json",JSON_ENCODE($image));
        }
    }else{
        echo json_last_error();
        echo json_last_error_msg();
    }
 }
}else{
    echo $xinet->error;
    echo "errror";
}
 Echo "End";
 $prod->close();
 $dev->close();
}catch(exception $e){
    print_r($e);
}

 ?>
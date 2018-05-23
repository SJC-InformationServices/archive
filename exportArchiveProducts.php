<?php
error_reporting(E_ALL & ~E_NOTICE); ini_set('display_errors', '1');
require_once "vendor/autoload.php";

header('Content-type: Text/HTML; Charset=UTF-8');
ini_set('memory_limit','512M');


//use \RedBeanPHP\R as R;

 /* R::setup("mysql:host=sjc-content-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com;dbname=sjcWalmartArchive;",'sjcArchiveAdmin','5jcAdmin');
 R::freeze(TRUE);
 R::addDatabase("prod","mysql:host=10.2.1.142;dbname=archive",'kevin.noseworthy','Knoseworthy0808!',TRUE);
*/
try{
$prod = new MySqli("10.2.1.142","kevin.noseworthy",'Knoseworthy0808!','archive','3306');
$dev = new MySqli("sjc-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com","sjcArchiveAdmin","5jcAdmin!","sjcWalmartArchive",'3306');
$prod->set_charset('utf-8');
$dev->set_charset('utf-8');
 

echo "Start<br>";
$productSql  = "select * from (select * from `offers` where `status` <> 'delete' and (`featured_item_number` is not null or `item_name`) is not null order by `featured_item_number`,`updatedon` desc limit 100) qry1
group by `featured_item_number`,`item_name_hash`";

if( $pqry = $prod->query($productSql))
{
 while($product = $pqry->fetch_assoc())
 {
    
    $product['name'] = $product['item_name'];
    
    if($data = json_encode($product)){
        $data = $dev->real_escape_string(JSON_ENCODE($product));
        echo "Good<br>";
    }else{
        echo json_last_error();
        echo json_last_error_msg();
    }
    
    /*$insert_product_sql = "insert into `products` (`rawdata`) values ('$data')";
    $insert_product_qry = $dev->query($insert_product_sql);
    if(!$insert_product_qry){
        file_put_contents("importrecords/".$product['item_name_hash'].".json",JSON_ENCODE($product));
    }*/
 }
}else{
    echo "errror";
}
 Echo "End";
 $prod->close();
 $dev->close();
}catch(exception $e){
    print_r($e);
}

 ?>
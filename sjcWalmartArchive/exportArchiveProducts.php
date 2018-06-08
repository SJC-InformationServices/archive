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
error_reporting(E_ALL & ~E_NOTICE); ini_set('display_errors', '1');
require_once "vendor/autoload.php";

header('Content-type: Text/HTML; Charset=UTF-8');
ini_set('memory_limit', '512M');
ini_set('default_charset', 'utf-8');

//use \RedBeanPHP\R as R;

 /* R::setup("mysql:host=sjc-content-archive-dev.cpi3jpipzm32.us-east-1.rds.amazonaws.com;dbname=sjcWalmartArchive;",'sjcArchiveAdmin','5jcAdmin');
 R::freeze(TRUE);
 R::addDatabase("prod","mysql:host=10.2.1.142;dbname=archive",'kevin.noseworthy','Knoseworthy0808!',TRUE);
*/
try {
    $prod = new MySqli("10.2.1.142", "kevin.noseworthy", 'Knoseworthy0808!', 'archive', '3306');/*$dev = new MySqli("sjc-archive-prod.cluster-cpi3jpipzm32.us-east-1.rds.amazonaws.com","sjcArchiveAdmin","5jcAdmin!","sjcWalmartArchive",'3306');*/
    $dev = new MySqli(
        "sjc-archive-dev.cluster-cpi3jpipzm32.us-east-1.rds.amazonaws.com", 
        "sjcArchiveAdmin", 
        "5jcAdmin!", 
        "sjcWalmartArchive", 
        '3306'
    );
    $prod->set_charset("utf8mb4");
    $dev->set_charset("utf8mb4");
    $prod->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    $dev->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "Start<br>";
    
    $productSql  = "select * from 
    (select * from `offers` where 
    `status` <> 'delete' and 
    (`featured_item_number` is not null or
     `item_name`) is not null 
     order by `featured_item_number`,`updatedon` desc) qry1 
     group by `featured_item_number`,`item_name_hash`";
    if ($pqry = $prod->query($productSql) ) {
        while ($product = $pqry->fetch_assoc() ) {
            unset($product['entity_id']);
            $product['name'] = $product['item_name'];
            if ($data = json_encode($product)) {
                $data = $dev->real_escape_string($data);
                $insert_product_sql = "
                insert into `products` (`rawdata`) values ('$data')";
                $insert_product_qry = $dev->query($insert_product_sql);
                
                if (!$insert_product_qry) { 
                    file_put_contents(
                        "importrecords/".$product['id']."-".
                        $product['item_name_hash'].".json", 
                        JSON_ENCODE($product)
                    );
                }
        
            } else {
                echo json_last_error();
                echo json_last_error_msg();
            }
        }
    } else {
        echo "errror";
    }
    Echo "End";
    $prod->close();
    $dev->close();
}catch(exception $e){
    print_r($e);
}

    ?>
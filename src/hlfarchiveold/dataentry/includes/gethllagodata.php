<?php
header('content-type: text/html; charset=utf-8'); 
ini_set('display_errors',1);
chdir("/storage/www/hlf/includes");
include "serverconfig.php";

	$c = oci_connect("sjcreadonly", "sjcreadonly", "10.3.0.186/cmsanew", "AL32UTF8") OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');
	
//$sql = "SELECT DISTINCT(ARTANRC) FROM HIGHLANDUSER.ART ORDER BY HIGHLANDUSER.ART.ARTANRC";
$sql = "SELECT DISTINCT(ARTANRC) FROM BENTON.ART WHERE BENTON.ART.ARTKTYKEYI = 6 ORDER BY BENTON.ART.ARTANRC";
$articlequery = oci_parse($c, $sql);
oci_execute($articlequery);
$x=0;


while($info = oci_fetch_array($articlequery, OCI_ASSOC))
{
//echo "x=$x ";
$articlenumber = $info['ARTANRC'];
$q = "SELECT BENTON.ART.ARTANRC AS ARTICLENUMBER, BENTON.ART.ARTBEZC as ARTNAME, BENTON.ART.ARTDLAD as ARTDLAD,
BENTON.ART.ARTVF1C AS CATEGORY, BENTON.ART.ARTVF2C AS UNITOFSALE, BENTON.ART.ARTVF4C AS GRAPHICSLOGO, 
BENTON.ART.ARTVF9C AS MEASURE, BENTON.ART.ARTVF10C AS TEXTCOMBINED,
BENTON.ART.ARTVF7C AS TEXTLINE1, BENTON.ART.ARTVF5C AS TEXTLINE2,
BENTON.ART.ARTVF6C AS TEXTLINE3,
BENTON.PRS.PRSPRSC, BENTON.PRS.PRSVF1C, BENTON.PRS.PRSVF2C, 
BENTON.PRO.PROBEZC AS ELENAME, BENTON.KAT.KATBEZC, BENTON.KAT.KATINAC   
FROM 
BENTON.ART ART, 
BENTON.KAV KAV, 
BENTON.KVT KVT, 
BENTON.PRS PRS, 
BENTON.PRO PRO, 
BENTON.AEZ AEZ, 
BENTON.KAT KAT 
WHERE  
BENTON.KAT.KATKTYKEYI = 6
AND BENTON.ART.ARTKAVKEYI = BENTON.KAV.KAVKEYI 
AND BENTON.KAV.KAVKVTKEYI = BENTON.KVT.KVTKEYI 
AND BENTON.ART.ARTKAVKEYI = BENTON.PRS.PRSKAVKEYI 
AND BENTON.ART.ARTKEYI = BENTON.PRS.PRSARTKEYI 
AND BENTON.ART.ARTKEYI = BENTON.AEZ.AEZARTKEYI 
AND BENTON.ART.ARTKAVKEYI = BENTON.AEZ.AEZKAVKEYI 
AND BENTON.AEZ.AEZKAVKEYI = BENTON.PRO.PROKAVKEYI 
AND BENTON.AEZ.AEZPROKEYI = BENTON.PRO.PROKEYI 
AND (BENTON.KVT.KVTBEZC = 'Standard') 
AND BENTON.ART.ARTANRC='$articlenumber' 
and BENTON.KAV.KAVKEYI = BENTON.KAT.KATKAVKEYI 
AND BENTON.ART.ARTDLAD IN (SELECT MAX(BENTON.ART.ARTDLAD) FROM BENTON.ART ART, BENTON.KAV KAV, BENTON.KVT KVT, BENTON.PRS PRS, BENTON.PRO PRO, BENTON.AEZ AEZ, BENTON.KAT KAT 
WHERE  BENTON.KAT.KATSAIKEYI <> 2  AND BENTON.ART.ARTKAVKEYI = BENTON.KAV.KAVKEYI AND BENTON.KAV.KAVKVTKEYI = BENTON.KVT.KVTKEYI AND BENTON.ART.ARTKAVKEYI = BENTON.PRS.PRSKAVKEYI AND BENTON.ART.ARTKEYI = BENTON.PRS.PRSARTKEYI AND BENTON.ART.ARTKEYI = BENTON.AEZ.AEZARTKEYI 
AND BENTON.ART.ARTKAVKEYI = BENTON.AEZ.AEZKAVKEYI AND BENTON.AEZ.AEZKAVKEYI = BENTON.PRO.PROKAVKEYI AND BENTON.AEZ.AEZPROKEYI = BENTON.PRO.PROKEYI AND (BENTON.KVT.KVTBEZC = 'Standard') 
AND BENTON.ART.ARTANRC='$articlenumber' and BENTON.KAV.KAVKEYI = BENTON.KAT.KATKAVKEYI) ";

$x++;
/* ************ below is to be used at changeover to unified Lago *************

 $sql = "SELECT DISTINCT(ARTANRC) FROM BENTON.ART ORDER BY BENTON.ART.ARTANRC
 WHERE BENTON.ART.ARTKTYKEYI = 6";
$articlequery = oci_parse($c, $sql);
oci_execute($articlequery);
$x=0;


while($info = oci_fetch_array($articlequery, OCI_ASSOC))
{
//echo "x=$x ";
$articlenumber = $info['ARTANRC'];
$q = "SELECT HIGHLANDUSER.ART.ARTANRC AS ARTICLENUMBER, HIGHLANDUSER.ART.ARTBEZC as ARTNAME, HIGHLANDUSER.ART.ARTDLAD as ARTDLAD,
HIGHLANDUSER.ART.ARTVF1C AS CATEGORY, HIGHLANDUSER.ART.ARTVF2C AS UNITOFSALE, HIGHLANDUSER.ART.ARTVF4C AS GRAPHICSLOGO, 
HIGHLANDUSER.ART.ARTVF9C AS MEASURE, HIGHLANDUSER.ART.ARTVF10C AS TEXTCOMBINED,
HIGHLANDUSER.ART.ARTVF7C AS TEXTLINE1, HIGHLANDUSER.ART.ARTVF5C AS TEXTLINE2,
HIGHLANDUSER.ART.ARTVF6C AS TEXTLINE3,
HIGHLANDUSER.PRS.PRSPRSC, HIGHLANDUSER.PRS.PRSVF1C, HIGHLANDUSER.PRS.PRSVF2C, 
HIGHLANDUSER.PRO.PROBEZC AS ELENAME, HIGHLANDUSER.KAT.KATBEZC, HIGHLANDUSER.KAT.KATINAC   
FROM 
HIGHLANDUSER.ART ART, 
HIGHLANDUSER.KAV KAV, 
HIGHLANDUSER.KVT KVT, 
HIGHLANDUSER.PRS PRS, 
HIGHLANDUSER.PRO PRO, 
HIGHLANDUSER.AEZ AEZ, 
HIGHLANDUSER.KAT KAT 
WHERE  
HIGHLANDUSER.KAT.KATSAIKEYI <> 2 
AND HIGHLANDUSER.ART.ARTKAVKEYI = HIGHLANDUSER.KAV.KAVKEYI 
AND HIGHLANDUSER.KAV.KAVKVTKEYI = HIGHLANDUSER.KVT.KVTKEYI 
AND HIGHLANDUSER.ART.ARTKAVKEYI = HIGHLANDUSER.PRS.PRSKAVKEYI 
AND HIGHLANDUSER.ART.ARTKEYI = HIGHLANDUSER.PRS.PRSARTKEYI 
AND HIGHLANDUSER.ART.ARTKEYI = HIGHLANDUSER.AEZ.AEZARTKEYI 
AND HIGHLANDUSER.ART.ARTKAVKEYI = HIGHLANDUSER.AEZ.AEZKAVKEYI 
AND HIGHLANDUSER.AEZ.AEZKAVKEYI = HIGHLANDUSER.PRO.PROKAVKEYI 
AND HIGHLANDUSER.AEZ.AEZPROKEYI = HIGHLANDUSER.PRO.PROKEYI 
AND (HIGHLANDUSER.KVT.KVTBEZC = 'Standard') 
AND HIGHLANDUSER.ART.ARTANRC='$articlenumber' 
and HIGHLANDUSER.KAV.KAVKEYI = HIGHLANDUSER.KAT.KATKAVKEYI 
AND HIGHLANDUSER.ART.ARTDLAD IN (SELECT MAX(HIGHLANDUSER.ART.ARTDLAD) FROM HIGHLANDUSER.ART ART, HIGHLANDUSER.KAV KAV, HIGHLANDUSER.KVT KVT, HIGHLANDUSER.PRS PRS, HIGHLANDUSER.PRO PRO, HIGHLANDUSER.AEZ AEZ, HIGHLANDUSER.KAT KAT 
WHERE  HIGHLANDUSER.KAT.KATSAIKEYI <> 2  AND HIGHLANDUSER.ART.ARTKAVKEYI = HIGHLANDUSER.KAV.KAVKEYI AND HIGHLANDUSER.KAV.KAVKVTKEYI = HIGHLANDUSER.KVT.KVTKEYI AND HIGHLANDUSER.ART.ARTKAVKEYI = HIGHLANDUSER.PRS.PRSKAVKEYI AND HIGHLANDUSER.ART.ARTKEYI = HIGHLANDUSER.PRS.PRSARTKEYI AND HIGHLANDUSER.ART.ARTKEYI = HIGHLANDUSER.AEZ.AEZARTKEYI 
AND HIGHLANDUSER.ART.ARTKAVKEYI = HIGHLANDUSER.AEZ.AEZKAVKEYI AND HIGHLANDUSER.AEZ.AEZKAVKEYI = HIGHLANDUSER.PRO.PROKAVKEYI AND HIGHLANDUSER.AEZ.AEZPROKEYI = HIGHLANDUSER.PRO.PROKEYI AND (HIGHLANDUSER.KVT.KVTBEZC = 'Standard') 
AND HIGHLANDUSER.ART.ARTANRC='$articlenumber' and HIGHLANDUSER.KAV.KAVKEYI = HIGHLANDUSER.KAT.KATKAVKEYI) ";

$x++;
*/
	// Parse the query.
	$s = oci_parse($c, $q);
	
	// Initialize the PHP variable:
	// here put php array handling to receive and print out the hllagodata table create and load file.
	$media = "";
	$mediaimportkey = "";
  $artname = "";
	$price = "";
	$pricelb = "";
	$pricekg = "";
	$lastdate = "";
	$elementname = "";
	$category = "";
	$unitofsale = "";
	$graphicslogo = "";
	$measure = "";
	$textcombined = "";
	$textline1 = "";
	$textline2 = "";
	$textline3 = "";
	
	// Bind the output to vars

	oci_define_by_name($s, "ARTNAME", $artname);
	oci_define_by_name($s, "PRSPRSC", $price);
	oci_define_by_name($s, "PRSVF1C", $pricelb);
	oci_define_by_name($s, "PRSVF2C", $pricekg);
	oci_define_by_name($s, "ARTDLAD", $lastdate);
	oci_define_by_name($s, "ELENAME", $elementname);
	oci_define_by_name($s, "KATBEZC", $media);
	oci_define_by_name($s, "KATINAC", $mediaimportkey);	
	oci_define_by_name($s, "CATEGORY", $category);
  oci_define_by_name($s, "UNITOFSALE", $unitofsale);	
  oci_define_by_name($s, "GRAPHICSLOGO", $graphicslogo);	
  oci_define_by_name($s, "MEASURE", $measure);
  oci_define_by_name($s, "TEXTCOMBINED", $textcombined);
  oci_define_by_name($s, "TEXTLINE1", $textline1);
  oci_define_by_name($s, "TEXTLINE2", $textline2);
  oci_define_by_name($s, "TEXTLINE3", $textline3);
  			
	$trademarkarray = array("(r)", "(R)", "?", chr(63));

	// Execute the query.
	oci_execute($s);
	
	// Fetch the results.
	oci_fetch($s);
	
if(strlen($textcombined)< 1)
{
$textconcat = $textline1." ".$textline2." ".$textline3;
$textcombined = ($textconcat);
//echo " is empty!! $textcombined";
}


if($price > 0 && $price != "." && strlen($price) > 1)
{
$price = substr($price,0,-2).".".substr($price,-2);
}
else
{
$price = "0.00";
}
if($pricelb > 0 && $pricelb != "." && strlen($pricelb) > 1)
{
$pricelb = substr($pricelb,0,-2).".".substr($pricelb,-2);
}
else
{
$pricelb = "0.00";
}
if($pricekg > 0 && $pricekg != "." && strlen($pricekg) > 1)
{
$pricekg = substr($pricekg,0,-2).".".substr($pricekg,-2);
}
else
{
$pricekg = "0.00";
}

$string =  $media."\t".$mediaimportkey."\t".$category."\t".$articlenumber."\t".$textcombined."\t".$elementname
."\t".$price."\t".$pricelb."\t".$pricekg."\t".$unitofsale."\t".$measure."\t".$graphicslogo."\t".$artname."\t".$lastdate."\n";
//$string = "lalalalallala";

//echo "  $media $mediaimportkey $category $articlenumber $textcombined $elementname
// $price $pricelb $pricekg $unitofsale $measure $graphicslogo $artname $lastdate <BR>";
//echo "String === $string <br>";

$file = fopen("../$importdir/$hllagodatafileforload", 'ab');
fwrite($file, $string);
fclose($file);


} 

?>

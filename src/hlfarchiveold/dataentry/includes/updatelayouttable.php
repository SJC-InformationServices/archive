<?php
$dataarray = array();
$dataarray['DATA'] = array(); 
$dataarray['ERRORS'] = array(); 
include "serverconfig.php";
include "oracleconnect_inc.php";
echo "Working---";


$layoutsql = "select * from $dbprefix.VGB VGB, $dbprefix.RAS RAS, $dbprefix.RAF RAF, $dbprefix.RFP RFP where 
VGBKEYI = RASVGBKEYI and raskeyi = rfpraskeyi and RFPRAFKEYI = RAFKEYI order by VGBBEZC, RASBEZC, RFPRFNS";
$layoutquery = oci_parse($ocilogon, $layoutsql);
oci_execute($layoutquery);
while($layoutdata = oci_fetch_array($layoutquery))
{
$template = addslashes($layoutdata['VGBBEZC']);
$pagelayout = addslashes($layoutdata['RASBEZC']);
$elementlayout = addslashes($layoutdata['RAFBEZC']);
$ratio = $layoutdata['VGBPWII'] / 800;
$pagewidth = round($layoutdata['VGBPWII'] / $ratio, 4);
$pageheight = round($layoutdata['VGBPHEI'] / $ratio, 4);
$positionnumber = $layoutdata['RFPRFNS'];
$x = round($layoutdata['RFPPOXI']/$ratio, 4);
$y = round($layoutdata['RFPPOYI']/$ratio, 4);
$w = round($layoutdata['RAFBREI']/$ratio, 4);
$h = round($layoutdata['RAFHOEI']/$ratio, 4);
$data = array("template"=>"$template", "pagelayout"=>"$pagelayout", "pagewidth"=>$pagewidth, "pageheight"=>$pageheight, "position"=>$positionnumber, "x"=>$x, "y"=>$y, "w"=>$w, "h"=>$h);
$sql = "insert into $layouttable2 (`id`, `template`, `pagelayout`, `elementlayout`, `pagewidth`, `pageheight`, `position`, `x`, `y`, `w`, `h`) 
values ('0', '$template', '$pagelayout', '$elementlayout', '$pagewidth', '$pageheight', '$positionnumber', '$x', '$y', '$w', '$h')";

$layoutquerymy = mysql_query($sql, $mysqlconnect);
if(!$layoutquerymy)
{
echo "$sql<br>";
}
}
//array_push($dataarray['ERRORS'], "Select * from $dbprefix.VGB VGB, $dbprefix.RAF RAF where RAFVGBKEYI = VGBKEYI and VGBBEZC = '$template' order by RAFBEZC");

mysql_close($mysqlconnect);

// json_encode($dataarray);

oci_close($ocilogon);

?>

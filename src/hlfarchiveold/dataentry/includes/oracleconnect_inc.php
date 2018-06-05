<?php
$dbprefix = 'BENTON';
//$ocilogon = oci_connect("sjcreadonly", "sjcreadonly", "10.3.0.186/CMSANEW");
$ocilogon = oci_connect("sjcreadonly", "sjcreadonly", "10.3.0.186/cmsanew", "AL32UTF8");
if (!$ocilogon) 
{
  $e = oci_error();   // For oci_connect errors pass no handle
  array_push($dataarray['ERRORS'], "See Lago Group<br>".htmlentities($e['message']));
}

?>

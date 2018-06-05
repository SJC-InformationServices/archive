<?php

$mysqlconnect = mysql_connect('localhost', 'hlfadmin', 'pi1g0!');
if (!$mysqlconnect) 
{
	die('mysql_login failed to connect : ' . mysql_error());
}
$mysqldb = mysql_select_db('hlf', $mysqlconnect);
if (!$mysqldb)
{
	die('mysql_db fail: ' . mysql_error());
}
$hlusertable = "hlusertable";
$stackertable = "hldata";
$stackertable2 = "hldata";
$stackermaxfield = "id";
$mediacontrol = "hlmediacontrol";
$hlcategorytable = "hlcategory";
$importdir = "importtext";
$hllagodatafileforload = "HLF_Lago_Loadfile.txt";
$hllagorawdatafileforload = "HLF_Raw_Product_Data3clean.txt";
$exportdir = "exportedtext";

$exportfileforload = "HLF_Retail_LoadFile-".date('Ymd-His').".imp";
$cataloguefileforload = "HLF_makecatalogue.imp";
//$cataloguefileforload = "makecatalogue-".date('Ymd-His').".imp";
$deletefileforload = "HLF_Delete_LoadFile-".date('Ymd-His').".imp";
$elementonlyfile = "HLF_ElementOnly_Loadfile-".date('Ymd-His').".imp";

$hllagotable = "hllagodata";
$lagostackermaxfield = "id";
$hlconstantstable = "hlconstants";
$layouttable = "hltemplates";
$layouttable2 = "hltemplates2";


// Create a function for escaping the data.
function escape_data ($data) {
	
	// Address Magic Quotes.
	if (ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}
	
	// Check for mysql_real_escape_string() support.
	if (function_exists('mysql_real_escape_string')) {
		global $mysqlconnect; // Need the connection.
		$data = mysql_real_escape_string (trim($data), $mysqlconnect);
	} else {
		$data = mysql_escape_string (trim($data));
	}

	// Return the escaped value.	
	return $data;

} // End of function.

?>

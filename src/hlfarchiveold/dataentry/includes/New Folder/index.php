


<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head><title>Highland Testing</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=\"UTF-8\">
<meta http-equiv=\"refresh\" content=\"3600;url=index.php\">


</head>
<body>
<form action = "index.php" id=\"createarticles\" method = 'post'>

<?php 
$mysqlconnect = mysql_connect('localhost', 'lago', 'como888');
if (!$mysqlconnect) 
{
	die('mysql_login failed to connect : ' . mysql_error());
}
$mysqldb = mysql_select_db('retailtesting', $mysqlconnect);
if (!$mysqldb)
{
	die('mysql_db fail: ' . mysql_error());
}

$stackertable = "hldata";
$stackermaxfield = "stackerfield";
$mediacontrol = "hlmediacontrol";
$hlcategorytable = "hlcategory";
echo "tables are connected!";

$query = "SELECT categoryname FROM $hlcategorytable ORDER BY categoryname";
$results = mysql_query($query);

echo "<select name = "categoryfield">";
foreach($results as $value)
{ echo "<option value=\"$value=\">$value</option>\n";
}


echo '</select>';
echo '</form>';
echo '</body>';
echo '</html>';
?>

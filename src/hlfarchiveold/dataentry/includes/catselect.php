<?php
session_start();
if(isset($_SESSION['id']) )
{
function catselect()
{
global $stackertable, $mysqldb, $mysqlconnect, $mysqldb, $mediacontrol, $selectedmedia, $selectedpage, $mediaimportkey, $userpermission; 
echo "<table class=\"catselect\">";
echo "<tr><td colspan=\"2\">Media Select</td></tr><tr><td>Media</td><td>Page</td></tr>";
echo "<tr><td>";
echo "<select name=\"media\" id=\"selectedmedia\" onchange=\"submitform(this.id)\">";
if(isset($selectedmedia))
{
echo "<option value=\"$selectedmedia\">$selectedmedia</option>";
}
// highland sees activemedia only as they are permission group level 1
if($userpermission > 8)
{
$sql = "Select Distinct(media), mediaimportkey, activemedia, id from $mediacontrol where activemedia = 1 order by `id` DESC";
}
else
{
$sql = "Select Distinct(media), mediaimportkey, activemedia, id from $mediacontrol WHERE activemedia = 1 order by `id` DESC";
}

$query = mysql_query($sql);
if(!$query)
{
$error = mysql_error($mysqlconnect);
echo "<option value=\"Failed Server\">$error</option>";
}
else
{
echo "<option value=\"\"></option>";
while($media = mysql_fetch_array($query))
{
echo "<option value=\"".$media['media']."\">".$media['media']."</option>";

}
}

echo "</select>";
echo "</td>";
if(isset($selectedmedia))
{
//$mediaimportkey = 'mediaimportkey';
echo "<td><select name=\"selectedpage\" id=\"selectedpage\" onchange=\"submitform(this.id)\">";
if(isset($selectedpage))
{
echo "<option VALUE=\"$selectedpage\">$selectedpage</option>";
}
$pagesql = "Select Distinct(page) from $stackertable where media='$selectedmedia' order by page";
$pagequery = mysql_query($pagesql);
if(!$pagequery)
{
echo "<option value=\"\">Error-See Lago Group</option>";
}
else
{
echo "<option value=\"\"></option>";
while($pages = mysql_fetch_array($pagequery))
{
echo "<option value=\"".$pages['page']."\">".$pages['page']."</option>";
}
}
echo "</select></td></tr>";
}
else
{
echo "<td><input type = \"text\" class=\"page\"></td></tr>";
}
echo "</table>";
}
}
else {
header("location:../index.php");
}

?>

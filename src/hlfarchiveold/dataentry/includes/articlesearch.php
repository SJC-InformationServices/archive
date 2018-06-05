<?php
ini_set('display_errors',1);
include 'serverconfig.php';
//if(isset($_SESSION['id']) )
//{
session_start();
$submittedtext = trim($_REQUEST['searchstring']);
$selectedmedia = trim($_REQUEST['selectedmedia']);
$selectedpage = trim($_REQUEST['selectedpage']);

//$selectedmedia = $_POST['selectedmedia'];
//$selectedpage = $_POST['selectedpage'];
if($selectedmedia == "" || $selectedpage == "" || $submittedtext == "")
{
echo "<div id=\"searchwindow\" class=\"searchwindow\"><table cellspacing=\"0\" cellpadding=\"0\">";
echo "Media $selectedmedia, and search text $submittedtext must be selected.";
echo "</table></div>";
}
else
{
//echo " media $selectedmedia and page $selectedpage selected.";
$submittedtextescape = "\"%$submittedtext%\"";
$searchtext = strtoupper(str_replace(" ", "%", $submittedtextescape));

if(isset($submittedtext) && $submittedtext != "")
{
echo "<div id=\"searchwindow\" class=\"searchwindow\"><table cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr>
<td>Media</td>
<td>Item No</td>
<td>Category</td>
<td>Text Lines</td>
<td>Price</td>
<td>Price LB</td>
<td>Price KG</td>
<td>UoS</td>
<td>Measure</td>
<td>Instructions</td>
<td>Logo</td></tr>";
$query = "Select media, page, position, articlenumber, articlename, category, textcombined, price,  pricelb, pricekg, elementlayout,uos, measure, instructions, artselection, graphicslogo,  markedforexport, exportedtolago, loadtolagodate, savedate, id FROM $stackertable
WHERE (articlenumber LIKE $searchtext OR UPPER(textcombined) LIKE $searchtext OR UPPER(category) LIKE $searchtext OR UPPER(uos) LIKE $searchtext OR UPPER(instructions) LIKE $searchtext OR UPPER(measure) LIKE $searchtext OR UPPER(graphicslogo) LIKE $searchtext) 
 AND media <> 'HLFrawdata' order by id DESC";

$results = mysql_query($query);
if(!$results)
{
$error = mysql_error($mysqlconnect);
echo "Error see lago group this is fetch1<br>";
echo "$error";
}
else
{	
while($lagorecord = mysql_fetch_array($results, MYSQL_ASSOC))
{
  $id = $lagorecord['id'];
  $media = $lagorecord['media'];
  $artnumber = trim($lagorecord['articlenumber']);
  $articlename = trim($lagorecord['articlename']);
  $page = $lagorecord['page'];
  $page = $selectedpage;
  $position = $lagorecord['position'];
  $category      = $lagorecord['category'];
	$textlines = $lagorecord['textcombined'];
	$price     = $lagorecord['price'];
  $pricelb   = $lagorecord['pricelb'];
  $pricekg   = $lagorecord['pricekg'];
  $elementlayout = $lagorecord['elementlayout'];
 	$unitofsale      = $lagorecord['uos'];
	$measure      = $lagorecord['measure'];
 	$instructions      = $lagorecord['instructions'];
 	$artselection = $lagorecord['artselection'];
	$graphicslogo      = $lagorecord['graphicslogo'];

$class = "notloaded";
$onclick = "\"artsearch('$media', '$page', '$artnumber',$position)\"";
echo "<tr class=\"$class\" onMouseOver=\"this.className='mouseover';\" onMouseOut=\"this.className='$class';\" onClick= $onclick ><td>$media </td><td>$artnumber </td><td>$category</td><td>$textlines</td><td>$price</td><td>$pricelb</td><td>$pricekg</td><td>$unitofsale</td><td>$measure</td><td>$instructions</td><td>$artselection</td><td>$graphicslogo</td></tr>";
}
}
echo "</table></div>";
}
}
?>

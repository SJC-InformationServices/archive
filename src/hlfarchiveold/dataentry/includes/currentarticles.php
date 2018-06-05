<?php
session_start();
if(isset($_SESSION['id']) )
{
function currentarticles()
{
global $stackertable, $stackermaxfield, $mysqldb, $mysqlconnect, $mysqldb, $mediacontrol, $selectedmedia, $selectedpage;
echo "<table class=\"createarticle\" id=\"currentarticlestable\">";
echo "<tr>
<td class=\"verysmall\">Page</td>
<td class=\"verysmall\">Pos</td>
<td class=\"verysmall\">Item No</td>
<td class=\"verysmall\">Category</td>
<td class=\"medium\">Text Lines</td>
<td class=\"verysmall\">Price</td>
<td class=\"verysmall\">Price LB</td>
<td class=\"verysmall\">Price KG</td>
<td class=\"verysmall\">Layout</td>
<td class=\"verysmall\">Unit of Sale</td>
<td class=\"small\">Measure</td>
<td class=\"small\">Instructions</td>
<td class=\"verysmall\">Art Instr</td>
<td class=\"verysmall\">Logo</td>
</tr>";

$sql = "Select page, position, articlenumber, category, textcombined, price,  pricelb, pricekg, elementlayout,uos, measure, instructions, artselection, graphicslogo, deletearticle, id, markedforexport, exportedtolago, loadtolagodate, savedate, $stackermaxfield  from $stackertable WHERE media = '$selectedmedia' AND page = '$selectedpage'  AND $stackermaxfield IN (SELECT MAX($stackermaxfield) FROM $stackertable WHERE media = '$selectedmedia' AND page = '$selectedpage' GROUP BY position, articlenumber) order by position,articlenumber";
/*$sql = "Select page, position, searsposition, articlenumber, articlename, elementname, retailprice, promoprice, retailpricerange, promopricerange, offerinfo, calcordernumber, id, markedforexport, exportedtolago, loadtolagodate, savedate from $stackertable where media = '$selectedmedia' and page = '$selectedpage'"; */
$results = mysql_query($sql);

if(!$results)
{
$error = mysql_error($mysqlconnect);
echo "Error see lago group<br>";
echo "$error";
}
else
{
$rowcount = 1;
while($articles = mysql_fetch_array($results, MYSQL_ASSOC))
{
$marked = $articles['markedforexport'];
$exported = $articles['exportedtolago'];
$thisarticle = $articles['articlenumber'];
$thisposition = $articles['position'];
$deletearticle = $articles['deletearticle'];

if($deletearticle == 1)
{
$onclick = "\"artname('$thisarticle',$thisposition)\"";
$class = "deleted";
}
elseif(($marked == 1 && $exported == 1)||($marked == 1 && $exported == 0))
{
//$string = "Marked for Export:".$articles['savedate']."\\nExported:".$articles['loadtolagodate'];
//$onclick = "\"alert('$string')\"";
$onclick = "\"artname('$thisarticle',$thisposition)\"";
$class = "loaded";
}
elseif($marked == 0 && $exported == 0)
{
$onclick = "\"artname('$thisarticle',$thisposition)\"";
$class = "notloaded";
}

echo "<tr class=\"$class\" title=\"closed\" id=\"".$articles['id']."\" onClick=$onclick 
onMouseOut=\"this.className='$class';\" onMouseOver=\"this.className='mouseover';\">";
array_pop($articles);
array_pop($articles);
array_pop($articles);
array_pop($articles);
array_pop($articles);
array_pop($articles);
foreach($articles as $keys => $values)
{
echo "<td title=\"$keys\">$values</td>";
}
echo "</tr>";
$rowcount++;
}
}
echo "</table>";
}
}
else {
header("location:../index.php");
}

?>

<?php
session_start();
if(isset($_SESSION['id']) )
{
function catcreate()
{
global $stackertable, $mysqldb, $mysqlconnect, $mysqldb, $mediacontrol, $hlconstantstable, $selectedmedia; 
$querytemplate = "SELECT hlconstantvalue FROM $hlconstantstable WHERE hlconstants = 'mediatemplate'";
$templateresults = mysql_query($querytemplate);
echo "<table class=\"catselect\">";
	echo "<tr>
			  <td colspan=\"1\">Create Media</td>";
		echo "<td colspan=\"1\">Choose template</td>
		 </tr>";
	echo "<tr>
				<td colspan=\"1\"><input type=\"text\" name=\"selectedmedia\" id=\"createmedia\"></td>
				<td colspan=\"1\">
					<select name=\"mediatemplate\" id=\"mediatemplate\">
									 <option value = \"\" selected=\"\">";
					while($hllayouts = mysql_fetch_array($templateresults, MYSQL_ASSOC))
					{
					foreach ($hllayouts as  $key => $value) {
						echo "<option value=\"$value\" >$value </option>";
					}
					}
					echo "</select>";
				 echo "</td>
		   </tr>";
$yearnow = date("Y"); 
$yearnext = date("Y") + 1;
$seasonarray = array($yearnow, $yearnext);
echo "<tr>
		<td>Season select ==></td>
		<td colspan=\"1\">
				<select name=\"seas\" id=\"seas\"><option value = \"\" selected=\"\">";
				foreach ($seasonarray as $val) {
						echo "<option value=\"$val\">$val</option>";
				}
echo "</select>";
echo "</td></tr>";

$numpagesarray = array(1,4,8,12,16);
echo "<tr>
			<td>Pages select ==></td>
			<td>
				<select name=\"numpage\" id=\"numpage\"><option value = \"\" selected=\"\">";
			foreach ($numpagesarray as $valuee) {
				echo "<option value=\"$valuee\" >$valuee </option>";
			}
echo "</select><td>";
echo "</tr>";
//echo "<td><input type=\"radio\" name=\"booktype\" value = \"flyer\" Flyer> 
//<input type=\"radio\" name=\"booktype\" value = \"newspaper\" Newspaper></td></tr>";
echo "<tr><td ><button name=\"catsubmit\" value=\"createmedia\" value onClick=\"submitform('createmedia')\">Create</button></td>";
echo "</table>";
}
}
else {
header("location:../index.php");
}
?>

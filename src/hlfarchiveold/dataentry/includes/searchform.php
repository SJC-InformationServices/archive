<?php
function searchform()
{

echo "<table class=\"catselect\">";
echo "<tr><td>Search</td></tr>";
echo "<tr><td colspan=\"1\"><input type=\"text\" id=\"searchstring\"></td></tr>";
//echo "<td><input type=\"radio\" name=\"booktype\" value = \"flyer\" Flyer> <br />
//<input type=\"radio\" name=\"booktype\" value = \"newspaper\" Newspaper></td></tr>";
echo "<tr><td colspan=\"1\"><button name=\"runsearch\" value=\"Search\" onClick=\"runsearch()\">Search</button></td></tr>";
echo "</table>";
}
?>

<?php
session_start();

if(isset($_SESSION['id']) )
{
//$sesid = $_REQUEST['PHPSESSID'];
$user = $_SESSION['user'];
$selectedmedia = $_REQUEST['selectedmedia'];
$selectedpage = $_REQUEST['selectedpage'];
$userpermission = $_SESSION['group'];
$userpassword = $_SESSION['password'];
$_SESSION['user'] = $user;
date_default_timezone_set("America/Toronto");
$today = date("F j, Y, g:i a"); 
if(isset($selectedmedia))
{
$_SESSION['selectedmedia'] = $selectedmedia;
}
else
{
$selectedmedia = $_SESSION['selectedmedia'];
}
if(isset($selectedpage))
{
$_SESSION['selectedpage'] = $selectedpage;
}
else
{
$selectedpage = $_SESSION['selectedpage'];
}
$selectedmedia = trim($selectedmedia);
$selectedpage = trim($selectedpage);
include "includes//serverconfig.php";
echo "
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head><title>HLF Data Entry</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=\"UTF-8\">
<meta http-equiv=\"refresh\" content=\"36000;url=index.php\">
<style type=\"text/css\" media=\"all\">@import \"css/default.css\";</style>
<style type=\"text/css\" media=\"all\">@import \"css/floating-window.css\";</style>
<style type=\"text/css\" media=\"all\">@import \"css/layout.css\";</style>
<script type=\"text/javascript\" src=\"js/ajax.js\"></script>
<script type=\"text/javascript\" src=\"js/floating-window.js\"></script>
<script type=\"text/javascript\" src=\"js/search.js\"></script>
<script type=\"text/javascript\" src=\"js/results.js\"></script>
<script type=\"text/javascript\" src=\"js/generatelayout.js\"></script>
</head>";
echo "<body>";
echo "<div class=\"welcome\"><a href=\"index.php\">Logout</a>  $user HLF <b>$selectedmedia  Pg. $selectedpage       --- $today</b></div>";
echo "<div class=\"welcome\"><button onclick=getlayout(this)>Get Layout</button></div>";
//media & page select or create
echo "<div class=\"catselect\">";
include "includes//catselect.php";
include "includes//catcreate.php";
include "includes//articlecreate.php";
include "includes//currentarticles.php";
include "includes//searchform.php";
  echo "<div class=\"catselectform\">";
  catselect();
  echo "</div>";
  if($userpermission > 1)
  {
  echo "<div class=\"catcreateform\">";
  catcreate();
  echo "</div>";
  }
  echo "<div class=\"catcreateform\">";
 searchform();
  echo "</div>";
  
  echo "<div class=\"pagecreateform\" id=\"response\">";
  //catselect();
  echo "</div>";
echo "</div>";

//create articles form
echo "<div class=\"createarticle\">";

echo "<span style=\"margin-left:10px; border-bottom:thin solid white;\">Article Entry for: $selectedmedia</span><br><span style=\"margin-left:10px; border-bottom:thin solid white;\">
<button name=\"articlesubmit\" value=\"createarticle\" onClick=\"submitarticles(this.id);\">Create Articles</button>"; 
echo "<form id=\"createarticles\" name=\"createarticles\" ENCTYPE = \"multipart/form-data\"|\"application/x-www-form-urlencoded\"|\"text/plain\">";
echo "<input type=\"hidden\" name=\"media\" value=\"$selectedmedia\">";
echo "<input type=\"hidden\" name=\"user\" value=\"$user\" id=\"currentuser\">";
articlecreate();
echo "</form>";
//echo "<span border-bottom:thin solid white;\"><button name=\"articlesubmit\" value=\"createarticle\" onClick=\"submitarticles(this.form)\">Create Articles</button></span>";
echo "</div>";
//current article from selected media
echo "<div class=\"currentarticles\"><span style=\"margin-left:10px; border-bottom:thin solid white;\">Current Articles for: $selectedmedia, Page: $selectedpage <button onClick=\"markforexport()\">Send to St. Joseph</button></span><br>";

if(strlen($selectedmedia) > 0 && strlen($selectedpage) > 0)
{
currentarticles();
}
echo "</div>";


echo "<div class=\"footer\"><HR><a href=\"index.php\">Logout</a></div>";

}
else
{
header("location:index.php");
}
echo "</body>";
echo "</html>";


?>

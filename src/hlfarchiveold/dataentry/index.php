<?php
if(isset($_REQUEST['PHPSESSID']))
{
session_start();
session_unset();
session_destroy();
}
include "includes//serverconfig.php";

$login = $_POST['Login'];
$user = $_POST['username'];
$password = $_POST['password'];
$actionlocation = $_SERVER['PHP_SELF'];

if(isset($login))
{
$userverify = "SELECT `group`, `id` from $hlusertable where (`username`='$user' or  `username`=UPPER('$user')) and (`password`=SHA('$password')) ";
$userresults = mysql_query ($userverify) or die('mysql log fail: ' . mysql_error());
$userpermission = mysql_fetch_row($userresults);
if(mysql_num_rows($userresults) > 0)
{
session_set_cookie_params(360000, '/','');
session_start();
$_SESSION['user'] = $user;
$_SESSION['id'] = $userpermission[1];
$_SESSION['group'] = $userpermission[0];
$sessid = session_id();

header("location:workindex.php");
}
else
{
echo "<html>
<head>
			<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\" />
			<meta name=\"generator\" content=\"Adobe GoLive PSPAD\" />
			<title>Media Data Entry</title>
			<link href=css/default.css rel=stylesheet type=text/css media=all />
		</head>";
echo "<div id=\"body\"><body>
		<form ENCTYPE = \"multipart/form-data\"|\"application/x-www-form-urlencoded\"|\"text/plain\" action='$actionlocation' method=\"post\" >
			<table width=\"500\" align=\"center\" valign=\"middle\" >
			<tr>
			<td bgcolor=\"White\" align=\"center\"><font color= green size = 8>Media Data Entry </font></td>
			</tr>
      </table><br>
      
      <table width=\"500\" align=\"center\" valign=\"middle\">
      <tr>
      <td width=\"250\">
      <table width=\"250\" align=\"center\" valign=\"middle\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
      <tr>
      <td>Enter User Name:<BR><input type=\"text\" value=\"\" name=\"username\"><BR></td>
      </tr>
      <tr>
      <td>Enter Password:<BR><input type=\"password\" value=\"\" name=\"password\"><BR><input type=\"submit\" value=\"Login\" name=\"Login\"></td>
      </td>
      </tr>
      </table>
      </td>
      
      <td width=\"250\">
     Your Login Failed - use lowercase only. Please Try Again!
      </td>
      
      </tr>
		  </table>
</body>";
echo "</div>";
}

}

else
{

echo "<body><div id=\"body\">
		<form ENCTYPE = \"multipart/form-data\"|\"application/x-www-form-urlencoded\"|\"text/plain\" action='$actionlocation' method=\"post\" >
			<table width=\"500\" align=\"center\" valign=\"middle\">
			<tr>
			<td bgcolor=\"White\" align=\"center\"><font color= green size = 8>HLF Media Data Entry </font></td>
			</tr>
      </table><br>
      
      <table width=\"500\" align=\"center\" valign=\"middle\">
      <tr>
      <td width=\"250\">
      <table width=\"250\" align=\"center\" valign=\"middle\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
      <tr>
      <td>Enter User Name:<BR><input type=\"text\" value=\"\" name=\"username\"><BR><BR></td>
      </tr>
      <tr>
      <td>Enter Password:<BR><input type=\"password\" value=\"\" name=\"password\"><BR><input type=\"submit\" value=\"Login\" name=\"Login\"></td>
      </td>
      </tr>
      </table>
      </td>
      
      <td width=\"250\">
      Welcome if you don't have permissions to login please ask your supervisor or email <a href=\"mailto:lago@stjosephcontent.com\">lago@stjosephcontent.com</a>
      </td>
      
      </tr>
		  </table>
</body>";
}


echo "</div>";
//echo " <br /> <font color = white> Production System. </font";
echo "<html>";
?>

<?php
session_start();
if(isset($_SESSION['id']) ){
$newmedia = strtoupper($_REQUEST['media']);
$template = $_REQUEST['mediatemplate'];
$yearselected = $_REQUEST['seas'];
$numberofpages = $_REQUEST['numpage'];

$user = $_SESSION['username'];
//echo "$newmedia";
$datestamp = DATE('Y-m-d G:i:s');
if(isset($newmedia) && $newmedia != "" && isset($template) && $template != ""  && isset($yearselected) && $yearselected != "" && isset($numberofpages) && $numberofpages != "")
{
include 'serverconfig.php';
$mediaimportkey = str_ireplace(" ","", $newmedia);
$variant = "Standard";
$workplan = "Highland Retail 20071107";
if(stristr($template, "JNP") && stristr($template, "Newspaper"))
{
$season = $yearselected." HL Retail";
$layout = "Newspaper_Jewish";
$pagefrom  = 1;
$pageto = 1;
$pagelayout = "ANY=Newspaper_Jewish";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
$string = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
fwrite($file, $string);
fclose($file);
}
elseif(stristr($template, "Newspaper"))
{
$season = $yearselected." HL Retail";
$layout = "Newspaper_Russian";
$pagefrom  = 1;
$pageto = 1;
$pagelayout = "ANY=Newspaper_Russian";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
$string = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
fwrite($file, $string);
fclose($file);

}
elseif($template == 'Highland') //8 pg book
{
$season = $yearselected." HL Retail";
$layout = 'frontcover';
$pagefrom  = 1;
$pageto = 1;
$pagelayout = "ANY=frontcover";
$string1 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 2;
$pageto = 3;
$pagelayout = "ANY=8pg_inside\ANY=8pg_inside";
$string2 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 4;
$pageto = 5;
$pagelayout = "ANY=8pg_inside\ANY=8pg_inside";
$string3 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 6;
$pageto = 7;
$pagelayout = "ANY=8pg_inside\ANY=8pg_inside";
$string4 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 8;
$pageto = 8;
$pagelayout = "ANY=8pg_back";
$string5 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
fwrite($file, $string1);
fwrite($file, $string2);
fwrite($file, $string3);
fwrite($file, $string4);
fwrite($file, $string5);
fclose($file);
}
elseif($template == 'Highland_4pg') // 4 pg book
{
$season = $yearselected." HL Retail";
$layout = 'frontcover';
$pagefrom  = 1;
$pageto = 1;
$pagelayout = "ANY=frontcover";
$string1 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 2;
$pageto = 3;
$pagelayout = "ANY=4pg_inside\ANY=4pg_inside";
$string2 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 4;
$pageto = 4;
$pagelayout = "ANY=4pg_back";
$string3 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
fwrite($file, $string1);
fwrite($file, $string2);
fwrite($file, $string3);
fclose($file);
}

elseif($template == 'Highland 9x11.125') //*******************20110202 new 8 page format for Highland*********************
{
echo "Created new media hlf using 9x11.125";
$season = $yearselected." HL Retail";
$layout = 'frontcover';
$pagefrom  = 1;
$pageto = 1;
$pagelayout = "ANY=front_cover_3_day";
$string1 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 2;
$pageto = 3;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string2 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 4;
$pageto = 5;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string3 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 6;
$pageto = 7;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string4 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
fwrite($file, $string1);
fwrite($file, $string2);
fwrite($file, $string3);
fwrite($file, $string4);
fclose($file);
if($numberofpages == '8')
{
$pagefrom  = 8;
$pageto = 8;
$pagelayout = "ANY=back_cover";
$string5 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
fwrite($file, $string5);
fclose($file);
}
elseif($numberofpages == '12')
{
$pagefrom  = 8;
$pageto = 9;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string5 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 10;
$pageto = 11;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string6 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 12;
$pageto = 12;
$pagelayout = "ANY=back_cover";
$string7 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
fwrite($file, $string5);
fwrite($file, $string6);
fwrite($file, $string7);
fclose($file);
}
else
//else($numberofpages = 16)
{
$pagefrom  = 8;
$pageto = 9;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string5 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 10;
$pageto = 11;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string6 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 12;
$pageto = 13;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string7 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 14;
$pageto = 15;
$pagelayout = "ANY=left_page_inside\ANY=right_page_inside";
$string8 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$pagefrom  = 16;
$pageto = 16;
$pagelayout = "ANY=back_cover";
$string9 = $season."\t".$newmedia."\t".$mediaimportkey."\t".$variant."\t".$template."\t".$pagefrom."\t".$pageto."\t".$pagelayout."\t".$workplan."\r\n";
$file = fopen("../$exportdir/HLF_makecatalogue.imp", 'ab');
fwrite($file, $string5);
fwrite($file, $string6);
fwrite($file, $string7);
fwrite($file, $string8);
fwrite($file, $string9);
fclose($file);
}
}
$insertquery = "insert into $mediacontrol (`media`, `mediaimportkey`, `mediatemplate`,`createdate`, `createdby`, `season`) VALUES ('$newmedia', '$mediaimportkey', '$template','$datestamp', '$user', '$yearselected')";
//switch to this insert query after changeover to ez catalogue

//$insertquery = "insert into $mediacontrol (`media`, `mediaimportkey`, `mediatemplate`,`createdate`, `createdby`) VALUES ('$newmedia', '$mediaimportkey', '$template','$datestamp', '$user')";
$results = mysql_query($insertquery);
if(!$results)
{
$error = mysql_error($mysqlconnect);
echo "$error\nReport to Lago Group";
}
else
{
echo "Created new media\n$newmedia";
}
}
else
{
echo "Media Field, Template, Season or Pages Incomplete";
}
}
else {
header("location:../index.php");
}


?>

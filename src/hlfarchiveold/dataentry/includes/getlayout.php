<?php
$dataarray = array();
$dataarray['DATA'] = array(); 
$dataarray['ERRORS'] = array(); 
$dataarray['DATA']['LAYOUT'] = array();
include "serverconfig.php";
//include "oracleconnect_inc.php";

$media = $_REQUEST['selectedmedia'];
$page = $_REQUEST['selectedpage'];

$templatesql = "select * from $mediacontrol where media = '$media'";
$templatequery = mysql_query($templatesql);
if(!$templatequery)
{
array_push($dataarray['ERRORS'], 'Failed TemplateQuery');
}
else
{
$templatearray = mysql_fetch_array($templatequery);
$template = $templatearray['mediatemplate'];
}

if(stristr($template, "JNP") && stristr($template, "Newspaper"))
{
$layout = "Newspaper_Jewish";
}
elseif(stristr($template, "Newspaper"))
{
$layout = "Newspaper_Russian";
}
elseif($page == 8 && $template == 'Highland')
{
$layout = '8pg_back';
}
elseif($page == 4 && $template == 'Highland_4pg')
{
$layout = '4pg_back';
}
elseif($page > 1 && $page < 8 && $template == 'Highland')
{
$layout = '8pg_inside';
}
elseif($page > 1 && $page < 4 && $template == 'Highland_4pg')
{
$layout = '4pg_inside';
}
//************added for new template 20110208 'Highland 9x11.125****************
elseif(($page == 1 || $page == 2 ) && $template == 'Highland 9x11.125' && $media == 'HLF PASSOVER INSERT 2018')
{
$layout = 'left_page_inside';
}
elseif(($page == 8 || $page == 12 || $page == 16) && $template == 'Highland 9x11.125')
{
$layout = 'back_cover';
}
elseif($page == 1 && $template == 'Highland 9x11.125')
{
$layout = 'front_cover_3_day';
}
elseif($page > 1 && $page < 15 && $template == 'Highland 9x11.125')
{
$layout = 'left_page_inside';
}
 //************added for new template 20170317 'Highland 5.25x11.125 Wrap Back'****************
elseif(($page == 1 || $page == 2) && $template == 'Highland 5.25x11.125 Wrap Back')
{
$layout = 'wrap_back';
}
elseif($page == 1)
{
$layout = 'frontcover';
}



$layoutsql = "select * from $layouttable where template = '$template' and pagelayout = '$layout' order by position";

$layoutquery = mysql_query($layoutsql);
if(!$layoutquery)
{
array_push($dataarray['ERRORS'], $layoutsql);
}
while($layoutdata = mysql_fetch_array($layoutquery))
{
$templatename = $layoutdata['template'];
$pagelayoutname = $layoutdata['pagelayout'];

$dataarray['DATA']['TEMPLATE'] = $templatename;
$dataarray['DATA']['PAGELAYOUT'] = $pagelayoutname;

//$ratio = $layoutdata['VGBPHEI'] / 800;
$pagewidth = $layoutdata['pagewidth'] ;
$pageheight = $layoutdata['pageheight'] ;

$dataarray['DATA']['WIDTH'] = $pagewidth;
$dataarray['DATA']['HEIGHT'] = $pageheight;

$positionnumber = $layoutdata['position'];
$x = $layoutdata['x'];
$y = $layoutdata['y'];
$w = $layoutdata['w'];
$h = $layoutdata['h'];
$data = array("POSITION"=>$positionnumber, "X"=>$x, "Y"=>$y, "W"=>$w, "H"=>$h);
$data['ART'] = array();

//$query = "SELECT *, $stackermaxfield FROM $stackertable WHERE media = '$media' and page = '$page' and position = '$positionnumber' and deletearticle = 0 and articlenumber > '' and $stackermaxfield IN  (SELECT MAX($stackermaxfield) FROM $stackertable WHERE media = '$media' and page = '$page' and position = '$positionnumber'  and articlenumber > '' GROUP BY articlenumber)ORDER BY position";
$query = "SELECT *, $stackermaxfield FROM $stackertable WHERE media = '$media' and page = '$page' and position = '$positionnumber' and deletearticle = 0  and $stackermaxfield IN  (SELECT MAX($stackermaxfield) FROM $stackertable WHERE media = '$media' and page = '$page' and position = '$positionnumber'   GROUP BY articlenumber)ORDER BY position";

//$q = "SELECT * FROM $stackertable WHERE media = '$media' and page = '$page'";
$r = mysql_query($query);
if(!$r)
{
array_push($dataarray['ERRORS'], $q);
}
while($record = mysql_fetch_array($r, MYSQL_ASSOC))
{
  $artnumber = $record['articlenumber'];
	$artname   = $record['articlename'];
	$price     = $record['price'];
  $pricelb   = $record['pricelb'];
  $pricekg   = $record['pricekg'];
  $position  = $record['position'];
  $elementlayout   = $record['elementlayout'];
  if($elementlayout == 'price feature' || $elementlayout == 'pricelb feature')
{
$elementlayout = ' feature';
}
else
{
$elementlayout = '';
}



	$textlines = $record['textcombined'];
	$media2      = $record['media'];
  $elementname = $record['elementname'];
  $artname = $record['articlename'];
 	$unitofsale      = $record['uos'];
	$measure      = $record['measure'];
 	$instructions      = $record['instructions'];
	$graphicslogo      = $record['graphicslogo'];
	$category      = $record['category'];
	$artselection = $record['artselection'];
	$deletearticle = $record['deletearticle'];
	$data['ELE'] = $record['textcombined'];
	$data['ELELAYOUT'] = $elementlayout;
	
	if($pricelb > 0)
{
$disprice = "\$$pricelb/lb \$$pricekg/kg";
}
elseif($unitofsale <> 'each' & $unitofsale > '0')
{
$disprice = "$unitofsale/\$$price ".$measure;
}
else
{
$disprice = "\$$price ".$measure;
}

if($graphicslogo > '')
{
$graphicslogo = ", $graphicslogo";
}
else
{
$graphicslogo = '';
}

if($instructions > '')
{
$instructions = "*$instructions*";
}
else
{
$instructions = $instructions;
}

$additionalinfo = $instructions.",".$artselection.$graphicslogo;
	
	
	$artdata = array("ARTNUM"=>$record['articlenumber'], "ARTNAME"=>$record['articlename'], "PRICE"=>$disprice, "ADDITIONALINFO"=>$additionalinfo);
	array_push($data['ART'], $artdata);







}


array_push($dataarray['DATA']['LAYOUT'], $data);

}

//mysql_close($mysqlconnect);

echo json_encode($dataarray);


?>

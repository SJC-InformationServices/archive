<?php
chdir("/storage/www/hlf/includes");
include "serverconfig.php";
//echo " <head>
//<TITLE>Load HL Lago Data</TITLE>
//<meta http-equiv=\"Content-Type\" content=\"text/html; charset=\"ISO-8859-1\">
//</head>";

$datestamp = DATE('Y-m-d G:i:s');
$actualdate = DATE('Y-m-d');
$datafile = "../$importdir/$hllagodatafileforload";
//echo $datafile;

$lagodata = fopen($datafile,"r");

$x = 0;
$counterinserted = 0;
$counterupdated = 0;
if ($lagodata)
  { 
  while(!feof($lagodata))
  {
  $line = fgets($lagodata);
  
  if(!feof($lagodata))
  {
  $explodedline = explode("\t",$line);
  
  $media = $explodedline[0];
  $mediaimportkey = $explodedline[1];
  $category = $explodedline[2];
  $articlenumber = $explodedline[3];
  $textcombined = $explodedline[4];
  $elementname = $explodedline[5];
  $price = $explodedline[6];
  $pricelb = $explodedline[7];
  $pricekg = $explodedline[8];
  $uos = $explodedline[9];
  $measure = $explodedline[10];
  $graphicslogo = $explodedline[11];
  $artname = $explodedline[12];
  $lastdate = $explodedline[13];

  $media = escape_data($media);
  $mediaimportkey = escape_data($mediaimportkey);
  $category = escape_data($category);
  $textcombined = escape_data($textcombined);
  $elementname = escape_data($elementname);
  $uos = escape_data($uos);
  $measure = escape_data($measure);
  $graphicslogo = escape_data($graphicslogo);
  $artname = escape_data($artname);

  
  $nv = NULL;
  $id = '0';
  $page = '0';
  $position = '0';
  $textline1 = $artname;
  $textline2 = $nv;
  $textline3 = $nv;
  $instructions = $nv;
  $imageref = $nv;
  $elemlayout = $nv;
  $bullets = $nv;
  $mediatemplate = $nv;
  $pagelayout = $nv;
  $offerid = $nv;
  $flasharticle = 0;
  $deletearticle = 0;
  $stackerfield = $nv;
  $dateload = DATE('Y-m-d G:i:s');
  $savedate = DATE('Y-m-d G:i:s');
  $loadtolagodate = DATE('Y-m-d G:i:s');
  $lastuser = "fromlagodata";
  $markedforexport = 0;
  $exportedtolago = 0;
  $plunumbr = $nv;
  $artselection = 'old art';
  
  
//echo "$artnum $artname $plunumbr<br>";
//$insertquery = "INSERT INTO $hllagotable ( articlenumber, articlename, plunumber) VALUES ( $artn, $artnam, $plunumb)";

$insertquery = "INSERT INTO $hllagotable (articlenumber, media, mediaimportkey, page, position, category,  textline1, textline2, textline3, textcombined, elementname, price, pricelb, pricekg, uos, measure, instructions, graphicslogo, articlename, imageref, elementlayout, bullets, mediatemplate, pagelayout, offerid, flasharticle, deletearticle, stackerfield, dateload, savedate, loadtolagodate, lastuser, markedforexport, exportedtolago, artselection, plunumber) 
VALUES
('$articlenumber', '$media', '$mediaimportkey', '$page', '$position', '$category',  '$textline1', '$textline2', '$textline3', '$textcombined', '$elementname','$price', '$pricelb', '$pricekg','$uos', '$measure', '$instructions', '$graphicslogo', '$artname', '$imageref', '$elemlayout', '$bullets', '$mediatemplate', '$pagelayout', '$offerid', '$flasharticle', '$deletearticle', '$stackerfield', '$dateload', '$savedate', '$loadtolagodate', '$lastuser', '$markedforexport', '$exportedtolago', '$artselection', '$plunumbr') 
ON DUPLICATE KEY UPDATE media=VALUES(media), mediaimportkey=VALUES(mediaimportkey), page = VALUES(page), category=VALUES(category), textcombined=VALUES(textcombined), price=VALUES(price), pricelb=VALUES(pricelb), pricekg=VALUES(pricekg), uos=VALUES(uos), measure=VALUES(measure), graphicslogo=VALUES(graphicslogo),  artselection=VALUES(artselection), dateload=VALUES(dateload), loadtolagodate=VALUES(loadtolagodate), lastuser=VALUES(lastuser)";

$results = mysql_query($insertquery);

if(mysql_affected_rows() < 2)
{
$counterinserted++;
}
else
{
$counterupdated++;
}

if(!$results)
{
$error = "<br>NO, not again an error!! ".mysql_error($mysqlconnect);
fclose($lagodata);
exit($error);
}
$updatehllagocopy = "UPDATE $stackertable SET textcombined = '$textcombined' WHERE mediaimportkey = '$mediaimportkey' AND articlenumber = '$articlenumber'";
$updateresults = mysql_query($updatehllagocopy);
if(!$updateresults)
{
$error = "<br>Not again an error!! ".mysql_error($mysqlconnect);
fclose($lagodata);
exit($error);
}
else 
{
//echo "textcombined22 = $textcombined  $updatehllagocopy";
}

}
}
fclose($lagodata);

$countertotal = $counterinserted + $counterupdated;

$tolist = 'bmiller@stjosephcontent.com;knoseworthy@stjosephcontent.com;lbuhajczyk@stjosephcontent.com';
$mailsubject = "Overnight Lago data has been updated to HLF lagodata table.";
$mailmessage = "Load is complete - total = $countertotal, with $counterinserted new records inserted, $counterupdated existing records updated.";
mail($tolist, $mailsubject, $mailmessage);
unlink($datafile);
}
?>

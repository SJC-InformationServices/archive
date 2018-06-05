<?php
session_start();
if(isset($_SESSION['id']) )
{
ini_set('display_errors', 1);
include 'serverconfig.php';
$date = date('Ymd');
$date2 = date('Y-m-d H:i');
$media = $_REQUEST['media'];
$page = $_REQUEST['page'];
$articlenumber = $_REQUEST['articlenumber'];
//$additionskus = $_REQUEST['extraskus'];
$position = $_REQUEST['position']; 
$category = $_REQUEST['category'];
$generaltext = $_REQUEST['textlines'];
$generaltext2 = str_replace("\r"," ", $generaltext);
$generaltext3 = str_replace("\n"," ", $generaltext2);
//$articlename = ($_REQUEST['articlename']);
$articlename = escape_data($_REQUEST['articlename']);
$textlines = ($generaltext3);
$textlines = escape_data($generaltext3);
//$elementdisplayname = (substr($generaltext3,0,200)); //shave to 200 
$elementdisplayname = escape_data(substr($generaltext3,0,200)); //shave to 200 
$price = $_REQUEST['price'];
$pricelb = $_REQUEST['pricelb'];
$pricekg = $_REQUEST['pricekg'];
$elementlayout = $_REQUEST['elementlayout'];
$uos = escape_data($_REQUEST['unitofsale']);
//$uos = ($_REQUEST['unitofsale']);
$measure = escape_data($_REQUEST['measure']);
$instructions = escape_data($_REQUEST['instructions']);
$graphicslogo = escape_data($_REQUEST['logo']);
$artselection = escape_data($_REQUEST['artselection']);
//$measure = ($_REQUEST['measure']);
//$instructions = ($_REQUEST['instructions']);
//$graphicslogo = ($_REQUEST['logo']);
//$artselection = ($_REQUEST['artselection']);
$deletearticle = $_REQUEST['deletearticle'];

$maxid2 = mysql_query("SELECT MAX(`id`) AS ID FROM $stackertable ");
$maxidarray2 = mysql_fetch_array($maxid2, MYSQL_BOTH);
$maxidint2 = $maxidarray2['ID'] + 1;
$searsdatamicroseconds2 = $date.$maxidint2;

//* ***** fetching the mediaimportkey for the media from hlmediacontrol
$query = "SELECT mediaimportkey, mediatemplate FROM $mediacontrol WHERE media = '$media'";
$results = mysql_query($query);
$row = mysql_fetch_array($results, MYSQL_NUM);
if (!$results)
{ 
 $error = mysql_error($mysqlconnect);
  echo "Failed to fetch mediaimportkey from hlmediacontrol! <br>$error";
}
else
{
$mediaimportkey = $row[0];
$mediatemplate = $row[1];
}

//*********fudging in the element layout for now until new dropdown list is approved 20110207 ****************
if($mediatemplate == 'Highland 9x11.125' )
{
if ($elementlayout == 'price')
{$elementlayout = '1 box dollar US';
}
elseif($elementlayout == 'pricelb')
{$elementlayout = '1 box dollar LB';
}
elseif($elementlayout == 'price feature')
{$elementlayout = '4 box dollar US';
}
elseif($elementlayout == 'pricelb feature')
{$elementlayout = '4 box dollar LB';
}
}
 //*********fudging in the element layout for now until new dropdown list is approved 20110207 ****************
if($mediatemplate == 'Highland 5.25x11.125 Wrap Back')
{
if ($elementlayout == 'price')
{$elementlayout = '1 box dollar US Wrap';
}
elseif($elementlayout == 'pricelb')
{$elementlayout = '1 box dollar LB Wrap';
}
elseif($elementlayout == 'price feature')
{$elementlayout = '2 box dollar US Wrap';
}
elseif($elementlayout == 'pricelb feature')
{$elementlayout = '2 box dollar LB Wrap';
}
}
 //***********************For new Highland Jewish Newspaper Ad 20170710 *********************************
if($mediatemplate == 'Highland Newspaper_JNP' )
{
if ($elementlayout == 'price')
{$elementlayout = 'Basic_US_NP';
}
elseif($elementlayout == 'pricelb')
{$elementlayout = 'Basic_LB_NP';
}
elseif($elementlayout == 'price feature')
{$elementlayout = 'Feature_US_NP';
}
elseif($elementlayout == 'pricelb feature')
{$elementlayout = 'Feature_LB_NP';
}
}
 //************For new Highland Russian Newspaper Ad 20171127 *****
 
if($mediatemplate == 'Highland Newspaper_RNP' )
{
if ($elementlayout == 'price')
{$elementlayout = 'Basic_US_RNP';
}
elseif($elementlayout == 'pricelb')
{$elementlayout = 'Basic_LB_RNP';
}
elseif($elementlayout == 'price feature')
{$elementlayout = 'Feature_US_RNP';
}
elseif($elementlayout == 'pricelb feature')
{$elementlayout = 'Feature_LB_RNP';
}
}

$user = $_REQUEST['user'];
$offerid = escape_data($mediaimportkey.str_pad($page, 4, "0", STR_PAD_LEFT).str_pad($position, 4, "0", STR_PAD_LEFT));
$flasharticle = 1;

// test if the article has not been exported which means it can be updated.
//$existquery = "select * from $stackertable where media='$media' and page='$page' and position='$position' and articlenumber = '$articlenumber' and exportedtolago = 0
//AND $stackermaxfield IN (SELECT MAX($stackermaxfield) FROM $stackertable WHERE media = '$media' AND page = '$page' AND articlenumber = '$articlenumber' AND position = '$position' GROUP BY articlenumber)";
$existquery = "select * from $stackertable where media='$media' and page='$page' and position='$position' and articlenumber = '$articlenumber' 
AND $stackermaxfield IN (SELECT MAX($stackermaxfield) FROM $stackertable WHERE media = '$media' AND page = '$page' AND articlenumber = '$articlenumber' AND position = '$position' GROUP BY articlenumber)";  //removed exportedtolago=0 requirement
$existresults = mysql_query($existquery);
$existnumnotexported = mysql_num_rows($existresults);    //this is if the article exists > 0 

while($lagorecord = mysql_fetch_array($existresults, MYSQL_ASSOC))
{
$currentrowid = $lagorecord['id'];
$updatemarkedforexport = $lagorecord['markedforexport'];
$artname = trim($lagorecord['articlename']); //now we leave the articlename as original
if($artname == NULL)
{
//$articlename = (substr($generaltext3,0,80));
$articlename = escape_data(substr($generaltext3,0,80));
}
else
{
//$articlename = ($artname);
$articlename = escape_data($artname);
} 
}
// test if the article exists
//$existqueryexported = "select * from $stackertable where media='$media' and page='$page' and position='$position' and articlenumber = '$articlenumber' and exportedtolago = 1";
$existqueryexported = "select * from $stackertable where media='$media' and page='$page' and position='$position' and articlenumber = '$articlenumber' ";   // test if the article exists
$existresultsexported = mysql_query($existqueryexported);
$existnumexported = mysql_num_rows($existresultsexported);       //will be greater than 0 if record exists but has been exported

while($lagorecord2 = mysql_fetch_array($existresultsexported, MYSQL_ASSOC))
{
$artname2 = trim($lagorecord2['articlename']); //now we leave the articlename as original
if($artname2 == NULL)
{
//$articlename = (substr($generaltext3,0,80));
$articlename = escape_data(substr($generaltext3,0,80));
}
else
{
//$articlename = ($artname2);
$articlename = escape_data($artname2);
} 
}

// make the update query string for nonexported record.
if($existnumnotexported > 0)
{
$insertupdatequery = "UPDATE $stackertable 
SET media='$media', mediaimportkey='$mediaimportkey' , page='$page', articlenumber='$articlenumber', category='$category', position='$position', textline1='$textlines', textcombined='$textlines', price='$price', pricelb='$pricelb', pricekg='$pricekg', mediatemplate='$mediatemplate', elementlayout='$elementlayout',uos='$uos', measure='$measure', instructions='$instructions', artselection='$artselection', graphicslogo='$graphicslogo', offerid='$offerid', lastuser='$user', savedate='$date2', markedforexport= 0, exportedtolago = 0, stackerfield='$searsdatamicroseconds2',  articlename='$articlename', elementname='$elementdisplayname', flasharticle='$flasharticle', deletearticle='$deletearticle'
WHERE media='$media' AND page='$page' AND articlenumber='$articlenumber' AND position='$position'
AND id = '$currentrowid' ";
}
//elseif($existnumexported > 0 || $existnumexported == 0)        //to eliminate stacking records this should read   elseif($existnumexported == 0)  (I think)
elseif($existnumexported == 0) 
{
if($articlename == NULL)
{
//$articlename = (substr($generaltext3,0,80));
$articlename = escape_data(substr($generaltext3,0,80));
}
else
{
//$articlename = ($articlename);
$articlename = escape_data($articlename);
} 
$insertupdatequery = "INSERT INTO $stackertable 
(media, mediaimportkey, page, articlenumber, category, position, textline1,textcombined, price, pricelb, pricekg, mediatemplate, elementlayout, uos, measure, instructions, artselection, graphicslogo, offerid, lastuser, savedate, markedforexport, exportedtolago,stackerfield,  articlename, elementname, flasharticle, deletearticle)
VALUES 
('$media', '$mediaimportkey', '$page', '$articlenumber', '$category',  '$position', '$textlines','$textlines', '$price', '$pricelb', '$pricekg', '$mediatemplate', '$elementlayout', '$uos', '$measure', '$instructions', '$artselection', '$graphicslogo' , '$offerid','$user', '$date2', 0, 0, '$searsdatamicroseconds2',  '$articlename', '$elementdisplayname', '$flasharticle', '$deletearticle')";
}
//run the insert or update query
 $insertupdateresults = mysql_query($insertupdatequery);
 if(!$insertupdateresults)
 {
 $error = mysql_error($mysqlconnect);
 echo "See Lago Group for help! <br>$error $insertupdatequery ";
 }
 else
 {
 echo "Success";
 }

}
else {
header("location:../index.php");
}

?>

<?php
session_start();
if(isset($_SESSION['id']) )
{
header('content-type: text/html; charset=utf-8'); 
error_reporting(-1);
include 'serverconfig.php';


if (isset($_REQUEST['articlenumber'])) {
  $artnum = $_GET['articlenumber']; 
  $media = $_GET['media']; 
  $page = $_GET['page']; 
  $position = $_GET['position'];
  
  //adding this for change from offer to article no in final dataarray.
//  $artnamefromscreen   = $_GET['textlines'];
  $artname = $_GET['articlename'];
	$price     = $_GET['price'];
  $pricelb   = $_GET['pricelb'];
  $pricekg   = $_GET['pricekg'];
  $elementlayoutget   = $_GET['elementlayout'];
  $elementlayout = trim($elementlayoutget);
	$textlines = $_GET['textlines'];
 	$unitofsale      = $_GET['unitofsale'];
	$measure      = $_GET['measure'];
 	$instructions      = $_GET['instructions'];
	$graphicslogo      = $_GET['logo'];
	$category      = $_GET['category'];
	$artselection = $GET['artselection'];
	$deletearticle = $GET['deletearticle'];

$checkexistsquery = "SELECT * FROM $stackertable WHERE articlenumber = '$artnum' AND media = '$media' 
and page = '$page' and position = '$position'";
$resultscheck = mysql_query($checkexistsquery);
$numrows = mysql_num_rows($resultscheck);

$checkhllagotablequery = "SELECT * FROM $hllagotable WHERE articlenumber = '$artnum'";
$resultscheckhllago = mysql_query($checkhllagotablequery);
$numrowshllago = mysql_num_rows($resultscheckhllago);

if($numrows > 0 )
{

	$q = "SELECT page, articlenumber, position, textcombined, $stackermaxfield, price, pricelb, pricekg, elementlayout, elementname, uos, measure, instructions, artselection, graphicslogo, media, category, deletearticle, articlename FROM $stackertable WHERE articlenumber ='$artnum' AND media = '$media' and page = '$page' and position = '$position' AND $stackermaxfield IN  (SELECT MAX($stackermaxfield) FROM $stackertable WHERE  articlenumber ='$artnum' AND media = '$media'
and page = '$page' and position = '$position')";
	$results = mysql_query($q);	

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
	// Initialize the PHP variable:
if($lagorecord['page'] == 0)
{
$page = $page;
}
else
{   
  $page = $lagorecord['page'];
}
    if($lagorecord['position']==0)
    {
 //   $position = $position;
    $position = "";
    }
    else
    {
    $position = $lagorecord['position'];
    }

	$artnumber = $lagorecord['articlenumber'];
	$artname   = $lagorecord['articlename'];
	$price     = $lagorecord['price'];
  $pricelb   = $lagorecord['pricelb'];
  $pricekg   = $lagorecord['pricekg'];
  $elementlayout   = $lagorecord['elementlayout'];
	$textlines = $lagorecord['textcombined'];
	$media      = $lagorecord['media'];
//  $elementname = $lagorecord['elementname'];
 	$unitofsale      = $lagorecord['uos'];
	$measure      = $lagorecord['measure'];
 	$instructions      = $lagorecord['instructions'];
	$graphicslogo      = $lagorecord['graphicslogo'];
	$category      = $lagorecord['category'];
	$artselection = $lagorecord['artselection'];
	$deletearticle = $lagorecord['deletearticle'];
	
	$trademarkarray = array("(r)", "(R)", "?", chr(63));
	
		$dataarray = array("page"=>"$page", "position"=>"$position", "articlenumber"=>"$artnumber","category"=>"$category","textlines"=>"$textlines", "price"=>"$price", "pricelb"=>"$pricelb", "pricekg"=>"$pricekg",  "elementlayout"=>"$elementlayout", "unitofsale"=>"$unitofsale", "measure"=>"$measure","instructions"=>"$instructions", "artselection"=>"$artselection","logo"=>"$graphicslogo", "deletearticle"=>$deletearticle, "articlename"=>"$artname");
		//Echo '{"createarticles":'.json_encode($dataarray).'}';
    echo '{"createarticles":'.json_encode($dataarray).'}';
}	
}
}
elseif($numrows == 0 && $numrowshllago > 0)
{
// echo "in the hllagodata fetch";
	$q = "SELECT page, articlenumber, position, textcombined, $lagostackermaxfield, price, pricelb, pricekg, elementlayout, elementname, uos, measure, instructions, artselection, graphicslogo, media, category, deletearticle, articlename FROM $hllagotable WHERE articlenumber ='$artnum' AND $lagostackermaxfield IN  (SELECT MAX($lagostackermaxfield) FROM $hllagotable WHERE  articlenumber ='$artnum') AND media <> 'HLFrawdata'";
	$results = mysql_query($q);	

  if(!$results)
  {
  $error = mysql_error($mysqlconnect);
  echo "Error see lago group this is fetch2<br>";
  echo "$error";
  }
  else
  {	
  while($lagorecord = mysql_fetch_array($results, MYSQL_ASSOC))
  {
  	// Initialize the PHP variable:
  if($lagorecord['page'] == 0)
  {
  $page = $page;
  }
  else
  {   
    $page = $lagorecord['page'];
  }
  if($lagorecord['position']==0)
  {
   $position = $position;
//    $position = "";
  }
  else
  {
  $position = $lagorecord['position'];
  }
		$artnumber = $lagorecord['articlenumber'];
	$artname   = $lagorecord['articlename'];
	$price     = $lagorecord['price'];
  $pricelb   = $lagorecord['pricelb'];
  $pricekg   = $lagorecord['pricekg'];
  $elementlayout   = $lagorecord['elementlayout'];
	$textlines = $lagorecord['textcombined'];
	$media      = $lagorecord['media'];
//  $elementname = $lagorecord['elementname'];
 	$unitofsale      = $lagorecord['uos'];
	$measure      = $lagorecord['measure'];
 	$instructions      = $lagorecord['instructions'];
	$graphicslogo      = $lagorecord['graphicslogo'];
	$category      = $lagorecord['category'];
	$artselection = $lagorecord['artselection'];
	$deletearticle = $lagorecord['deletearticle'];
	
	$trademarkarray = array("(r)", "(R)", "?", chr(63));
	
  $dataarray = array("page"=>"$page", "position"=>"$position", "articlenumber"=>"$artnumber","category"=>"$category","textlines"=>"$textlines", "price"=>"$price", "pricelb"=>"$pricelb", "pricekg"=>"$pricekg", "elementlayout"=>"$elementlayout", "unitofsale"=>"$unitofsale", "measure"=>"$measure","instructions"=>"$instructions" , "artselection"=>"$artselection", "logo"=>"$graphicslogo","deletearticle"=>$deletearticle, "articlename"=>"$artname" );

    echo '{"createarticles":'.json_encode($dataarray).'}';
	}
}	
} //with the elseif that fetches hllagodata
else
{
//echo "just setting the variables without fetch";
$page = $page;
$position = $position;

	$trademarkarray = array("(r)", "(R)", "?", chr(63));
	
  $dataarray = array("page"=>"$page", "position"=>"$position", "articlenumber"=>"$artnum", "category"=>"$category","textlines"=>"$textlines", "price"=>"$price", "pricelb"=>"$pricelb", "pricekg"=>"$pricekg", "elementlayout"=>"$elementlayout", "unitofsale"=>"$unitofsale", "measure"=>"$measure","instructions"=>"$instructions" , "artselection"=>"$artselection", "logo"=>"$graphicslogo","deletearticle"=>$deletearticle, "articlename"=>"$artname" );

    echo '{"createarticles":'.json_encode($dataarray).'}';
}

}
}
else {
header("location:../index.php");
}
?>

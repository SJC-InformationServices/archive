<?php
error_reporting(-1);
chdir("/storage/www/hlf/includes");
include "serverconfig.php";
$tolist = 'bmiller@pimedia.com;lbuhajczyk@stjosephcontent.com';

if(file_exists("../$exportdir/$exportfileforload") || file_exists("../$exportdir/$elementonlyfile"))
{
echo "Load File(s) Exist.";
//mail($tolist, "HLF Load file(s) exist on 10.2.1.141!", "HLF deployed system - load files exist!");
exit;
}
else
{
$sqlmedia = "Select Distinct(media) from $stackertable where markedforexport = '1' and exportedtolago = '0'";
$mediaresults = mysql_query($sqlmedia);
while($media = mysql_fetch_array($mediaresults))
{

foreach($media as $loadmedia)
{
//echo " this is the media $media and this is loadmedia $loadmedia";
// fetch out the media template from hlmediacontrol
$query= "SELECT * FROM $mediacontrol WHERE media = '$loadmedia'";
$results = mysql_query($query);
//echo "results query = $results";
$row = mysql_fetch_row($results);
if(!$results)
{
//echo "<br> Failed to get media template<br>";
}
else
{
$mediatemplate = $row[6];
}

$sqlelements = "Select * from $stackertable where markedforexport = 1 and exportedtolago = 0 and media = '$loadmedia' and stackerfield in (select max(stackerfield) from $stackertable where markedforexport = 1 and exportedtolago = 0 and media = '$loadmedia' group by offerid, articlenumber) group by offerid, articlenumber order by deletearticle asc, media, page, position, articlenumber";
$sqlresults = mysql_query($sqlelements);
if(!$sqlresults)
{
//echo "<br>Failed to Get Load Results<br>$sqlelements";

}
else
{
//echo "<br>Media being loaded  $loadmedia<hr>";
$x=0;

while($elementresults = mysql_fetch_array($sqlresults))
{

//if($elementresults['articlenumber'] > 0)
if($elementresults['articlenumber'] > 0 && $elementresults['deletearticle'] == 0)
{
$file = fopen("../$exportdir/$exportfileforload", 'ab');
}
elseif($elementresults['articlenumber'] > 0 && $elementresults['deletearticle'] == 1)
{
$file = fopen("../$exportdir/$deletefileforload", 'ab');
}
else
{
$file = fopen("../$exportdir/$elementonlyfile", 'ab');
}

$string = $elementresults['mediaimportkey']."\t".$elementresults['page']."\t".$elementresults['position']."\t".$elementresults['category']."\t".$elementresults['articlenumber']."\t".$elementresults['articlename']."\t".$elementresults['textcombined']."\t".$elementresults['elementname']."\t".$elementresults['price']."\t".$elementresults['pricelb']."\t".$elementresults['pricekg']."\t".$elementresults['uos']."\t".$elementresults['measure']."\t".$elementresults['instructions']."\t".$elementresults['offerid']."\t".$elementresults['graphicslogo']."\t".$elementresults['flasharticle']."\t".$mediatemplate."\t".$elementresults['elementlayout']."\t".$elementresults['deletearticle']."\n";

if ($elementresults['deletearticle'] == 1)
{
$delart = 'DELETED';
}
else
{
$delart = '';
}
$mailstring = $elementresults['mediaimportkey']."\t".$elementresults['page']."\t".$elementresults['position']."\t".$elementresults['articlenumber']."\t".$elementresults['textcombined']."\t[".$elementresults['artselection']."]\t".$elementresults['instructions']."\t".$delart."\r\n";

//echo implode(" \t ", $elementresults)."<br>";
fwrite($file, $string);
fclose($file);
$date = date('Y-m-d H:i');
$updatestatment = "update $stackertable set exportedtolago = 1, loadtolagodate = '$date' where media='$loadmedia' and articlenumber='".escape_data($elementresults['articlenumber'])."' and offerid='".$elementresults['offerid']."'";
//echo "$updatestatment<br>";
$updateresults = mysql_query($updatestatment);
if(!$updateresults)
{
$newdate= date('Ymd');
$error = mysql_error($mysqlconnect);
$log = fopen("..//logs//loadfailedlog-$newdate.txt", 'ab');
fwrite($log, "$updatestatment\n$error\n-------------------------------------------------\n");
fclose($log);
//echo "$error<br>";
}
else
{
$headers = "From: lago@pimedia \r\n";
$headers.= "Content-Type: text/html; charset=ISO-8859-1 ";
$headers .= "MIME-Version: 1.0 "; 
//array_push($emailstring, "$mailstring");
$emailstring .= $mailstring;
//$emailstring .= "____________________________________________________________________________________________________________\r\n";
$x++;


}

}
}

}
}
if(file_exists("../$exportdir/$exportfileforload") || file_exists("../$exportdir/$deletefileforload") || file_exists("../$exportdir/$elementonlyfile"))
{
$mailsubject = "Media has been exported.";
$mailmessage = "Media has been exported and will be applied in Lago within 30 minutes.";
//mail($tolist, $mailsubject, $mailmessage);
//$tolist = 'bob.miller@stjoseph.com;luke.buhajczyk@stjoseph.com;stefan.schoenebeck@stjoseph.com;stephanie.walker@stjoseph.com';
$tolist = 'bob.miller@stjoseph.com;luke.buhajczyk@stjoseph.com;jeff.desjardins@stjoseph.com;stefan.schoenebeck@stjoseph.com;maria.gagliano@stjoseph.com;brad.morgan@stjoseph.com;stephanie.walker@stjoseph.com';
//$tolist = 'bob.miller@stjoseph.com;luke.buhajczyk@stjoseph.com;stephanie.walker@stjoseph.com';
// we use this one below bobby
        mail($tolist, 'HLF art instructions.', $emailstring);
}
}
//************************************* new code for .24
//now move the files to the Article_import server
/*
$sshconn = ssh2_connect('10.2.1.212', 22);
if(!$sshconn){
	echo "FAiled Server Connection";
	exit();
	}
	else{
		if(!ssh2_auth_password($sshconn, 'root', 'como888!'))
		{
			echo "FAiled Server Connection";
			exit();
		}
	}

 */
// upload the article file
//*****
if(file_exists("../$exportdir/$exportfileforload"))
{
      $fname = "../exportedtext/$exportfileforload";
      $fnamenew = "../exportedtext/HLF_Retail_LoadFilenew-".date('Ymd-His').".imp";
      copy($fname, $fnamenew) or die("Unable to copy $fname to $fnamenew.");
      rename($fname,"/mnt/Lago_LFS/lagoimports/highland/$exportfileforload");
      rename($fnamenew,"/mnt/Lago_LFS/lagoimports/highlandnew/HLF_Retail_LoadFilenew-".date('Ymd-His').".imp"); 
      //we use this one below bobby     
      mail($tolist, "$exportfileforload upload succeeded!", "$exportfileforload file uploaded.");
}
      
/*      echo "$fname";

        if(is_file($fname)){			
				if(ssh2_scp_send($sshconn,"$fname","/lago/import/highland/$exportfileforload"))*/
			//mail($tolist, "$exportfileforload upload succeeded!", "$exportfileforload file uploaded.");
/*				
				unlink("$fname");
				}else{
				             //mail($tolist, "HLF $exportfileforload upload failed!", "HLF $exportfileforload upload to 212 Transfer server failed!");
				}		
		}
*/
// upload the deletearticle file
//*****

      $fname = "../exportedtext/$deletefileforload";
      $fnamenewdel = "../exportedtext/HLF_Delete_LoadFilenew-".date('Ymd-His').".imp";
      copy($fname, $fnamenewdel) or die("Unable to copy $fname to $fnamenewdel."); 
      rename($fname,"/mnt/Lago_LFS/lagoimports/highland/$deletefileforload");
      rename($fnamenewdel,"/mnt/Lago_LFS/lagoimports/highlandnew/HLF_Delete_LoadFilenew-".date('Ymd-His').".imp"); 
      
     // mail($tolist, "HLF $deletefileforload upload succeeded!", "HLF $deletefileforload file uploaded.");
     
       
/*       echo "$fname";

        if(is_file($fname)){			
				if(ssh2_scp_send($sshconn,"$fname","/lago/import/highland/$deletefileforload"))

				
				unlink("$fname");
				}else{
				             //mail($tolist, "HLF $deletefileforload upload failed!", "HLF $deletefileforload upload to 212 Transfer server failed!");
				}		
		}
    */
// upload the element file
//*****
//    if(file_exists("../$exportdir/$elementonlyfile"))
//{
//       $fname = "../exportedtext/$elementonlyfile";
//       rename($fname,"/mnt/Lago_LFS/lagoimports/highland/$elementonlyfile");
//      mail($tolist, "$elementonlyfile upload succeeded!", "$elementonlyfile file uploaded.");
//     }       
//       echo "$fname";

//        if(is_file($fname)){			
//				if(ssh2_scp_send($sshconn,"$fname","/lago/import/highland/$elementonlyfile"))
//				{mail($tolist, "HLF $elementonlyfile upload succeeded!", "HLF $elementonlyfile file uploaded.");
				
//				unlink("$fname");
//				}else{
				            // mail($tolist, "HLF $elementonlyfile upload failed!", "HLF $elementonlyfile File upload to 212 Transfer server failed!");
//				}		
//		}
    
 
   
?>


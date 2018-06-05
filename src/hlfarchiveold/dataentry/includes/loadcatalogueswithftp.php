<?php
//error_reporting(-1);
ini_set("error_log","/var/log/createcatalog.log");
chdir("/storage/www/hlf/includes");
include "serverconfig.php";
$tolist = 'bob.miller@stjoseph.com;luke.buhajczyk@stjoseph.com';
$fname = "../exportedtext/$cataloguefileforload";
$fnamenew = "../exportedtext/HLF_makecataloguenew.imp";
copy($fname, $fnamenew) or die("Unable to copy $fname to $fnamenew.");
if(!rename($fname,"/mnt/Lago_LFS/lagoimports/highland/$cataloguefileforload".date('Ymd Hi').".imp" ))
{
error_log("Failed To Move File");
}
else{
mail($tolist, "HLF Catalogue File upload succeeded!", "HLF Catalogue file uploaded.");

}

if(!rename($fnamenew,"/mnt/Lago_LFS/lagoimports/highlandnew/HLF_makecataloguenew".date('Ymd Hi').".imp" ))
{
error_log("Failed To Move File");
}
else{
mail($tolist, "HLF NEW Catalogue File upload succeeded!", "HLF NEW Catalogue file uploaded.");
}
unlink("$fname");
unlink("$fnamenew");

/*
//*********************************************new code for .24

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
//*****
       $fname = "../exportedtext/$cataloguefileforload";
       echo "$fname";

        if(is_file($fname)){			
				if(ssh2_scp_send($sshconn,"$fname","/lago/import/highland/makecatalogue".date('Ymd Hi').".imp"))
				{mail($tolist, "HLF Catalogue File upload succeeded!", "HLF Catalogue file uploaded.");
				
				unlink("$fname");
				}else{
				             mail($tolist, "HLF Catalogue File upload failed!", "HLF Catalogue File upload to 212 Transfer server failed!");
				}		
		}
//*********************************************new code for .24     */

?>

function Log([string]$error){
    Write-Host($error);
}
function Connect-MySQL([string]$user,[string]$pass,[string]$MySQLHost,[string]$database) { 
  # Load MySQL .NET Connector Objects 
    [void][system.reflection.Assembly]::LoadWithPartialName("MySql.Data") 
 
    # Open Connection 
    $connStr = "server=" + $MySQLHost + ";port=3306;uid=" + $user + ";pwd=" + $pass + ";database="+$database+";Pooling=FALSE;Allow User Variables=True;defaultcommandtimeout=600;connectiontimeout=25" 
    try {
        $conn = New-Object MySql.Data.MySqlClient.MySqlConnection($connStr) 
        $conn.Open()
    } catch [System.Management.Automation.PSArgumentException] {
        Log "Unable to connect to MySQL server, do you have the MySQL connector installed..?"
        Log $_.Exception.Message
        Exit
    } catch {
        Log "Unable to connect to MySQL server..."
        Log $_.Exception.GetType().FullName
        Log $_.Exception.Message
        exit
    }
    Log "Connected to MySQL database $MySQLHost\$database"

  return $conn 
}
 
function Execute-MySQLNonQuery($conn, [string]$query) { 
  $command = $conn.CreateCommand()                  # Create command object
  $command.CommandText = $query                     # Load query into object
  $RowsInserted = $command.ExecuteNonQuery()        # Execute command
  $command.Dispose()                                # Dispose of command object
  if ($RowsInserted) { 
    return $RowInserted 
  } else { 
    return $false 
  } 
} 
function Execute-MySQLQuery([string]$query) { 
  # NonQuery - Insert/Update/Delete query where no return data is required
  $cmd = New-Object MySql.Data.MySqlClient.MySqlCommand($query, $db)    # Create SQL command
  $dataAdapter = New-Object MySql.Data.MySqlClient.MySqlDataAdapter($cmd)      # Create data adapter from query command
  $dataSet = New-Object System.Data.DataSet                                    # Create dataset
  $dataAdapter.Fill($dataSet, "data")                                          # Fill dataset from data adapter, with name "data"              
  $cmd.Dispose()
  return $dataSet.Tables["data"]                                               # Returns an array of results
}
function Escape-MySQLText([string]$text) {
    $text = [regex]::replace($text, "\\", "\\\\")
    #$text = [regex]::replace($text, '"', '\"')
    $text = [regex]::replace($text, "'", "\'")
    return $text
}
function Disconnect-MySQL($conn) {
  $conn.Close()
}
function Is-Numeric ($Value) {
  return $Value -match "^[\d\.]+$"
}
function Format-Json([Parameter(Mandatory, ValueFromPipeline)][String] $json) {
  $indent = 0;
  ($json -Split '\n' |
    ForEach-Object {
      if ($_ -match '[\}\]]') {
        # This line contains  ] or }, decrement the indentation level
        $indent--
      }
      $line = (' ' * $indent * 2) + $_.TrimStart().Replace(':  ', ': ')
      if ($_ -match '[\{\[]') {
        # This line contains [ or {, increment the indentation level
        $indent++
      }
      $line
  }) -Join "`n"
}

function insertArchiveRow($conn,$row,$tbl,$logfile)
{
  $obj = @{}
  $row.PSObject.Properties | ForEach-Object {
    $ka = $_.Name.ToLower() -replace '[^\w]',''
    $k = $ka.Trim() -replace '[^a-z]','_'
    $v = $_.Value
    if($null -ne $v){
      $obj[$k] = $v.ToString().Trim() -replace '\\','\\\\' -replace '"','\"' -Replace "`n",'\\n' -Replace "'","\'" -Replace "`r",""
    }
  }
  $ins = $obj | ConvertTo-Json -Depth 50 | ForEach-Object { [System.Text.RegularExpressions.Regex]::Unescape($_) } 
   
  $insObj = $ins -replace '"','\"'
  
  $qry = "insert into ``$tbl`` (``rawdata``) values ('" + $insObj + "') on duplicate key update ``rawdata``=archiveJsonMerge(``rawdata``, '" + $insObj + "')"

  try{
    $exqry = Execute-MySQLNonQuery $db $qry
    return $exqry;
  }catch{
          $ErrorMessage = $_.Exception.Message
          $of = $logfile + ".log"
          $oj = $logfile + ".json"
          $str = "<div>" +$ErrorMessage + "</div><div>"+ $insObj + '</div><div>' + $qry + '</div><div>'         
          $str | Out-File $of -Encoding utf8    
          $insObj | Out-File $oj -Encoding utf8
          return $false
  }

}

#db credentials
$dbHost = "sjc-archive-prod.cluster-cpi3jpipzm32.us-east-1.rds.amazonaws.com"
$dbuser = "sjcArchiveAdmin"
$dbpass = "5jcAdmin!"
$database = "sjcMckessonArchive";
$db = Connect-MySQL $dbuser $dbpass $dbHost $database
$path = "excelFiles";
$files = Get-ChildItem $path -Force -Filter "*.xlsx"
$previousObject = New-Object PSObject;
ForEach($f in $files)
{
    
    $offers = Import-Excel -Path $f.fullName -WorksheetName "Proposition promotion" -DataOnly -StartRow 7
    $offers.length
    $projectid = "1"
    for($count = 0; $count -lt $offers.length; $count++)
    {
      $count;
      $offer = $offers[$count];
      
      $productlog = "jsonfiles\products\products.log"
      $offerlog = "jsonfiles\offers\offers.log"
      
      if ($offer."Advertising Description"){
        #Create New Product
        #Create New Offer
        $itemProp = @{}
        $offerProp = @{}
        
    $offer.PSObject.Properties | ForEach-Object {
    $ka = $_.Name.ToLower() -replace '[^\w]','-'
    $k = $ka.Trim() -replace '[^a-z]','_' -replace "__","_"
    $v = $_.Value
    if($null -ne $v){
      $offerProp[$k] = $v.ToString().Trim() -replace '\\','\\\\' -replace '"','\"' -Replace "`n",'\\n' -Replace "'","\'" -Replace "`r","" -Replace "`t"," "
    }
  }
       $itemProp.gtin = $offerProp.ad_shot_upc
       $itemProp.advertising_description = $offerProp["advertising_description"]
       $offerProp["gtin"] = $offerProp.ad_shot_upc
       $offerProp["pagefrom"] = $offerProp.page_
       $offerProp["layout_position"] = [regex]::Matches([regex]::Matches($offerProp.head_office_comments,"[grid].\d+").value,"\d+").value
       $inso = $offerProp | ConvertTo-Json -Depth 50 | ForEach-Object { [System.Text.RegularExpressions.Regex]::Unescape($_) } 
       $insa = $itemProp | ConvertTo-Json -Depth 50 | ForEach-Object { [System.Text.RegularExpressions.Regex]::Unescape($_) } 

       $insObj = $inso -replace '"','\"'
       $insObjb = $insa -replace '"','\"'
       $previousObject = $offerProp;
       $qry = "insert into ``offers`` (``rawdata``,``project_id`) values ('" + $insObj + "','" + $projectid + "') on duplicate key update ``rawdata``=archiveJsonMerge(``rawdata``, '" + $insObj + "')"
       $qryb = "insert into ``products`` (``rawdata``) values ('" + $insObjb + "') on duplicate key update ``rawdata``=archiveJsonMerge(``rawdata``, '" + $insObjb + "')"
       write-host("NEW")
       #$qry
       #$qrybd
       
       try{
         Execute-MySQLNonQuery $db $qry
         Execute-MySQLNonQuery $db $qryb
       }catch{
               $ErrorMessage = $_.Exception.Message
               $of = $logfile + ".log"
               $oj = $logfile + ".json"
               $str = "<div>" +$ErrorMessage + "</div><div>"+ $insObj + '</div><div>' + $qry + '</div><div>'         
               $str | Out-File $of -Encoding utf8    
               $insObj | Out-File $oj -Encoding utf8
               
       }
      }
      elseif(!$offer."Advertising Description" -and $offer."Ad Shot UPC")
      {
        #Create / Update Items
        #Add Item To Offer
        $offerprev = $previousObject;
       
        $itemProp = @{}
        
        $offer.PSObject.Properties | ForEach-Object {
          $ka = $_.Name.ToLower() -replace '[^\w]','-'
          $k = $ka.Trim() -replace '[^a-z]','_' -replace "__","_"
          $v = $_.Value
          if($null -ne $v){
            $itemProp[$k] = $v.ToString().Trim() -replace '\\','\\\\' -replace '"','\"' -Replace "`n",'\\n' -Replace "'","\'" -Replace "`r",""
          }
        }
        $itemProp["gtin"] = $itemProp.ad_shot_upc
        
        $insa = $itemProp | ConvertTo-Json -Depth 50 | ForEach-Object { [System.Text.RegularExpressions.Regex]::Unescape($_) } 
 
        $insObj = $insa -replace '"','\"'

        $qry = "update ``offers`` set ``rawdata`` = JSON_SET(``rawdata``,'$.gtin', CONCAT(``rawdata``->>'$.gtin',' ','" +  $itemProp["gtin"] + "')) where ``project_id`` = '" + $projectid + "' and ``pagefrom`` = '" + $offerprev["pagefrom"] + "' and ``layout_position`` = '" + $offerprev["layout_position"] + "' limit 1"
        $qryb = "insert ignore into ``products`` (``rawdata``) values ('" + $insObj + "') "
        #$qry
        #$qryb
        write-host("update")
        try{
          Execute-MySQLNonQuery $db $qry
          Execute-MySQLNonQuery $db $qryb
        }catch{
                $ErrorMessage = $_.Exception.Message
                $of = $logfile + ".log"
                $oj = $logfile + ".json"
                $str = "<div>" +$ErrorMessage + "</div><div>"+ $insObj + '</div><div>' + $qry + '</div><div>'         
                $str | Out-File $of -Encoding utf8    
                $insObj | Out-File $oj -Encoding utf8
                
        }

      }
      else{
        #INSF
      }

    }
    
}






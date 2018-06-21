function Log([string]$error){
    Write-Host($error);
}
function Connect-MySQL([string]$user,[string]$pass,[string]$MySQLHost,[string]$database) { 
  # Load MySQL .NET Connector Objects 
    [void][system.reflection.Assembly]::LoadWithPartialName("MySql.Data") 
 
    # Open Connection 
    $connStr = "server=" + $MySQLHost + ";port=3306;uid=" + $user + ";pwd=" + $pass + ";database="+$database+";Pooling=FALSE;Allow User Variables=True;" 
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

function insertAlphaRow($conn,$row,$tbl,$logfile)
{
  $obj = @{}
  $row.PSObject.Properties | ForEach-Object {
    $ka = $_.Name.ToLower() -replace '[^\w]',''
    $k = $ka.Trim() -replace '[^a-z]','_'
    $v = $_.Value
    if($v -ne $null){
      $obj[$k] = $v.ToString().Trim() -replace '"','\"' -Replace "`n",'\\n' -Replace "'","\'"
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
$database = "sjcAlphaBroderArchive";
$db = Connect-MySQL $dbuser $dbpass $dbHost $database
$path = "excelFiles";
$files = Get-ChildItem $path -Force -Filter "*.xlsx"

ForEach($f in $files)
{
    
    $styles = Import-Excel -Path $f.fullName -WorksheetName "Styles" -DataOnly
    $colors = Import-Excel -Path $f.fullName -WorksheetName "Colors" -DataOnly
    
    foreach($srow in $styles)
    {      
        write-host $srow."AB Style #"
        $tbl = "products"
        $style = $srow."AB Style #"
        $log = "jsonfiles\styles\" + $style
        $ins = insertAlphaRow $db $srow $tbl $log
        $ins
    }
    foreach($crow in $colors){
        write-host $crow."AB Style #" + $crow."Color Name"
        $tbl = "colors"
        $style = $crow."AB Style #"
        $color = $crow."Color Name".toString() -replace '[^a-z]',''
        $log = "jsonfiles\colors\" + $style + "-" + $color
        $ins = insertAlphaRow $db $crow $tbl $log
        $ins      
    }
}






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

    foreach($row in $styles){
        $obj = ConvertTo-Json($row) -Depth 50 | % { [System.Text.RegularExpressions.Regex]::Unescape($_) }
        $insObj = $obj -replace "null" -replace "`r`n","`n" -replace '\\','\\\\' -replace "'", "\'" -replace '"', '\"'
        $qry = "insert into ``products`` (``rawdata``) values ('" + $insObj + "')"
        #write-host $qry
        Execute-MySQLNonQuery $db $qry
        $of = "jsonfiles\" + $row."AB Style #" + ".json"
        ConvertTo-Json($row) -Depth 50 | % { [System.Text.RegularExpressions.Regex]::Unescape($_) } | Out-File  $of -Encoding utf8
    }
    foreach($row in $colors){
        $obj = ConvertTo-Json($row) -Depth 50 | % { [System.Text.RegularExpressions.Regex]::Unescape($_) } 
        $insObj = $obj -replace "`r`n","`n" -replace '\\','\\\\' -replace "'", "\'" -replace '"', '\"'
        $qry = "insert into ``products`` (``rawdata``) values ('" + $insObj + "')"
        #write-host $qry
        #$of = "jsonfiles\" + $row."AB Style #" + $row."Color Name" +".json"
        ConvertTo-Json($row) -Depth 50 | % { [System.Text.RegularExpressions.Regex]::Unescape($_) } | Out-File  $of -Encoding utf8
        Execute-MySQLNonQuery $db $qry
    }

}



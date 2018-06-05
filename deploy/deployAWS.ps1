Import-Module AWSPowerShell
Set-DefaultAWSRegion -Region us-east-1

$date = get-date -format yyyy-MM-dd-HHmmss
$datestr = $date.ToString()
$name = "sjcArchiveDeploy-"+$datestr+".zip"
git add --all 
git commit -m "sjcArchiveDeploy $name"
#git archive -v -o $name --format=zip HEAD
#Write-S3Object -BucketName sjcarchivefiles-dev -File $name
#aws elasticbeanstalk create-application-version --application-name "SJC_Archive" --version-label $name --description sjc_archive_dev --source-bundle S3Bucket="sjcarchivefiles-dev",S3Key=$name
#Update-EBEnvironment -ApplicationName "SJC_Archive" -EnvironmentName "sjcarchiveold" -VersionLabel $name
#Remove-Item -path $name
#aws s3 cp "C:\Users\kevin_000\Documents\DevWorkSpaces\sdmArchive\lib\sjccontent\" s3://sjcarchiveassets/lib/sjccontent/ --recursive

#aws s3 cp "C:\Users\kevin_000\Documents\DevWorkSpaces\sdmArchive\lib\css\" s3://sjcarchiveassets/lib/css/ --recursive
#aws s3 cp "C:\Users\kevin_000\Documents\DevWorkSpaces\sdmArchive\lib\apps\" s3://sjcarchiveassets/lib/apps/ --recursive

#aws s3 cp "C:\Users\kevin_000\Documents\DevWorkSpaces\sdmArchive\lib\images\" s3://sjcarchiveassets/lib/images/ --recursive
eb deploy
#New-EBApplicationVersion -ApplicationName SJC_Archive -VersionLabel $name -SourceBuildInformation_SourceType Zip -SourceBuildInformation_SourceRepository S3 -SourceBuildInformation_SourceLocation sjcarchivefiles-dev/$name

# SIG # Begin signature block
# MIIFdgYJKoZIhvcNAQcCoIIFZzCCBWMCAQExCzAJBgUrDgMCGgUAMGkGCisGAQQB
# gjcCAQSgWzBZMDQGCisGAQQBgjcCAR4wJgIDAQAABBAfzDtgWUsITrck0sYpfvNR
# AgEAAgEAAgEAAgEAAgEAMCEwCQYFKw4DAhoFAAQUeM3rvVjVYqG346Vz4W7wq1w5
# ISagggMOMIIDCjCCAfKgAwIBAgIQMbVpKr4JObFBz03yO2iUoTANBgkqhkiG9w0B
# AQsFADAdMRswGQYDVQQDDBJQb3dlclNoZWxsIFNpZ25pbmcwHhcNMTgwNjA1MTY0
# MTQwWhcNMTkwNjA1MTcwMTQwWjAdMRswGQYDVQQDDBJQb3dlclNoZWxsIFNpZ25p
# bmcwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC1IsD2/QMB72BpSGkY
# hJHfdRL4cuiaB834zWr9Mb/Yk0WCqxrb3Y+5P3df9hISn3pCDy4Urr0kogxHRDRV
# tiISEL7SY1xeeragEbrHISnKTqoyJ76NCTVKuHWVRLnkCQvhLNYlleuWT/VhglKM
# rY0VAIULze7uycEsFQIMVQY9eYFsXgq0dJZi7md7uj2y7s7AeQ2jVarR4j/Gtfm2
# ODDnYtRtGdcjbvjiv5zPFjk/Db5Coa6cj/od2/KG1i65nH9fG8WzDticBt8/QPmY
# A609VXd/T3seSkIiZFi0HawA0f/cbR5vOcVlFMqzT53oEiw0KvnVDkcUpyMmAoU8
# VPmZAgMBAAGjRjBEMA4GA1UdDwEB/wQEAwIHgDATBgNVHSUEDDAKBggrBgEFBQcD
# AzAdBgNVHQ4EFgQUdb/FJizS04/v3p7WYX1hEOBEQDkwDQYJKoZIhvcNAQELBQAD
# ggEBACBacjQe0x9dE2l4hU5qqpBvZz7Uq73K9Je9I+IZl5WRPyQOoKi1VUV6wMZP
# lV3FJsYDJXJ1jwrtwKrL+sVUC0JW5M+TJr9BszaYTNtVwNQV4YuTECx58kUbh6Dq
# MUswzEQuwzaSJ3qp2enhX3a/J53aoBM5j1K3V091cWL+1L7fLhdFHn933wpUbL5s
# DvAq37kZlWOrEoe2OIM6ULuaGIADEdcMVURQzNeF9v2xV9NcOZ1oqbIMMO0nTyAI
# SiXjjmV57H6lF7gR0cm7fc9mO+x3Rhdu5tZ58kpU9aHH0Idj3h5dGX91MR8+quiX
# g8RSvMqqTeSJixSH37Dvk9H2g6wxggHSMIIBzgIBATAxMB0xGzAZBgNVBAMMElBv
# d2VyU2hlbGwgU2lnbmluZwIQMbVpKr4JObFBz03yO2iUoTAJBgUrDgMCGgUAoHgw
# GAYKKwYBBAGCNwIBDDEKMAigAoAAoQKAADAZBgkqhkiG9w0BCQMxDAYKKwYBBAGC
# NwIBBDAcBgorBgEEAYI3AgELMQ4wDAYKKwYBBAGCNwIBFTAjBgkqhkiG9w0BCQQx
# FgQUOIMNLt+C0cOWQCx/x6S7cEmQFGYwDQYJKoZIhvcNAQEBBQAEggEAL5FIzkFq
# mI1xZu5g/L9sqkET5jDFoA8zWqDMgJLaN+H0gyziLRmsQU+11SbNM1kvNK0RIxqF
# 636deFa9VZkNX+KR4Bo2MHnH0vy968bTqn6dYhme37M52vxGQaz6WJUgnfBYNSLq
# hxM0gUzvJnfuJF1dkhdbWQwFjVI/H9s70fqS27+3zh9kZko6z9kzFLW8o4ip09TQ
# 0bDM8HS6TMWiDTS2EfkY1gJ3ti2EoEw4C6haejhXH6aQh8251FdMUDFuFHtkzItA
# zvOuDLcSouwLg46qYRPH6Ei78+4Wzsa43kQg+ZVDbvz5WMQB2pZmWiXRlzbI5e8j
# WwKtg3aJR6FrPg==
# SIG # End signature block

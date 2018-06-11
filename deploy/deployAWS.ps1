Import-Module AWSPowerShell
Set-DefaultAWSRegion -Region us-east-1

$date = get-date -format yyyy-MM-dd-HHmmss
$datestr = $date.ToString()
$name = "sjcArchiveDeploy-"+$datestr+".zip"
git add --all;git commit -m "sjcArchiveDeploy";eb deploy;
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
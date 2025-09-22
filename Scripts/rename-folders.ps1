$folders = Get-ChildItem -Recurse -Path "C:\Users\lprab\iCloudDrive\iCloud~md~obsidian\Paperlapapp\"

foreach ($folder in $folders) {
    if ($folder -match "[0-9]{0} - .*") {
        write-host $folder.FullName
        $dest = $folder.FullName -replace " - ", "_"
        Write-Host $dest
        Move-Item -Path  $folder.FullName -Destination $dest 
    }
}
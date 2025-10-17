$consoleOutputFile = "C:\Users\lprab\Desktop\CS\ValueScript\ConsoleOutput.txt"
$logFile = "B:\SteamLibrary\steamapps\common\Counter-Strike Global Offensive\game\csgo\console.log"

$ConsoleRegex = "\[Console\]\s*(.*)"
$VoiceRegex = 
$MapRegex = 

# $Arguments = @(
#     "-NoExit"
#     "-Command"
#     "Get-Content -Path `"$logFile`" -Wait | Where-Object {`$_ -match `"\[Console\]\s*(.*)`"} | ForEach-Object { `$matches[1] | Out-File -FilePath `"$consoleOutputFile`" -Append }"
# )

# Start-Process -FilePath "powershell.exe" -



Get-Content -Path $logFile -Wait | 
Where-Object { $_ -match $ConsoleRegex } |
ForEach-Object {
    $matches[1] | Out-File -FilePath $consoleOutputFile -Append
} 

Get-Content -Path $consoleOutputFile -Wait 
Get-Content -Path $consoleOutputFile -Wait | Where-Object { $_ -match $LogRegex } 


$InfoLinePrefix = "[i]" # Information Command (Like help or so)
$ValueLine = "[v]"
$VoiceVal = ""
$MapVal = ""
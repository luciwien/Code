$consoleOutputFile = "C:\Users\lprab\Desktop\CS\ValueScript\ConsoleOutput.txt"
$logFile = "B:\SteamLibrary\steamapps\common\Counter-Strike Global Offensive\game\csgo\console.log"

$ConsoleRegex = "\[Console\]\s*(.*)"
$InfoLinePrefix = "\[I\]\s*(.*)"         # Information Command (Like help or so)
$CfgLine = "\[CFG\]\s*(.*)"            # Current config
$VoiceVal = "snd_voipvolume = (.*)"
$MapVal = "cl_radar_scale = (.*)"


function Write-LogLine {
    Param([Parameter(Position = 0)]
        [String]$LogEntry)

    process {
       
        if ($LogEntry.Contains("[CFG]")) { 
            Write-Host $LogEntry -ForegroundColor Black -BackgroundColor Magenta 
        }
        elseif ($LogEntry.Contains("[CH]")) { 
            Write-Host $LogEntry  -ForegroundColor Black -BackgroundColor Magenta 
        }
        elseif ($LogEntry.Contains("[I]")) { 
            Write-Host $LogEntry  -ForegroundColor White
        }
        elseif ($LogEntry.Contains("snd_voipvolume")) { 
            Write-Host $LogEntry -ForegroundColor Black -BackgroundColor Cyan 
        }
        elseif ($LogEntry.Contains("[V]")) { 
            Write-Host $LogEntry -ForegroundColor Black -BackgroundColor Cyan 
        }
        elseif ($LogEntry.Contains("cl_radar_scale")) { 
            Write-Host $LogEntry -ForegroundColor Black -BackgroundColor Cyan 
        }
        elseif($LogEntry.Contains("CGameRules - unpaused on tick")){
            Write-Host $LogEntry -ForegroundColor White
        }
         elseif($LogEntry.Contains("[Refrag.gg - NADR]")){
            Write-Host $LogEntry -ForegroundColor Yellow
        }
        else { 
            return;
        }
    }
}
# $GetLogPath = "C:\Users\lprab\Documents\iCloudDrive\iCloud~md~obsidian\Paperlapapp\99_Meta\00_Code\CS\Scripts\Get-Logs.ps1"
# Start-Process -FilePath "powershell.exe" -ArgumentList $GetLogPath -WindowStyle Normal

$logFile = "B:\SteamLibrary\steamapps\common\Counter-Strike Global Offensive\game\csgo\console.log"
$consoleOutputFile = "C:\Users\lprab\Desktop\CS\ValueScript\ConsoleOutput.txt"
Get-Content -Path $logFile -Wait | 
ForEach-Object {
  Write-LogLine $_ 
} 


# Get-Content -Path $consoleOutputFile -Wait | ForEach-Object { Write-LogLine $_ }

# cd "C:\Users\lprab\Documents\iCloudDrive\iCloud~md~obsidian\Paperlapapp\99_Meta\00_Code\CS\Scripts\"
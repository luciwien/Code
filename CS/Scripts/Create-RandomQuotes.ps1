$path = "CS\Scripts\"

$pathCsv = $path + "Random-Quotes.csv"
$pathCfg = $path + "cfg_RandomQuotes.cfg"
$echoCfg = $path + "see_RandomQuotes.cfg"

Clear-Content -Path $pathCfg
Clear-Content -Path $echoCfg 

$quotes = Import-Csv -Delimiter ";" -Path $pathCsv -Encoding UTF8

$topics = $quotes | Select-Object Topic -Unique | Where-Object { $_.Topic.Length -gt 1 }

foreach ($topic in $topics.Topic) {
    Write-Host "Topic $($topic)"
    Add-Content -Path $pathCfg -Value "// $("="*60) $topic $("="*60)//"
    Add-Content -Path $pathCfg -Value "alias $($topic) $($topic)0"
    $echoLine1 = "echo " + '"' + "// $("="*60) $topic $("="*60)//" + '"'
    $echoLine1 | Add-Content -Path $echoCfg -Encoding UTF8
    $currentQuotes = $quotes | Where-Object { $_.Topic -eq $topic } 

    for ($i = 0; $i -lt $currentQuotes.Length; $i++) { 
        $thisAlias = "$topic$($i)"
        $nextI = $i+1
        $nextAlias = "$topic$($nextI)"
        
        if ($nextI -eq $currentQuotes.Length) {
            $nextAlias = "$($topic)0"
        }
        
        $configLine = "alias $thisAlias " + '"' + "alias $($topic) $nextAlias; say $($currentQuotes[$i].Quote)" + '"'
        $configLine | Add-Content -Path $pathCfg -Encoding UTF8

        $upperAlias = $thisAlias.ToUpper()
        $echoLine2 = "echo " + '"' + "// $($upperAlias)      $($currentQuotes[$i].Quote)" + '"'

        $echoLine2 | Add-Content -Path $echoCfg -Encoding UTF8
        if ($currentQuotes[$i].Translation) {
            $translation = "echo " + '"' + "// $(' ' * $upperAlias.Length)      $($currentQuotes[$i].Translation)" + '"'
            $translation | Add-Content -Path $echoCfg -Encoding UTF8
        }

    }
    
}

Write-Host "Done" 
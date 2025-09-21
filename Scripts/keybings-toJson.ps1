$lines = get-content -Path .\Scripts\cfg_autoexec.cfg

$bindings = New-Object System.Collections.Generic.List[System.Object]
$aliases = New-Object System.Collections.Generic.List[System.Object]

foreach ($line in $lines) {
    if ($line.StartsWith("bind ")) {
        $pattern = 'bind (?<key>.*)(?<binding> ".*)'
        if($line -match $pattern){
             $newBind = @{
                Key    = $Matches.key
                Command = $Matches.binding
            }
            $bindings.Add($newBind)
        }
        
    }
    elseif ($line.StartsWith("alias ")) {
        $pattern = 'alias (?<name>.*)(?<command> ".*)'
        if ($line -match $pattern) {
            $newAlias = @{
                Name    = $Matches.name
                Command = $Matches.command
            }
            $aliases.Add($newAlias)
        }
        
    }
}


ConvertTo-Json -InputObject $aliases | Out-File -FilePath "aliases.json"
ConvertTo-Json -InputObject $bindings | Out-File -FilePath "bindings.json"

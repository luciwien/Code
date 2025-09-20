<#
# Lucis Powershell Profile
# With aliases and more
#
#>

function Start-AdminShell { 
    Start-Process powershell -Verb runAs #test
}
function Test-Administrator  
{  
    $user = [Security.Principal.WindowsIdentity]::GetCurrent();
    (New-Object Security.Principal.WindowsPrincipal $user).IsInRole([Security.Principal.WindowsBuiltinRole]::Administrator)  
}

Set-Alias -Name admshell -Value Start-AdminShell
Set-Alias -Name isadmin -Value Test-Administrator
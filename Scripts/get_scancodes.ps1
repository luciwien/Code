Set-Content -Path .\Config\cfg_scancodes.cfg -Value ""

for ($i = 0; $i -lt 150; $i++) {
    Add-Content -Path .\Config\cfg_scancodes.cfg -Value "bind scancode$i say scancode$i"
}
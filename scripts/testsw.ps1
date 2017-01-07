$timeout = new-timespan -seconds 30
$sw = [diagnostics.stopwatch]::StartNew()
$i=0
$f = $False
while ($sw.elapsed -lt $timeout){
    if ($i++ -eq 40){
		$f = $True
		break
    }
	write-host $i
	start-sleep -seconds 1
}
write-host "fini avec $f"
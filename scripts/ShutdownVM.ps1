<#
	.SYNOPSIS
    Shutdown Guest Os of VM

	.DESCRIPTION
    This command stops VM using guest os shutdown 
		
	.FUNCTIONALITY
		vSphere
		
	.NOTES
		DATE: 23/12/2016
		AUTHOR: Nicolas MONGIN
		EMAIL: nicolas.mongin@cramif.cnamts.fr
#>

Param (
	[string]
		$vmName,
	[string]	
		$VIServer=$env:defaultVIServer
)

try{
	add-pssnapin VMware.VimAutomation.Core -EA silentlycontinue
	
	# La boucle d'attente d'arrêt de la VM dure 120 secondes au maximum
	$timeoutValue = 120
	
	#Connexion au vCenter avec les crédentials stockés
	$vc = connect-VIServer -Server $VIServer -wa 0 -EA silentlycontinue
	
	#Récupération du status de la VM à arrêter
	$vm = get-vm -Server $vc -Name $vmName -EA silentlycontinue
	
	#Si la VM est déja arrêtee, on sort
	if ($vm.PowerState -eq "PoweredOff") {
		write-host "<vm>"
		write-host ("<name>$vmName</name>")
		write-host ("<powerstate>PoweredOff</powerstate>")
		write-host "</vm>"
		[Environment]::exit("0")
	}
	
	#Récupération de l'adresse IP de la VM à arrêter
	$ip = $vm.guest.ipaddress|?{$_ -match "55."}
	
	#Guest Shutdown de la VM
	$vm | Stop-VMGuest -Server $vc -Confirm:$false -EA silentlycontinue
	
	#Boucle d'attente de non réponse au ping de la VM à arrêter
	$timeout = new-timespan -seconds $timeoutValue
	$sw = [diagnostics.stopwatch]::StartNew()
	$VMStopped = $False
	
	while ($sw.elapsed -lt $timeout){
		if (-not(test-connection -ComputerName $ip -bufferSize 8 -Count 1 -quiet)) {
			$VMStopped = $True
			break
		}
		start-sleep -seconds 1
	}
	if ($VMStopped){
		write-host "<vm>"
		write-host ("<vcenter_user>" + $vc.user + "</vcenter_user>")
		write-host ("<vm_id>" + $vm.id + "</vm_id>")
		write-host ("<name>" + $vm.name + "</name>")
		write-host ("<ip>" + $ip + "</ip>")
		write-host ("<powerstate>PoweredOff</powerstate>")
		write-host "</vm>"
	} Else {
		throw "Arret de la VM impossible"
	}
	[Environment]::exit("0")
}catch [Exception] {
	#$m = $_.Exception.GetType().FullName
	$mm = $_.Exception.Message
	#write-host "erreur"
	#write-host "$m"
	write-host "$mm"
	[Environment]::exit("1")
	
}
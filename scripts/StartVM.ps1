<#
	.SYNOPSIS
    starts VM

	.DESCRIPTION
    This command starts VM .
		
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
	
	# La boucle d'attente du démarrage de la VM dure 120 secondes au maximum
	$timeoutValue = 120
	
	#Connexion au vCenter avec les crédentials stockés
	$vc = connect-VIServer -Server $VIServer -wa 0 -EA silentlycontinue
	
	#Récupération du status de la VM à démarrer
	$vm = get-vm -Server $vc -Name $v -EA silentlycontinue
	
	#Récupération de l'adresse IP de la VM à démarrer
	$ip = $vm.guest.ipaddress|?{$_ -match "55."}
	
	#Démarrage de la VM
	$vm | Start-VMGuest -Server $vc -EA silentlycontinue
	
	#Boucle d'attente de réponse au ping de la VM à démarrer
	$timeout = new-timespan -seconds $timeoutValue
	$sw = [diagnostics.stopwatch]::StartNew()
	$VMStarted = $False
	
	while ($sw.elapsed -lt $timeout){
		if (test-connection -ComputerName $ip -bufferSize 8 -Count 1 -quiet) {
			$VMStarted = $True
			break
		}
		start-sleep -seconds 1
	}
	if ($VMStarted){
		write-host "<vm>"
		write-host ("<vcenter_user>" + $vc.user + "</vcenter_user>")
		write-host ("<vm_id>" + $vm.id + "</vm_id>")
		write-host ("<name>" + $vm.name + "</name>")
		write-host ("<ip>" + $ip + "</ip>")
		write-host ("<powerstate>PoweredOn</powerstate>")
		write-host "</vm>"
	} Else {
		throw "Erreur demarrage de la VM impossible"
	}
}catch [Exception] {
	$m = $_.Exception.GetType().FullName
	$mm = $_.Exception.Message
	write-host "erreur"
	write-host "$m"
	write-host "$mm"
	#[Environment]::exit("1")
	
}
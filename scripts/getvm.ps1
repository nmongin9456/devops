<#
	.SYNOPSIS
    Get VM list from vCenter

	.DESCRIPTION
    This command retrieve virtual machine list from vcenter. 
		
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
	$vc = connect-VIServer -Server $VIServer -wa 0 -EA silentlycontinue
	$vmNameList = $vmName.split(",") | %{$_.trim()}
	foreach ($v in $vmNameList)
	{
		$vms = get-vm -Server $vc -Name $v -EA silentlycontinue
		foreach ($vm in $vms)
		{
			$ip = $vm.guest.ipaddress|?{$_ -match "55."}
			write-host "<vm>"
			write-host ("<vcenter_user>" + $vc.user + "</vcenter_user>")
			write-host ("<vm_id>" + $vm.id + "</vm_id>")
			write-host ("<name>" + $vm.name + "</name>")
			write-host ("<ip>" + $ip + "</ip>")
			write-host ("<powerstate>" + $vm.powerstate + "</powerstate>")
			write-host "</vm>"
		}
	}
	#sleep 10
}catch [Exception] {
	#$m = $_.Exception.GetType().FullName
	#$mm = $_.Exception.Message
	#write-host "erreur"
	#write-host "$m"
	#write-host "$mm"
	[Environment]::exit("1")
	
}
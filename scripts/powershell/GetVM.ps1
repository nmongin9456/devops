<#
Copyright (c) 2012-2014 VMware, Inc.
#>

<#
	.SYNOPSIS
    Upgrade ESX to a new build

	.DESCRIPTION
    This command ugrades an ESX server to a new build. 
		
	.FUNCTIONALITY
		vSphere
		
	.NOTES
		AUTHOR: Jerry Liu
		EMAIL: liuj@vmware.com
#>

Param (
	[parameter(
		Mandatory=$true,
		HelpMessage="IP or FQDN of the ESX server"
	)]
	[string]
		$vmName, 
	
	[string]	
		$serverUser=$env:defaultUser, 
	
	[string]	
		$serverAddress="vcenter.local", 
	
	[string]	
		$serverPassword=$env:defaultPassword
	
)

foreach ($paramKey in $psboundparameters.keys) {
	$oldValue = $psboundparameters.item($paramKey)
	$newValue = [system.web.httputility]::urldecode("$oldValue")
	set-variable -name $paramKey -value $newValue
}

. ..\objects.ps1

$serverAddressList = $serverAddress.split(",") | %{$_.trim()}
$vmNameList = $vmName.split(",") | %{$_.trim()}
foreach ($serverAddress in $serverAddressList) {
	$server = newServer $serverAddress $serverUser $serverPassword
	foreach ($v in $vmNameList)
	{
		$vms = get-vm -Server $server.viserver -Name $v
		write-host "<vm>"
		foreach ($vm in $vms)
		{
			write-host ("<name>" + $vm.name + "</name>")
			write-host ("<powerstate>" + $vm.powerstate + "</powerstate>")
		}
		write-host "</vm>"
	}
}
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
		$vmName
	
)

#foreach ($paramKey in $psboundparameters.keys) {
#	$oldValue = $psboundparameters.item($paramKey)
#	$newValue = [system.web.httputility]::urldecode("$oldValue")
#	set-variable -name $paramKey -value $newValue
#}

try{
	add-pssnapin VMware.VimAutomation.Core -EA silentlycontinue
	$vc = connect-VIServer "x.x.x.x" -user "user" -password "pass" -NotDefault -wa 0 -EA silentlycontinue
	$vmNameList = $vmName.split(",") | %{$_.trim()}
	foreach ($v in $vmNameList)
	{
		$vms = get-vm -Server $vc -Name $v -EA silentlycontinue
		foreach ($vm in $vms)
		{
			write-host "<vm>"
			write-host ("<vm_id>" + $vm.id + "</vm_id>")
			write-host ("<name>" + $vm.name + "</name>")
			write-host ("<powerstate>" + $vm.powerstate + "</powerstate>")
			write-host "</vm>"
		}
	}
}catch{
	[Environment]::exit("1")
}

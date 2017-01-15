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

$vmNameList = $vmName.split(",") | %{$_.trim()}
foreach ($vm in $vmNameList)
{
	write-host "<vm>"
	write-host ("<vcenter_user>CNAMTS\C417501-TACHAD</vcenter_user>")
	write-host ("<vm_id>id123456</vm_id>")
	write-host ("<name>" + $vm + "</name>")
	write-host ("<ip>55.191.1.192</ip>")
	write-host ("<powerstate>PoweredOff</powerstate>")
	write-host "</vm>"
}
[Environment]::exit("0")
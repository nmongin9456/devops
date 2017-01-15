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
		$vmName
)

start-sleep -Seconds 1

write-host "<vm>"
write-host ("<vcenter_user>CNAMTS\C417501-TACHAD</vcenter_user>")
write-host ("<vm_id>id123456</vm_id>")
write-host ("<name>" + $vmName + "</name>")
write-host ("<ip>55.171.1.192</ip>")
write-host ("<powerstate>PoweredOn</powerstate>")
write-host "</vm>"
[Environment]::exit("0")

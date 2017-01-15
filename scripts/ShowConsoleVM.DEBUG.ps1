<#
	.SYNOPSIS
    Show VM Console via VMRC

	.DESCRIPTION
    Lance VMRC pour afficher la console. 
		
	.FUNCTIONALITY
		vSphere
		
	.NOTES
		DATE: 16/01/2017
		AUTHOR: Nicolas MONGIN
		EMAIL: nicolas.mongin@cramif.cnamts.fr
#>

Param (
	[string]
		$vmName
)
$ErrorActionPreference  = SilentlyContinue
try{
	#add-pssnapin VMware.VimAutomation.Core -EA silentlycontinue
	#$vc = connect-VIServer -Server $VIServer -wa 0 -EA silentlycontinue

	# Configurer powershell comme suit
	# Set-PowerCLIConfiguration –VMConsoleWindowBrowser "C:\Program Files (x86)\Mozilla Firefox\firefox.exe"
	# "C:\Program Files (x86)\Mozilla Firefox\firefox.exe" -P Work $url
	#$url = Open-VMConsoleWindow –VM $vmName –UrlOnly
	$url = "http://www.google.fr"

	write-host "<vm>"
	write-host ("<vcenter_user>CNAMTS\C417501-TACHAD</vcenter_user>")
	write-host ("<vm_id>id123456</vm_id>")
	write-host ("<name>" + $vm + "</name>")
	write-host ("<ip>55.191.1.192</ip>")
	write-host ("<powerstate>PoweredOff</powerstate>")
	write-host ("<url>" + $url + "</url>")
	write-host "</vm>"
	[Environment]::exit("0")
}
catch [Exception] {
	$mm = $_.Exception.Message
	write-host "$mm"
	[Environment]::exit("1")
}
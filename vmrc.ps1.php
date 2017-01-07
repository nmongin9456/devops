Set-PowerCLIConfiguration –VMConsoleWindowBrowser 'C:\Program Files (x86)\Internet Explorer\iexplore.exe'
$VMurl = Open-VMConsoleWindow -VM S417501* -UrlOnly

header("location: $VMurl");
die();
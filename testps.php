<?php
$xmloutput = "<xml>";
$psPath = realpath('scripts/');
chdir($psPath);
$cmd = 'C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe -file .\\getvm.ps1 "S41750100108928 - MONGIN Nicolas - CNAMTS"';
exec($cmd,$result,$exitcode);
$output = "";
foreach ($result as $line) {
	$output .= $line . "\r\n";
}
if ($exitcode != 0) {
		$output = "<stdOutput><![CDATA[" . $output . "]]></stdOutput>";
		$output .= "<customizedOutput> [";
		$output .= date("Y-m-d H:i:s") . "] Fail - run command</customizedOutput>";
	}	
	$xmloutput .= "<result>" . $output . "</result>";
	if (strpos($output, "] Fail - ") === FALSE) {
		header("return-code:4488");
		$xmloutput .= "<returnCode>4488</returnCode>";
	} else {
		$isKnown = false;
		while ($errType = current($codeArray)){
			if (strpos($output, $errType) > 0){
				header("return-code:" . key($codeArray));
				$xmloutput .= "<returnCode>" . key($codeArray) . "</returnCode>";
				$isKnown = true;
				break;
			}
			next($codeArray);
		}
		if ($isKnown === false) {
			header("return-code:4444");
			$xmloutput .= "<returnCode>4444</returnCode>";
		}
	}


//$xmloutput .= "<result>" . $output . "</result>";
$xmloutput .= "</xml>";

$ret = simplexml_load_string($xmloutput);
print_r("<pre>");
print_r($xmloutput);
print_r("</pre>");
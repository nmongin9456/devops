<?php
function debugOn(){ include WWW_ROOT . 'lib/debug.php'; }

function debug($params){
	echo '<pre>';
	print_r($params);
	echo '</pre>';
}

// exec command-let
function callCmd($cmd) {
	global $xmloutput, $codeArray;
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
}
?>


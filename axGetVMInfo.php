<?php
include '/lib/tools.php'; 

if(isset($_POST["action"])){
	$action = $_POST["action"];
	if(isset($_POST["vm"])){
		$vm = $_POST["vm"];
		$ligne = $_POST["ligne"];
		switch ($action) {
			  case "ShutdownVM":

		        $xmloutput = "<xml>";
		        $psPath = realpath('scripts/');
			  	chdir($psPath);
			  	$cmd = "set runFromWeb=true & powershell .\\ShutdownVM.ps1 '".$vm."'";
		        callCmd($cmd);
		        //$xmloutput .= "<vm>".$action."</vm><vm>".$vm."</vm><vm>".$csrf."</vm><path>".getcwd()."</path><cmd>$cmd</cmd></xml>";
		        //$xmloutput .= "</xml>";
		        
				/*
				//////////////////////////////////////////////////////////
				/
				/ MODE DEBUG
				/
				//////////////////////////////////////////////////////////
				*/
				//$xmloutput = "<xml><result><customizedOutput>[2016-12-02 16:18:10] Success - connect to server vcenter.cramif.cnamts.fr</customizedOutput><customizedOutput>[2016-12-02 16:18:10] Info - server is of product line: VPX</customizedOutput><vm><name>S4175010208928 - MONGIN Nicolas - CRAMIF</name><powerstate>PoweredOff</powerstate></vm></result><returnCode>4488</returnCode><ligne>".$ligne."</ligne></xml>";

				/*
				//////////////////////////////////////////////////////////
				*/
				$ret = simplexml_load_string($xmloutput);
				//echo"<pre>";
				sleep(1);
        	    print_r ($ret->asXML());
        	    //echo"</pre>";
				break;
		}
	}
}

?>
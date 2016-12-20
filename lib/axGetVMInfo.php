<?php
//$auth = 0;
//require 'tools.php'; 
/*
$_POST['action']="StartVM";
$_POST['vm']="toto";
$_POST['csrf']="dd";
$_POST['ligne']="2";
*/
if(isset($_POST["action"])){
	$action = $_POST["action"];
	if(isset($_POST["vm"])){
		$vm = $_POST["vm"];
		if(isset($_POST["csrf"])){
			$csrf = $_POST["csrf"];
			$ligne = $_POST["ligne"];
			switch ($action) {
			  case "StartVM":
			  	/*
		        $xmloutput = "<xml>";
		        $psPath = realpath('../scripts/powershell/');
			  	chdir($psPath);
			  	$cmd = "set runFromWeb=true & powershell .\\GetVM.ps1 '".$vm."'";
		        callCmd($cmd);
		        $xmloutput .= "<vm>".$action."</vm><vm>".$vm."</vm><vm>".$csrf."</vm><path>".getcwd()."</path><cmd>$cmd</cmd></xml>";
		        $xmloutput .= "</xml>";
		        */
				/*
				//////////////////////////////////////////////////////////
				/
				/ MODE DEBUG
				/
				//////////////////////////////////////////////////////////
				*/
				$xmloutput = "<xml><result><customizedOutput>[2016-12-02 16:18:10] Success - connect to server vcenter.cramif.cnamts.fr</customizedOutput><customizedOutput>[2016-12-02 16:18:10] Info - server is of product line: VPX</customizedOutput><vm><name>S4175010208928 - MONGIN Nicolas - CRAMIF</name><powerstate>PoweredOn</powerstate></vm></result><returnCode>4488</returnCode><ligne>".$ligne."</ligne></xml>";

				/*
				//////////////////////////////////////////////////////////
				*/
				$ret = simplexml_load_string($xmloutput);
				//echo"<pre>";
        	    print_r ($ret);
        	    //echo"</pre>";
				break;
			}
		}
	}
}

?>
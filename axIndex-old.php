<?php
$auth = 0;
require 'inc/bootstrap.php'; 
include 'lib/tools.php';

$auth = new Auth();
if($auth->userIsValid()){
	$VMList = new collection($auth->getVM());
	if(isset($_POST["action"])){
		switch ($_POST["action"]) {
		  case "GetVM":
	    	
	    	$pshParams = "";
	    	foreach($VMList as $key=>$value){
	        	$pshParams .= $value['name'].",";
	        }
	        $pshParams = rtrim($pshParams, ',');
	        $ajaxParams = '[' . $VMList->extract('name')->join(',') . ']';
	        $xmloutput = "<xml>";
	        $psPath = realpath('scripts/');
		  	chdir($psPath);
		  	//$cmd = "set runFromWeb=true & powershell .\\GetVM.ps1 '".$pshParams."'";
			$cmd = "powershell .\\GetVM.ps1 '".$pshParams."'";
	        //callCmd($cmd);
	        $xmloutput .= "</xml>";
			
			/*
			//////////////////////////////////////////////////////////
			/
			/ MODE DEBUG
			/
			//////////////////////////////////////////////////////////
			*/

			//$xmloutput = "<xml><result><vm><name>S41750100008928 - MONGIN Nicolas CRAMIF</name><powerstate>PoweredOn</powerstate></vm><vm><name>S41750100008928 - MONGIN Nicolas CNAMTS</name><powerstate>PoweredOff</powerstate></vm></result></xml>";
			$xmloutput = "<xml><result><vm><name>S41750100108928 - MONGIN Nicolas - CNAMTS</name><powerstate>PoweredOn</powerstate></vm></result></xml>";
			//"c:\windows\syswow64\windowspowershell\v1.0\powershell.exe" -file "e:\web\devops\scripts\getvm.ps1" "S41750100108928 - MONGIN Nicolas - CNAMTS"

			/*
			//////////////////////////////////////////////////////////
			*/      
			$idRows = 0;
			//sleep(2);
			$xml = simplexml_load_string($xmloutput);
			$VMList = $xml->result->vm;
			break;
		}
	}
	else{	/*
			print_r("erreur die");
			$xmloutput = "<xml>";
	        $psPath = realpath('scripts/');
		  	chdir($psPath);
		  	//$cmd = "set runFromWeb=true & powershell .\\GetVM.ps1 '".$pshParams."'";
			$cmd = "powershell .\\GetVM.ps1 'S41750100108928 - MONGIN Nicolas - CNAMTS'";
	        callCmd($cmd);
	        $xmloutput .= "</xml>";
			$xml = simplexml_load_string($xmloutput);
			$VMList = $xml->result->vm;
			print_r("<pre>");
			print_r($VMList);
			print_r("</pre>");
			*/
			die("");
	}
}
?>

<table class="table table-hover"> 
		<caption>Les Machines Virtuelles de <strong><?= Session::getInstance()->getFirstName(); ?></strong> :<br></caption>
		<thead> 
			<tr class="danger"> 
				<th class="col-md-1"></th> 
				<th class="col-md-4">VM Name</th> 
				<th class="col-md-1">Status</th> 
				<th class="col-md-3"></th> 
			</tr> 
		</thead> 
		<tbody> 
		<?php foreach ($VMList as $key => $value): ?>
			<tr> 
				<th scope="row" style="vertical-align:middle"><?= ++$idRows; ?></th> 
				<td style="vertical-align:middle"><?= $value->name; ?></td> 
				<td id="STATUS_<?= $idRows; ?>" style="vertical-align:middle">
					<?php if($value->powerstate == "PoweredOn"): ?>
						<span class="glyphicon glyphicon-check text-success"></span>
					<?php else: ?>
						<span class="glyphicon glyphicon-remove text-danger"></span>
					<?php endif ?>
				</td> 
				<td>
					<button 	type="button" 
								class="btn btn-success actionsVM<?php if($value->powerstate == 'PoweredOn'){echo ' disabled';}; ?>"
								data-vm="<?= $value->name; ?>"
								data-action="StartVM"
								data-ligne="<?= $idRows; ?>"
								data-toggle="tooltip" 
								data-placement="bottom" 
								title="Démarrage de la machine virtuelle">
						<span class="glyphicon glyphicon-play text-success"></span>
					</button>
					<button 	type="button" 
								class="btn btn-danger actionsVM<?php if($value->powerstate == 'PoweredOff'){echo ' disabled';}; ?>" 
								data-vm="<?= $value->name; ?>"
								data-action="ShutdownVM"
								data-ligne="<?= $idRows; ?>"
								data-toggle="tooltip" 
								data-placement="bottom" 
								title="Arrêt de l'OS invité">
						<span class="glyphicon glyphicon-stop text-danger"></span>
					</button>
					<button 	type="button" 
								class="btn btn-info actionsVM<?php if($value->powerstate == 'PoweredOff'){echo ' disabled';}; ?>"
								data-vm="<?= $value->name; ?>"
								data-action="RestartVM"
								data-ligne="<?= $idRows; ?>"
								data-toggle="tooltip" 
								data-placement="bottom" 
								title="Re-Démarrage de l'OS invité">
						<span class="glyphicon glyphicon-refresh text-info"></span>
					</button>
					<button 	type="button" 
								class="btn btn-danger actionsVM<?php if($value->powerstate == 'PoweredOff'){echo ' disabled';}; ?>"
								data-vm="<?= $value->name; ?>"
								data-action="ForceStopVM"
								data-ligne="<?= $idRows; ?>"
								data-toggle="tooltip" 
								data-placement="bottom" 
								title="Arrêt brutal de la machine virtuelle">
						<span class="glyphicon glyphicon-off text-danger"></span>
					</button>
				</td> 
			</tr> 
		<?php endforeach; ?>
		</tbody> 
	</table>
	<div id="ajaxResultInfo" class="alert alert-success" style="padding: 20px; width: 100%;"></div>
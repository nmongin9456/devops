<?php
require 'inc/bootstrap.php'; 

$auth = new Auth();
if($auth->userIsValid()){
	$VMList = $auth->getVM();
	$task = new Psexec($_POST, [ "psParams" => $VMList ]);
	$xml = $task->runAction();
	    	    	
			/*
			//////////////////////////////////////////////////////////
			/
			/ MODE DEBUG
			/
			//////////////////////////////////////////////////////////
			/*
			$xmloutput = "<xml><result><vm><name>S41750100108928 - MONGIN Nicolas - CNAMTS</name><powerstate>PoweredOn</powerstate></vm></result></xml>";
			*/
	$idRows = 0;
	//print_r($xml);
	//die();
	$xml = simplexml_load_string($xml);
	$VMList = $xml->result->vm;
	$fSD = strval($xml->flagError);
	$MSG = $xml->statusMSG;
	Session::getInstance()->setFlash($fSD, $MSG);
	}
else{	
	die("");
}
?>
<?php if($fSD == "success") : ?>
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
						<button 	type="button" 
									class="btn btn-warning actionsVM""
									data-vm="<?= $value->name; ?>"
									data-action="ShowConsoleVM"
									data-ligne="<?= $idRows; ?>"
									data-toggle="tooltip" 
									data-placement="bottom" 
									title="Affichage de la console de la machine virtuelle">
							<span class="glyphicon glyphicon-modal-window text-danger"></span>
						</button>
					</td> 
				</tr> 
			<?php endforeach; ?>
			</tbody> 
		</table>
	<?php endif; ?>
	
	<?php if(Session::getInstance()->hasFlashes()): ?>
		<?php foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
			<div id="ajaxResultInfo" class="alert alert-<?=$type; ?>" style="padding: 20px; width: 100%;">
				<?=$message; ?>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<div id="ajaxResultInfo" class="alert alert-success" style="padding: 20px; width: 100%;"></div>
	<?php endif; ?>
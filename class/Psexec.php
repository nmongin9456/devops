<?php
class Psexec{

private $codeArray = array(
	"4000" => "Missing parameters",
	"4001" => "Fail - connect to server",
	"4002" => "Fail - connect to VC",
	"4003" => "Fail - get VM",
	"4004" => "Fail - get snapshot",	
	"4005" => "Fail - open page",
	"4006" => "Fail - find installer", 
	"4007" => "Fail - VMware Tools is not running",
	"4008" => "Fail - find snapshot",
	"4009" => "Fail - restore snapshot",
	"4010" => "Fail - delete snapshot",
	"4011" => "Fail - create snapshot",
	"4012" => "Fail - find unique snapshot",
	"4013" => "Fail - initalize broker",
	"4014" => "Fail - invalid license",
	"4015" => "Fail - add VC to broker",
	"4016" => "Fail - pool already exists",
	"4017" => "Fail - add pool to broker",
	"4018" => "Fail - run VM script",
	"4019" => "Fail - copy file",
	"4020" => "Fail - find desktop",
	"4021" => "Fail - find user",
	"4022" => "Fail - find a desktop assigned to user",
	"4023" => "Fail - update VMware Tools",
	"4024" => "Fail - add broker license",
	"4025" => "Fail - add transfer server",
	"4026" => "Fail - find transfer server",
	"4027" => "Fail - remove transfer server",
	"4028" => "Fail - find internal cmdlets",
	"4029" => "Fail - add standalone composer",
	"4030" => "Fail - find VC",
	"4031" => "Fail - add composer domain",
	"4032" => "Fail - send file to remote machine",
	"4033" => "Fail - entitle pool",
	"4034" => "Fail - connect to remote Windows system",
	"4035" => "Fail - unknown product type",
	"4036" => "Fail - uninstall application",
	"4037" => "Fail - download file",
	"4038" => "Fail - install",
	"4039" => "Fail - create new virtual machine",
	"4040" => "Fail - find build",
	"4041" => "Fail - set event database",
	"4042" => "Fail - create AD forest",
	"4043" => "Fail - join machine to domain",
	"4044" => "Fail - upgrade Powershell",
	"4045" => "Fail - create AD domain",
	"4046" => "Fail - update firmware",
	"4047" => "Fail - add farm",
	"4048" => "Fail - delete farm",
	"4049" => "Fail - add RDS server to farm",
	"4050" => "Fail - remove RDS server from farm",
	"4051" => "Fail - add application",
	"4052" => "Fail - delete application",
	"4053" => "Fail - entitle application",
	"4054" => "Fail - set HTML access",
	"4055" => "Fail - create desktop pool",
	"4056" => "Fail - set pool display name",
	"4400" => "Fail - execution timeout",
	"4444" => "Fail - unknown error occurred",
	"4445" => "Fail - run Powershell script",
    "5500" => "Ajax - No Action Verb",
	"5501" => "Ajax - No VM Name",
	"4488" => "Success - no error occurred"
);
private $xmlOutput;
private $psPath;    // contient le chemin du script PS1 à lancer
private $cmdLine;   // contient la commande powershell à lancer
private $psParams;  // contient les parametres du script PS1 à lancer
private $exitCode;
private $errorCode;
private $errorMSG;
private $flagError; // contient success ou danger en fonction du retour du script PS1
private $result;
private $executionTime;


public function __construct(Array $postproperties=[], Array $Params=[]){
    foreach($postproperties as $key => $value){
        $this->{$key} = $value;
    }
    foreach($Params as $key => $value){
        $this->{$key} = $value;
    }

    if(!$this->action){$this->errorCode = "5500";}
    if(!$this->vm){$this->errorCode = "5501";}
    if($this->errorCode){$this->errorMSG=$this->codeArray[$this->errorCode];}

    $this->psPath = realpath('scripts/');

    // psParams est initialisé par l'appel à une callback ex: getGetVM_Params()
    $this->psParams = call_user_func(array($this,"get".$this->action."_Params"));

    // Mode DEBUG: Appel des scripts PS1 avec le suffixe DEBUG  -> ShutdownVM.DEBUG.ps1
    $this->cmdLine = "powershell .\\".$this->action.".DEBUG.ps1 '".$this->psParams."'";
    //   $this->cmdLine = "powershell .\\".$this->action.".ps1 '".$this->psParams."'";

}

/*
Renvoie les parametres nécessaires au lacement du script powershell GETVM.PS1
*/
private function getGetVM_Params()
{
    $pshParams = "";
    foreach($this->psParams as $key=>$value){
	    $pshParams .= $value['name'].",";
	}
	$this->psParams = rtrim($pshParams, ',');
    return($this->psParams);
}

/*
Renvoie les parametres nécessaires au lacement du script powershell StartVM.PS1
*/
private function getStartVM_Params()
{
    return($this->vm);
}
/*
Renvoie les parametres nécessaires au lacement du script powershell ShutdownVM.PS1
*/
private function getShutdownVM_Params()
{
    return($this->vm);
}

/*
Renvoie les parametres nécessaires au lacement du script powershell ShowConsoleVM.PS1
*/
private function getShowConsoleVM_Params()
{
    return($this->vm);
}

public function getErrorCode(){
    return $this->errorCode;
}


/*
renvoi le contenu de $this->Action
*/
public function getAction(){
    return $this->action;
}

public function runAction(){
    $this->flagError = "success";
    if($this->errorCode){
        header("HTTP/1.0 418 Error:".$this->errorCode."   Message: ".$this->errorMSG);
        sleep(1);
        $this->flagError = "danger";
        return False;
    }

    $t0 = microtime(true);
    chdir($this->psPath);
    exec($this->cmdLine,$this->result,$this->exitCode);
	
    $output = "";
	foreach ($this->result as $line) {
		$output .= $line . "\r\n";
	}
	if ($this->exitCode != 0) {
        $this->errorMSG = $output;
        $this->flagError = "danger";
		$output = "<stdOutput>\n<![CDATA[" . $output . "]]></stdOutput>\n";
		$output .= "<customizedOutput> [";
		$output .= date("Y-m-d H:i:s") . "] Fail - run command</customizedOutput>";
	}	
	$this->xmlOutput .= "<xml>\n<result>\n" . $output . "</result>\n";
	if (strpos($output, "] Fail - ") === FALSE) {
		header("return-code:4488");
		$this->xmlOutput .= "<returnCode>4488</returnCode>";
	} else {
		$isKnown = false;
		while ($errType = current($this->codeArray)){
			if (strpos($output, $errType) > 0){
				header("return-code:" . key($this->codeArray));
				$this->xmlOutput .= "<returnCode>" . key($this->codeArray) . "</returnCode>";
				$isKnown = true;
				break;
			}
			next($this->codeArray);
		}
		if ($isKnown === false) {
			header("return-code:4444");
			$this->xmlOutput .= "<returnCode>4444</returnCode>";
		}
	}
    $this->executionTime = microtime(true) - $t0;
    if(isset($this->ligne)){$this->xmlOutput .= "\n<ligne>" . $this->ligne . "</ligne>";}
    $this->xmlOutput .= "\n<flagError>" . $this->flagError . "</flagError>";
    $this->xmlOutput .= "\n<action>" . $this->action . "</action>";
    $this->xmlOutput .= sprintf("\n<executionTime>%.1f seconds</executionTime>", $this->executionTime);
    $this->xmlOutput .= sprintf("\n<statusMSG>Delai d'execution de la requete '%s' :     %.1f secondes</statusMSG>", $this->action, $this->executionTime);
    $this->xmlOutput .= "\n</xml>";
    $ret = simplexml_load_string($this->xmlOutput);
    return ($ret->asXML());
    //return($this->xmlOutput);
}

public function getErrorMSG(){
    header("HTTP/1.0 418 Error:".$this->errorCode."    Message: ".$this->errorMSG);
    sleep(1);
    return $false;
}
}
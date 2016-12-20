<?php 
require 'inc/bootstrap.php';
// Récupération de la liste des VM autorisées (dans base mysql)

$auth = new Auth();
if(!$auth->userIsValid()){
	if($auth->isInDeveloppeurs()){
		Session::setFlash('success', 'Vous êtes dans le groupe DEVELOPPEURS');
		$userInfo = $auth->getUserinfo();
		if ($userInfo){
			$auth->register($userInfo);	
		}
	}
}

if($auth->userIsValid()){
	$VMList = new collection($auth->getVM());
	$ajaxParams = '[' . $VMList->extract('name')->join(',') . ']';
}else{
	header('HTTP/1.0 401 Unauthorized');
	App::redirect('reject.php');
	die();
}
include 'partials/header.php';
//********************************************************************************************
//App::debug($auth->getUserinfo());
//$ADC = new myADClient('http://mongin-08928-test02.cramif.cnamts.fr/adws/adws.php?wsdl');
//App::debug('UserInfos: ' . $ADC->getUserLastName('MONGIN-08928'));
//********************************************************************************************
?>
<center>
<div id="loader">
	<div style="min-height:45vh;">
	<br><br>
	<h3>Chargement des données depuis le vCenter pour le compte de <?=Session::getInstance()->getCompleteName(); ?></h3>
	<br><br>
	<h4>Merci de patienter...</h4>
	</div>
	<div class="loader"></div>
</div>
<div id="ajaxParams" class="hidden"><?= $ajaxParams ?></div>
<div id="ajaxResult"></div>
</center>
<?php require "./partials/footer.php"; ?>

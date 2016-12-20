<?php
//session_start();


/**
 * si on appelle cette page avec un require ou include
 * on initialise les variables de Session sinon
 * l'appel provient d'ajax en on revoie un jeton json
 * Voir paragraphe + bas
 */	
if(!isset($_POST['csrf'])){
	if (!isset($GLOBALS['auth'])) {
		if (!isset($_SESSION['Auth']['id'])){
			header('Location:' . WEBROOT . 'login.php');
			die();
		}
	}else{
		unset($GLOBALS['auth']);
	}
}

if (!isset($_SESSION['Auth']['username'])){
	if(isset($_SERVER["LOGON_USER"])){
		$userName = str_replace("CNAMTS\\", "", $_SERVER["LOGON_USER"]);
		$_SESSION['Auth'] = getJetonAuth($userName);
	}else{
		$_SESSION['Auth']['username'] = "MONGIN-08928";
	}

}
if (!isset($_SESSION['csrf'])){
	$_SESSION['csrf'] = md5(time() + rand());
}

/**
 *   si le csrf passé en mode POST egal à celui de la session en cours
 *   on renvoie TRUE à la fonction ajax $('.reply').click(function(e)); de APP.JS (appelée par 'comment.php')

	if(isset($_POST['csrf']) && isset($_SESSION['csrf'])){
		header('Content-type: Application/json');
		$record['success'] = ($_POST['csrf'] == $_SESSION['csrf']);
		die(json_encode($record));	
	}

 */
/********************************************************
 *                      Les fonctions                   *
 ********************************************************/

function csrf(){
	return 'csrf=' . $_SESSION['csrf'];
}

function csrfJS(){
	return $_SESSION['csrf'];
}


function csrfInput(){
	return '<input type="hidden" value="' . $_SESSION['csrf'] . '" name="csrf">';
}

function checkCsrf() {
	if (
		(isset($_GET['csrf']) && $_GET['csrf'] == $_SESSION['csrf']) ||
		(isset($_POST['csrf']) && $_POST['csrf'] == $_SESSION['csrf'])
		){
		return true;
	}
header('Location:' . WEBROOT . 'lib/csrf.php');
die();
}

function checkCsrfJS($csrf) {
	return ($csrf == $_SESSION['csrf']);
}
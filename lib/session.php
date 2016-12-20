<?php
function flash(){
	if (isset($_SESSION['flash'])){
		extract($_SESSION['flash']); //extraction des variables $type et $message
		unset($_SESSION['flash']);  //suppression de message
		return "<div class='alert alert-$type'>$message</div>";
	}
}

function setFlash($message, $type='success'){
	$_SESSION['flash']['message'] = $message;
	$_SESSION['flash']['type'] = $type;
}

function getSessionUserName(){
	if (isset($_SESSION['Auth']['username'])){
		return($_SESSION['Auth']['username']);
	}else{
		return false;
	}
}

function getSessionFirstName(){
	if (isset($_SESSION['Auth']['firstname'])){
		return($_SESSION['Auth']['firstname']);
	}else{
		return false;
	}
}

function getSessionCompleteName(){
	if (isset($_SESSION['Auth']['firstname']) && (isset($_SESSION['Auth']['lastname']))){
		return($_SESSION['Auth']['firstname'] . " " . $_SESSION['Auth']['lastname']);
	}else{
		return false;
	}
}
function getAuthVM(){
	/*
	global $db;
	$userID = getUserID($username);
	if (isset($userID)){
		$req = $db->query("SELECT vms.name, vms.status FROM permissions INNER JOIN vms WHERE permissions.vm_id=vms.id AND permissions.user_id=".$userID["id"].";");
		return $req->fetchAll();
	}else{
		return NULL;
	}
	*/
	if (isset($_SESSION['Auth']['vm'])){
		return($_SESSION['Auth']['vm']);
	}else{
		return false;
	}
}
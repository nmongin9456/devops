<?php
global $db;

try{
	//db = new PDO('mysql:host=localhost;dbname=gvm', 'root', 'azertyuiop');
	$db = new PDO('mysql:host=w417501tinf03.cramif.cnamts.fr;dbname=gvm', 'root', 'Cramif2010');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (Exception $e){
	echo 'Impossible de se connecter a la base de donnees : ';
	echo $e->getMessage();
	die();
}
 

function getRec($ref, $return, $where){
	global $db;
	$req = $db->query("SELECT $return FROM $ref WHERE $where");
	return $req->fetchAll();
}

function getUserID($username){
	global $db;
	$req = $db->query("SELECT id FROM users WHERE username='".$username."'");
	return $req->fetch();		
}

function getJetonAuth($userName){
	global $db;
	if (isset($userName)){
		$req = $db->query("SELECT id, username, firstname, lastname FROM users WHERE username='".$userName."';");
		$result = $req->fetch();
		$req = $db->query("SELECT vms.name FROM vms INNER JOIN permissions WHERE vms.id=permissions.vm_id AND permissions.user_id=".$result['id'].";");
		$result['vm'] = $req->fetchAll();
		return $result;
	}else{
		return NULL;
	}
}
?>
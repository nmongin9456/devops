<?php
require "inc/bootstrap.php";
/*
$r= App::getDatabase()->query("select * from vms where id = ?", [3])->fetch();
//debug($r);

$v=new Validator([
	'user1' => 'NicolasMongin',
	'user2' => 'toto@titi.com'
	]);

$v->alphaNumeriq('user1','Pas alpha num');
if(!$v->isValid()){
	debug($v->getErrors());
}

$auth = new Auth(App::getDatabase());
if(!$auth->userExists('MONGI-08928')){
	$auth->register('MONGI-08928', 'TOTOESTMALADE', 'nicolas.mongin@cramif.cnamts.fr');
	Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé...');
}
*/

//$auth = new Auth();
//$a=$auth->authenticateUser();
print"<pre>";
$sth = App::getDatabase()->query("SELECT vms.name FROM vms INNER JOIN permissions WHERE vms.id=permissions.vm_id AND permissions.user_id = 6", []);
print("PDO::FETCH_ASSOC: ");
print("Retourne la ligne suivante en tant qu'un tableau indexé par le nom des colonnes\n");
$result = $sth->fetch(PDO::FETCH_ASSOC);
print_r($result);
print("\n");

print("PDO::FETCH_BOTH: ");
print("Retourne la ligne suivante en tant qu'un tableau indexé par le nom et le numéro de la colonne\n");
$result = $sth->fetch(PDO::FETCH_BOTH);
print_r($result);
print("\n");

print("PDO::FETCH_OBJ: ");
print("Retourne la ligne suivante en tant qu'objet anonyme ayant les noms de colonnes comme propriétés\n");
$result = $sth->fetch(PDO::FETCH_OBJ);
print_r($result);
print("\n");

print("PDO::FETCH_OBJ: ");
print("Retourne la ligne suivante en tant qu'objet anonyme ayant les noms de colonnes comme propriétés\n");
$result = $sth->fetch(PDO::FETCH_OBJ);
print_r ($result);
print("\n");
print"</pre>";
require 'partials/header.php';
?>



<?php require 'partials/footer.php'; ?>

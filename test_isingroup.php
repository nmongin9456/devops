<?php

function filter01($v) {
	global $groupName_G;
	return (strpos($v, "CN=".$groupName_G) !== false);
}


require 'class/adLDAP/adLDAP.php';

$adldap = new adLDAP();

$groupName_G = 'G417501-INFORMATIQUE-DEVELOPPEURSS';
$userMemberOf = $adldap->user()->infoCollection('MONGIN-08928', array("memberof"))->memberof; 

print_r($adldap->user()->infoCollection('MONGIN-08928', array("memberof"))->memberof);
echo"<br><br>";
print_r(count(array_filter($userMemberOf, 'filter01')));

if(is_array($userMemberOf)){
	return(count(array_filter($userMemberOf, 'filter01')) > 0);
}else{
	return(strpos($user->memberof, $groupName) !== false);
}
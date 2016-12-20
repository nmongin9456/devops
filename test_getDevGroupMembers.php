<?php

require 'inc/bootstrap.php';
require 'partials/header.php';
/**
 * Constantes à inclure ......
 */
$urlWSDL = "http://mongin-08928-test02.cramif.cnamts.fr/adws/adws.php?wsdl";

/*----------------------------------------------------------------------------*/

$ADC = new myADClient($urlWSDL);
$SearchGroupName = "G417501-INFORMATIQUE-DEVELOPPEURS";

print_r("<pre>"); 
print_r("<br>------------- Liste des Membres du groupe $SearchGroupName --------------<br><br>");
foreach($ADC->getGroupMembers($SearchGroupName) as $userName){
	print_r("Nom Complet : " . $ADC->getUserFullName($userName) . "<br>");
	print_r("Courriel : " . $ADC->getUserMail($userName) . "<br>");
	print_r("Description : " . $ADC->getUserDescription($userName) . "<br>");
	print_r("--------------------------------------------------------------<br>");
}

echo"</pre>";

?>
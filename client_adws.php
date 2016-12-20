<?php

require 'inc/bootstrap.php';
require 'partials/header.php';
/**
 * Constantes à inclure ......
 */
$urlWSDL = "http://mongin-08928-test02.cramif.cnamts.fr/adws/adws.php?wsdl";

/*----------------------------------------------------------------------------*/
//require('lib/myADClient.php');

$ADC = new myADClient($urlWSDL);
$UserName = "MONGIN-08928";
$SearchGroupName = "G417501-INVENTAIRE";

print_r("<pre>"); 
print_r("<br>------------- Liste des Services Aramis dans l'ADN --------------<br>");
print_r($ADC->getServicesList());

print_r("<br>----------------- Information sur l'utilisateur -----------------<br>");
print_r("Nom Complet : " . $ADC->getUserFullName($UserName) . "<br>");
print_r("Courriel : " . $ADC->getUserMail($UserName) . "<br>");
print_r("Description : " . $ADC->getUserDescription($UserName) . "<br>");
print_r("<br>-----------------------------------------------------------------<br>");

print_r(($ADC->isInInventaire($UserName)) ? "$UserName est autorisé à accèder à l'application 'Inventaire'" : "$UserName n'est pas autorisé à accèder à l'application 'Inventaire'");
print_r("<br>-----------------------------------------------------------------<br>");

echo "<br><br><br><br><br><br>";
print_r("<br>-------- Liste des fonctions fournies par le webservice ---------<br>");
print_r(($ADC->getFunctions()));
print_r("<br>-----------------------------------------------------------------<br>");
print_r("<br>-------- Liste des fonctions fournies par le webservice Global ---------<br>");
//print_r($ADC->getClient()->getGroupMembers($SearchGroupName));
print_r($ADC->getGroupMembers($SearchGroupName));


echo"</pre>";

?>
<?php

class myADClient{

	private $wsdl;		//URL WSDL du serveur de webservice
	private $client;	//Objet pointant sur le client nuSOAP

	public $errors = array();

	
	public function __construct($urlWSDL){
		$this->wsdl  		= $urlWSDL;
		$this->client 		= new nusoap_client($this->wsdl, 'wsdl');
		if($this->client->getError()) {
			$this->error = $this->client->getError();
		}
	}
 
 	public function getClient(){
 		return $this->client;
 	}

 	/* 
 	 * Fonction getServiceList() -> Renvoie la liste des services Aramis d?lar? dans l'ADN.
 	 */

 	public function getServicesList(){
 		$tmpArray = array();
 		$Services= $this->client->call('getServicesList', array());
 		if($this->client->getError()) {
 			return($this->client->getError());
 		}else{
	 		if(is_array($Services)){
				foreach ($Services as $Service) {
					$tmpArray[] = $Service;
				}
				return($tmpArray);
			}else{
				if(isset($Services)){
					$tmpArray[] = $services;
					return($tmpArray);
				}else{
					return false;
				}
			}
		}
 	}

 	/* 
 	 * Fonction getUserFullName($samAccountName) -> Renvoie une chaine form? du pr?om Nom de l'utilisateur pass?en param?re.
 	 */
 	public function getUserFullName($samAccountName){
 		return($this->client->call('getFullName', array('samAccountName'=>$samAccountName)));
 	}

 	/* 
 	 * Fonction getUserMail($samAccountName) -> Renvoie une chaine form? du courriel de l'utilisateur pass?en param?re.
 	 */
 	public function getUserMail($samAccountName){
 		return($this->client->call('getMail', array('samAccountName'=>$samAccountName)));
 	}

	/* 
 	 * Fonction getUserFirstName($samAccountName) -> Renvoie une chaine formée du prénom de l'utilisateur passé en paramètre.
 	 */
 	public function getUserFirstName($samAccountName){
 		return($this->client->call('getFirstName', array('samAccountName'=>$samAccountName)));
 	}

	/* 
 	 * Fonction getUserLastName($samAccountName) -> Renvoie une chaine formée du nom de famille de l'utilisateur passé en paramètre.
 	 */
 	public function getUserLastName($samAccountName){
 		return($this->client->call('getLastName', array('samAccountName'=>$samAccountName)));
 	}

 	/* 
 	 * Fonction getUserDescription($samAccountName) -> Renvoie une chaine form? de la description de l'utilisateur pass?en param?re.
 	 */
 	public function getUserDescription($samAccountName){
 		return($this->client->call('getDescription', array('samAccountName'=>$samAccountName)));
 	}

	/* 
 	 *  Fonction getUserMemberOf($samAccountName) -> Renvoie la liste des groupes dont l'utilisateur est membre.
 	 *//*
 	public function getUserMemberOf($samAccountName){
 		return($this->client->call('getUserMemberOf', array('samAccountName'=>$samAccountName)));
 	}
	*/
	
  	/* 
 	 * priv? Fonction isInGroup($searchGroupName, $samAccountName) -> Renvoie vrai si le group contient l'utilisateur.
 	 */ 
 	private function isInGroup($searchGroupName, $userName){
 		return $this->client->call('isInGroup', array('groupName'=>$searchGroupName, 'samAccountName'=>$userName));
 	}

 	/* 
 	 * priv? Fonction getGroupMembers($samAccountName) -> Renvoie les membres du groupe.
 	 */ 
 	public function getGroupMembers($groupName){
 		return $this->client->call('getGroupMembers', array('samAccountName'=>$groupName));
 	}

	/* 
 	 * Fonction isInInventaire($samAccountName) -> Renvoie vrai si l'utilisateur est dans le groupe "Inventaire".
 	 */
 	public function isInInventaire($userName){
 		return $this->isInGroup('G417501-INVENTAIRE', $userName);
 	}

 	/* 
 	 * Fonction isInDeveloppeur($samAccountName) -> Renvoie vrai si l'utilisateur est dans le groupe "G417501-INFORMATIQUE-DEVELOPPEURS".
 	 */
 	public function isInDeveloppeurs($userName){
 		return in_array($userName, $this->getGroupMembers('G417501-INFORMATIQUE-DEVELOPPEURS'));
 	}

	/*
	*  Renvoie la liste des fonctions publiques contenues dans la classe myADClient faisant appel aux webservices.
	*/ 
 	private function getFunctions(){
 		$content = explode("\n",file_get_contents(__FILE__));
		$content = array_filter($content, "filter01");
		
		$content = array_filter($content, 'filter02');
		
		$content = array_values($content);
		return $content;
 	}
}

/*
*		Primitives de travail
*/
function filter01($s){
	return strpos($s,'* Fonction');
}

function filter02($s){
	return !strpos($s,'return strpos');
}

/*
*		Affichage de la liste des fonction disponibles lors de l'appel de l'URL  ../myADClient.php

if(!isset($urlWSDL)){
echo "<pre>";
$content = explode("\n",file_get_contents(__FILE__));
$content = array_filter($content, 'filter01');

$content = array_filter($content, 'filter02');

echo "Voici la liste des fonctions disponibles dans myADClient<br>";
echo "--------------------------------------------------------<br><br>";
foreach(array_values($content) as $function){
	echo trim($function)."<br>";
}
echo "</pre>";
}
*/
?>
<?php

class Auth{

	const urlWSDL = "http://mongin-08928-test02.cramif.cnamts.fr/adws/adws.php?wsdl"; 

	public function __construct(){
	}	

	public function register($userInfo){
		$username = $userInfo['username'];
		$firstname = $userInfo['firstname']; 
		$lastname = $userInfo['lastname'];
		//$password = password_hash($password, PASSWORD_BCRYPT);
		//$token = Str::random(60);
		App::getDatabase()->query("INSERT INTO users SET username = ?, firstname = ?, lastname = ?", [
			$username,
			$firstname,
			$lastname
		]);
	//$user_id = App::getDatabase()->lastInsertId();
	//mail($email, 'Confirmation de votre compte', "Afin de valider votre compte, merci de cliquer sur ce lien\n\nhttp://localhost/devops/confirm.php?id=$user_id&token=$token");
	}

	public function userExists($username){
		return (App::getDatabase()->query("SELECT username FROM users WHERE username = ?", [
			$username
		])->rowCount() >= 1);
	}

/*
	Authentifie l'utilisateur qui lance la requete HTTP de démarrage... (authentification windows activée)
*/
	public function getVM(){
		$result = Session::getInstance()->getData('Auth'); 
		if ($result['id']){
			$reqVM = App::getDatabase()->query("SELECT vms.name FROM vms INNER JOIN permissions WHERE vms.id=permissions.vm_id AND permissions.user_id = ?", [$result['id']]);
			$_SESSION['Vm'] = $reqVM->fetchAll();
		}
		return $_SESSION['Vm'];
	}

	public function userIsValid(){
		if(isset($_SERVER["LOGON_USER"])){
			$result = [];
			$userName =  App::parseUserName();
			$result = App::getDatabase()->query('SELECT id, username, firstname, lastname FROM users WHERE username = ?', [$userName])->fetch();
			if (!$result){
				$result['username'] = $userName;
				Session::getInstance()->setData('Auth', $result);
				return false;
			}else{
				Session::getInstance()->setData('Auth', $result);
				return $result;
			}
		}else{
			return false;
		}
	}

	public function isInDeveloppeurs(){
		$ADC = new myADClient(Self::urlWSDL);
		return $ADC->isInDeveloppeurs(Session::getInstance()->getUserName());
		
	}

	public function getUserinfo(){
		if(isset($_SERVER["LOGON_USER"])){
			$result = [];
			$userName =  App::parseUserName();
			$ADC = new myADClient(Self::urlWSDL);
			$result['username'] = $userName;
			$result['firstname'] = ucfirst(strtolower($ADC->getUserFirstName($userName)));
			$result['lastname'] = strtoupper($ADC->getUserLastName($userName));
			return $result;
		}else{
			return false;
		}
	}
}

<?php

class App{
	
	static $db = null;

	static function getDatabase(){
		if (!self::$db){
			//self::$db = new Database('root', 'azertyuiop', 'gvm');
			self::$db = new Database('root', 'Cramif2010', 'gvm', 'w417501tinf03.cramif.cnamts.fr');
		}
		return self::$db;
	}

	static function redirect($page){
		header("location:$page");
	}

	static function debug($params){
		echo '<pre>';
		print_r($params);
		echo '</pre>';
	}

	static function parseUserName(){
		return strtoupper(str_replace("CNAMTS\\", "", $_SERVER["LOGON_USER"]));
	}

}


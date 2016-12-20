<?php

/*
	singleton : Cette classe ne peut être instanciée qu'une seule fois...
	Une seule session !
*/

class Session{
	
	static $instance;

	static function getInstance(){
		if(!self::$instance){
			self::$instance = new Session();
		}
		return self::$instance;
	}

	public function __construct(){
		session_start();
	} 

	public function setFlash($key, $message){
		$_SESSION['flash'][$key] = $message;
	}

	public function hasFlashes(){
		return isset($_SESSION['flash']);
	}

	public function getFlashes(){
		$flash = $_SESSION['flash'];
		unset($_SESSION['flash']);
		return $flash;
	}

	public function getData($key){
		if (isset($_SESSION[$key])){
			return $_SESSION[$key];
		}else{
			return false;
		}
	}

	public function setData($key, $value){
		$_SESSION[$key] = $value;
	}

	public function getFirstName(){
		if(isset($_SESSION['Auth']['firstname'])){
			return $_SESSION['Auth']['firstname'];
		}else{
			return null;
		}
	}

	Public function getUserName(){
		if(isset($_SESSION['Auth']['username'])){
			return $_SESSION['Auth']['username'];
		}else{
			return null;
		}
	}

	Public function getCompleteName(){
		if (isset($_SESSION['Auth']['firstname']) && isset($_SESSION['Auth']['lastname'])){
			return $_SESSION['Auth']['firstname'] . " " . $_SESSION['Auth']['lastname'];
		}else{
			return false;
		}
	}

}
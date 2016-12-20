<?php

class str{
	
	static function random($length){
		$alphabet="0123456789azertyuiopqsdfghjklmwxcvbn";
		return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
	}

}
<?php
define("KEY","23456789abcdefghijkmnoprstuvwxyz");
define("CODELEN",4);
class Model {
	private $type;

	public function __construct() {
		// clean up, supposed to run once in ten
		if (rand(1,10) == 1)
			$this->cleanUp();
	}
	
	public function storeNote() {
		$key = KEY;
		$fn = "";
		for (;strlen($fn)<CODELEN || file_exists($fn.".txt");$fn = (strlen($fn)>=CODELEN)?$key[rand(0,strlen($key)-1)]:$fn.$key[rand(0,strlen($key)-1)]);
		if (file_put_contents("var/".$fn.".txt",$_POST["note"])) {
			return $fn;
		}
		else
			return "";
	}
	
	public function cleanUp() {
		$fl = glob("var/*.txt");
		foreach ($fl as $fn) {
			if ((time()-filemtime($fn))/3600 > 1)
				// time - last modified > 1 hours
				unlink($fn);
		}
	}
	
	public function clearStorage() {
		$fl = glob("var/*");
		foreach ($fl as $fn) {
			unlink($fn);
		}
	}

	public function getNote() {
		$code = $_POST["code"];
		if ((strlen($code) != CODELEN) || !is_file("var/".$code.".txt")) {
			return false;
		}
		else {
			$fc = file_get_contents("var/".$code.".txt");
			unlink("var/".$code.".txt");
			return $fc;
		}
	}
}

?>
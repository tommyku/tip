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
		// generate random non-repeated filename
		for (;strlen($fn)<CODELEN || file_exists($fn.".json");$fn = (strlen($fn)>=CODELEN)?$key[rand(0,strlen($key)-1)]:$fn.$key[rand(0,strlen($key)-1)]);

		$tmpObj = new StdClass();
		$tmpObj->note = $_POST["note"];
		$tmpObj->success = true;

		if (file_put_contents("var/".$fn.".json", json_encode($tmpObj))) {
			return $fn;
		}
		else
			return "";
	}
	
	public function cleanUp() {
		$fl = glob("var/*.json");
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
		if ((strlen($code) != CODELEN) || !is_file("var/".$code.".json")) {
			return false;
		}
		else {
			$fc = file_get_contents("var/".$code.".json");
			unlink("var/".$code.".json");
			return $fc;
		}
	}
	
	public function getURLNote($code = NULL) {
		if (!$code || (strlen($code) != CODELEN) || !is_file("var/".$code.".json")) {
			return false;
		}
		else {
			$fc = json_decode(file_get_contents("var/".$code.".json"));
			unlink("var/".$code.".json");
			return $fc->note;
		}
	}
}

?>
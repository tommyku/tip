<?php
class View {
	private $type;
	private $formfield;

	public function __construct($type='') {
		$this->type = $type;
	}
	public function render() {
		switch ($this->type) {
			case "index":
				$this->renderStatic("index.tpl.html");
				break;
			case "codeSuccess":
				$this->renderNormal("codesuccess.tpl.json");
				break;
			case "codeFail":
				$this->renderStatic("codefail.tpl.json");
				break;
			case "noteSuccess":
				$this->renderNormal("notesuccess.tpl.json");
				break;
			case "noteFail":
				$this->renderStatic("notefail.tpl.json");
				break;
			case "404":
			default:
				$this->renderStatic("404.tpl.html");
		};
	}
	public function setVar($formfield) {
		$this->formfield = $formfield;
	}
	public function setView($type) {
		$this->type = $type;
	}
	private function renderStatic($template) {
		$message = file_get_contents(dirname(__FILE__).'/'.$template);
		header('Content-Type: text/html; charset=utf-8');
		echo $message;
	}
	private function renderNormal($template) {
		$message = file_get_contents(dirname(__FILE__).'/'.$template);
		$message = str_replace(array_keys($this->formfield), array_values($this->formfield), $message);
		header('Content-Type: text/html; charset=utf-8');
		echo $message;
	}
}

?>
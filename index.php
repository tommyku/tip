<?php
session_start();

require_once(dirname(__FILE__)."/model/"."Model.php");
require_once(dirname(__FILE__)."/view/"."View.php");

date_default_timezone_set("Asia/Hong_Kong");

// this is the Control, it handles pretty much everything

class Control {
	private $model = NULL;
	private $view = NULL;
	private $action = "";

	public function __construct() {
        $this->action = isset($_REQUEST['action']) ? strtolower($_REQUEST['action']) : (($_SERVER["QUERY_STRING"] == "") ? 'index' : $_SERVER["QUERY_STRING"]);
		$this->model = new Model;
		$this->view = new View;
	}
	
	public function run() {
		switch ($this->action) {
			case "index":
				$this->handleIndex();
				break;
			case "note":
				$this->handleNote();
				break;
			case "code":
				$this->handleCode();
				break;
			case "clear":
				$this->handleClear();
				break;
			case "404":
				$this->handle404();
			default:
				$this->handleURLCode();
		};
	}

	private function handleIndex() {
		$this->view->setView("index");
		$this->view->render();
	}

	private function handleNote() {
		$code = $this->model->storeNote();
		if (strlen($code) > 0) {
			$formfield = array(
				"{code}" => $code
			);
			$this->view->setVar($formfield);
			$this->view->setView("noteSuccess");
		}
		else
			$this->view->setView("noteFail");
		$this->view->render();
	}

	private function handleCode() {
		$note = $this->model->getNote();
		if ($note !== false) {
			$formfield = array(
				"{note}" => $note
			);
			$this->view->setVar($formfield);
			$this->view->setView("codeSuccess");
		}
		else
			$this->view->setView("codeFail");
		$this->view->render();
	}

	private function handleURLCode() {
		$note = $this->model->getURLNote($this->action);
		if ($note !== false) {
			$formfield = array(
				"{note}" => $note,
				"{code}" => $this->action
			);
			$this->view->setVar($formfield);
			$this->view->setView("codeUrlSuccess");
		}
		else {
			$formfield = array(
				"{code}" => $this->action
			);
			$this->view->setVar($formfield);
			$this->view->setView("codeUrlFail");
		}
		$this->view->render();
	}
	
	private function handleClear() {
		$this->model->clearStorage();
		$this->handleIndex();
	}

	private function handle404() {
		header('HTTP/1.1 404 Not Found', true, 404);
		$this->view->setView("404");
		$this->view->render();
	}
}

$actions = new Control;
$actions->run();
?>
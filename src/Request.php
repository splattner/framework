<?php

namespace splattner\framework;


class Request {

	private $getVars;
	private $postVars;

	public function __construct() {

		foreach($_POST as $key => $value) {

			$this->postVars[$key] = $value;
		}

		foreach($_GET as $key => $value) {

			$this->getVars[$key] = $value;
		}


	}

	public function getGetVars() {
		return $this->getVars;
	}

	public function getPostVars() {
		return $this->getPostVars;
	}

	public function getGetVar($key) {
		return $this->getVars[$key];
	}

	public function getPostVar($key) {
		return $this->postVars[$key];
	}


}
?>
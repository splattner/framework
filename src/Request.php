<?php

namespace splattner\framework;


class Request {

	private $getVars;
	private $postVars;

	public function __construct() {

		for ($key => $value in $_POST) {

			$this->postVars[$key] = $value;
		}

		for ($key => $value in $_GET) {

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
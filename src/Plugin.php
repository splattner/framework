<?php

namespace splattner\framework;

abstract class Plugin{


	/**
	 * Provides the Smarty Functions
	 * @access public
	 * @var mixed
	 */
	public $smarty;


	/**
	 * Manage the db connection
	 * @access public
	 * @var mixed
	 */
	public $pdo;

	/**
	 * Page Object
	 * @access public
	 * @var mixes
	 */
	public $page;

	/**
	 * The Session object to manage all Session related stuff
	 * @access public
	 * @var mixed
	 */
	public $session;

	/**
	 * Provides the PHPGACL Functions
	 * @access public
	 * @var mixed
	 */
	public $acl;

	
	/*
	 * Contains the HTLM Skin File for this Plugin
	 */
	public $contentFile;

	/*
	 * Structure to Passing Datas
	 */
	protected $data;


	public function setData($data) {
		$this->data = $data;
	}

	private function registerPlugin($registerAs) {
		$this->page->plugins[$registerAs] = $this;
	}

	public function __construct($registerAs) {

		$this->page = Application::getInstance("page");
		$this->smarty = Application::getInstance("smarty");
		$this->session = Application::getInstance("session");
		$this->pdo = Application::getInstance("pdo");
		$this->acl = Application::getInstance("acl");

		$this->registerPlugin($registerAs);
		
	}


	abstract public function run($action);

	public function __toString() {
		return $this->smarty->fetch($this->contentFile);
	}



}

?>

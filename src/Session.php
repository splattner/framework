<?php

namespace sebastianplattner\framework;


class Session {

	/**
	 * Current Session ID
	 * @access public
	 * @var string
	 */
	public $sid;

	/**
	 * Current userID
	 * @access public
	 * @var int
	 */
	public $uid;

	/**
	 * Current User is authenticated
	 * @access public
	 * @var boolean
	 */
	public $isAuth;
		/**
	 * Manage the db connection
	 * @access private
	 * @var mixed
	 */
	private $db;

	/**
	 * Provides the PHPGACL Functions
	 * @access public
	 * @var mixed
	 */
	private $acl;
	
	/**
	 * Array to Share Values between the Sessions
	 */
	public $share;
	
	/**
	 * The current page called by GET
	 */
	public $currentPage;

	/**
	 * Constructor for the MySession Class
	 * @param mixed $db
	 * @param mixed $acl
	 */
	public function __construct() {

		$this->loadShare();
		
		if(isset($_GET["page"])) {
			$this->currentPage = strtolower($_GET["page"]);
		} else {
			$this->currentPage = "index";
		}
		
		$this->db = Application::getInstance("db");
		$this->acl = Application::getInstance("acl");
		
		$this->initSession();
		$this->loadSessionFromDB();
	}
	
	public function __destruct() {
		$this->saveShare();
	}
	/**
	 * Check if this email and password is allowed to authenticate and if email and password are corresponding
	 * @param string $email
	 * @param string $password
	 * @return boolean successfull or not
	 */
	public function auth($email, $password) {
		
        $sql = $this->db->Prepare("SELECT * FROM persons WHERE email = ? AND password = MD5(?)");
        $rs = $this->db->execute($sql,array($email, $password));
		
		/**
		 * Check if any record are available and if acl allowes to authenticate
		 */
		if($rs->recordCount() == 1 && $this->acl->acl_check('auth', 'login', 'user', $rs->fields["id"])) {
			$this->uid = $rs->fields["id"];
			$this->isAuth = true;
			$this->updateSession($this->getSessionID());
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Wrapper for closeSession to Close my own Session
	 */
	public function closeMySession() {
		$this->closeSession($this->getSessionID());
	}

	/**
	 * Close Session in the DB, Set UID to NULL, and isAuth to false
	 * @param string $sid
	 */
	public function closeSession($sid) {
		$this->uid = 0;
		$this->isAuth = false;
		$this->updateSession($sid);
	}

	/**
	 * Update current session
	 * @param string $sid
	 */
	private function updateSession($sid) {
		$record["uid"] = $this->uid;
		$record["isAuth"] = $this->isAuth;
		$this->db->AutoExecute("session", $record, "UPDATE", "id = '" . $sid . "'");
	}

	/**
	 * Init a new Session
	 */
	private function initSession() {
		$this->clearSessions();
		$this->setSessionID(session_id());
		$this->uid = 0;
		$this->isAuth = false;
	}
	
	private function clearSessions() {
		$sql = "DELETE FROM session WHERE lastUpdate < NOW() - INTERVAL 30 MINUTE";
		$this->db->Execute($sql);
	}
	
	/**
	 * Load the session from the DB, or if new, create a new Session
	 */
	private function loadSessionFromDB() {
		$sql = "SELECT * FROM session WHERE id = '" . $this->getSessionID() . "'";
		$record = $this->db->Execute($sql);
		
		if ($record->RecordCount() > 0) {
			$this->uid = $record->fields["uid"];
			$this->isAuth = $record->fields["isAuth"];
			
			$sql = "UPDATE session SET lastUpdate = NOW() WHERE id= '" . $this->getSessionID() ."'";
			$this->db->Execute($sql);
			
		} else {
			$this->addSessionToDB();
			$this->loadSessionFromDB();
		}
	}

	/**
	 * Add current Session to db
	 */
	private function addSessionToDB() {
		$record["id"] = $this->getSessionID();
		$record["uid"] = 0;
		
		$this->db->autoExecute("session", $record, "INSERT");	
	}
		/**
	 * Get current Session
	 * @return string
	 */
	public function getSessionID() {
		return $this->sid;
	}

	/**
	 * Set current Session
	 * @param string $sid
	 */
	public function setSessionID($sid) {
		$this->sid = $sid;
	}
	
	/**
	 * Load all vars in $this->share into the session
	 */
	public function saveShare() {
		$_SESSION["share"] = $this->share;
	}
	
	/**
	 * Load all share vars into $this->share
	 */
	public function loadShare() {
		$this->share = $_SESSION["share"];
	}
	
	
}
?>

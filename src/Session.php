<?php

namespace splattner\framework;


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
	 * Roles for current User
	 * @access public
	 * @var string
	 */
	public $role;

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
	private $pdo;

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

		$this->pdo = Application::getInstance("pdo");

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

        $sql = $this->pdo->Prepare("SELECT * FROM persons WHERE email = ? AND password = MD5(?)");
        $sql->execute(array($email, $password));
        $rs = $sql->fetch();

		/**
		 * Check if any record are available and if acl allowes to authenticate
		 */
		if($sql->rowCount() == 1) {
			$this->uid = $rs["id"];
			if ($rs["role"] != "") {
				$this->role = $rs["role"];
			} else {
				$this->role = "guest";
			}
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
		$this->role = "guest";
		$this->updateSession($sid);
	}

	/**
	 * Update current session
	 * @param string $sid
	 */
	private function updateSession($sid) {
		$sql = $this->pdo->Prepare("UPDATE session SET uid = ?, isAuth = ?, role = ?,  lastUpdate = NOW() WHERE sid = ?");
        $sql->execute(array($this->uid, $this->isAuth, $this->role, $sid));
	}

	/**
	 * Init a new Session
	 */
	private function initSession() {
		$this->clearSessions();
		$this->setSessionID(session_id());
		$this->uid = 0;
		$this->isAuth = false;
		$this->role = "guest";
	}

	private function clearSessions() {
		$sql = $this->pdo->prepare("DELETE FROM session WHERE lastUpdate < NOW() - INTERVAL 30 MINUTE");
		$sql->Execute();
	}

	/**
	 * Load the session from the DB, or if new, create a new Session
	 */
	private function loadSessionFromDB() {
		$sql = $this->pdo->prepare("SELECT * FROM session WHERE `sid` = '" . $this->getSessionID() . "'");
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$res = $sql->fetch();

			$this->uid = $res["uid"];
			$this->isAuth = $res["isAuth"];
			if ($res["role"] != "") {
				$this->role = $res["role"];
			} else {
				$this->role = "guest";
			}


			$sql = $this->pdo->prepare("UPDATE `session` SET lastUpdate = NOW() WHERE `sid` = '" . $this->getSessionID() ."'");
			$sql->Execute();

		} else {
			$this->addSessionToDB();
			$this->loadSessionFromDB();
		}
	}

	/**
	 * Add current Session to db
	 */
	private function addSessionToDB() {

		$sql = $this->pdo->Prepare("INSERT INTO session (sid, uid) VALUES(?,?)");
        $sql->execute(array($this->getSessionID(), 0));

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

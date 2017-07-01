<?php

namespace splattner\framework;


class Application {
	
	static private $instances = array();
    static private $config;


    /**
     * All loaded services
     * @access public
     * @var mixed
     */
    private static $services;


    /**
     * Load all available Services and store them in service array
     */
    private static function loadServices() {

        Application::$services = array();

        $config = Application::getConfig();

        if ($handle = opendir($config["system"]["service-folder"])) {

            /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    require $config["system"]["service-folder"] . "/" . $entry;
                    $className = $config["system"]["namespace"] . "\\service\\" . basename($entry,".php");
                    $testClass = new \ReflectionClass($className);
                    if (!$testClass->isAbstract()) {
                        Application::$services[basename($entry,".php")] = new $className();
                    }

                }
            }
            closedir($handle);
        }
    }

    /**
     *
     * Get access to a Service
     *
     * @param $serviceName
     * @return mixed
     */
    public static function getService($serviceName) {
        return Application::$services[$serviceName];
    }


    /**
     * Return the application config array
     * @return mixed
     */
    static public function getConfig() {
        return Application::$config;
    }

    /**
     * Load all models from model Folder
     */
    static private function loadModels() {

        $config = Application::getConfig();

        if ($handle = opendir($config["system"]["models-folder"])) {

            /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
            while (false !== ($entry = readdir($handle))) {
                if($entry != "." && $entry != "..") {
                    require $config["system"]["models-folder"] . "/" .$entry;
                }
            }
            closedir($handle);
        }
    }

    /**
     * Load all models from model Folder
     */
    static private function loadPlugins() {

        $config = Application::getConfig();

        if ($handle = opendir($config["system"]["plugin-folder"])) {

            /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
            while (false !== ($entry = readdir($handle))) {
                if($entry != "." && $entry != "..") {
                    require $config["system"]["plugin-folder"] . "/" .$entry;
                }
            }
            closedir($handle);
        }
    }


    /**
     *
     * Initialize all necessary application stuff
     *
     * @param $config
     */
	static public function init($config) {

        // Store Config
        Application::$config = $config;


        // Load Models;
        Application::loadModels();

        /**
         * Initialize the Database Connection
         */
        $dsn = "mysqli://"
            . $config["db"]["username"] . ":"
            . $config["db"]["password"] . "@"
            . $config["db"]["server"] . "/"
            . $config["db"]["database"];
        $db = NewADOConnection($dsn);
        $db->debug = $config["system"]["debug"];
        $db->EXECUTE("set names 'utf8'");



        /**
         * PDO Initialization
         */
        $pdo = new \PDO(
            "mysql:host=" .  $config["db"]["server"] . ";dbname=" . $config["db"]["database"], 
            $config["db"]["username"], 
            $config["db"]["password"]
            );
        $pdo->query("set names 'utf8'");
        Application::setInstance("pdo", $pdo);


        /**
         * Setup ACL
         */
        if ($config["system"]["acl"] == null) {
            $acl = new \gburtini\ACL\ACL();
        } else {
            $acl = $config["system"]["acl"];
        }
        
        Application::setInstance("acl", $acl);

        /**
         * Initialize the Smarty Template Engine
         */
        $smarty = new \Smarty();
        $smarty->template_dir = $config["system"]["skins-folder"] . "/" . $config["template"]["default"] . "/templates";
        $smarty->compile_dir = $config["system"]["skins-folder"] . "/" . $config["template"]["default"] . "/templates_c";
        $smarty->cache_dir = "tmp/smarty";
        $smarty->config_dir = "etc/smarty";
        Application::setInstance("smarty", $smarty);


        /**
         * Session Management
         */
        $session = new Session();
        Application::setInstance("session",$session);

        /**
         * Load Services
         */
        Application::loadServices();

        /**
         * Load Plugins
         */
        Application::loadPlugins();


    }

    /**
     * Return an application instance (singleton)
     * @param $className
     * @return mixed
     */
	static public function getInstance($className) {
		if (!isset(self::$instances[$className])) {
			self::$instances[$className] = new $className();
		}
		return self::$instances[$className];
	}


	static public function setInstance($className, $object){
		self::$instances[$className] = $object;
	}


    /**
     * Create the application page class
     */
	static public function createPage() {
		$session = Application::getInstance("session");
        $config = Application::getConfig();

        if (isset($config["system"]["customPageClass"])) {
            require $config["system"]["pages-folder"] . "/" . $config["system"]["customPageClass"] . ".php";
        }
		
		include_once $config["system"]["pages-folder"] . "/" . $session->currentPage . ".page.php";
		$pageClass = $config["system"]["namespace"] . "\\pages\\Page" . ucfirst($session->currentPage);


		$page =  new $pageClass();

		Application::setInstance("page", $page);
	}

    /**
     * Run the Application
     * Check if this is an API call or a Page call
     */
	static public function run() {

        $session = Application::getInstance("session");

		/**
		 * Check if this is an API Call
		 */
		$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
		$isAPICall = ($request[0] == "api");
        $basicAuth = false;

		if ($isAPICall) {

            if ($session->isAuth || isset($_SERVER['PHP_AUTH_USER'])) {

                if (isset($_SERVER['PHP_AUTH_USER'])) {

                    $basicAuth = true;

                    $email = $_SERVER['PHP_AUTH_USER'];
                    $password = $_SERVER['PHP_AUTH_PW'];

                    if (!$session->auth($email, $password)) {
                        http_response_code(401);
						return;
                    }

                }

                $api = Application::getInstance(__NAMESPACE__ . "\\API");
                $api->call();

                if ($basicAuth) {
                    $session->closeMySession();
                }
            } else {
                http_response_code(403);
                return;
            }

		} else {
			$page = Application::getInstance("page");

			$page->init();
			$page->work();
			$page->render();

		}


	}
	
	static public function finish() {
		$session = Application::getInstance("session");
		
		unset($session);
	}
	
}
?>
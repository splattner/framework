<?php


namespace splattner\framework;

/**
 * Created by PhpStorm.
 * User: sebastianplattner
 * Date: 25.08.16
 * Time: 16:56
 */



class API
{

    /**
     * Manage the db connection
     * @access public
     * @var mixed
     */
    public $db;

    /**
     * The Session object to manage all Session related stuff
     * @access public
     * @var mixed
     */
    public $session;



    private $method;
    private $input;
    private $request;


    public function __construct() {

        $this->db = Application::getInstance("db");
        $this->session = Application::getInstance("session");

    }

    /**
     * Call either get,post,put,delete on a model
     * or call a API function
     */
    public function call() {

        $config = Application::getConfig();

        header('Content-type: application/json');

        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
        $this->input = json_decode(file_get_contents('php://input'), true);

        $apiCall = $this->request[1];


        $apiFile = $config["system"]["api-folder"] . "/" . $apiCall . ".api.php";
        $modelClass = $config["system"]["namespace"] . "\\models\\M" . $apiCall;

        // REST API for a Model
        if(class_exists($modelClass) && (!isset($this->request[2]) || is_numeric($this->request[2]))) {

            $model =  new $modelClass();

            if (count($this->request) > 2) {
                $id = $this->request[2];
            } else {
                $id = 0;
            }

            switch($this->method) {
                case 'GET':
                    $model->api_get($id);
                    break;
                case 'PUT':
                    $model->api_put($id, $this->input);
                    break;
                case 'POST':
                    $model->api_post($this->input);
                    break;
                case 'DELETE':
                    $model->api_delete($id);
                    break;
            }
        } else { // REST API with specific functions
            if(file_exists($apiFile)) {
                include_once $apiFile;
                $APIClass = $config["system"]["namespace"] . "\\api\\API" . $apiCall;
                $api =  new $APIClass();


                if (isset($this->request[2])) {
                    $function = $this->request[2];

                    $api->$function($this->request, $this->input);
                }

            }

        }



    }




}
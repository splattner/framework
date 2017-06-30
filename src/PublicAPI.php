<?php

namespace splattner\framework;

/**
 * Created by PhpStorm.
 * User: sebastianplattner
 * Date: 26.08.16
 * Time: 13:38
 */
class PublicAPI
{

    /**
     * Manage the db connection
     * @access public
     * @var mixed
     */
    public $pdo;


    protected $method;


    public function __construct()
    {
        header('Content-type: application/json');

        $this->pdo = Application::getInstance("pdo");
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

}
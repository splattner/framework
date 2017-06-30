<?php
/**
 * Created by PhpStorm.
 * User: sebastianplattner
 * Date: 04.09.16
 * Time: 13:56
 */

namespace splattner\framework;


class Service
{

    /**
     * Manage the db connection
     * @access protected
     * @var mixed
     */
    protected $pdo;

    /**
     * The Session object to manage all Session related stuff
     * @access protected
     * @var mixed
     */
    protected $session;

    public function __construct() {
        $this->pdo = Application::getInstance("pdo");
        $this->session = Application::getInstance("session");
    }

}
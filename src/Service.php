<?php
/**
 * Created by PhpStorm.
 * User: sebastianplattner
 * Date: 04.09.16
 * Time: 13:56
 */

namespace sebastianplattner\framework;


class Service
{

    /**
     * Manage the db connection
     * @access protected
     * @var mixed
     */
    protected $db;

    /**
     * The Session object to manage all Session related stuff
     * @access protected
     * @var mixed
     */
    protected $session;

    public function __construct() {
        $this->db = Application::getInstance("db");
        $this->session = Application::getInstance("session");
    }

}
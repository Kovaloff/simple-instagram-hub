<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/6/2016
 * Time: 4:54 PM
 */
namespace Application\Core;

use SessionHandler\Session\Session;

class Controller
{
    public $model;
    public $view;
    public $helper;

    function __construct()
    {
        $this->view = new View();
        $this->helper = new Helper();
        $this->dbh = new \PDO(
            "mysql:host={$this->helper->config['db_credentials']['host']};
            dbname={$this->helper->config['db_credentials']['dbname']}",
            $this->helper->config['db_credentials']['user'],
            $this->helper->config['db_credentials']['pass']);
        $this->model = new Model($this->dbh);
        Session::start();
    }

    function action_index()
    {
    }
}
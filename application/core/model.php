<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/6/2016
 * Time: 4:53 PM
 */
namespace Application\Core;

class Model
{
    protected $dbh;

    public function __construct(\PDO $db)
    {
        $this->dbh = $db;
    }

    public function get_data()
    {
    }

    public function DB()
    {
        return $this->dbh;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/10/2016
 * Time: 12:11 PM
 */
namespace Application\Core;

class Helper
{
    public $config;

    function __construct() {
        $this->config = $this->get_config();
    }

    private function get_config()
    {
        $config = parse_ini_file(__DIR__ . '/../config/config.ini',true);
        return $config;
    }
}
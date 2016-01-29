<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/6/2016
 * Time: 5:34 PM
 */
use Application\Core\Controller;
use SessionHandler\Session\Session;

class Controller_Logout extends Controller
{

    function action_index()
    {
        Session::destroy();
        header('Location: http://' . $_SERVER['SERVER_NAME'].'/');
    }

}
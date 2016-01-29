<?php
/**
 * Created by PhpStorm.
 * User: Mantikora
 * Date: 1/6/2016
 * Time: 5:33 PM
 */
use Application\Core\Controller;

class Controller_404 extends Controller
{
    function action_index() {
        $this->view->generate('404_view.phtml','layout.phtml');
    }
}
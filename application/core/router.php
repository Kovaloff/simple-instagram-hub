<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/6/2016
 * Time: 4:42 PM
 */
namespace Application\Core;

class Router
{
    static function start()
    {
        // default controller
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // get controller name
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }

        // get method name
        if (!empty($routes[2])) {
            $action_name = $routes[2];
        }

        // add prefix
        $model_name = 'Model_' . $controller_name;
        $controller_name = 'Controller_' . $controller_name;
        $action_name = 'action_' . $action_name;

        // get file with model class

        $model_file = strtolower($model_name) . '.php';
        $model_path = "application/models/" . $model_file;
        if (file_exists($model_path)) {
            include "application/models/" . $model_file;
        }

        // get file with controller
        $controller_file = strtolower($controller_name) . '.php';
        $controller_path = "application/controllers/" . $controller_file;
        if (file_exists($controller_path)) {
            include "application/controllers/" . $controller_file;
        } else {
            Router::ErrorPage404();
        }

        // crete controller
        $controller = new $controller_name;
        $action = $action_name;

        if (method_exists($controller, $action)) {
            // get controller action
            $controller->$action();
        } else {
            // here we can throw exception , but now just redirect
            Router::ErrorPage404();
        }

    }

    function ErrorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
    }
}
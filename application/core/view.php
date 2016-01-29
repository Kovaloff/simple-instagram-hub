<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 1/6/2016
 * Time: 4:54 PM
 */
namespace Application\Core;

class View
{
    //public $template_view; // здесь можно указать общий вид по умолчанию.

    function generate($content_view, $template_view, $data = null)
    {
        /*
        if(is_array($data)) {
            // преобразуем элементы массива в переменные
            extract($data);
        }
        */

        include 'application/views/'.$template_view;
    }
}
<?php

namespace App\Http\Controllers;

use Core\views\Layout;

abstract class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        $layout = new Layout();
        $layout->setView(__DIR__ . "/../views/$view.php");
        echo $layout->render();
//        require_once __DIR__ . "/../views/$view.php";
    }
}

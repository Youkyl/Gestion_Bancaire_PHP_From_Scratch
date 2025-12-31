<?php

namespace App\Core;

class Router
{
    public function run()
    {
        $controller = $_GET['controller'] ?? 'home';
        $action     = $_GET['action'] ?? 'index';

        $controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controller introuvable";
            return;
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $action)) {
            http_response_code(404);
            echo "Action introuvable";
            return;
        }

        $controllerInstance->$action();
    }
}
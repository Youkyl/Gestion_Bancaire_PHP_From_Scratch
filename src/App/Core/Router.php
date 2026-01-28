<?php

namespace App\core;

use App\http\Request;
use ReflectionMethod;

class Router
{

    
    public function run()
    {
        /*
             http://localhost:8000/index.php?controller=compte&action=create
             $controller = $_GET['controller'];  ==> compte
             $action =  $_GET['action'];

         */

            /* 
                  $url =   http://localhost:8000/index.php/home/index
              $routes = [
                'home' => [
                    'controller' => HomeController::class,
                    'actions' => ['index'],

                ],

                'compte' => [
                    'controller' => CompteController::class,
                    'actions' => ['index', 'create', 'store'],
                ],

                'transaction' => [
                    'controller' => TransactionController::class,
                    'actions' => ['index', 'create', 'store', 'list'],
                ],
              ]
           Documentation sur $_SERVER
            */

        $controller = $_REQUEST['controller'] ?? 'home';  //ucfirst(home) ==> Home
        $action     = $_REQUEST['action'] ?? 'index';

        $donnee     = $_REQUEST['donnee'] ?? null;

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

        $request = Request::createFromGlobals();

        $reflection = new ReflectionMethod($controllerInstance, $action);
        $parameters = $reflection->getParameters();

        if (count($parameters) > 0) {
            $controllerInstance->$action($request);
        } else {
            $controllerInstance->$action();
        }
        
       // $controllerInstance->$action($donnee, $request);
    }
}
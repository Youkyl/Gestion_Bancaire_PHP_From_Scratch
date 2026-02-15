<?php

namespace App\core;

// use App\controllers\CompteController;
// use App\controllers\HomeController;
// use App\controllers\TransactionController;
// use App\http\Request;
// use ReflectionMethod;

// class Router
// {

    
//     public function run()
//     {
//         /*
//              http://localhost:8000/index.php?controller=compte&action=create
//              $controller = $_GET['controller'];  ==> compte
//              $action =  $_GET['action'];

//          */

//             /* 
//               $url =   http://localhost:8000/index.php/home/index
//               $routes = [
//                 'home' => [
//                     'controller' => HomeController::class,
//                     'actions' => ['index'],

//                 ],

//                 'compte' => [
//                     'controller' => CompteController::class,
//                     'actions' => ['index', 'create', 'store'],
//                 ],

//                 'transaction' => [
//                     'controller' => TransactionController::class,
//                     'actions' => ['index', 'create', 'store', 'list'],
//                 ],
//               ]
//            Documentation sur $_SERVER
//             */

//         $controller = $_REQUEST['controller'] ?? 'home';  //ucfirst(home) ==> Home
//         $action     = $_REQUEST['action'] ?? 'index';

//         //$donnee     = $_REQUEST['donnee'] ?? null;

//         $controllerClass = 'App\\controllers\\' . ucfirst($controller) . 'Controller';
      
//         if (!class_exists($controllerClass)) {
//             http_response_code(404);
//             echo "Controller introuvable";
//             return;
//         }

//         $controllerInstance = new $controllerClass();
//         if (!method_exists($controllerInstance, $action)) {
//             http_response_code(404);
//             echo "Action introuvable";
//             return;
//         }

//         $request = Request::createFromGlobals();

//         $reflection = new ReflectionMethod($controllerInstance, $action);
//         $parameters = $reflection->getParameters();

//         if (count($parameters) > 0) {
//             $controllerInstance->$action($request);
//         } else {
//             $controllerInstance->$action();
//         }
        
//        // $controllerInstance->$action($donnee, $request);
//     }
// }



use App\controllers\CompteController;
use App\controllers\HomeController;
use App\controllers\TransactionController;
use App\http\Request;
use ReflectionMethod;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->defineRoutes();
    }

    /**
     * ✅ Définir toutes les routes de l'application
     */
    private function defineRoutes(): void
    {
        $this->addRoute('home', HomeController::class, ['index']);
        
        $this->addRoute('compte', CompteController::class, [
            'index', 
            'create', 
            'store'
        ]);
        
        $this->addRoute('transaction', TransactionController::class, [
            'index', 
            'create', 
            'store', 
            'list'
        ]);
    }

    /**
     * ✅ Ajouter une route
     */
    private function addRoute(string $name, string $controller, array $actions): void
    {
        $this->routes[$name] = [
            'controller' => $controller,
            'actions' => $actions,
        ];
    }

    public function run()
    {
        $uri = $this->getUri();
        
        [$controllerName, $action] = $this->parseUri($uri);

        $this->dispatch($controllerName, $action);
    }

    /**
     * ✅ Récupérer et nettoyer l'URI
     */
    private function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = str_replace('/index.php', '', $uri);
        return trim($uri, '/');
    }

    /**
     * ✅ Parser l'URI en contrôleur et action
     */
    private function parseUri(string $uri): array
    {
        if (empty($uri)) {
            return ['home', 'index'];
        }

        $segments = explode('/', $uri);
        
        $controllerName = $segments[0] ?? 'home';
        $action = $segments[1] ?? 'index';

        return [$controllerName, $action];
    }

    /**
     * ✅ Dispatcher la requête
     */
    private function dispatch(string $controllerName, string $action): void
    {
        
        if (!isset($this->routes[$controllerName])) {
            $this->notFound("Route '$controllerName' introuvable");
            return;
        }

        $routeConfig = $this->routes[$controllerName];

        if (!in_array($action, $routeConfig['actions'])) {
            $this->notFound("Action '$action' non autorisée pour '$controllerName'");
            return;
        }

        $controllerClass = $routeConfig['controller'];

        if (!class_exists($controllerClass)) {
            $this->notFound("Controller class introuvable");
            return;
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $action)) {
            $this->notFound("Méthode '$action' introuvable");
            return;
        }

        $request = Request::createFromGlobals();

        $this->callAction($controllerInstance, $action, $request);
    }

    /**
     * ✅ Appeler l'action du contrôleur
     */
    private function callAction(object $controller, string $action, Request $request): void
    {
        $reflection = new ReflectionMethod($controller, $action);
        $parameters = $reflection->getParameters();

        if (count($parameters) > 0) {
            $controller->$action($request);
        } else {
            $controller->$action();
        }
    }

    /**
     * ✅ Gérer les erreurs 404
     */
    private function notFound(string $message = "404 - Page non trouvée"): void
    {
        http_response_code(404);
        
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>404 - Erreur</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background: #f5f5f5;
                }
                .error-container {
                    text-align: center;
                    padding: 40px;
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                h1 { color: #e74c3c; font-size: 72px; margin: 0; }
                p { color: #555; font-size: 18px; }
                a { color: #3498db; text-decoration: none; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h1>404</h1>
                <p>$message</p>
                <a href='/'>← Retour à l'accueil</a>
            </div>
        </body>
        </html>";
    }
}
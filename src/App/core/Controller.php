<?php

namespace App\core;

class Controller
{
    /*
       $data=[
           'var1'=> 'value1',
           'var2'=> 'value2'
        ];
        // Dans la vue on aura accès à :
            $data['var1'] => value1
            $data['var2'] => value2
       //extract va créer des variables à partir des clés du tableau associatif
                extract($data);
             // Dans la vue on aura accès à :
                $var1 => value1
                $var2 => value2

    
    
    */

    private static Controller $instance;

    private function __construct()
    {
    }

    public static function getInstance(): Controller
    {
        if (self::$instance === null) {
            self::$instance = new Controller();
        }
        return self::$instance;
    }
    
    protected function renderHtml(string $templateName, array $data = [])
    {
        $path = PATH_ROOT . '/templates/' . $templateName;
        if (!file_exists($path)) {
            http_response_code(404);
            die("Page introuvable");
        }
          extract($data);
          ob_start();
           require_once $path;
           $contentForView = ob_get_clean();
           
           require_once PATH_ROOT . '/templates/base.layout.html.php';
    }

    protected function redirect(string $uri)
    {
        header("Location: index.php?$uri");
        exit;
    }
}
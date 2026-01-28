<?php

namespace App\http;

// class Request
// {
//     public $query;
//     public $post;
    
//     public function __construct()
//     {
//         $this->query = new ParameterBag($_GET);
//         $this->post = new ParameterBag($_POST);
//     }
// }


class Request
{
    private array $query;      // $_GET
    private array $request;    // $_POST
    private array $server;     // $_SERVER
    private array $files;      // $_FILES
    
    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
    }
    
    /**
     * Récupérer un paramètre GET
     */
    public function get(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }
    
    /**
     * Récupérer un paramètre POST
     */
    public function post(string $key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }
    
    /**
     * Récupérer tous les paramètres GET
     */
    public function all(): array
    {
        return $this->query;
    }
    
    /**
     * Vérifier si un paramètre GET existe
     */
    public function has(string $key): bool
    {
        return isset($this->query[$key]);
    }
    
    /**
     * Récupérer la méthode HTTP (GET, POST, etc.)
     */
    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }
    
    /**
     * Vérifier si la requête est POST
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }
    
    /**
     * Récupérer l'URL complète
     */
    public function url(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }
    
    /**
     * Créer une instance depuis les superglobales
     */
    public static function createFromGlobals(): self
    {
        return new self();
    }
}
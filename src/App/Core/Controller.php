<?php

namespace App\Core;

class Controller
{
    protected function renderHtml(string $path, array $data = [])
    {
        if (!file_exists($path)) {
            http_response_code(404);
            die("Page introuvable");
        }

        require_once $path;
    }

    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }
}
<?php

namespace App\http;


class ParameterBag
{
    private array $parameters;
    
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }
    
    public function get(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }
    
    public function all(): array
    {
        return $this->parameters;
    }
    
    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }
}
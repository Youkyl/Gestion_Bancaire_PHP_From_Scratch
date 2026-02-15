<?php

namespace App\core;


class Validator
{
    private array $data;
    private array $rules = [];
    private array $errors = [];

    private static Validator | null $instance = null;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function getInstance(array $data): Validator
    {
        if (self::$instance === null) {
            self::$instance = new Validator($data);
        }
        return self::$instance;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function passes(): bool
    {
        foreach ($this->rules as $field => $rules) {

            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {

                $param = null;

                if (strpos($rule, ':') !== false) {
                    [$rule, $param] = explode(':', $rule, 2);
                }

                $method = 'validate' . ucfirst($rule);

                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $param);
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    /* ================= RULES ================= */

    private function validateRequired(string $field, $value): void
    {
        if ($value === null || trim($value) === '') {
            $this->errors[$field][] = "$field is required.";
        }
    }

    private function validateEmail(string $field, $value): void
    {
        if ($value !== null && $value !== '' &&
            !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "$field must be a valid email.";
        }
    }

    private function validateMin(string $field, $value, $param): void
    {
        if ($value !== null && strlen($value) < (int)$param) {
            $this->errors[$field][] =
                "$field must have at least $param characters.";
        }
    }

    private function validateMax(string $field, $value, $param): void
    {
        if ($value !== null && strlen($value) > (int)$param) {
            $this->errors[$field][] =
                "$field must have at most $param characters.";
        }
    }

    private function validateNumeric(string $field, $value): void
    {
        if ($value !== null && !is_numeric($value) && $value < 0) {
            $this->errors[$field][] = "$field must be numeric.";
        }
    }

    private function validateAccType(string $field, $value){
            if (!in_array($value, ['CHEQUE', 'EPARGNE'])) {
                $this->errors[$field] = "Type de compte invalide.";
            }
    }

}

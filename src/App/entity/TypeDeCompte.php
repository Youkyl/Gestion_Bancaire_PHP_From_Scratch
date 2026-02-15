<?php
namespace App\entity;

enum TypeDeCompte: string
{
    case EPARGNE = 'Epargne';
    case CHEQUE  = 'Cheque';

    public static function fromDatabase(string $value): self
    {
        return match (strtoupper($value)) {
            'EPARGNE' => self::EPARGNE,
            'CHEQUE' => self::CHEQUE,
            default => throw new \ValueError("Invalid type: $value")
        };
    }
}
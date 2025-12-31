<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {

        if (self::$pdo === null) {
            try {
                $dsn = sprintf(
                    '%s:host=%s;port=%s;dbname=%s',
                    $_ENV['DATABASE_DRIVE'],
                    $_ENV['DATABASE_HOST'],
                    $_ENV['DATABASE_PORT'],
                    $_ENV['DATABASE_NAME']
                );
                self::$pdo = new PDO(
                    $dsn,
                    $_ENV['DATABASE_USER'],
                    $_ENV['DATABASE_PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur connexion BD : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

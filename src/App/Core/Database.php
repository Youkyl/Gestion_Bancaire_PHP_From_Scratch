<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                // Si DATABASE_URL est définie, utiliser NeonDB
                if (!empty($_ENV['DATABASE_URL'])) {
                    // Parser l'URL de connexion NeonDB
                    $dbUrl = parse_url($_ENV['DATABASE_URL']);
                    
                    $dsn = sprintf(
                        'pgsql:host=%s;port=%s;dbname=%s;sslmode=require',
                        $dbUrl['host'],
                        $dbUrl['port'] ?? 5432,
                        ltrim($dbUrl['path'], '/')
                    );
                    
                    self::$instance = new PDO(
                        $dsn,
                        $dbUrl['user'],
                        $dbUrl['pass'],
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]
                    );
                } else {
                    // Sinon, utiliser la connexion locale
                    $dsn = sprintf(
                        '%s:host=%s;port=%s;dbname=%s',
                        $_ENV['DATABASE_DRIVE'],
                        $_ENV['DATABASE_HOST'],
                        $_ENV['DATABASE_PORT'],
                        $_ENV['DATABASE_NAME']
                    );

                    self::$instance = new PDO(
                        $dsn,
                        $_ENV['DATABASE_USER'],
                        $_ENV['DATABASE_PASSWORD'],
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]
                    );
                }
            //dd(self::$instance);
            } catch (PDOException $e) {
                die("Erreur base de données : " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    public static function closeConnexion(): void
    {
        self::$instance = null;
    }
}
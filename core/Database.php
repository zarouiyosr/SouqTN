<?php
class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $db   = getenv('DB_NAME') ?: 'souqtn';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $port = getenv('DB_PORT') ?: '3306';

            self::$instance = new PDO(
                "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
                $user, $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_SSL_CA => '/var/www/html/ca.pem',
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
                ]
            );
        }
        return self::$instance;
    }
}

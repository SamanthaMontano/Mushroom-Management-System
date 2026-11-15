<?php
/**
 * CMD - Mushroom Management System
 * Database configuration (PDO singleton)
 */

class Database
{
    private static ?PDO $instance = null;

    // ---- Update these to match your environment ----
    private static string $host    = 'localhost';
    private static string $dbname  = 'mushroom_db';
    private static string $user    = 'root';
    private static string $pass    = '';
    private static string $charset = 'utf8mb4';
    // --------------------------------------------------

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=' . self::$charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}

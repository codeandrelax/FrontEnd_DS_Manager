<?php
// Path: app/Config/Database.php
namespace App\Config;

use PDO;
use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Database
{
    private static $authConnection = null;
    private static $glavnilagerConnection = null;
    private static $authWowConnection = null;
    private static $charactersConnection = null;
    private static $worldConnection = null;
    private static $logger;

    // Initialize logger to log database connection errors into database.log file
    private static function initLogger()
    {
        if (self::$logger === null) {
            self::$logger = new Logger('database');
            self::$logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/database.log', Logger::ERROR));
        }
    }

    // We call this method to get the connection to the Auth database
    public static function getAuthConnection()
    {
        if (self::$authConnection === null) {
            self::$authConnection = self::createConnection('Auth');
        }
        return self::$authConnection;
    }

    // We call this method to get the connection to the GlavniLager database
    public static function getGlavniLagerConnection()
    {
        if (self::$glavnilagerConnection === null) {
            self::$glavnilagerConnection = self::createConnection('glavnilager');
        }
        return self::$glavnilagerConnection;
    }

    // We call this method to get the connection to the AuthWow database
    public static function getAuthWowConnection()
    {
        if (self::$authWowConnection === null) {
            self::$authWowConnection = self::createConnection('authwow');
        }
        return self::$authWowConnection;
    }

    // We call this method to get the connection to the Characters database
    public static function getCharactersConnection()
    {
        if (self::$charactersConnection === null) {
            self::$charactersConnection = self::createConnection('characters');
        }
        return self::$charactersConnection;
    }

    // We call this method to get the connection to the World database
    public static function getWorldConnection()
    {
        if (self::$worldConnection === null) {
            self::$worldConnection = self::createConnection('world');
        }
        return self::$worldConnection;
    }

    // Create a new PDO connection
    private static function createConnection($type)
    {
        self::initLogger();

        $config = require __DIR__ . '/Config.php'; // Loading database credentials
        $dbConfig = $config[$type . '_db'];

        try {
            return new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8",
                $dbConfig['user'],
                $dbConfig['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            // Log the error if the connection fails
            self::$logger->error("Database connection failed for type {$type}", [
                'message' => $e->getMessage(),
                'type' => $type,
                'host' => $dbConfig['host'],
                'dbname' => $dbConfig['dbname'],
                'time' => date('Y-m-d H:i:s'),
            ]);

            // Return null or handle gracefully
            throw new \Exception("Unable to connect to the database. Please try again later.");
        }
    }
}

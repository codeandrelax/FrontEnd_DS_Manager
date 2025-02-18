<?php

namespace App\Config;

use Delight\Auth\Auth;
use App\Services\PaginationService;
use App\Middleware\CsrfVerifier;
use App\Services\SessionTokenProvider;

class Container
{
    private static $instances = [];

    public static function get($key)
    {
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = self::createInstance($key);
        }
        return self::$instances[$key];
    }

    private static function createInstance($key)
    {
        switch ($key) {
            case 'auth': // Delight Auth authentication library
                return new Auth(self::get('Auth_db'));

            case 'Auth_db': // Database connection to the Auth database
                return Database::getAuthConnection();

            case 'glavnilager_db':
                return Database::getGlavniLagerConnection();

            case 'authwow_db':
                return Database::getAuthWowConnection();

            case 'characters_db':
                return Database::getCharactersConnection();

            case 'world_db':
                return Database::getWorldConnection();


            case 'pagination':
                return new PaginationService();

            case 'logger':
                return self::createLogger();

            case 'csrf':
                $csrfVerifier = new CsrfVerifier();
                $csrfVerifier->setTokenProvider(new SessionTokenProvider());
                return $csrfVerifier;

            case 'authController':
                $auth = self::get('auth');
                $csrfVerifier = self::get('csrf');
                $logger = self::get('logger');
                $authDb = self::get('Auth_db');
                $onlineModifyModel = new \App\Models\OnlineModifyModel($authDb);
                return new \App\Controllers\AuthController($auth, $csrfVerifier, $logger, $onlineModifyModel);

            case 'userController':
                $auth = self::get('auth');
                $db = self::get('Auth_db');
                $logger = self::get('logger');
                return new \App\Controllers\UserController($auth, $db, $logger);

            case 'adminsController':
                $auth = self::get('auth');
                $db = self::get('Auth_db');
                return new \App\Controllers\AdminsController($auth, $db);


            case 'reportsController':
                return new \App\Controllers\ReportsController();

            case 'dashboardController':
                $auth = self::get('auth');
                $csrfVerifier = self::get('csrf');
                $routesRoles = self::get('routesRoles');
                return new \App\Controllers\DashboardController($auth, $routesRoles);

            case 'routesRoles':
                return require __DIR__ . '/routes_roles.php';

            default:
                throw new \Exception("No such service: $key");
        }
    }

    private static function createLogger()
    {
        $logger = new \Monolog\Logger('app');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../../logs/unknown-routes.log', \Monolog\Logger::WARNING));
        return $logger;
    }
}

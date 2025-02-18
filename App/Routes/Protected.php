<?php

// Protected.php (routes)
use Pecee\SimpleRouter\SimpleRouter;
use App\Config\Container;

// Controllers that are protected by the GeneralMiddleware must be added here
$authController = Container::get('authController');
$dashboardController = Container::get('dashboardController');
$userController = Container::get('userController');
$reportsController = Container::get('reportsController');
$adminsController = Container::get('adminsController');

// Group of routes that are protcted by the GeneralMiddleware and require authentication also must be added here
SimpleRouter::group(['middleware' => \App\Middleware\GeneralMiddleware::class], function () use (
    $authController,
    $dashboardController,
    $userController,
    $reportsController,
    $adminsController,
) {
    // Here we add all URLs that need to use Dashboard, if u want pages to be seen add them here
    $dashboardRoutes = [
        '/dashboard' => 'dashboard',
        '/developers' => 'developers',
        '/administrators' => 'administrators',
        '/administrators/upravljanje' => '/administrators/upravljanje',
        '/administrators/registracija' => '/administrators/registracija',
        '/administrators/konfiguracija' => '/administrators/konfiguracija',
        '/korisnici' => 'korisnici',
        '/korisnici/reklame' => '/korisnici/reklame',
        '/nova-reklama/upload' => '/nova-reklama/upload',
        '/managers' => 'managers',
    ];

    // Here we simply define dashboard to URL mapping and call the showDashboard method
    foreach ($dashboardRoutes as $uri => $view) {
        SimpleRouter::get($uri, function () use ($dashboardController, $view) {
            $dashboardController->showDashboard($view);
        });
    }

    //**********************/
    // Manage Users Routes //
    SimpleRouter::get('/administrators/upravljanje/data', function () use ($userController) {
        $userController->fetchUsers(); // API route for fetching users
    });

    SimpleRouter::post('/administrators/upravljanje/edit', function () use ($userController) {
        $userController->editUser(); // API route for editing users
    });

    SimpleRouter::post('/administrators/upravljanje/add', function () use ($userController) {
        $userController->addUser(); // API route for adding users
    });

    SimpleRouter::post('/administrators/upravljanje/delete', function () use ($userController) {
        $userController->deleteUser(); // API route for deleting users
    });

    //*****************/
    // Reports Routes //
    SimpleRouter::get('/reports/getLogs', function () use ($reportsController) {
        $reportsController->getLogs(); // API route for fetching logs
    });

    SimpleRouter::get('/reports/viewLog', function () use ($reportsController) {
        $reportsController->viewLog(); // API route for viewing log content
    });

    //**************/
    // API Routes //
    SimpleRouter::get('/api/users-by-roles', function () use ($adminsController) {
        $adminsController->getUsersByRoles();
    });

    SimpleRouter::get('/api/active-users-by-days', function () use ($adminsController) {
        $adminsController->getActiveUsersByDays();
    });

    SimpleRouter::get('/api/registered-users-by-months', function () use ($adminsController) {
        $adminsController->getRegisteredUsersByMonths();
    });

    SimpleRouter::get('/api/server-usage', function () use ($adminsController) {
        $adminsController->getServerUsage();
    });

    SimpleRouter::get('/api/online-users', function () use ($adminsController) {
        $adminsController->getOnlineUsers();
    });

    //**************//
    // Logout ruta //
    SimpleRouter::get('/logout', function () use ($authController) {
        $authController->logout();
    });
});

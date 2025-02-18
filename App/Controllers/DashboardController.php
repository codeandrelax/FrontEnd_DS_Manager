<?php
// DashboardController.php
namespace App\Controllers;

use App\Config\Container;

class DashboardController
{
    private $auth;
    private $routesRoles;
    private $logger;

    public function __construct()
    {
        $this->auth = Container::get('auth');
        $this->routesRoles = Container::get('routesRoles');
        $this->logger = Container::get('logger');
    }

    public function showDashboard(string $page)
    {
        // Debug logging za praćenje problema
        $this->logger->debug('Showing dashboard', [
            'page'  => $page,
            'user'  => $this->auth->getUsername(),
            'roles' => $this->auth->getRoles()
        ]);

        // Za testiranje (ne koristiti u produkciji)
        $auth = $this->auth;
        $routes = $this->routesRoles['routes'];

        // Varijable za sidebar (prikaz korisnika i njihovog ranga)
        $username   = $this->auth->getUsername();
        $auth_roles = implode(', ', $this->auth->getRoles());

        // Učitavanje konfiguracije ruta i mape ikona
        $routes  = $this->routesRoles['routes'];
        $iconMap = $this->routesRoles['iconMap'];

        // Filtriranje navigacijskih stavki prema korisničkim rolama
        $filteredNavItems = [];
        foreach ($routes as $path => $item) {
            $hasAccess = false;
            // Ako je korisnik Developer, pristup je dozvoljen svuda
            if ($this->auth->hasRole(\Delight\Auth\Role::DEVELOPER)) {
                $hasAccess = true;
            } else if (isset($item['roles'])) {
                foreach ($item['roles'] as $role) {
                    if ($this->auth->hasRole($role)) {
                        $hasAccess = true;
                        break;
                    }
                }
            }
            if ($hasAccess) {
                $filteredNavItems[$path] = $item;
            }
        }

        // Određivanje kojeg content fajla učitati pomoću rekurzivnog pretraživanja
        $contentFile = null;
        $currentPath = '/' . strtolower(trim($page, '/'));
        $routeData = $this->findRouteData($currentPath, $routes);
        if ($routeData && isset($routeData['view']) && file_exists($routeData['view'])) {
            $contentFile = $routeData['view'];
            // Debug logging za putanju content fajla
            $this->logger->debug('Content file path', [
                'path'   => $contentFile,
                'exists' => file_exists($contentFile)
            ]);
        }

        // Uključivanje sidebar šablona (layout) i prosljeđivanje potrebnih varijabli
        require_once __DIR__ . '/../Views/Protected/Sidebar.php';
    }

    /**
     * Rekurzivna funkcija za pronalaženje podataka o ruti (uključujući 'sub' kategorije)
     *
     * @param string $uri Traženi URI, npr. '/korisnici/reklame'
     * @param array  $routes Niz ruta (top-level ili podniz)
     * @return array|null Vraća konfiguraciju rute ako je pronađena, inače null.
     */
    protected function findRouteData(string $uri, array $routes)
    {
        // Provjera na top-levelu
        if (isset($routes[$uri])) {
            return $routes[$uri];
        }

        // Ako nije pronađeno, pretražujemo sve unose koji imaju podkategorije ('sub')
        foreach ($routes as $route) {
            if (isset($route['sub']) && is_array($route['sub'])) {
                // Direktna provjera unutar 'sub' niza
                if (isset($route['sub'][$uri])) {
                    return $route['sub'][$uri];
                }
                // Rekurzivno pretraživanje unutar 'sub'
                $found = $this->findRouteData($uri, $route['sub']);
                if ($found !== null) {
                    return $found;
                }
            }
        }

        // Ruta nije pronađena
        return null;
    }
}

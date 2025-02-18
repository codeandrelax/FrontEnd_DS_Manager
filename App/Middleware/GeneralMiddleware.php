<?php

namespace App\Middleware;

use App\Config\Container;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter;

/**
 * GeneralMiddleware
 *
 * Ovaj middleware služi za upravljanje pristupom rutama na osnovu
 * autentifikacije i autorizacije korisnika. Podržava rekurzivno pretraživanje
 * ruta (uključujući sub-rute) i nasleđivanje uloga sa roditeljskih ruta.
 *
 * Odgovornosti:
 * - Validira konfiguraciju ruta.
 * - Normalizuje i dobija URI trenutnog zahteva.
 * - Proverava da li je ruta javna (bez potrebe za prijavom).
 * - Preusmerava korisnike koji nisu prijavljeni na login stranicu.
 * - Dozvoljava korisnicima sa Developer ulogom potpun pristup.
 * - Rekurzivno pretražuje konfiguraciju ruta kako bi pronašao podatke za
 *   traženu rutu (uključujući ugnježdene sub-rute).
 * - Proverava da li korisnik ima potrebne uloge za pristup ruti.
 * - Loguje greške i pokušaje neautorizovanog pristupa te preusmerava korisnike
 *   u slučaju greške ili nedostatka pristupa.
 */
class GeneralMiddleware implements IMiddleware
{
    /**
     * Rekurzivno pretražuje niz ruta da pronađe odgovarajuće podatke za zadati URI.
     *
     * Ako se pronađe direktan podudaranje u trenutnom nivou, a uloga nije definisana,
     * nasleđuje se uloga od roditelja.
     *
     * @param array  $routes      Niz ruta iz konfiguracije.
     * @param string $requestUri  Normalizovani URI trenutnog zahteva.
     * @param array  $parentRoles Uloge nasleđene sa roditeljske rute (podrazumevano prazan niz).
     *
     * @return array|null Vraća podatke o ruti ako je pronađena, u suprotnom null.
     */
    private function findRouteData($routes, $requestUri, $parentRoles = [])
    {
        // Ako postoji direktno podudaranje za traženi URI na trenutnom nivou
        if (isset($routes[$requestUri])) {
            $routeData = $routes[$requestUri];
            // Ako uloge nisu definisane, nasleđuju se uloge roditelja
            if (empty($routeData['roles'])) {
                $routeData['roles'] = $parentRoles;
            }
            return $routeData;
        }

        // Ako nema direktnog podudaranja, iteriramo kroz sve rute
        // i proveravamo postoji li podudaranje unutar pod-ruta (sub-rute)
        foreach ($routes as $route => $data) {
            if (isset($data['sub'])) {
                // Pozivamo ovu metodu rekurzivno za pretragu sub-ruta
                $result = $this->findRouteData($data['sub'], $requestUri, $data['roles'] ?? $parentRoles);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        // Ako ruta nije pronađena, vraćamo null
        return null;
    }

    /**
     * Glavni handler koji procesuira svaki dolazni HTTP zahtjev.
     *
     * Metoda vrši sledeće:
     * - Dobija potrebne servise iz kontejnera (autentifikacija, konfiguracija ruta, loger).
     * - Validira ispravnost konfiguracije ruta.
     * - Normalizuje URI zahteva (pretvara u mala slova, uklanja viška kosih crta).
     * - Loguje informacije o zahtevu za potrebe debagovanja.
     * - Proverava da li je zahtevana ruta javna i, ako jeste, dozvoljava pristup.
     * - Ako korisnik nije prijavljen, preusmerava ga na login stranicu.
     * - Ako korisnik ima ulogu Developer, dozvoljava mu pristup svim rutama.
     * - Pomoću rekurzivnog pretraživanja pronalazi podatke o ruti (uključujući i sub-rute).
     * - Ako ruta ne postoji, loguje grešku i prikazuje 404 stranicu.
     * - Ako ruta postoji, proverava da li korisnik ima potrebne uloge.
     * - U slučaju nedovoljnih privilegija, loguje pokušaj neautorizovanog pristupa
     *   i preusmerava korisnika na dashboard.
     *
     * @param Request $request Dolazni HTTP zahtjev.
     */
    public function handle(Request $request): void
    {
        // Dobijamo servise iz kontejnera
        $auth = Container::get('auth');
        $routesRoles = Container::get('routesRoles');
        $logger = Container::get('logger');

        // Validiramo da li je konfiguracija ruta ispravna
        if (!isset($routesRoles['routes']) || !is_array($routesRoles['routes'])) {
            $logger->error('Invalid routes configuration');
            throw new \RuntimeException('Invalid routes configuration');
        }

        // Normalizacija URI-ja: pretvaramo u mala slova i uklanjamo nepotrebne kose crte
        $requestUri = '/' . strtolower(trim($request->getUrl()->getPath(), '/'));

        // Logujemo detalje o zahtevu za debagovanje
        $logger->debug('Processing request', [
            'uri'         => $requestUri,
            'isLoggedIn'  => $auth->isLoggedIn(),
            'userRoles'   => $auth->isLoggedIn() ? $auth->getRoles() : []
        ]);

        // Proveravamo da li je zahtevana ruta javna (nije potrebna autentifikacija)
        $publicRoutes = $routesRoles['publicRoutes'] ?? [];
        if (in_array($requestUri, $publicRoutes)) {
            return;
        }

        // Ako korisnik nije prijavljen, preusmeravamo ga na login stranicu
        if (!$auth->isLoggedIn()) {
            SimpleRouter::response()->redirect('/login')->send();
            exit;
        }

        // Korisnici sa ulogom Developer imaju pristup svim rutama
        if ($auth->hasRole(\Delight\Auth\Role::DEVELOPER)) {
            return;
        }

        // Pretražujemo konfiguraciju ruta, uključujući sub-rute, za odgovarajuću rutu
        $routes = $routesRoles['routes'];
        $routeData = $this->findRouteData($routes, $requestUri);

        // Ako ruta nije pronađena, logujemo pokušaj pristupa nepostojećoj ruti i prikazujemo 404 stranicu
        if ($routeData === null) {
            $logger->warning('Non-existent route attempted', [
                'uri'  => $requestUri,
                'user' => $auth->isLoggedIn() ? $auth->getUsername() : 'anonymous'
            ]);

            require_once __DIR__ . '/../Views/Errors/404.php';
            exit;
        }

        // Ako ruta postoji, proveravamo da li su definisane potrebne uloge
        $requiredRoles = $routeData['roles'] ?? [];
        if (!empty($requiredRoles)) {
            $hasAccess = false;
            // Prolazimo kroz sve potrebne uloge i proveravamo da li korisnik ima barem jednu od njih
            foreach ($requiredRoles as $role) {
                if ($auth->hasRole($role)) {
                    $hasAccess = true;
                    break;
                }
            }

            // Ako korisnik nema potrebne privilegije, logujemo pokušaj neautorizovanog pristupa
            // i preusmeravamo ga na dashboard
            if (!$hasAccess) {
                $logger->warning('Unauthorized access attempt', [
                    'uri'           => $requestUri,
                    'user'          => $auth->getUsername(),
                    'requiredRoles' => $requiredRoles
                ]);
                SimpleRouter::response()->redirect('/dashboard')->send();
                exit;
            }
        }
    }
}

<?php
// index.php

// Omogući prikazivanje grešaka za debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Učitavanje autoloadera
require __DIR__ . '/vendor/autoload.php';

// Uključivanje helper funkcija
require __DIR__ . '/App/Config/helpers.php';

use Pecee\SimpleRouter\SimpleRouter;
use App\Config\Container;




// Učitavanje ruta
require __DIR__ . '/App/Routes/Public.php';
require __DIR__ . '/App/Routes/Protected.php';

// Postavljanje CSRF verifier-a iz kontejnera
SimpleRouter::csrfVerifier(Container::get('csrf'));

SimpleRouter::error(function (\Pecee\Http\Request $request, \Exception $exception) {
    $logger = \App\Config\Container::get('logger');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
    $url = $request->getUrl()->getPath();
    $time = date('Y-m-d H:i:s');

    if ($exception instanceof \Pecee\SimpleRouter\Exceptions\NotFoundHttpException) {
        $logger->warning('Unknown route accessed', [
            'ip' => $ip,
            'url' => $url,
            'time' => $time
        ]);
        http_response_code(404);
        echo 'Stranica nije pronađena.';
        exit;
    }

    if ($exception instanceof \Pecee\Http\Middleware\Exceptions\TokenMismatchException) {
        $logger->warning('CSRF token mismatch', [
            'ip' => $ip,
            'url' => $url,
            'time' => $time
        ]);
        http_response_code(403);
        echo 'CSRF token ne odgovara.';
        exit;
    }

    $logger->error('Unhandled exception', [
        'message' => $exception->getMessage(),
        'trace' => $exception->getTraceAsString(),
        'ip' => $ip,
        'url' => $url,
        'time' => $time
    ]);

    http_response_code(500);
    echo 'Došlo je do greške. Molimo pokušajte kasnije.';
    exit;
});

// Pokrećemo rutiranje
SimpleRouter::start();

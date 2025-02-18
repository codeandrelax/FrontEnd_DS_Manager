<?php

use Pecee\SimpleRouter\SimpleRouter;
use App\Config\Container;

// Učitavanje kontrolera iz DI kontejnera
$authController = Container::get('authController');
$auth = Container::get('auth');

//**************************/
// Ruta za prikazivanje login stranice
SimpleRouter::get('/login', function () use ($authController, $auth) {

    // Proverava da li je sesija pokrenuta; ako nije, započinje je
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Proverava da li je korisnik već prijavljen
    if ($auth->isLoggedIn()) {
        // Ako je korisnik prijavljen, preusmerava ga na dashboard
        SimpleRouter::response()->redirect('/dashboard')->send();
        exit;
    }

    // Ako korisnik NIJE prijavljen, prikazuje stranicu za login
    $authController->loginPage();
});

//**************************/
// Ruta za obradu login forme (POST metod)
SimpleRouter::post('/login', function () use ($authController) {
    $authController->processLogin();
});

//**************************/
// Ruta za prikazivanje stranice za registraciju (GET metod)
SimpleRouter::get('/register', function () use ($authController, $auth) {

    // Proverava da li je korisnik već prijavljen
    if ($auth->isLoggedIn()) {
        // Ako je korisnik prijavljen, preusmerava ga na dashboard
        SimpleRouter::response()->redirect('/dashboard')->send();
        exit;
    }

    // Ako korisnik NIJE prijavljen, prikazuje stranicu za registraciju
    $authController->registerPage();
});

//**************************/
// Ruta za obradu registracione forme (POST metod)
SimpleRouter::post('/register', function () use ($authController) {
    $authController->register();
});

//**************************/
// Ruta za root URL ("/")
// Ako korisnik poseti osnovni URL aplikacije, preusmerava ga na odgovarajuću stranicu
SimpleRouter::get('/', function () use ($auth) {

    // Proverava da li je korisnik prijavljen
    if ($auth->isLoggedIn()) {

        // Ako je korisnik prijavljen, preusmerava ga na dashboard
        SimpleRouter::response()->redirect('/dashboard')->send();
    } else {

        // Ako korisnik NIJE prijavljen, preusmerava ga na login stranicu
        SimpleRouter::response()->redirect('/login')->send();
    }
    exit;
});

/*
// Ruta za odjavu korisnika, koristimo za debug i testiranje aplikacije u javnom okruženju, ne koristiti u produkciji

SimpleRouter::get('/logout', function () use ($authController) {
    $authController->logout();
});

*/
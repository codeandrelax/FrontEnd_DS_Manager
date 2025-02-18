<?php
// App/Middleware/CsrfVerifier.php

namespace App\Middleware;

use Pecee\Http\Middleware\BaseCsrfVerifier;
use Pecee\Http\Request;

class CsrfVerifier extends BaseCsrfVerifier
{
    /**
     * CSRF validacija će biti ignorisana za sledeće URL-ove.
     */
    protected array $except = ['/api/*'];

    /**
     * Opcionalno: Promenite ključ tokena ako je potrebno.
     */
    // protected function getTokenKey(): string
    // {
    //     return 'csrf_token';
    // }

    /**
     * Opcionalno: Prilagodite validaciju ako je potrebno.
     */
    // protected function validate(Request $request): void
    // {
    //     // Implementirajte prilagođenu logiku validacije ako je potrebno
    // }
}

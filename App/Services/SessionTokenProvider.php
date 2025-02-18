<?php
// App/Services/SessionTokenProvider.php

namespace App\Services;

use Pecee\Http\Security\ITokenProvider;

class SessionTokenProvider implements ITokenProvider
{
    /**
     * Refreshuje token. Generiše novi CSRF token i čuva ga u sesiji ako ne postoji ili je prazan.
     */
    public function refresh(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Regeneriše CSRF token. Koristi se nakon uspešnog POST zahteva.
     */
    public function regenerate(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /**
     * Validira CSRF token.
     *
     * @param string $token
     * @return bool
     */
    public function validate($token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return 
            $token !== null && 
            isset($_SESSION['csrf_token']) && 
            hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Vraća trenutni CSRF token.
     *
     * @param string|null $defaultValue
     * @return string|null
     */
    public function getToken(?string $defaultValue = null): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
            $this->refresh();
        }

        return $_SESSION['csrf_token'];
    }
}

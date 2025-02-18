<?php
// App/Config/helpers.php

if (!function_exists('csrf_token')) {
    /**
     * Vraća trenutni CSRF token.
     *
     * @return string|null
     */
    function csrf_token(): ?string
    {
        return \App\Config\Container::get('csrf')->getTokenProvider()->getToken();
    }
}

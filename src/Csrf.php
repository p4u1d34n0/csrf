<?php

namespace Security;

class Csrf
{
    private static $sessionKey = 'csrf_tokens';

    /**
     * Initialize CSRF tokens on page load.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::$sessionKey])) {
            $_SESSION[self::$sessionKey] = [];
        }
    }

    /**
     * Generate a new CSRF token and store it.
     *
     * @return string
     */
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        // Store token in session
        $_SESSION[self::$sessionKey][$token] = true;
        return $token;
    }

    /**
     * Inject a CSRF hidden input into a form.
     */
    public static function input(): void
    {
        $token = self::generateToken();
        echo '<input type="hidden" name="csrf_token" value="'.$token.'">';
    }

    /**
     * Validate the submitted CSRF token. Ensure One-time use.
     *
     * @param string $submittedToken
     * @return bool
     */
    public static function validate(string $submittedToken): bool
    {
        if (isset($_SESSION[self::$sessionKey][$submittedToken])) {
            unset($_SESSION[self::$sessionKey][$submittedToken]);
            return true;
        }
        return false;
    }

    /**
     * Clear all stored CSRF tokens (optional).
     */
    public static function clearTokens(): void
    {
        $_SESSION[self::$sessionKey] = [];
    }

}

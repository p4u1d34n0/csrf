<?php

namespace Security;

class Csrf
{
    private static $tokens = [];
    private static $sessionKey = 'csrf_tokens';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Load existing tokens from session
        if (isset($_SESSION[$this->sessionKey])) {
            $this->tokens = $_SESSION[$this->sessionKey];
        }
    }

    /**
     * Generate a CSRF token for a specific form.
     *
     * @param string $formName
     * @return string
     */
    public static function generateToken(string $formName): string
    {
        $token = bin2hex(random_bytes(32));
        self::$tokens[$formName] = $token;
        self::storeTokens();
        return $token;
    }

    /**
     * Validate a CSRF token for a specific form.
     *
     * @param string $formName
     * @param string $submittedToken
     * @return bool
     */
    public static function validateToken(string $formName, string $submittedToken): bool
    {
        if (!isset(self::$tokens[$formName])) {
            // No token exists for this form
            return false;
        }

        if (hash_equals(self::$tokens[$formName], $submittedToken)) {
            // Invalidate token after validation
            unset(self::$tokens[$formName]);
            self::storeTokens();
            return true;
        }

        // Token mismatch
        return false;
    }

    /**
     * Remove expired or unused tokens.
     */
    public static function clearTokens(): void
    {
        self::$tokens = [];
        self::storeTokens();
    }

    /**
     * Store the tokens in the session.
     */
    private static function storeTokens(): void
    {
        $_SESSION[self::$sessionKey] = self::$tokens;
    }
}
<?php

namespace Security;

class Csrf
{
    private $tokens = [];
    private $sessionKey = 'csrf_tokens';

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
    public function generateToken(string $formName): string
    {
        $token = bin2hex(random_bytes(32));
        $this->tokens[$formName] = $token;
        $this->storeTokens();
        return $token;
    }

    /**
     * Validate a CSRF token for a specific form.
     *
     * @param string $formName
     * @param string $submittedToken
     * @return bool
     */
    public function validateToken(string $formName, string $submittedToken): bool
    {
        if (!isset($this->tokens[$formName])) {
            return false; // No token exists for this form
        }

        if (hash_equals($this->tokens[$formName], $submittedToken)) {
            unset($this->tokens[$formName]); // Invalidate token after validation
            $this->storeTokens();
            return true;
        }

        return false; // Token mismatch
    }

    /**
     * Remove expired or unused tokens.
     */
    public function clearTokens(): void
    {
        $this->tokens = [];
        $this->storeTokens();
    }

    /**
     * Store the tokens in the session.
     */
    private function storeTokens(): void
    {
        $_SESSION[$this->sessionKey] = $this->tokens;
    }
}
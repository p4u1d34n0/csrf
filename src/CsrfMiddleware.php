<?php

namespace Security;

class CsrfMiddleware
{
    /**
     * Check if the request is valid.
     * If invalid, halt execution.
     */
    public static function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $headers = getallheaders();
            $csrfToken = $_POST['csrf_token'] ?? ($headers['X-CSRF-Token'] ?? '');

            if (!Csrf::validate($csrfToken)) {
                // Halt execution if CSRF check fails
                http_response_code(403);
                die(json_encode(['error' => 'CSRF validation failed.']));
            }
        }
    }
}

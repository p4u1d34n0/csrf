# CSRF Protection Implementation

This guide will walk you through the implementation and usage of CSRF (Cross-Site Request Forgery) protection using PHP and JavaScript. The code contains two primary components: the `Csrf` class for generating and validating CSRF tokens.

---

## Overview

Cross-Site Request Forgery (CSRF) attacks exploit a user's authenticated session to perform unauthorized actions on a web application. CSRF protection typically involves generating a unique token for each form and validating that token when the form is submitted.

In this implementation:
- The `Csrf` class handles CSRF token creation, storage, and validation.

---

## PHP Backend Implementation

### Csrf Class

The `Csrf` class is responsible for generating CSRF tokens, storing them in the user's session, and validating incoming requests.

#### Key Methods:
- **`generateToken()`**: Creates a new CSRF token and stores it in the session.
- **`input()`**: Injects the CSRF token as a hidden form field.
- **`validate($submittedToken)`**: Validates the CSRF token submitted with the request.
- **`clearTokens()`**: Clears all CSRF tokens from the session (useful for logout or session reset).

---

### CsrfMiddleware Class

The `CsrfMiddleware` class is a middleware component that checks for the presence and validity of a CSRF token in the POST request.

#### Key Method:
- **`handle()`**: This method checks the request method and validates the CSRF token. If validation fails, it sends a `403 Forbidden` response and halts further execution.

To use this middleware, you would typically call `CsrfMiddleware::handle()` before processing any form data or sensitive POST actions.

Use this in your PHP API before handling any business logic.

---

### Injecting CSRF Token into Forms

You can inject a CSRF token as a hidden input field in your form like this:

```html
<form method="POST" action="/submit">
    <?php \Security\Csrf::input(); ?>
    <input type="text" name="username" />
    <input type="password" name="password" />
    <button type="submit">Submit</button>
</form>
```

### Validation Example
```php

// Include the CSRF class
use \Security\Csrf;

// Check if the form has been submitted and validate CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the CSRF token is present and validate it
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!Csrf::validate($submittedToken)) {
        // CSRF token validation failed
        echo "Invalid CSRF token. Request denied.";
        exit; // Optionally, you can send a 403 response here
    }

    // Token validated, proceed with business logic
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Process form data (e.g., authentication, database save, etc.)
    echo "Form submitted successfully! Username: $username";
}

```

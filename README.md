# CSRF Protection Class

This PHP class provides a simple way to generate and validate CSRF (Cross-Site Request Forgery) tokens for forms in your application. It helps protect against CSRF attacks by ensuring that form submissions originate from your site.

## Features
- Generate CSRF tokens for specific forms
- Validate CSRF tokens upon form submission
- Store tokens in session for security
- Invalidate tokens after validation
- Clear all stored tokens when needed

## Installation
Clone the repository or download the `Csrf.php` file and include it in your project.

```bash
composer require your/package-name # (if applicable)
```

## Usage
### 1. Initialize CSRF Protection
```php
require 'Csrf.php';
use Security\Csrf;

$csrf = new Csrf();
```

### 2. Generate a Token
```php
$token = $csrf->generateToken('my_form');
```
Use this token in your form:
```html
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    <button type="submit">Submit</button>
</form>
```

### 3. Validate a Token
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($csrf->validateToken('my_form', $_POST['csrf_token'])) {
        echo "Valid request!";
    } else {
        echo "Invalid CSRF token!";
    }
}
```

### 4. Clear Tokens (Optional)
```php
$csrf->clearTokens();
```

## Security Best Practices
- Ensure sessions are started before using CSRF tokens.
- Always validate CSRF tokens before processing form submissions.
- Regenerate tokens periodically to prevent reuse.
- Use HTTPS to prevent token leakage via MITM attacks.

## License
This project is licensed under the MIT License.

## Contributions
Contributions are welcome! Feel free to submit issues or pull requests.

## Author
Developed by Paul Dean

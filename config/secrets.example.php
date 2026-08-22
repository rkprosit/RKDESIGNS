<?php
/**
 * Template for sensitive configuration.
 *
 * 1. Copy this file to config/secrets.php (it is git-ignored).
 * 2. Fill in your real values.
 * 3. Upload config/secrets.php to the server manually.
 *
 * Generate a password hash with: php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"
 */

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'your_database_name',
        'user' => 'your_database_user',
        'pass' => 'your_database_password',
    ],
    'admin' => [
        'username'      => 'admin',
        // Prefer a bcrypt hash ($2y$...). A plain string also works but is not recommended.
        'password_hash' => '$2y$10$REPLACE_WITH_PASSWORD_HASH',
    ],
];

<?php
/**
 * WordPress Configuration with Environment Variables
 */

// Load environment variables from .env.local (preferred) or .env file
$env_file = file_exists(__DIR__ . '/.env.local') ? __DIR__ . '/.env.local' : __DIR__ . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Helper function to get environment variables
function get_env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        $value = isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }
    return $value;
}

// ** Database settings ** //
define('DB_NAME', get_env('DB_NAME', 'wordpress_db'));
define('DB_USER', get_env('DB_USER', 'wordpress'));
define('DB_PASSWORD', get_env('DB_PASSWORD', 'wordpress_password'));
define('DB_HOST', get_env('DB_HOST', 'db:3306'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// ** Table prefix ** //
$table_prefix = get_env('DB_PREFIX', 'wp_');

// ** Authentication keys and salts ** //
define('AUTH_KEY', get_env('AUTH_KEY', 'put your unique phrase here'));
define('SECURE_AUTH_KEY', get_env('SECURE_AUTH_KEY', 'put your unique phrase here'));
define('LOGGED_IN_KEY', get_env('LOGGED_IN_KEY', 'put your unique phrase here'));
define('NONCE_KEY', get_env('NONCE_KEY', 'put your unique phrase here'));
define('AUTH_SALT', get_env('AUTH_SALT', 'put your unique phrase here'));
define('SECURE_AUTH_SALT', get_env('SECURE_AUTH_SALT', 'put your unique phrase here'));
define('LOGGED_IN_SALT', get_env('LOGGED_IN_SALT', 'put your unique phrase here'));
define('NONCE_SALT', get_env('NONCE_SALT', 'put your unique phrase here'));

// ** WordPress URLs ** //
define('WP_HOME', get_env('WP_HOME', 'http://localhost:8000'));
define('WP_SITEURL', get_env('WP_SITEURL', 'http://localhost:8000'));

// ** Debug settings ** //
define('WP_DEBUG', get_env('WP_DEBUG', true));
define('WP_DEBUG_LOG', get_env('WP_DEBUG_LOG', true));
define('WP_DEBUG_DISPLAY', false);
ini_set('display_errors', 0);

// ** File system settings ** //
define('FS_METHOD', 'direct');

// ** Memory limit ** //
define('WP_MEMORY_LIMIT', get_env('WP_MEMORY_LIMIT', '256M'));

// ** Environment type ** //
define('WP_ENVIRONMENT_TYPE', get_env('WP_ENVIRONMENT', 'development'));

// ** REST API Authentication Configuration ** //
define('JWT_AUTH_SECRET_KEY', 'mcp-jwt-secret-' . AUTH_KEY);
define('JWT_AUTH_CORS_ENABLE', true);

// Enable Application Passwords for REST API (moved to plugin or theme)

// ** WordPress MCP Server Configuration ** //
define('WPMCP_JWT_SECRET_KEY', get_env('WPMCP_JWT_SECRET_KEY', 'default-secret-key-change-in-production'));
define('WPMCP_DEBUG', get_env('WPMCP_DEBUG', true));
define('WPMCP_TOKEN_EXPIRATION', get_env('WPMCP_TOKEN_EXPIRATION', 24));
define('WPMCP_ENABLE_CRUD', get_env('WPMCP_ENABLE_CRUD', true));
define('WPMCP_ALLOWED_ORIGINS', get_env('WPMCP_ALLOWED_ORIGINS', 'http://localhost:8000'));

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
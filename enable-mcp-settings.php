<?php
// Enable WordPress MCP plugin settings programmatically
require_once 'wp-config.php';

echo "Enabling WordPress MCP settings...\n";

// Enable main MCP functionality
update_option('wpmcp_enable_mcp', true);
echo "✓ Enabled MCP functionality\n";

// Enable all tool categories
$tools = [
    'wpmcp_enable_crud_tools' => true,
    'wpmcp_enable_post_tools' => true,
    'wpmcp_enable_user_tools' => true,
    'wpmcp_enable_media_tools' => true,
    'wpmcp_enable_admin_tools' => true,
    'wpmcp_enable_debug_tools' => true,
    'wpmcp_enable_pages_tools' => true,
    'wpmcp_enable_settings_tools' => true,
    'wpmcp_enable_custom_post_types_tools' => true,
    'wpmcp_enable_woo_products_tools' => true,
    'wpmcp_enable_woo_orders_tools' => true
];

foreach ($tools as $option => $value) {
    update_option($option, $value);
    echo "✓ Enabled $option\n";
}

// Ensure JWT secret exists
$jwt_secret = get_option('wpmcp_jwt_secret_key');
if (!$jwt_secret) {
    $jwt_secret = wp_generate_password(64, false);
    update_option('wpmcp_jwt_secret_key', $jwt_secret);
    echo "✓ Generated JWT secret key\n";
}

// Set token expiration
update_option('wpmcp_token_expiration', 24);
echo "✓ Set token expiration to 24 hours\n";

// Enable CORS
update_option('wpmcp_allowed_origins', 'http://localhost:8000,https://claude.ai');
echo "✓ Set allowed origins\n";

echo "\nWordPress MCP settings have been enabled!\n";
echo "You should now see tools in the MCP settings page.\n";
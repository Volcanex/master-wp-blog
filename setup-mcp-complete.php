<?php
// Complete WordPress MCP setup script
require_once 'wp-config.php';

echo "Setting up WordPress MCP configuration...\n";

// Activate the WordPress MCP plugin
$plugin_file = 'wordpress-mcp/wordpress-mcp.php';
if (!is_plugin_active($plugin_file)) {
    $result = activate_plugin($plugin_file);
    if (is_wp_error($result)) {
        echo "✗ Failed to activate WordPress MCP plugin: " . $result->get_error_message() . "\n";
    } else {
        echo "✓ Activated WordPress MCP plugin\n";
    }
} else {
    echo "✓ WordPress MCP plugin already active\n";
}

// Create or update mcpuser
$username = 'mcpuser';
$password = '1qp6lufizIWFiLKuv6aPPWMy';
$email = 'mcp@localhost.local';

$user = get_user_by('login', $username);
if (!$user) {
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        echo "✗ Failed to create user: " . $user_id->get_error_message() . "\n";
    } else {
        $user = new WP_User($user_id);
        $user->set_role('administrator');
        echo "✓ Created mcpuser with administrator role\n";
    }
} else {
    echo "✓ mcpuser already exists\n";
    // Ensure user is administrator
    if (!user_can($user->ID, 'manage_options')) {
        $user->set_role('administrator');
        echo "✓ Updated mcpuser to administrator role\n";
    }
    // Update password if needed
    wp_set_password($password, $user->ID);
    echo "✓ Updated mcpuser password\n";
}

// Create application password for the user
if ($user && !is_wp_error($user)) {
    // Remove existing application passwords
    WP_Application_Passwords::delete_all_application_passwords($user->ID);

    // Create new application password
    $app_password = WP_Application_Passwords::create_new_application_password($user->ID, array(
        'name' => 'MCP WordPress Remote'
    ));

    if (is_wp_error($app_password)) {
        echo "✗ Failed to create application password: " . $app_password->get_error_message() . "\n";
    } else {
        echo "✓ Created application password: " . $app_password[0] . "\n";
        echo "  Username: $username\n";
        echo "  Password: " . $app_password[0] . "\n";
    }
}

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

echo "\nWordPress MCP setup complete!\n";
echo "You should now be able to use @automattic/mcp-wordpress-remote.\n";
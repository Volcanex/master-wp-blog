<?php
// Test authentication and application passwords
require_once 'wp-config.php';

echo "Testing Application Passwords functionality...\n";

// Check if Application Passwords is available
if (class_exists('WP_Application_Passwords')) {
    echo "✓ WP_Application_Passwords class exists\n";

    // Check if Application Passwords are enabled
    if (WP_Application_Passwords::is_available()) {
        echo "✓ Application Passwords are available\n";
    } else {
        echo "✗ Application Passwords are not available\n";
        echo "Reasons: \n";
        $reasons = WP_Application_Passwords::get_available_reasons();
        foreach ($reasons as $reason) {
            echo "  - $reason\n";
        }
    }
} else {
    echo "✗ WP_Application_Passwords class not found\n";
}

// Check user and their app passwords
$user = get_user_by('login', 'mcpuser');
if ($user) {
    echo "✓ Found mcpuser (ID: {$user->ID})\n";
    echo "  Roles: " . implode(', ', $user->roles) . "\n";
    echo "  Capabilities: manage_options=" . ($user->has_cap('manage_options') ? 'yes' : 'no') . "\n";

    $app_passwords = WP_Application_Passwords::get_user_application_passwords($user->ID);
    echo "  Application passwords: " . count($app_passwords) . "\n";

    foreach ($app_passwords as $app_password) {
        echo "    - {$app_password['name']} (created: {$app_password['created']})\n";
    }
} else {
    echo "✗ mcpuser not found\n";
}

// Test basic auth header parsing
echo "\nTesting basic auth parsing...\n";
$test_auth = base64_encode('mcpuser:B8ksx48AAuB4AIcYQSytASJL');
echo "Test auth header: Basic $test_auth\n";

$parsed = WP_Application_Passwords::get_user_data_from_basic_auth(array('authorization' => "Basic $test_auth"));
if ($parsed) {
    echo "✓ Basic auth parsed successfully\n";
    echo "  User: {$parsed['user']->user_login}\n";
    echo "  Password UUID: {$parsed['password']['uuid']}\n";
} else {
    echo "✗ Basic auth parsing failed\n";
}
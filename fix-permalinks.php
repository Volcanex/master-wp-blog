<?php
// Fix permalinks for REST API
require_once 'wp-config.php';

echo "Fixing permalink structure...\n";

// Set permalink structure to post name
update_option('permalink_structure', '/%postname%/');
echo "✓ Set permalink structure to /%postname%/\n";

// Flush rewrite rules
flush_rewrite_rules(true);
echo "✓ Flushed rewrite rules\n";

// Test REST API root
$rest_url = get_rest_url();
echo "\nREST API URL: $rest_url\n";

echo "\nTrying to test REST API availability...\n";
$response = wp_remote_get($rest_url);
if (!is_wp_error($response)) {
    echo "✓ REST API is accessible\n";
} else {
    echo "✗ REST API error: " . $response->get_error_message() . "\n";
}

echo "\nMCP endpoints should be at:\n";
echo "  " . get_rest_url(null, 'wp/v2/wpmcp') . "\n";
echo "  " . get_rest_url(null, 'wp/v2/wpmcp/streamable') . "\n";

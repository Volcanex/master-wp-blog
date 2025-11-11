<?php
// Check MCP plugin status
require_once 'wp-config.php';

echo "Checking WordPress MCP status...\n\n";

// Check if plugin is active
$plugin_file = 'wordpress-mcp/wordpress-mcp.php';
$is_active = is_plugin_active($plugin_file);
echo "Plugin active: " . ($is_active ? "YES" : "NO") . "\n";

// Check MCP enabled option
$mcp_enabled = get_option('wpmcp_enable_mcp');
echo "MCP enabled: " . ($mcp_enabled ? "YES" : "NO") . "\n";

// Flush rewrite rules to register new endpoints
flush_rewrite_rules();
echo "Flushed rewrite rules\n";

// List active plugins
echo "\nActive plugins:\n";
$active_plugins = get_option('active_plugins');
foreach ($active_plugins as $plugin) {
    echo "  - $plugin\n";
}

echo "\nMCP endpoint should now be available at:\n";
echo "  http://localhost:8000/wp-json/wp/v2/wpmcp\n";
echo "  http://localhost:8000/wp-json/wp/v2/wpmcp/streamable\n";

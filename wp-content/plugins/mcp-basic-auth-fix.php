<?php
/**
 * Plugin Name: MCP Basic Auth Fix
 * Description: Adds Application Password (Basic Auth) support to WordPress MCP plugin
 * Version: 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Basic Authentication support to WordPress MCP
 */
function mcp_basic_auth_handler($result, $request) {
    // If already authenticated, return early
    if (!empty($result)) {
        return $result;
    }

    // If no request object, we can't authenticate
    if (null === $request) {
        return $result;
    }

    // Get the authorization header
    $auth_header = $request->get_header('authorization');
    if (empty($auth_header)) {
        return $result;
    }

    // Check if it's Basic authentication
    if (!preg_match('/^Basic\s+(.+)$/i', $auth_header, $matches)) {
        return $result;
    }

    // Decode the Basic auth credentials
    $credentials = base64_decode($matches[1]);
    if (!$credentials || !str_contains($credentials, ':')) {
        return $result;
    }

    list($username, $password) = explode(':', $credentials, 2);

    // Try to authenticate with Application Passwords
    $user = wp_authenticate_application_password(null, $username, $password);

    if (is_wp_error($user)) {
        return $result; // Authentication failed
    }

    if ($user && $user->exists()) {
        // Set the current user
        wp_set_current_user($user->ID);
        return true; // Authentication successful
    }

    return $result;
}

// Hook into the MCP authentication filter
add_filter('wpmcp_authenticate_request', 'mcp_basic_auth_handler', 10, 2);

// Also ensure Application Passwords are enabled
add_filter('wp_is_application_passwords_available', '__return_true');
add_filter('wp_is_application_passwords_available_for_user', '__return_true');
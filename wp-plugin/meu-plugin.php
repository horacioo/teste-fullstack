<?php
/**
 * Plugin Name: Meu Plugin (Cache de Produtos)
 * Description: Cacheia produtos de uma API externa e expõe páginas de admin e endpoints REST.
 * Version: 0.1.0
 * Author: Equipe
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

declare(strict_types=1);

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

// Constants
if (! defined('MEUPLUGIN_VERSION')) {
    define('MEUPLUGIN_VERSION', '0.1.0');
}

if (! defined('MEUPLUGIN_CACHE_TTL')) {
    // 10 minutos (ajustável via constante)
    define('MEUPLUGIN_CACHE_TTL', 600);
}

if (! defined('MEUPLUGIN_NS')) {
    define('MEUPLUGIN_NS', 'meuplugin/v1');
}

// Simple PSR-4 like autoloader for this plugin namespace
spl_autoload_register(static function (string $class): void {
    $prefix = 'MeuPlugin\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Register REST routes
add_action('rest_api_init', static function (): void {
    \MeuPlugin\Rest\Routes::register();
});

// Admin menu/pages (only in admin)
if (is_admin()) {
    add_action('admin_menu', static function (): void {
        \MeuPlugin\Admin\Menu::register();
    });
}

// Minimal permissive CORS for this plugin's GET endpoints only
add_filter('rest_pre_serve_request', static function ($served, $result, $request, $server) {
    // Only apply to our namespace and GET requests
    if ($request instanceof WP_REST_Request) {
        $route = $request->get_route();
        $method = $request->get_method();
        if (is_string($route) && str_starts_with($route, '/' . MEUPLUGIN_NS) && strtoupper($method) === 'GET') {
            // Allow all origins for GET only; front-end should handle CORS appropriately
            header('Access-Control-Allow-Origin: *');
            header('Vary: Origin');
        }
    }
    return $served;
}, 10, 4);

// WP-CLI command
if (defined('WP_CLI') && WP_CLI) {
    \MeuPlugin\Cli\CacheCommand::register();
}



<?php
/**
 * Theme Functions
 *
 * WordPress Theme
 * Built with Roots Sage 10, Tailwind CSS, and Laravel Mix
 */

namespace ShaganPlaatjies;

/**
 * Theme Bootstrap
 *
 * Load all theme functionality and helpers
 */
function bootstrap() {
    // Load theme configuration
    require_once __DIR__ . '/app/setup.php';
    require_once __DIR__ . '/app/acf-helpers.php';
    require_once __DIR__ . '/app/blocks.php';
}

/**
 * Initialize Composer Autoloader
 */
if (file_exists($autoload = __DIR__ . '/vendor/autoload.php')) {
    require_once $autoload;
}

/**
 * Bootstrap the theme
 */
bootstrap();

/**
 * Get theme configuration
 */
function get_theme_config($key) {
    $defaults = [
        'key' => 'shaganplaatjies',
        'name' => 'Your Theme Name',
    ];

    $config = [
        'key' => env('THEME_KEY', $defaults['key']),
        'name' => env('THEME_NAME', $defaults['name']),
    ];

    return $config[$key] ?? $defaults[$key];
}

/**
 * Enqueue CSS and JavaScript
 */
function enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    $cache_bust = defined('WP_DEBUG') && WP_DEBUG ? time() : $theme_version;
    $theme_key = get_theme_config('key');

    // Enqueue main stylesheet (compiled from Tailwind)
    wp_enqueue_style(
        $theme_key . '-main',
        get_template_directory_uri() . '/dist/css/app.css',
        [],
        $cache_bust
    );

    // Enqueue main script (compiled from webpack)
    wp_enqueue_script(
        $theme_key . '-main',
        get_template_directory_uri() . '/dist/js/app.js',
        [],
        $cache_bust,
        true
    );

    // Pass PHP data to JavaScript
    wp_localize_script(
        $theme_key . '-main',
        str_replace('-', '_', $theme_key),
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce($theme_key . '-nonce'),
        ]
    );
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets');

/**
 * Enqueue block editor styles
 */
function enqueue_editor_assets() {
    $theme_version = wp_get_theme()->get('Version');
    $cache_bust = defined('WP_DEBUG') && WP_DEBUG ? time() : $theme_version;
    $theme_key = get_theme_config('key');

    wp_enqueue_style(
        $theme_key . '-editor',
        get_template_directory_uri() . '/dist/css/editor.css',
        ['wp-edit-blocks'],
        $cache_bust
    );
}
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_assets');

/**
 * Theme Initialization Hook
 *
 * Allows child themes and plugins to hook into theme initialization
 */
do_action(get_theme_config('key') . '_init');

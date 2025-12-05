<?php
/**
 * Theme Functions
 *
 * Shagan Plaatjies WordPress Theme
 * Built with Roots Sage 10, Tailwind CSS, and Laravel Mix
 */

namespace Shaganplaatjies;

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
 * Enqueue CSS and JavaScript
 */
function enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    $cache_bust = defined('WP_DEBUG') && WP_DEBUG ? time() : $theme_version;

    // Enqueue main stylesheet (compiled from Tailwind)
    wp_enqueue_style(
        'shaganplaatjies-main',
        get_template_directory_uri() . '/dist/css/app.css',
        [],
        $cache_bust
    );

    // Enqueue main script (compiled from webpack)
    wp_enqueue_script(
        'shaganplaatjies-main',
        get_template_directory_uri() . '/dist/js/app.js',
        [],
        $cache_bust,
        true
    );

    // Pass PHP data to JavaScript
    wp_localize_script(
        'shaganplaatjies-main',
        'shaganplaatjies',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shaganplaatjies-nonce'),
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

    wp_enqueue_style(
        'shaganplaatjies-editor',
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
do_action('shaganplaatjies_init');

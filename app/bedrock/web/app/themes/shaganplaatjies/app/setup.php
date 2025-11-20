<?php
/**
 * Theme Setup
 *
 * Configuration for theme features and WordPress integration
 */

namespace Shaganplaatjies;

/**
 * Register Theme Support
 *
 * Add support for core WordPress features and custom theme features
 */
function setup_theme_support() {
    // Enable support for post thumbnails (featured images)
    add_theme_support('post-thumbnails');

    // Enable support for title tag
    add_theme_support('title-tag');

    // Enable support for custom logo
    add_theme_support('custom-logo', [
        'height' => 200,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    // Enable support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Enable HTML5 semantic markup for forms, search, comment forms, and galleries
    add_theme_support('html5', [
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Enable support for block styles (Gutenberg)
    add_theme_support('wp-block-styles');

    // Enable support for wide alignment in blocks
    add_theme_support('align-wide');

    // Enable support for custom spacing in blocks
    add_theme_support('spacing');

    // Load theme text domain for translations
    load_theme_textdomain('shaganplaatjies', get_template_directory() . '/resources/languages');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup_theme_support');

/**
 * Register Menus
 */
function register_menus() {
    register_nav_menus([
        'primary_navigation' => esc_html__('Primary Navigation', 'shaganplaatjies'),
        'footer_navigation' => esc_html__('Footer Navigation', 'shaganplaatjies'),
    ]);
}
add_action('init', __NAMESPACE__ . '\\register_menus');

/**
 * Register Custom Post Types
 *
 * Add custom post types as needed for your site
 */
function register_custom_post_types() {
    // Example: Portfolio/Projects post type
    // Uncomment and customize as needed

    // register_post_type('project', [
    //     'labels' => [
    //         'name' => esc_html__('Projects', 'shaganplaatjies'),
    //         'singular_name' => esc_html__('Project', 'shaganplaatjies'),
    //     ],
    //     'public' => true,
    //     'has_archive' => true,
    //     'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
    //     'menu_icon' => 'dashicons-briefcase',
    // ]);
}
add_action('init', __NAMESPACE__ . '\\register_custom_post_types');

/**
 * Register Custom Taxonomies
 */
function register_custom_taxonomies() {
    // Example: Project categories
    // Uncomment and customize as needed

    // register_taxonomy('project_category', 'project', [
    //     'labels' => [
    //         'name' => esc_html__('Project Categories', 'shaganplaatjies'),
    //         'singular_name' => esc_html__('Project Category', 'shaganplaatjies'),
    //     ],
    //     'hierarchical' => true,
    //     'publicly_queryable' => true,
    //     'show_in_rest' => true,
    // ]);
}
add_action('init', __NAMESPACE__ . '\\register_custom_taxonomies');

/**
 * Theme Constants
 *
 * Define useful constants for use throughout the theme
 */
define('SHAGANPLAATJIES_THEME_PATH', get_template_directory());
define('SHAGANPLAATJIES_THEME_URL', get_template_directory_uri());
define('SHAGANPLAATJIES_DIST_PATH', SHAGANPLAATJIES_THEME_PATH . '/dist');
define('SHAGANPLAATJIES_DIST_URL', SHAGANPLAATJIES_THEME_URL . '/dist');

/**
 * Disable WordPress Block Library CSS
 *
 * If you're managing all block styles via Tailwind, uncomment this to remove
 * the WordPress default block styles and reduce CSS bloat
 */
// function disable_wp_block_library_css() {
//     wp_dequeue_style('wp-block-library');
//     wp_dequeue_style('wp-block-library-theme');
//     wp_dequeue_style('wc-block-style');
// }
// add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\disable_wp_block_library_css');

/**
 * Custom Image Sizes
 */
function add_custom_image_sizes() {
    // Hero section image
    add_image_size('hero', 1920, 600, true);

    // Featured image
    add_image_size('featured', 600, 400, true);

    // Thumbnail
    add_image_size('thumbnail-custom', 300, 300, true);
}
add_action('after_setup_theme', __NAMESPACE__ . '\\add_custom_image_sizes');

/**
 * Modify Excerpt Length
 */
function custom_excerpt_length($length) {
    return 20; // Customize as needed
}
// Uncomment to enable custom excerpt length
// add_filter('excerpt_length', __NAMESPACE__ . '\\custom_excerpt_length');

/**
 * Allow SVG Upload
 *
 * Add SVG MIME type to allowed file uploads
 */
function allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', __NAMESPACE__ . '\\allow_svg_upload');

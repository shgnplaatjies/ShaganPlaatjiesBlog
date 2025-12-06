<?php
/**
 * Theme Setup
 *
 * Configuration for theme features and WordPress integration
 */

namespace ShaganPlaatjies;

function setup_theme_support() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo', [
        'height' => 200,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('html5', [
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('spacing');
    load_theme_textdomain('shaganplaatjies', get_template_directory() . '/resources/languages');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup_theme_support');

function register_menus() {
    register_nav_menus([
        'primary_navigation' => esc_html__('Primary Navigation', 'shaganplaatjies'),
        'footer_navigation' => esc_html__('Footer Navigation', 'shaganplaatjies'),
    ]);
}
add_action('init', __NAMESPACE__ . '\\register_menus');

function register_custom_post_types() {
}
add_action('init', __NAMESPACE__ . '\\register_custom_post_types');

function register_custom_taxonomies() {
}
add_action('init', __NAMESPACE__ . '\\register_custom_taxonomies');

define('SHAGANPLAATJIES_THEME_PATH', get_template_directory());
define('SHAGANPLAATJIES_THEME_URL', get_template_directory_uri());
define('SHAGANPLAATJIES_DIST_PATH', SHAGANPLAATJIES_THEME_PATH . '/dist');
define('SHAGANPLAATJIES_DIST_URL', SHAGANPLAATJIES_THEME_URL . '/dist');

function add_custom_image_sizes() {
    add_image_size('hero', 1920, 600, true);
    add_image_size('featured', 600, 400, true);
    add_image_size('thumbnail-custom', 300, 300, true);
}
add_action('after_setup_theme', __NAMESPACE__ . '\\add_custom_image_sizes');

function custom_excerpt_length($length) {
    return 20;
}

function allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', __NAMESPACE__ . '\\allow_svg_upload');

function register_theme_customizer($wp_customize) {
    $wp_customize->add_section('iframe_settings', [
        'title' => esc_html__('Client App Settings', 'shaganplaatjies'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('client_app_url', [
        'default' => 'https://localhost:3000',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control('client_app_url', [
        'label' => esc_html__('Client App URL', 'shaganplaatjies'),
        'description' => esc_html__('URL of your React/Next.js app', 'shaganplaatjies'),
        'section' => 'iframe_settings',
        'type' => 'url',
    ]);
}
add_action('customize_register', __NAMESPACE__ . '\\register_theme_customizer');

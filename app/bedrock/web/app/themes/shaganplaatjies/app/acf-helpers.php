<?php
/**
 * ACF Helper Functions
 *
 * Utility functions for working with Advanced Custom Fields
 */

namespace ShaganPlaatjies;

/**
 * Check if ACF is Active
 *
 * @return bool True if ACF is installed and active
 */
function is_acf_active() {
    return function_exists('get_field');
}

/**
 * Get Field with Fallback
 *
 * @param string $field_name The ACF field name
 * @param mixed $post_id The post ID (defaults to current post)
 * @param mixed $default Default value if field doesn't exist
 *
 * @return mixed The field value or default
 */
function get_field_safe($field_name, $post_id = false, $default = null) {
    if (!is_acf_active()) {
        return $default;
    }

    $value = get_field($field_name, $post_id);
    return $value !== false ? $value : $default;
}

/**
 * Get Theme Option
 *
 * Get a value from theme options stored in ACF
 *
 * @param string $option_name The option field name
 * @param mixed $default Default value
 *
 * @return mixed The option value or default
 */
function get_theme_option($option_name, $default = null) {
    if (!is_acf_active()) {
        return $default;
    }

    $value = get_field($option_name, 'option');
    return $value !== false ? $value : $default;
}

/**
 * Get Repeater Field
 *
 * Safely retrieve a repeater field
 *
 * @param string $field_name The repeater field name
 * @param mixed $post_id The post ID
 *
 * @return array Array of repeater rows, empty array if none found
 */
function get_repeater_field($field_name, $post_id = false) {
    if (!is_acf_active()) {
        return [];
    }

    $rows = get_field($field_name, $post_id);
    return is_array($rows) ? $rows : [];
}

/**
 * Has Flexible Content
 *
 * Check if a flexible content field has any layouts
 *
 * @param string $field_name The flexible content field name
 * @param mixed $post_id The post ID
 *
 * @return bool True if field has content
 */
function has_flexible_content($field_name, $post_id = false) {
    if (!is_acf_active()) {
        return false;
    }

    $layouts = get_field($field_name, $post_id);
    return is_array($layouts) && count($layouts) > 0;
}

/**
 * Get Color from Theme Options
 *
 * Retrieve a color value from theme ACF options
 *
 * @param string $color_key The color option key (e.g., 'primary_color')
 * @param string $default Default color hex value
 *
 * @return string The color value
 */
function get_theme_color($color_key, $default = '#000000') {
    return get_theme_option($color_key, $default);
}

/**
 * Get Social Media Links
 *
 * Retrieve social media profile links from theme options
 *
 * @return array Array of social media links
 */
function get_social_media_links() {
    if (!is_acf_active()) {
        return [];
    }

    $social_links = get_field('social_media_links', 'option');
    return is_array($social_links) ? $social_links : [];
}

/**
 * Echo Field Value
 *
 * Output a field value safely with filtering
 *
 * @param string $field_name The field name
 * @param mixed $post_id The post ID
 * @param string $filter Optional filter to apply
 */
function the_field_filtered($field_name, $post_id = false, $filter = 'wp_kses_post') {
    $value = get_field_safe($field_name, $post_id);

    if ($value && function_exists($filter)) {
        echo call_user_func($filter, $value);
    } else {
        echo esc_html($value);
    }
}

/**
 * Register ACF Options Pages
 *
 * Create ACF options pages for theme-wide settings
 */
function register_acf_options_pages() {
    if (!is_acf_active() || !function_exists('acf_add_options_page')) {
        return;
    }

    // Theme Settings page
    \acf_add_options_page([
        'page_title' => esc_html__('Theme Settings', 'shaganplaatjies'),
        'menu_title' => esc_html__('Theme Settings', 'shaganplaatjies'),
        'menu_slug' => 'theme-settings',
        'capability' => 'edit_theme_options',
        'redirect' => false,
        'icon_url' => 'dashicons-admin-generic',
    ]);

    // Styling Options page
    \acf_add_options_sub_page([
        'page_title' => esc_html__('Styling Options', 'shaganplaatjies'),
        'menu_title' => esc_html__('Styling', 'shaganplaatjies'),
        'parent_slug' => 'theme-settings',
    ]);

    // Social Media page
    acf_add_options_sub_page([
        'page_title' => esc_html__('Social Media', 'shaganplaatjies'),
        'menu_title' => esc_html__('Social Media', 'shaganplaatjies'),
        'parent_slug' => 'theme-settings',
    ]);
}
add_action('acf/init', __NAMESPACE__ . '\\register_acf_options_pages');

/**
 * Export ACF Field Groups to JSON
 *
 * Helper function to enable ACF JSON sync
 * This allows field group definitions to be version controlled
 */
function setup_acf_json_sync() {
    if (!is_acf_active()) {
        return;
    }

    // Save ACF field groups as JSON
    add_filter('acf/settings/save_json', function ($path) {
        return SHAGANPLAATJIES_THEME_PATH . '/acf-config';
    });

    // Load ACF field groups from JSON
    add_filter('acf/settings/load_json', function ($paths) {
        $paths[] = SHAGANPLAATJIES_THEME_PATH . '/acf-config';
        return $paths;
    });
}
add_action('acf/init', __NAMESPACE__ . '\\setup_acf_json_sync');

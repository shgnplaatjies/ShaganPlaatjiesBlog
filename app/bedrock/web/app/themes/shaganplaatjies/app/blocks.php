<?php
/**
 * Custom Block Registration
 *
 * Register custom block types and configure block editor settings
 */

namespace Shaganplaatjies\Blocks;

/**
 * Register Custom Block Types
 *
 * Each block should have:
 * - name: Unique block identifier (namespace/name format)
 * - title: Display name in block editor
 * - description: Help text
 * - icon: Dashicon or SVG
 * - category: Block category
 * - supports: Block editor features
 * - render_callback: Function to render block output
 * - attributes: Block data schema
 */
function register_custom_blocks() {
    // Example: Hero Block
    // register_block_type(
    //     'shaganplaatjies/hero',
    //     [
    //         'render_callback' => __NAMESPACE__ . '\\render_hero_block',
    //         'attributes' => [
    //             'backgroundColor' => [
    //                 'type' => 'string',
    //                 'default' => '#ffffff',
    //             ],
    //             'title' => [
    //                 'type' => 'string',
    //                 'default' => '',
    //             ],
    //             'subtitle' => [
    //                 'type' => 'string',
    //                 'default' => '',
    //             ],
    //         ],
    //     ]
    // );

    // Example: Features Grid Block
    // register_block_type(
    //     'shaganplaatjies/features-grid',
    //     [
    //         'render_callback' => __NAMESPACE__ . '\\render_features_grid_block',
    //     ]
    // );

    // Example: Testimonials Block
    // register_block_type(
    //     'shaganplaatjies/testimonials',
    //     [
    //         'render_callback' => __NAMESPACE__ . '\\render_testimonials_block',
    //     ]
    // );
}
add_action('init', __NAMESPACE__ . '\\register_custom_blocks');

/**
 * Register Block Categories
 *
 * Create custom block categories for organized block selection
 */
function register_block_categories($categories, $post) {
    $categories[] = [
        'slug' => 'shaganplaatjies',
        'title' => esc_html__('Shagan Plaatjies', 'shaganplaatjies'),
        'icon' => 'wordpress',
    ];

    return $categories;
}
add_filter('block_categories_all', __NAMESPACE__ . '\\register_block_categories', 10, 2);

/**
 * Render Hero Block
 *
 * Example render callback for a hero block
 *
 * @param array $attributes Block attributes
 * @param string $content Block inner HTML
 *
 * @return string Block HTML output
 */
// function render_hero_block($attributes, $content) {
//     $background_color = isset($attributes['backgroundColor'])
//         ? sanitize_hex_color($attributes['backgroundColor'])
//         : '#ffffff';
//
//     $title = isset($attributes['title'])
//         ? sanitize_text_field($attributes['title'])
//         : '';
//
//     $subtitle = isset($attributes['subtitle'])
//         ? sanitize_text_field($attributes['subtitle'])
//         : '';
//
//     return sprintf(
//         '<section class="hero" style="background-color: %s;">
//             <div class="container">
//                 <h1>%s</h1>
//                 <p>%s</p>
//             </div>
//         </section>',
//         esc_attr($background_color),
//         esc_html($title),
//         esc_html($subtitle)
//     );
// }

/**
 * Register Block Editor Scripts
 *
 * Load custom block registration scripts for the block editor
 */
function register_block_editor_assets() {
    // Register editor JavaScript
    // wp_register_script(
    //     'shaganplaatjies-blocks',
    //     SHAGANPLAATJIES_DIST_URL . '/js/blocks.js',
    //     ['wp-blocks', 'wp-element', 'wp-editor'],
    //     filemtime(SHAGANPLAATJIES_DIST_PATH . '/js/blocks.js'),
    //     true
    // );
    //
    // // Enqueue in editor
    // wp_enqueue_script('shaganplaatjies-blocks');
}
// add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\register_block_editor_assets');

/**
 * Allowed Block Types
 *
 * Restrict which blocks are available in the editor (optional)
 *
 * @param array|bool $allowed_blocks Current allowed blocks
 * @param object $post The post object
 *
 * @return array|bool Filtered allowed blocks
 */
// function restrict_block_types($allowed_blocks, $post) {
//     return [
//         'core/paragraph',
//         'core/heading',
//         'core/image',
//         'core/gallery',
//         'core/buttons',
//         'core/quote',
//         'core/list',
//         'shaganplaatjies/hero',
//         'shaganplaatjies/features-grid',
//     ];
// }
// add_filter('allowed_block_types_all', __NAMESPACE__ . '\\restrict_block_types', 10, 2);

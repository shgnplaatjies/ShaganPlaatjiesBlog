<?php
/**
 * Theme Index Template
 *
 * The main template file. WordPress will use this template when no more specific
 * template file qualifies. For example, if the query is for a single post, WordPress
 * will first look for single.php. If it doesn't exist, it will use index.php.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/template-hierarchy/
 */

// Get the main template content based on context
if (is_front_page()) {
    get_template_part('templates/pages/front-page');
} elseif (is_home()) {
    get_template_part('templates/pages/home');
} elseif (is_single()) {
    get_template_part('templates/pages/single');
} elseif (is_archive()) {
    get_template_part('templates/pages/archive');
} elseif (is_search()) {
    get_template_part('templates/pages/search');
} elseif (is_404()) {
    get_template_part('templates/pages/404');
} else {
    // Fallback to page template
    get_template_part('templates/page');
}

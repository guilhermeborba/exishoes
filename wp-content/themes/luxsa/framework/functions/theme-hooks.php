<?php
/**
 * This file includes helper functions used throughout the theme.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter( 'body_class', 'luxsa_body_classes' );

/**
 * Head
 */
add_action('wp_head', 'luxsa_add_meta_into_head_tag', 100 );
add_action('luxsa/action/head', 'luxsa_add_extra_data_into_head');

add_action('luxsa/action/before_outer_wrap', 'luxsa_add_pageloader_icon', 1);

/**
 * Header
 */
add_action( 'luxsa/action/header', 'luxsa_render_header', 10 );


/**
 * Page Header
 */
add_action( 'luxsa/action/page_header', 'luxsa_render_page_header', 10 );


/**
 * Sidebar
 */


$site_layout = luxsa_get_site_layout();

if($site_layout == 'col-2cr' || $site_layout == 'col-2cr-l'){
    add_action( 'luxsa/action/after_primary', 'luxsa_render_sidebar', 10 );
}
else{
    add_action( 'luxsa/action/before_primary', 'luxsa_render_sidebar', 10 );
}


/**
 * Footer
 */
add_action( 'luxsa/action/footer', 'luxsa_render_footer', 10 );

add_action( 'luxsa/action/after_outer_wrap', 'luxsa_render_footer_searchform_overlay', 10 );
add_action( 'luxsa/action/after_outer_wrap', 'luxsa_render_footer_cartwidget_overlay', 15 );
add_action( 'luxsa/action/after_outer_wrap', 'luxsa_render_footer_newsletter_popup', 20 );
add_action( 'luxsa/action/after_outer_wrap', 'luxsa_render_footer_handheld', 25 );
add_action( 'wp_footer', 'luxsa_render_footer_custom_js', 100 );


add_action( 'luxsa/action/after_page_entry', 'luxsa_render_comment_for_page', 0);

/**
 * Related Posts
 */
add_action( 'luxsa/action/after_main', 'luxsa_render_related_posts' );

/**
 * FILTERS
 */
add_filter('luxsa/filter/get_theme_option_by_context', 'luxsa_override_page_title_bar_from_context', 10, 2);
add_filter('previous_post_link', 'luxsa_override_post_navigation_template', 10, 5);
add_filter('next_post_link', 'luxsa_override_post_navigation_template', 10, 5);

add_filter('luxsa/filter/sidebar_primary_name', 'luxsa_override_sidebar_name_from_context');
add_filter('wp_get_attachment_image_attributes', 'luxsa_add_lazyload_to_image_tag');
add_filter('excerpt_length', 'luxsa_change_excerpt_length');

add_filter('luxsa/filter/show_page_title', 'luxsa_filter_page_title', 10, 1);
add_filter('luxsa/filter/show_breadcrumbs', 'luxsa_filter_show_breadcrumbs', 10, 1);

add_filter('register_taxonomy_args', 'luxsa_override_portfolio_tax_type_args', 99, 2);
add_filter('register_post_type_args', 'luxsa_override_portfolio_content_type_args', 99, 2);

add_filter( 'pre_get_posts', 'luxsa_setup_post_per_page_for_portfolio');


add_action('wp_head', 'luxsa_render_custom_block');
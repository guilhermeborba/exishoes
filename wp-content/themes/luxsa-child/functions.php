<?php

/**
 * Child Theme Function
 *
 */

add_action( 'after_setup_theme', 'luxsa_child_theme_setup' );
add_action( 'wp_enqueue_scripts', 'luxsa_child_enqueue_styles', 100);

if( !function_exists('luxsa_child_enqueue_styles') ) {
    function luxsa_child_enqueue_styles() {
        $version = wp_get_theme()->get('Version');
        wp_enqueue_style( 'luxsa-child-style', get_stylesheet_directory_uri() . '/style.css', null, $version );
    }
}


if( !function_exists('luxsa_child_theme_setup') ) {
    function luxsa_child_theme_setup() {
        load_child_theme_textdomain( 'luxsa-child', get_stylesheet_directory() . '/languages' );
    }
}


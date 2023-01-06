<?php
/**
 * The Header for our theme.
 *
 * @package Luxsa WordPress theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
error_reporting(0);
?><!DOCTYPE html>
<html <?php language_attributes(); ?><?php luxsa_schema_markup( 'html' ); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="facebook-domain-verification" content="yi6vacjc5zg0zj4awcmca8jij99rkc" />
    <link rel="profile" href="//gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if(function_exists('wp_body_open')) { wp_body_open(); } ?>

<?php do_action('luxsa/action/before_outer_wrap'); ?>

<div id="outer-wrap" class="site">

    <?php do_action('luxsa/action/before_wrap'); ?>

    <div id="wrap">
        <?php

            do_action('luxsa/action/before_header');

            if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
                do_action('luxsa/action/header');
            }

            do_action('luxsa/action/after_header');

        ?>

        <?php do_action('luxsa/action/before_main'); ?>

        <main id="main" class="site-main"<?php luxsa_schema_markup('main') ?>>
            <?php

                do_action('luxsa/action/before_page_header');

                do_action('luxsa/action/page_header');

                do_action('luxsa/action/after_page_header');
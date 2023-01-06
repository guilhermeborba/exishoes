<?php
/**
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Luxsa WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header(); ?>

<?php do_action( 'luxsa/action/before_content_wrap' ); ?>

    <div id="content-wrap" class="container">

        <?php do_action( 'luxsa/action/before_primary' ); ?>

        <div id="primary" class="content-area">

            <?php do_action( 'luxsa/action/before_content' ); ?>

            <div id="content" class="site-content">

                <?php do_action( 'luxsa/action/before_content_inner' ); ?>

                <?php

                    // Elementor `single` location
                    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

                        // Loop through posts
                        while ( have_posts() ) : the_post();

                            get_template_part( 'partials/page/layout' );

                        endwhile;

                        wp_reset_postdata();

                    } ?>

                <?php do_action( 'luxsa/action/after_content_inner' ); ?>

            </div><!-- #content -->

            <?php do_action( 'luxsa/action/after_content' ); ?>

        </div><!-- #primary -->

        <?php do_action( 'luxsa/action/after_primary' ); ?>

    </div><!-- #content-wrap -->

<?php do_action( 'luxsa/action/after_content_wrap' ); ?>

<?php get_footer();?>
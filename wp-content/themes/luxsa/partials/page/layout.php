<?php
/**
 * Outputs correct page layout
 *
 * @package Luxsa WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<article class="single-content-article single-page-article">

    <?php
    // Get page entry
    get_template_part( 'partials/page/article' );

    ?>

</article>
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$vendor      = dokan()->vendor->get( get_query_var( 'author' ) );
$enable_header_banner = dokan_get_option('enable_theme_store_header_tpl', 'dokan_general');

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

if($enable_header_banner != 'on'){
    echo '<div class="dokan-single-store">';
    dokan_get_template_part( 'store-header' );
    echo '</div>';
}

?>
    <div id="store-toc-wrapper">
        <div id="store-toc">
            <?php
            if( ! empty( $vendor->get_store_tnc() ) ):
                ?>
                <h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'luxsa' ); ?></h2>
                <div>
                    <?php
                    echo wp_kses_post( nl2br( $vendor->get_store_tnc() ) );
                    ?>
                </div>
            <?php
            endif;
            ?>
        </div><!-- #store-toc -->
    </div><!-- #store-toc-wrap -->
<?php

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
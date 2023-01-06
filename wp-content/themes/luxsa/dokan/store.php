<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$enable_header_banner = dokan_get_option('enable_theme_store_header_tpl', 'dokan_general');

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

$catalog_class = array('catalog-grid-1 products grid-items');
$catalog_class[] = luxsa_get_responsive_column_classes('woocommerce_catalog_columns');

if($enable_header_banner != 'on'){
    echo '<div class="dokan-single-store">';
    dokan_get_template_part( 'store-header' );
    echo '</div>';
}

if ( have_posts() ) {

    do_action( 'woocommerce_before_shop_loop' );

    ?>
    <div id="la_shop_products" class="la-shop-products woocommerce">
        <div class="la-ajax-shop-loading"><div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>
        <?php

        wc_set_loop_prop( 'is_main_loop', true );

        woocommerce_product_loop_start();

        while ( have_posts() ) {
            the_post();

            do_action( 'woocommerce_shop_loop' );

            wc_get_template_part( 'content', 'product' );
        }

        woocommerce_product_loop_end();

        do_action( 'woocommerce_after_shop_loop' );


        ?>
    </div>
    <?php
}
else {

    ?>
    <div class="wc-toolbar-container"><div class="hidden wc-toolbar wc-toolbar-top clearfix"></div></div>
    <div id="la_shop_products" class="la-shop-products no-products-found woocommerce">
        <div class="la-ajax-shop-loading"><div class="la-ajax-loading-outer"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div></div></div>
        <?php do_action( 'woocommerce_no_products_found' ); ?>
    </div>
    <?php
}

do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
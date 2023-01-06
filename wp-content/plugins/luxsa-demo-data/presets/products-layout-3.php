<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_products_layout_3()
{
    return [
        [
            'key' => 'woocommerce_product_page_design',
            'value' => '4'
        ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Products Layout 3';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
        ],

    ];
}
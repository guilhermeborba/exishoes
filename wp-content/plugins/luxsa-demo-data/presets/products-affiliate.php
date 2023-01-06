<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_products_affiliate()
{
    return [
        [
            'key' => 'woocommerce_products_page_design',
            'value' => '1'
        ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Products Affiliate';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
        ],

    ];
}
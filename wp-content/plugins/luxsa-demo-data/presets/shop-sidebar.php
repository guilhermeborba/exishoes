<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_shop_sidebar()
{
    return [
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Shop Sidebar';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
        ]
    ];
}
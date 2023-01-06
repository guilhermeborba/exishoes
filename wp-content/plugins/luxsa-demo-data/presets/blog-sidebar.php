<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_blog_sidebar()
{
    return [
        [
            'key'       => 'layout_blog',
            'value'     => 'col-2cr'
        ],

        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Blog Sidebar';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
        ],
        [
            'filter_name'       => 'luxsa/filter/get_option',
            'filter_func'       => function( $value, $key ) {
                if( $key == 'la_custom_css'){
                    $value .= '

';
                }
                return $value;
            },
            'filter_priority'   => 10,
            'filter_args'       => 2
        ],
    ];
}
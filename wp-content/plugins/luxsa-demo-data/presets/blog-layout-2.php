<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_blog_layout_2()
{
    return [
        [
            'key'       => 'layout_blog',
            'value'     => 'col-1c'
        ],
	    [
		    'key'       => 'blog_design',
		    'value'     => 'list-2'
	    ],
	    [
		    'key'       => 'blog_thumbnail_height_mode',
		    'value'     => 'custom'
	    ],
	    [
		    'key'       => 'blog_thumbnail_height_custom',
		    'value'     => '65%'
	    ],

	    [
		    'key'       => 'blog_excerpt_length',
		    'value'     => 25
	    ],
        [
            'key'       => 'blog_item_space',
            'value'     => [
                'desktop' => [
                    'top' => '0',
                    'bottom' => '90',
                    'left' => '',
                    'right' => '',
                ],
                'tablet' => [
                    'top' => '',
                    'bottom' => '50',
                    'left' => '',
                    'right' => '',
                ]
            ]
        ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Blog Layout 2';
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
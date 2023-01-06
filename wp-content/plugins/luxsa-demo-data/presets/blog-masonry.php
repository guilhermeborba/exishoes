<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_blog_masonry()
{
    return [
	    [
		    'key'       => 'layout_blog',
		    'value'     => 'col-1c'
	    ],
	    [
		    'key'       => 'blog_design',
		    'value'     => 'grid-3'
	    ],
	    [
		    'key'       => 'container_width_archive_post',
		    'value'     => [
		    	'desktop' => [
		    		'width' => 1440,
		    		'unit'	=> 'px'
		    	],
		    ]
	    ],
	    [
		    'key'       => 'blog_thumbnail_height_mode',
		    'value'     => 'original'
	    ],
	    [
		    'key'       => 'blog_masonry',
		    'value'     => 'on'
	    ],
	    [
		    'key'       => 'blog_pagination_type',
		    'value'     => 'pagination'
	    ],
	    [
		    'key'       => 'blog_post_column',
		    'value'     => [
			    'mobile' => 1,
			    'mobile_landscape' => 2,
			    'tablet' => 2,
			    'laptop' => 3,
			    'desktop' => 3,
		    ]
	    ],
	    [
		    'key'       => 'blog_item_space',
		    'value'     => [
			    'desktop' => [
				    'top' => '0',
				    'bottom' => '60',
				    'left' => '30',
				    'right' => '30',
			    ],
			    'tablet' => [
				    'top' => '',
				    'bottom' => '',
				    'left' => '15',
				    'right' => '15',
			    ]
		    ]
	    ],
	    [
		    'key'       => 'blog_excerpt_length',
		    'value'     => 0
	    ],
        
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Blog Masonry';
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
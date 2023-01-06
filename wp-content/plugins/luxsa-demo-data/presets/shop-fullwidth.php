<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_shop_fullwidth()
{
    return [
        [
            'key'               => 'layout_archive_product',
            'value'             => 'col-1c'
        ],
	    [
		    'key'               => 'woocommerce_shop_page_columns',
		    'value'             => [
			    'desktop' => 4,
			    'laptop' => 4,
			    'tablet' => 3,
			    'mobile_landscape' => 2,
			    'mobile' => 1
		    ]
	    ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Shop Fullwidth';
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
                        .la-pagination.active-loadmore {
                            margin-top: 4em;
                        }
                        .products-grid.cover-img-bg .product_item--inner .figure__object_fit img {
                            object-position: top;
                        }
                        .products-grid.cover-img-bg .product_item--thumbnail .figure__object_fit>div {
                            background-position: top center;
                        }
                        body.woocommerce-shop .woocommerce-pagination{
                            text-align: center;
                        }
                    ';
                }
                return $value;
            },
            'filter_priority'   => 10,
            'filter_args'       => 2
        ],
    ];
}
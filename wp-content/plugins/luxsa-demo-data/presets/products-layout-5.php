<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_products_layout_5()
{
    return [
        [
            'key' => 'woocommerce_product_page_design',
            'value' => '5'
        ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Products Layout 5';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
        ],
        [
            'key' => 'main_full_width_single_product',
            'value' => 'yes'
        ],
        [
            'key' => 'move_woo_tabs_to_bottom',
            'value' => 'no'
        ],
        [
            'key' => 'woo_tabs_top_style',
            'value' => 'vertical'
        ],
        [
            'key' => 'product_gallery_column',
            'value' => [
                'mobile' => 1,
                'mobile_landscape' => 3,
                'tablet' => 3,
                'laptop' => 3,
                'desktop' => 3,
            ]
        ],
        
        [
            'filter_name'       => 'luxsa/filter/get_option',
            'filter_func'       => function( $value, $key ) {
                if( $key == 'la_custom_css'){
                    $value .= '
                        .la-p-single-5.la-p-single-wrap.wc_tabs_at_top {
                            margin-bottom: 0;
                            width: 88%;
                            margin-left: auto;
                            margin-right: auto;
                        }
                        .product-main-image {
                            margin-bottom: 70px;                       
                        }
                        .la-p-single-5.la-p-single-wrap .s_product_content_top>.product--summary {
                            width: 90%;
                        }
                        .product-main-image .la-woo-product-gallery {
                            margin-bottom: 0;
                        }
                        body .product_desc-row .product_desc-img img.size-full{
                            width: 100%;
                            max-height: 375px;
                            object-fit: cover;
                            object-position: top;
                            margin-top: 35px;
                        }
				    ';
                }
                return $value;
            },
            'filter_priority'   => 10,
            'filter_args'       => 2
        ]
    ];
}
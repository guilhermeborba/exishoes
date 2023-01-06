<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_products_layout_4()
{
    return [
        [
            'key' => 'woocommerce_product_page_design',
            'value' => '3'
        ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Products Layout 4';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
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
            'filter_name'       => 'luxsa/filter/get_option',
            'filter_func'       => function( $value, $key ) {
                if( $key == 'la_custom_css'){
                    $value .= '
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
        ],
    ];
}
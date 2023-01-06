<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_shop_metro()
{
    return [
        [
            'key'               => 'layout_archive_product',
            'value'             => 'col-1c'
        ],
        [
            'filter_name'       => 'luxsa/filter/current_title',
            'filter_func'       => function( $title ) {
                $title = 'Shop Metro';
                return $title;
            },
            'filter_priority'   => 10,
            'filter_args'       => 1
        ],

        [
            'key' => 'main_full_width_archive_product',
            'value' => 'no'
        ],
        [
            'key' => 'active_shop_masonry',
            'value' => 'on'
        ],
        [
            'key' => 'woocommerce_toggle_grid_list',
            'value' => 'off'
        ],
       
        [
            'key' => 'shop_item_space',
            'value' => [
                'mobile' => [
                    'top' => 2,
                    'left' => 2,
                    'right' => 2,
                    'bottom' => 2
                ],
            ]
        ],
        [
            'key' => 'shop_catalog_grid_style',
            'value' => '6'
        ],
        [
            'key' => 'product_masonry_container_width',
            'value' => 1440
        ],
        [
            'key' => 'product_masonry_item_width',
            'value' => 360
        ],
        [
            'key' => 'product_masonry_item_height',
            'value' => 490
        ],
        [
            'key' => 'shop_masonry_column_type',
            'value' => 'custom'
        ],
        [
            'key' => 'enable_shop_masonry_custom_setting',
            'value' => 'on'
        ],
        [
            'key' => 'product_per_page_allow',
            'value' => '10,15,30'
        ],
        [
            'key' => 'product_per_page_default',
            'value' => 10
        ],
        
        [
            'key' => 'shop_masonry_item_setting',
            'value' =>
                array (
                    0 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    1 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    2 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    3 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    4 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    5 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    6 =>
                        array (
                            'size_name' => '2x Width + 1x Height',
                            'w' => '2',
                            'h' => '1',
                        ),
                    7 =>
                        array (
                            'size_name' => '2x Width + 1x Height',
                            'w' => '2',
                            'h' => '1',
                        ),
                    8 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                    9 =>
                        array (
                            'size_name' => '1x Width + 1x Height',
                            'w' => '1',
                            'h' => '1',
                        ),
                )

        ],


        [
            'key' => 'woocommerce_shop_masonry_custom_columns',
            'value' => array(
                'md' => 2,
                'sm' => 1,
                'xs' => 1,
                'mb' => 1
            )
        ],
        [
            'filter_name'       => 'luxsa/filter/get_option',
            'filter_func'       => function( $value, $key ) {
                if( $key == 'la_custom_css'){
                    $value .= '
                        .products-grid.cover-img-bg .product_item--inner .figure__object_fit img {
                            object-position: top;
                        }
                        .products-grid.cover-img-bg .product_item--thumbnail .figure__object_fit>div {
                            background-position: top center;
                        }
                        body.woocommerce-shop .woocommerce-pagination{
                            text-align: center;
                            margin-top: 100px;
                        }
                        @media (max-width: 992px){
                            body.woocommerce-shop .woocommerce-pagination{
                                margin-top: 50px;
                            }
                        }
                        @media (max-width: 767px){
                            body.woocommerce-shop .woocommerce-pagination{
                                margin-top: 30px;
                            }
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
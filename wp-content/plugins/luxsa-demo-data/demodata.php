<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_get_demo_array($dir_url, $dir_path){

    $demo_items = array(
        'main-01' => array(
            'link'          => 'https://luxsa.la-studioweb.com/',
            'title'         => 'Main 01',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'data_slider'   => 'main-01.zip',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
        'main-02' => array(
            'link'          => 'https://luxsa.la-studioweb.com/main-02/',
            'title'         => 'Main 02',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'data_slider'   => 'main-02.zip',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
        'main-03' => array(
            'link'          => 'https://luxsa.la-studioweb.com/main-03/',
            'title'         => 'Main 03',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
        'main-04' => array(
            'link'          => 'https://luxsa.la-studioweb.com/main-04/',
            'title'         => 'Main 04',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'data_slider'   => 'main-04.zip',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
        'main-05' => array(
            'link'          => 'https://luxsa.la-studioweb.com/main-05/',
            'title'         => 'Main 05',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'data_slider'   => 'main-05.zip',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
        'main-06' => array(
            'link'          => 'https://luxsa.la-studioweb.com/main-06/',
            'title'         => 'Main 06',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'data_slider'   => 'main-06.zip',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
        'main-07' => array(
            'link'          => 'https://luxsa.la-studioweb.com/main-07/',
            'title'         => 'Main 07',
            'data_sample'   => 'sample-data.json',
            'data_product'  => 'sample-product.csv',
            'data_widget'   => 'widget.json',
            'data_slider'   => 'main-07.zip',
            'category'      => array(
                'Demo',
	            'Fashion'
            )
        ),
    );

    $default_image_setting = array(
        'woocommerce_single_image_width' => 1000,
        'woocommerce_thumbnail_image_width' => 500,
        'woocommerce_thumbnail_cropping' => 'custom',
        'woocommerce_thumbnail_cropping_custom_width' => 4,
        'woocommerce_thumbnail_cropping_custom_height' => 5,
        'thumbnail_size_w' => 370,
        'thumbnail_size_h' => 350,
        'medium_size_w' => 0,
        'medium_size_h' => 0,
        'medium_large_size_w' => 0,
        'medium_large_size_h' => 0,
        'large_size_w' => 0,
        'large_size_h' => 0,
        'lastudio_header_layout' => 'builder'
    );

    $default_menu = array(
        'main-nav'              => 'Primary Navigation'
    );

    $default_page = array(
        'page_for_posts' 	            => 'Blog',
        'woocommerce_shop_page_id'      => 'Shop',
        'woocommerce_cart_page_id'      => 'Cart',
        'woocommerce_checkout_page_id'  => 'Checkout',
        'woocommerce_myaccount_page_id' => 'My account'
    );

    $slider = $dir_path . 'Slider/';
    $content = $dir_path . 'Content/';
    $product = $dir_path . 'Product/';
    $widget = $dir_path . 'Widget/';
    $setting = $dir_path . 'Setting/';
    $preview = $dir_url;


    if( class_exists('LAHB_Helper')){
        $header_presets = LAHB_Helper::getHeaderDefaultData();

        $h1 = json_decode($header_presets['luxsa-header-01']['data'], true);
        $h2 = json_decode($header_presets['luxsa-header-02']['data'], true);
        $h4 = json_decode($header_presets['luxsa-header-04']['data'], true);
        $h5 = json_decode($header_presets['luxsa-fashion-05']['data'], true);
        $h7 = json_decode($header_presets['luxsa-header-07']['data'], true);
        $h8 = json_decode($header_presets['luxsa-header-08']['data'], true);
        $h9 = json_decode($header_presets['luxsa-header-09']['data'], true);
        $hv2 = json_decode($header_presets['luxsa-header-vertical-02']['data'], true);

        $demo_items['main-01']['other_setting'] = $h4 ;
        $demo_items['main-02']['other_setting'] = $h1;
        $demo_items['main-03']['other_setting'] = $h2;
        $demo_items['main-04']['other_setting'] = $hv2;
        $demo_items['main-05']['other_setting'] = $h8;
        $demo_items['main-06']['other_setting'] = $h7;
        $demo_items['main-07']['other_setting'] = $h9;
    }

    $data_return = array();

    foreach ($demo_items as $demo_key => $demo_detail){
	    $value = array();
	    $value['title']             = $demo_detail['title'];
	    $value['category']          = !empty($demo_detail['category']) ? $demo_detail['category'] : array('Demo');
	    $value['demo_preset']       = $demo_key;
	    $value['demo_url']          = $demo_detail['link'];
	    $value['preview']           = !empty($demo_detail['preview']) ? $demo_detail['preview'] : ($preview . $demo_key . '.jpg');
	    $value['option']            = $setting . $demo_key . '.json';
	    $value['content']           = !empty($demo_detail['data_sample']) ? $content . $demo_detail['data_sample'] : $content . 'sample-data.json';
	    $value['product']           = !empty($demo_detail['data_product']) ? $product . $demo_detail['data_product'] : $product . 'sample-product.json';
	    $value['widget']            = !empty($demo_detail['data_widget']) ? $widget . $demo_detail['data_widget'] : $widget . 'widget.json';
	    $value['pages']             = array_merge( $default_page, array( 'page_on_front' => $demo_detail['title'] ));
	    $value['menu-locations']    = array_merge( $default_menu, isset($demo_detail['menu-locations']) ? $demo_detail['menu-locations'] : array());
	    $value['other_setting']     = array_merge( $default_image_setting, isset($demo_detail['other_setting']) ? $demo_detail['other_setting'] : array());
	    if(!empty($demo_detail['data_slider'])){
		    $value['slider'] = $slider . $demo_detail['data_slider'];
	    }
	    $data_return[$demo_key] = $value;
    }

    return $data_return;
}
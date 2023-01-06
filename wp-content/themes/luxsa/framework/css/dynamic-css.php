<?php
/**
 * This file includes dynamic css
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


$breakpoints = luxsa_get_custom_breakpoints();

$css_primary_color = luxsa_get_option('primary_color', '#B87E3A');
$css_secondary_color = luxsa_get_option('secondary_color', '#212121');
$css_three_color = luxsa_get_option('three_color', '#999');
$css_border_color = luxsa_get_option('border_color', '#ebebeb');

$device_lists = array('mobile', 'mobile_landscape', 'tablet', 'laptop', 'desktop');

$all_styles = array(
    'mobile' => array(),
    'mobile_landscape' => array(),
    'tablet' => array(),
    'laptop' => array(),
    'desktop' => array()
);

$body_font_family = luxsa_get_option('body_font_family');
$headings_font_family = luxsa_get_option('headings_font_family');
$three_font_family = luxsa_get_option('three_font_family');


$root_style = array();

if(!empty($body_font_family['font-family'])){
	$root_style[] = '--theme-body-font-family: "' .$body_font_family['font-family'] . '"';
}
if(!empty($body_font_family['color'])){
	$root_style[] = '--theme-body-font-color: ' .$body_font_family['color'];
}
if(!empty($headings_font_family['font-family'])){
	$root_style[] = '--theme-heading-font-family: "' .$headings_font_family['font-family'] . '"';
}
if(!empty($headings_font_family['color'])){
	$root_style[] = '--theme-heading-font-color: ' .$headings_font_family['color'];
}
if(!empty($headings_font_family['font-weight'])){
    $root_style[] = '--theme-heading-font-weight: ' .$headings_font_family['font-weight'];
}
if(!empty($three_font_family['font-family'])){
	$root_style[] = '--theme-three-font-family: "' .$three_font_family['font-family'] . '"';
}
if(!empty($three_font_family['font-weight'])){
	$root_style[] = '--theme-three-font-weight: ' .$three_font_family['font-weight'];
}

if(!empty($css_primary_color)){
	$root_style[] = '--theme-primary-color: ' . $css_primary_color;
	$root_style[] = '--theme-link-hover-color: ' . $css_primary_color;
}
if(!empty($css_secondary_color)){
	$root_style[] = '--theme-secondary-color: ' . $css_secondary_color;
}
if(!empty($css_three_color)){
	$root_style[] = '--theme-three-color: ' . $css_three_color;
}
if(!empty($css_border_color)){
	$root_style[] = '--theme-border-color: ' . $css_border_color;
}

$root_style[] = '--theme-newsletter-popup-width: ' . luxsa_get_option('popup_max_width', 790) . 'px';
$root_style[] = '--theme-newsletter-popup-height: ' . luxsa_get_option('popup_max_height', 430) . 'px';

$body_boxed = luxsa_get_option('body_boxed', 'no');
$body_max_width = luxsa_get_option('body_max_width', '1230');
$body_boxed_background = luxsa_get_option('body_boxed_background');
if(!empty(luxsa_array_filter_recursive($body_boxed_background)) && luxsa_string_to_bool($body_boxed)){
    echo luxsa_render_background_style_from_setting($body_boxed_background, '.body-boxed #outer-wrap');
}
if(luxsa_string_to_bool($body_boxed) && !empty($body_max_width)){
    $root_style[] = '--theme-boxed-width:' . $body_max_width . 'px';
}

if(!empty($root_style)){
	echo ':root{';
	echo join(';', $root_style);
	echo '}';
}

/**
 * Footer Bars
 */

$mb_footer_bar_visible = luxsa_get_option('mb_footer_bar_visible', '600');

echo '@media(min-width: '.esc_attr($mb_footer_bar_visible).'px){ body.enable-footer-bars{ padding-bottom: 0} .footer-handheld-footer-bar { opacity: 0 !important; visibility: hidden !important; height: 0; } }';

/**
 * Body Background
 */

$body_background = luxsa_get_option('body_background');
if(!empty(luxsa_array_filter_recursive($body_background))){
    echo luxsa_render_background_style_from_setting($body_background, 'body.luxsa-body');
}

/**
 * Main_Space
 */
$main_space = luxsa_get_theme_option_by_context('main_space');
if(!empty($main_space)){
    foreach ($main_space as $screen => $value ){
        $_css = '';
        $unit = !empty($value['unit'])? $value['unit']: 'px';
        $value_atts = shortcode_atts(array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
        ), $value);
        foreach ($value_atts as $k => $v){
            if($v !== ''){
                $_css .= 'padding-' . $k . ':' . $v . $unit . ';';
            }
        }
        if(!empty($_css)) {
            $all_styles[$screen][] = '#main #content-wrap{'. $_css .'}';
        }
    }
}

/**
 * Page Title Bar
 */
$page_title_bar_func = 'luxsa_get_option';
if( luxsa_string_to_bool(luxsa_get_theme_option_by_context('page_title_bar_style', 'no')) ){
    $page_title_bar_func = 'luxsa_get_theme_option_by_context';
}
if( luxsa_is_blog() ){
    if( luxsa_string_to_bool( luxsa_get_option('blog_post_override_page_title_bar', 'off') ) ) {
        $page_title_bar_func = 'luxsa_get_theme_option_by_context';
    }
}
elseif ( function_exists('is_product') && is_product() ){
    if( luxsa_string_to_bool( luxsa_get_option('single_product_override_page_title_bar', 'off') ) ) {
        $page_title_bar_func = 'luxsa_get_theme_option_by_context';
    }
}
elseif ( post_type_exists('la_portfolio') && is_singular('la_portfolio') ){
    if( luxsa_string_to_bool( luxsa_get_option('single_portfolio_override_page_title_bar', 'off') ) ) {
        $page_title_bar_func = 'luxsa_get_theme_option_by_context';
    }
}
elseif ( is_singular('post') ){
    if( luxsa_string_to_bool( luxsa_get_option('single_post_override_page_title_bar', 'off') ) ) {
        $page_title_bar_func = 'luxsa_get_theme_option_by_context';
    }
}
elseif ( function_exists('is_woocommerce') && is_woocommerce() ) {
    if( luxsa_string_to_bool( luxsa_get_option('woo_override_page_title_bar', 'off') ) ) {
        $page_title_bar_func = 'luxsa_get_theme_option_by_context';
    }
}
elseif ( post_type_exists('la_portfolio') && (is_post_type_archive('la_portfolio') || ( is_tax() && !empty(get_object_taxonomies( 'la_portfolio' )) && is_tax( get_object_taxonomies( 'la_portfolio' ) ) )) ) {
    if( luxsa_string_to_bool( luxsa_get_option('archive_portfolio_override_page_title_bar', 'off') ) ) {
        $page_title_bar_func = 'luxsa_get_theme_option_by_context';
    }
}

$page_title_bar_space = call_user_func($page_title_bar_func, 'page_title_bar_space');

if(!empty($page_title_bar_space)){
    foreach ($page_title_bar_space as $screen => $value ){
        $_css = '';
        $unit = !empty($value['unit'])? $value['unit']: 'px';
        $value_atts = shortcode_atts(array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
        ), $value);
        foreach ($value_atts as $k => $v){
            if($v !== ''){
                $_css .= 'padding-' . $k . ':' . $v . $unit . ';';
            }
        }
        if(!empty($_css)) {
            $all_styles[$screen][] = '.section-page-header .page-header-inner{'. $_css .'}';
        }
    }
}

$page_title_bar_border = call_user_func($page_title_bar_func, 'page_title_bar_border');
$page_title_bar_background = call_user_func($page_title_bar_func, 'page_title_bar_background');

$page_title_bar_heading_color = call_user_func($page_title_bar_func, 'page_title_bar_heading_color', $css_secondary_color);
$page_title_bar_text_color = call_user_func($page_title_bar_func, 'page_title_bar_text_color', $css_secondary_color);
$page_title_bar_link_color = call_user_func($page_title_bar_func, 'page_title_bar_link_color', $css_secondary_color);
$page_title_bar_link_hover_color = call_user_func($page_title_bar_func, 'page_title_bar_link_hover_color', $css_primary_color);


if(!empty(luxsa_array_filter_recursive($page_title_bar_border))){
    echo luxsa_render_border_style_from_setting($page_title_bar_border, '.section-page-header');
}

if(!empty(luxsa_array_filter_recursive($page_title_bar_background))){
    echo luxsa_render_background_style_from_setting($page_title_bar_background, '.section-page-header');
}

/**
 * Build Typography - Page Header
 */
$page_title_bar_heading_fonts = call_user_func($page_title_bar_func, 'page_title_bar_heading_fonts');
$page_title_bar_breadcrumb_fonts = call_user_func($page_title_bar_func, 'page_title_bar_breadcrumb_fonts');
foreach ($device_lists as $screen){

    $_css = luxsa_render_typography_style_from_setting( $page_title_bar_heading_fonts, '.section-page-header .page-title', $screen );
    if(!empty($_css)) {
        $all_styles[$screen][] = $_css;
    }

    $_css = luxsa_render_typography_style_from_setting( $page_title_bar_breadcrumb_fonts, '.section-page-header .site-breadcrumbs', $screen );
    if(!empty($_css)) {
        $all_styles[$screen][] = $_css;
    }

}

if(!empty($page_title_bar_heading_color)){
    echo '.section-page-header .page-title { color: '.esc_attr($page_title_bar_heading_color).' }';
}

if(!empty($page_title_bar_text_color)){
    echo '.section-page-header { color: '.esc_attr($page_title_bar_text_color).' }';
}

if(!empty($page_title_bar_link_color)){
    echo '.section-page-header .breadcrumb-sep,.section-page-header a { color: '.esc_attr($page_title_bar_link_color).' }';
}
if(!empty($page_title_bar_link_hover_color)){
    echo '.section-page-header a:hover { color: '.esc_attr($page_title_bar_link_hover_color).' }';
}

/**
 * Popup Style
 */
$popup_background = luxsa_get_option('popup_background');
if(!empty(luxsa_array_filter_recursive($popup_background))){
    echo luxsa_render_background_style_from_setting($popup_background, '.open-newsletter-popup .featherlight-content');
}


/**
 * Shop Item Space
 */
$shop_item_space = luxsa_get_option('shop_item_space');
if(!empty($shop_item_space)){
    foreach ($shop_item_space as $screen => $value ){
        $_css = '';
        $_css2 = '';
        $unit = !empty($value['unit'])? $value['unit']: 'px';

        $value_atts = shortcode_atts(array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => ''
        ), $value);


        foreach ($value_atts as $k => $v){
            if($v !== ''){
                $_css .= 'padding-' . $k . ':' . $v . $unit . ';';
                if($k == 'left' || $k == 'right'){
                    $_css2 .= 'margin-' . $k . ':-' . $v . $unit . ';';
                }
            }
        }

        if(!empty($_css)) {
            $all_styles[$screen][] = '.la-shop-products .ul_products.products{'. $_css2 .'}';
            $all_styles[$screen][] = '.la-shop-products .ul_products.products li.product_item{'. $_css .'}';
        }
    }
}
/**
 * Blog Item Image Height
 */
$blog_item_space = luxsa_get_option('blog_item_space');
if(!empty($blog_item_space)){
    foreach ($blog_item_space as $screen => $value ){
        $_css = '';
        $_css2 = '';
        $unit = !empty($value['unit'])? $value['unit']: 'px';

        $value_atts = shortcode_atts(array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
        ), $value);
        foreach ($value_atts as $k => $v){
            if($v !== ''){
                $_css .= 'padding-' . $k . ':' . $v . $unit . ';';
                if($k == 'left' || $k == 'right'){
                    $_css2 .= 'margin-' . $k . ':-' . $v . $unit . ';';
                }
            }
        }
        if(!empty($_css)) {
            $all_styles[$screen][] = '.lastudio-posts.blog__entries{'. $_css2 .'}';
            $all_styles[$screen][] = '.lastudio-posts.blog__entries .loop__item{'. $_css .'}';
        }
    }
}

$blog_thumbnail_height_mode = luxsa_get_option('blog_thumbnail_height_mode', 'original');
$blog_thumbnail_height_custom = luxsa_get_option('blog_thumbnail_height_custom', '70%');
$blog_thumbnail_height = '70%';

switch ($blog_thumbnail_height_mode){
    case '1-1':
        $blog_thumbnail_height = '100%';
        break;
    case '4-3':
        $blog_thumbnail_height = '75%';
        break;
    case '3-4':
        $blog_thumbnail_height = '133.34%';
        break;
    case '16-9':
        $blog_thumbnail_height = '56.25%';
        break;
    case '9-16':
        $blog_thumbnail_height = '177.78%';
        break;
    case 'custom':
        $blog_thumbnail_height = $blog_thumbnail_height_custom;
        break;
}
if($blog_thumbnail_height_mode != 'original'){
    $all_styles['mobile'][] = '.lastudio-posts.blog__entries .post-thumbnail .blog_item--thumbnail, .lastudio-posts.blog__entries .post-thumbnail .blog_item--thumbnail .slick-slide .sinmer{ padding-bottom: '.$blog_thumbnail_height.'}';
}

/**
 * Porfolios
 */

$portfolio_item_space = luxsa_get_option('portfolio_item_space');
if(!empty($portfolio_item_space)){
    foreach ($portfolio_item_space as $screen => $value ){
        $_css = '';
        $_css2 = '';
        $unit = !empty($value['unit'])? $value['unit']: 'px';

        $value_atts = shortcode_atts(array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
        ), $value);
        foreach ($value_atts as $k => $v){
            if($v !== ''){
                $_css .= 'padding-' . $k . ':' . $v . $unit . ';';
                if($k == 'left' || $k == 'right'){
                    $_css2 .= 'margin-' . $k . ':-' . $v . $unit . ';';
                }
            }
        }
        if(!empty($_css)) {
            $all_styles[$screen][] = '#pf_main_archive .lastudio-portfolio__list_wrapper{'. $_css2 .'}';
            $all_styles[$screen][] = '#pf_main_archive .loop__item{'. $_css .'}';
        }
    }
}

$portfolio_thumbnail_height_mode = luxsa_get_option('portfolio_thumbnail_height_mode', 'original');
$portfolio_thumbnail_height_custom = luxsa_get_option('portfolio_thumbnail_height_custom', '70%');
$portfolio_thumbnail_height = '70%';

switch ($portfolio_thumbnail_height_mode){
    case '1-1':
        $portfolio_thumbnail_height = '100%';
        break;
    case '4-3':
        $portfolio_thumbnail_height = '75%';
        break;
    case '3-4':
        $portfolio_thumbnail_height = '133.34%';
        break;
    case '16-9':
        $portfolio_thumbnail_height = '56.25%';
        break;
    case '9-16':
        $portfolio_thumbnail_height = '177.78%';
        break;
    case 'custom':
        $portfolio_thumbnail_height = $portfolio_thumbnail_height_custom;
        break;
}
if($portfolio_thumbnail_height_mode != 'original'){
    $all_styles['mobile'][] = '#pf_main_archive .lastudio-portfolio__image{ padding-bottom: '.$portfolio_thumbnail_height.'}';
}

/**
 * Build Typography
 */

$typography_selectors = array(
    'body_font_family'                  => 'body',
    'three_font_family'                 => '.three_font_family,.highlight_font_family',
    'headings_font_family'              => 'h1,h2,h3,h4,h5,h6,.theme-heading, .widget-title, .comments-title, .comment-reply-title, .entry-title',
    'heading1_font_family'              => 'h1',
    'heading2_font_family'              => 'h2',
    'heading3_font_family'              => 'h3',
    'heading4_font_family'              => 'h4',
    'blog_entry_title_font_family'      => '.lastudio-posts.blog__entries .entry-title',
    'blog_entry_meta_font_family'       => '.lastudio-posts.blog__entries .post-meta',
    'blog_entry_content_font_family'    => '.lastudio-posts.blog__entries .entry-excerpt',
    'blog_post_meta_font_family'        => '.single-post-article > .post-meta__item, .single-post-article > .post-meta .post-meta__item',
    'blog_post_content_font_family'     => 'body:not(.page-use-builder) .single-post-article > .entry',
);

foreach ($device_lists as $screen){
    foreach ($typography_selectors as $opt_key => $typography_selector ){
        $_css = luxsa_render_typography_style_from_setting( luxsa_get_option($opt_key), $typography_selector, $screen );
        if(!empty($_css)) {
            $all_styles[$screen][] = $_css;
        }
    }
}

/**
 * Build Typography - Custom Selector
 */
$extra_typography = luxsa_get_option('extra_typography');
if(!empty($extra_typography)){
    foreach ($extra_typography as $item){
        if(!empty($item['selector']) && !empty($item['fonts'])){
            $css_custom_selector = rtrim(trim($item['selector']), ',');
            if(!empty($css_custom_selector)){
                foreach ($device_lists as $screen){
                    $_css = luxsa_render_typography_style_from_setting( $item['fonts'], $css_custom_selector, $screen );
                    if(!empty($_css)) {
                        $all_styles[$screen][] = $_css;
                    }
                }
            }
        }
    }
}

$product_main_image_width = luxsa_get_option('woocommerce_product_page_main_image_width');
if(!empty($product_main_image_width)){
    if(!empty($product_main_image_width['tablet']) && !empty($product_main_image_width['tablet']['width']) && !empty($product_main_image_width['tablet']['unit'])){
        $all_styles['tablet'][] = ':root{--theme-wc-single-main-image-width:'.esc_attr($product_main_image_width['tablet']['width'] . $product_main_image_width['tablet']['unit']).'}';
    }
    if(!empty($product_main_image_width['laptop']) && !empty($product_main_image_width['laptop']['width']) && !empty($product_main_image_width['laptop']['unit'])){
        $all_styles['laptop'][] = ':root{--theme-wc-single-main-image-width:'.esc_attr($product_main_image_width['laptop']['width'] . $product_main_image_width['laptop']['unit']).'}';
    }
    if(!empty($product_main_image_width['desktop']) && !empty($product_main_image_width['desktop']['width']) && !empty($product_main_image_width['desktop']['unit'])){
        $all_styles['desktop'][] = ':root{--theme-wc-single-main-image-width:'.esc_attr($product_main_image_width['desktop']['width'] . $product_main_image_width['desktop']['unit']).'}';
    }
}

$container_width = luxsa_get_theme_option_by_context('container_width');
if(!empty($container_width)){
    if(!empty($container_width['tablet']) && !empty($container_width['tablet']['width']) && !empty($container_width['tablet']['unit'])){
        $all_styles['tablet'][] = ':root{--theme-container-width:'.esc_attr($container_width['tablet']['width'] . $container_width['tablet']['unit']).'}';
    }
    if(!empty($container_width['laptop']) && !empty($container_width['laptop']['width']) && !empty($container_width['laptop']['unit'])){
        $all_styles['laptop'][] = ':root{--theme-container-width:'.esc_attr($container_width['laptop']['width'] . $container_width['laptop']['unit']).'}';
    }
    if(!empty($container_width['desktop']) && !empty($container_width['desktop']['width']) && !empty($container_width['desktop']['unit'])){
        $all_styles['desktop'][] = ':root{--theme-container-width:'.esc_attr($container_width['desktop']['width'] . $container_width['desktop']['unit']).'}';
    }
}

$custom_sidebar_width = luxsa_get_theme_option_by_context('custom_sidebar_width');
if(!empty($custom_sidebar_width)){
    if(!empty($custom_sidebar_width['tablet']) && !empty($custom_sidebar_width['tablet']['width']) && !empty($custom_sidebar_width['tablet']['unit'])){
        $all_styles['tablet'][] = ':root{--theme-sidebar-width:'.esc_attr($custom_sidebar_width['tablet']['width'] . $custom_sidebar_width['tablet']['unit']).'}';
    }
    if(!empty($custom_sidebar_width['laptop']) && !empty($custom_sidebar_width['laptop']['width']) && !empty($custom_sidebar_width['laptop']['unit'])){
        $all_styles['laptop'][] = ':root{--theme-sidebar-width:'.esc_attr($custom_sidebar_width['laptop']['width'] . $custom_sidebar_width['laptop']['unit']).'}';
    }
    if(!empty($custom_sidebar_width['desktop']) && !empty($custom_sidebar_width['desktop']['width']) && !empty($custom_sidebar_width['desktop']['unit'])){
        $all_styles['desktop'][] = ':root{--theme-sidebar-width:'.esc_attr($custom_sidebar_width['desktop']['width'] . $custom_sidebar_width['desktop']['unit']).'}';
    }
}

$custom_sidebar_space = luxsa_get_theme_option_by_context('custom_sidebar_space');
if(!empty($custom_sidebar_space)){
    if(!empty($custom_sidebar_width['tablet']) && !empty($custom_sidebar_space['tablet']['width']) && !empty($custom_sidebar_space['tablet']['unit'])){
        $all_styles['tablet'][] = ':root{--theme-sidebar-space:'.esc_attr($custom_sidebar_space['tablet']['width'] . $custom_sidebar_space['tablet']['unit']).'}';
    }
    if(!empty($custom_sidebar_space['laptop']) && !empty($custom_sidebar_space['laptop']['width']) && !empty($custom_sidebar_space['laptop']['unit'])){
        $all_styles['laptop'][] = ':root{--theme-sidebar-space:'.esc_attr($custom_sidebar_space['laptop']['width'] . $custom_sidebar_space['laptop']['unit']).'}';
    }
    if(!empty($custom_sidebar_space['desktop']) && !empty($custom_sidebar_space['desktop']['width']) && !empty($custom_sidebar_space['desktop']['unit'])){
        $all_styles['desktop'][] = ':root{--theme-sidebar-space:'.esc_attr($custom_sidebar_space['desktop']['width'] . $custom_sidebar_space['desktop']['unit']).'}';
    }
}

/**
 * Print the styles
 */
/**
 * MOBILE FIRST
 */
if(!empty($all_styles['mobile'])){
    echo join('', $all_styles['mobile']);
}

/**
 * MOBILE LANDSCAPE AND TABLET PORTRAIT
 */
if(!empty($all_styles['mobile_landscape'])){
    echo '@media (min-width: '.$breakpoints['sm'].'px) {';
    echo join('', $all_styles['mobile_landscape']);
    echo '}';
}

/**
 * TABLET LANDSCAPE
 */
if(!empty($all_styles['tablet'])){
    echo '@media (min-width: '.$breakpoints['md'].'px) {';
    echo join('', $all_styles['tablet']);
    echo '}';
}

/**
 * LAPTOP LANDSCAPE
 */
if(!empty($all_styles['laptop'])){
    echo '@media (min-width: '.$breakpoints['lg'].'px) {';
    echo join('', $all_styles['laptop']);
    echo '}';
}

/**
 * DESKTOP LANDSCAPE
 */
if(!empty($all_styles['desktop'])){
    echo '@media (min-width: '.$breakpoints['xl'].'px) {';
    echo join('', $all_styles['desktop']);
    echo '}';
}
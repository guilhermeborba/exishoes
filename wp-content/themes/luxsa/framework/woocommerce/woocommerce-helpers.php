<?php
/**
 * WooCommerce helper functions
 * This functions only load if WooCommerce is enabled because
 * they should be used within Woo loops only.
 *
 * @package Luxsa WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('luxsa_modify_sale_flash')){
    function luxsa_modify_sale_flash( $output ){
        return str_replace('class="onsale"', 'class="la-custom-badge onsale"', $output);
    }
}
add_filter('woocommerce_sale_flash', 'luxsa_modify_sale_flash');

if(!function_exists('luxsa_modify_product_list_preset')){
    function luxsa_modify_product_list_preset( $preset ){
        $preset = array(
            '1' => esc_html__( 'Default', 'luxsa' )
        );
        return $preset;
    }
}
add_filter('LaStudioElement/products/control/list_style', 'luxsa_modify_product_list_preset');

if(!function_exists('luxsa_modify_product_grid_preset')){
    function luxsa_modify_product_grid_preset( $preset ){
        return array(
            '1' => esc_html__( 'Type 1', 'luxsa' ),
            '2' => esc_html__( 'Type 2', 'luxsa' ),
            '3' => esc_html__( 'Type 3', 'luxsa' ),
            '4' => esc_html__( 'Type 4', 'luxsa' ),
            '5' => esc_html__( 'Type 5', 'luxsa' ),
            '6' => esc_html__( 'Type 6', 'luxsa' ),
            '7' => esc_html__( 'Type 7', 'luxsa' ),
            '8' => esc_html__( 'Type 8', 'luxsa' ),
            '9' => esc_html__( 'Type 9', 'luxsa' ),
            'mini' => esc_html__( 'Minimalist', 'luxsa' )
        );
    }
}
add_filter('LaStudioElement/products/control/grid_style', 'luxsa_modify_product_grid_preset');

if(!function_exists('luxsa_modify_product_masonry_preset')){
    function luxsa_modify_product_masonry_preset( $preset ){
        return array(
	        '1' => esc_html__( 'Type 1', 'luxsa' ),
	        '2' => esc_html__( 'Type 2', 'luxsa' ),
	        '3' => esc_html__( 'Type 3', 'luxsa' ),
	        '4' => esc_html__( 'Type 4', 'luxsa' ),
	        '5' => esc_html__( 'Type 5', 'luxsa' ),
	        '6' => esc_html__( 'Type 6', 'luxsa' ),
	        '7' => esc_html__( 'Type 7', 'luxsa' ),
	        '8' => esc_html__( 'Type 8', 'luxsa' ),
	        '9' => esc_html__( 'Type 9', 'luxsa' ),
        );
    }
}
add_filter('LaStudioElement/products/control/masonry_style', 'luxsa_modify_product_masonry_preset');

add_filter('woocommerce_product_description_heading', '__return_empty_string');
add_filter('woocommerce_product_additional_information_heading', '__return_empty_string');

if(!function_exists('luxsa_woo_get_product_per_page_array')){
    function luxsa_woo_get_product_per_page_array(){
        $per_page_array = apply_filters('luxsa/filter/get_product_per_page_array', luxsa_get_option('product_per_page_allow', ''));
        if(!empty($per_page_array)){
            $per_page_array = explode(',', $per_page_array);
            $per_page_array = array_map('trim', $per_page_array);
            $per_page_array = array_map('absint', $per_page_array);
            asort($per_page_array);
            return $per_page_array;
        }
        else{
            return array();
        }
    }
}

if(!function_exists('luxsa_woo_get_product_per_row_array')){
    function luxsa_woo_get_product_per_row_array(){
        $per_page_array = apply_filters('luxsa/filter/get_product_per_row_array', luxsa_get_option('product_per_row_allow', ''));
        if(!empty($per_page_array)){
            $per_page_array = explode(',', $per_page_array);
            $per_page_array = array_map('trim', $per_page_array);
            $per_page_array = array_map('absint', $per_page_array);
            asort($per_page_array);
            return $per_page_array;
        }
        else{
            return array();
        }
    }
}

if(!function_exists('luxsa_woo_get_product_per_page')){
    function luxsa_woo_get_product_per_page(){
        return apply_filters('luxsa/filter/get_product_per_page', luxsa_get_option('product_per_page_default', 9));
    }
}

if(!function_exists('luxsa_get_base_shop_url')){
    function luxsa_get_base_shop_url( ){
        if(function_exists('la_get_base_shop_url')){
            $url = la_get_base_shop_url();
        }
        else{
            $url = get_post_type_archive_link( 'product' );
        }
        if( is_tax( get_object_taxonomies( 'product' ) ) && !is_filtered() ) {
            $url = get_post_type_archive_link( 'product' );
        }
        return $url;
    }
}

if(!function_exists('luxsa_get_wc_attribute_for_compare')){
    function luxsa_get_wc_attribute_for_compare(){
        return array(
            'image'         => esc_html__( 'Image', 'luxsa' ),
            'title'         => esc_html__( 'Title', 'luxsa' ),
            'add-to-cart'   => esc_html__( 'Add to cart', 'luxsa' ),
            'price'         => esc_html__( 'Price', 'luxsa' ),
            'sku'           => esc_html__( 'Sku', 'luxsa' ),
            'description'   => esc_html__( 'Description', 'luxsa' ),
            'stock'         => esc_html__( 'Availability', 'luxsa' ),
            'weight'        => esc_html__( 'Weight', 'luxsa' ),
            'dimensions'    => esc_html__( 'Dimensions', 'luxsa' )
        );
    }
}

if(!function_exists('luxsa_get_wc_attribute_taxonomies')){
    function luxsa_get_wc_attribute_taxonomies( ){
        $attributes = array();
        if( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if(!empty($attribute_taxonomies)){
                foreach( $attribute_taxonomies as $attribute ) {
                    $tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
                    $attributes[$tax] = ucfirst( wc_attribute_label( $tax ) );
                }
            }
        }

        return $attributes;
    }
}

/**
 * This function allow get property of `woocommerce_loop` inside the loop
 * @since 1.0.0
 * @param string $prop Prop to get.
 * @param string $default Default if the prop does not exist.
 * @return mixed
 */

if(!function_exists('luxsa_get_wc_loop_prop')){
    function luxsa_get_wc_loop_prop( $prop, $default = ''){
        return isset( $GLOBALS['woocommerce_loop'], $GLOBALS['woocommerce_loop'][ $prop ] ) ? $GLOBALS['woocommerce_loop'][ $prop ] : $default;
    }
}

/**
 * This function allow set property of `woocommerce_loop`
 * @since 1.0.0
 * @param string $prop Prop to set.
 * @param string $value Value to set.
 */

if(!function_exists('luxsa_set_wc_loop_prop')){
    function luxsa_set_wc_loop_prop( $prop, $value = ''){
        if(isset($GLOBALS['woocommerce_loop'])){
            $GLOBALS['woocommerce_loop'][ $prop ] = $value;
        }
    }
}
/**
 * Override template product title
 */
if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
    function woocommerce_template_loop_product_title() {
        the_title( sprintf( '<h3 class="product_item--title"><a href="%s">', esc_url( get_the_permalink() ) ), '</a></h3>' );
    }
}


if(!function_exists('luxsa_wc_filter_show_page_title')){
    function luxsa_wc_filter_show_page_title( $show ){
        if( is_singular('product') && luxsa_string_to_bool( luxsa_get_option('product_single_hide_page_title', 'no') ) ){
            return false;
        }
        return $show;
    }
    add_filter('luxsa/filter/show_page_title', 'luxsa_wc_filter_show_page_title', 10, 1 );
}

if(!function_exists('luxsa_wc_filter_show_breadcrumbs')){
    function luxsa_wc_filter_show_breadcrumbs( $show ){
        if( is_singular('product') && luxsa_string_to_bool( luxsa_get_option('product_single_hide_breadcrumb', 'no') ) ){
            return false;
        }
        return $show;
    }
    add_filter('luxsa/filter/show_breadcrumbs', 'luxsa_wc_filter_show_breadcrumbs', 10, 1 );
}


if(!function_exists('luxsa_wc_allow_translate_text_in_swatches')){

    function luxsa_wc_allow_translate_text_in_swatches( $text ){
        return esc_html_x( 'Choose an option', 'front-view', 'luxsa' );
    }

    add_filter('LaStudio/swatches/args/show_option_none', 'luxsa_wc_allow_translate_text_in_swatches', 10, 1);
}

/**
 * Override page title bar from global settings
 * What we need to do now is
 * 1. checking in single content types
 *  1.1) post
 *  1.2) product
 *  1.3) portfolio
 * 2. checking in archives
 *  2.1) shop
 *  2.2) portfolio
 *
 * TIPS: List functions will be use to check
 * `is_product`, `is_single_la_portfolio`, `is_shop`, `is_woocommerce`, `is_product_taxonomy`, `is_archive_la_portfolio`, `is_tax_la_portfolio`
 */

if(!function_exists('luxsa_wc_override_page_title_bar_from_context')){
    function luxsa_wc_override_page_title_bar_from_context( $value, $key ){

        $array_key_allow = array(
            'page_title_bar_style',
            'page_title_bar_layout',
            'page_title_bar_border',
            'page_title_bar_background',
            'page_title_bar_space',
            'page_title_bar_heading_fonts',
            'page_title_bar_breadcrumb_fonts',
            'page_title_bar_heading_color',
            'page_title_bar_text_color',
            'page_title_bar_link_color',
            'page_title_bar_link_hover_color'
        );

        $array_key_alternative = array(
            'page_title_bar_layout',
            'page_title_bar_border',
            'page_title_bar_background',
            'page_title_bar_space',
            'page_title_bar_heading_fonts',
            'page_title_bar_breadcrumb_fonts',
            'page_title_bar_heading_color',
            'page_title_bar_text_color',
            'page_title_bar_link_color',
            'page_title_bar_link_hover_color'
        );

        /**
         * Firstly, we need to check the `$key` input
         */
        if( !in_array($key, $array_key_allow) ){
            return $value;
        }

        /**
         * Secondary, we need to check the `$context` input
         */

        if( !is_woocommerce() ){
            return $value;
        }
        if($key == 'page_title_bar_layout' && function_exists('dokan_is_store_page') && dokan_is_store_page()){
            return 'hide';
        }

        $func_name = 'luxsa_get_post_meta';
        $queried_object_id = get_queried_object_id();

        if( is_product_taxonomy() ){
            $func_name = 'luxsa_get_term_meta';
        }

        if( is_shop() ){
            $queried_object_id = wc_get_page_id( 'shop' );
        }

        if ( 'page_title_bar_layout' == $key ) {
            $page_title_bar_layout = call_user_func($func_name, $queried_object_id, $key);
            if($page_title_bar_layout && $page_title_bar_layout != 'inherit'){
                return $page_title_bar_layout;
            }
        }

        if( 'yes' == call_user_func($func_name ,$queried_object_id, 'page_title_bar_style') && in_array($key, $array_key_alternative) ){
            return $value;
        }

        $key_override = $new_key = false;

        if( is_product() ){
            $key_override = 'single_product_override_page_title_bar';
            $new_key = 'single_product_' . $key;
        }
        elseif ( is_shop() || is_product_taxonomy() ) {
            $key_override = 'woo_override_page_title_bar';
            $new_key = 'woo_' . $key;
        }

        if(false != $key_override){
            if( 'on' == luxsa_get_option($key_override, 'off') ){
                return luxsa_get_option($new_key, $value);
            }
        }

        return $value;
    }

    add_filter('luxsa/filter/get_theme_option_by_context', 'luxsa_wc_override_page_title_bar_from_context', 20, 2);
}

if(!function_exists('luxsa_override_woothumbnail_size_name')){
    function luxsa_override_woothumbnail_size_name( ) {
        return 'woocommerce_gallery_thumbnail';
    }
    add_filter('woocommerce_gallery_thumbnail_size', 'luxsa_override_woothumbnail_size_name', 0);
}


if(!function_exists('luxsa_override_woothumbnail_size')){
    function luxsa_override_woothumbnail_size( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'gallery_thumbnail_image_width', 600 ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = '';
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_gallery_thumbnail', 'luxsa_override_woothumbnail_size');
}

if(!function_exists('luxsa_override_woothumbnail_single')){
    function luxsa_override_woothumbnail_single( $size ) {
        if(!function_exists('wc_get_theme_support')){
            return $size;
        }
        $size['width'] = absint( wc_get_theme_support( 'single_image_width', get_option( 'woocommerce_single_image_width', 600 ) ) );
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );

        if ( 'uncropped' === $cropping ) {
            $size['height'] = '';
            $size['crop']   = 0;
        }
        elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }

        return $size;
    }
    add_filter('woocommerce_get_image_size_single', 'luxsa_override_woothumbnail_single', 0);
}



if ( !function_exists('luxsa_modify_text_woocommerce_catalog_orderby') ){
    function luxsa_modify_text_woocommerce_catalog_orderby( $data ) {
        $data = array(
            'menu_order' => esc_html__( 'Sort by Default', 'luxsa' ),
            'popularity' => esc_html__( 'Sort by Popularity', 'luxsa' ),
            'rating'     => esc_html__( 'Sort by Rated', 'luxsa' ),
            'date'       => esc_html__( 'Sort by Latest', 'luxsa' ),
            'price'      => sprintf( wp_kses( __( 'Sort by Price: %s', 'luxsa' ), array( 'i' => array( 'class' => array() ) ) ), '<i class="lastudioicon-arrow-up"></i>' ),
            'price-desc' => sprintf( wp_kses( __( 'Sort by Price: %s', 'luxsa' ), array( 'i' => array( 'class' => array() ) ) ), '<i class="lastudioicon-arrow-down"></i>' ),
        );
        return $data;
    }

    add_filter('woocommerce_catalog_orderby', 'luxsa_modify_text_woocommerce_catalog_orderby');
}

if(!function_exists('luxsa_add_custom_badge_for_product')){
    function luxsa_add_custom_badge_for_product(){
        global $product;
        $product_badges = luxsa_get_post_meta($product->get_id(), 'product_badges');
        if(empty($product_badges)){
            return;
        }
        $_tmp_badges = array();
        foreach($product_badges as $badge){
            if(!empty($badge['text'])){
                $_tmp_badges[] = $badge;
            }
        }
        if(empty($_tmp_badges)){
            return;
        }
        foreach($_tmp_badges as $i => $badge){
            $attribute = array();
            if(!empty($badge['bg'])){
                $attribute[] = 'background-color:' . esc_attr($badge['bg']);
            }
            if(!empty($badge['color'])){
                $attribute[] = 'color:' . esc_attr($badge['color']);
            }
            $el_class = ($i%2==0) ? 'odd' : 'even';
            if(!empty($badge['el_class'])){
                $el_class .= ' ';
                $el_class .= $badge['el_class'];
            }

            echo sprintf(
                '<span class="la-custom-badge %1$s" style="%3$s"><span>%2$s</span></span>',
                esc_attr($el_class),
                esc_html($badge['text']),
                (!empty($attribute) ? esc_attr(implode(';', $attribute)) : '')
            );
        }
    }
    add_action( 'woocommerce_before_shop_loop_item_title', 'luxsa_add_custom_badge_for_product', 9 );
    add_action( 'woocommerce_before_single_product_summary', 'luxsa_add_custom_badge_for_product', 9 );
}

if(!function_exists('luxsa_wc_add_custom_stock_to_product_details')){
    function luxsa_wc_add_custom_stock_to_product_details(){
        global $product;
        $stock_sold = ($total_sales = $product->get_total_sales()) ? $total_sales : 0;
        if($stock_sold > 0){
            $availability = sprintf(__('%s Sold', 'luxsa'), $stock_sold );
            echo str_replace('">', '"><span>' . $availability . '</span><i></i>', wc_get_stock_html( $product ));
        }
        else{
            echo wc_get_stock_html( $product );
        }
    }
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 8 );
add_action( 'woocommerce_single_product_summary', 'luxsa_wc_add_custom_stock_to_product_details', 7 );


if(!function_exists('luxsa_add_custom_block_to_cart_page')){
    function luxsa_add_custom_block_to_cart_page(){
        ?>
        <div class="lasf-extra-cart lasf-extra-cart--calc">
            <h2><?php esc_html_e('Estimate Shipping', 'luxsa'); ?></h2>
            <p><?php esc_html_e('Enter your destination to get shipping', 'luxsa'); ?></p>
            <div class="lasf-extra-cart-box"></div>
        </div>
        <div class="lasf-extra-cart lasf-extra-cart--coupon">
            <h2><?php esc_html_e('Discount code', 'luxsa'); ?></h2>
            <p><?php esc_html_e('Enter your coupon if you have one', 'luxsa'); ?></p>
            <div class="lasf-extra-cart-box"></div>
        </div>
        <?php
    }
    add_action('woocommerce_cart_collaterals', 'luxsa_add_custom_block_to_cart_page', 5);
}

if(!function_exists('luxsa_add_custom_step_into_woocommerce')){
    function luxsa_add_custom_step_into_woocommerce(){
        if( is_ajax() || ! empty( $_GET['wc-ajax'] ) || is_admin() ){
            return;
        }
?>
        <div class="row section-checkout-step">
            <div class="col-xs-12">
                <ul>
                    <li class="step-1"><span class="step-name"><span><span class="step-num"><?php esc_html_e('01', 'luxsa') ?></span><span><?php esc_html_e('Shopping Cart', 'luxsa') ?></span></span></span>
                    </li><li class="step-2"><span class="step-name"><span><span class="step-num"><?php esc_html_e('02', 'luxsa') ?></span><span><?php esc_html_e('Check out', 'luxsa') ?></span></span></span>
                    </li><li class="step-3"><span class="step-name"><span><span class="step-num"><?php esc_html_e('03', 'luxsa') ?></span><span><?php esc_html_e('Order completed', 'luxsa') ?></span></span></span></li>
                </ul>
            </div>
        </div>
<?php
    }
}
add_action('woocommerce_check_cart_items', 'luxsa_add_custom_step_into_woocommerce');

if(!function_exists('luxsa_add_custom_heading_to_checkout_order_review')){
    function luxsa_add_custom_heading_to_checkout_order_review(){
        ?><h3 id="order_review_heading_ref">Seu pedido</h3><?php
    }
}
add_action('woocommerce_checkout_order_review', 'luxsa_add_custom_heading_to_checkout_order_review', 0);


if(!function_exists('luxsa_override_woocommerce_product_get_rating_html')){
    function luxsa_override_woocommerce_product_get_rating_html( $html ) {
        if(!empty($html)){
            $html = '<div class="product_item--rating">'.$html.'</div>';
        }
        return $html;
    }
}
add_filter('woocommerce_product_get_rating_html', 'luxsa_override_woocommerce_product_get_rating_html');


if(!function_exists('luxsa_add_custom_block_to_single_product_page')){
    function luxsa_add_custom_block_to_single_product_page(){

        $position_detect = array(
            'pos1' => array(
                'hook_name' => 'woocommerce_single_product_summary',
                'priority'  => 30 /* After Cart */
            ),
            'pos2' => array(
                'hook_name' => 'woocommerce_single_product_summary',
                'priority'  => 40 /* After Meta */
            ),
            'pos3' => array(
                'hook_name' => 'woocommerce_single_product_summary',
                'priority'  => 10 /* After Price */
            ),
            'pos4' => array(
                'hook_name' => 'woocommerce_single_product_summary',
                'priority'  => 5 /* After Title */
            ),
            'pos5' => array(
                'hook_name' => 'woocommerce_single_product_summary',
                'priority'  => 20 /* After Description */
            ),
            'pos6' => array(
                'hook_name' => 'luxsa/action/after_woocommerce_single_product_summary',
                'priority'  => 10 /* Beside Summary */
            ),
            'pos7' => array(
                'hook_name' => 'luxsa/action/before_wc_tabs',
                'priority'  => 10 /* Before Tabs */
            ),
            'pos8' => array(
                'hook_name' => 'luxsa/action/after_wc_tabs',
                'priority'  => 10 /* Before Tabs */
            ),
            'pos9' => array(
                'hook_name' => 'woocommerce_after_single_product_summary',
                'priority'  => 30 /* After Related */
            ),
            'pos10' => array(
                'hook_name' => 'woocommerce_after_single_product_summary',
                'priority'  => 15 /* After Up-sells */
            ),
            'pos11' => array(
                'hook_name' => 'luxsa/action/before_main',
                'priority'  => 10 /* After Main Wrap */
            ),
            'pos12' => array(
                'hook_name' => 'luxsa/action/after_main',
                'priority'  => 10 /* After Main Wrap */
            )
        );

        if( is_product() && luxsa_string_to_bool(luxsa_get_option('woo_enable_custom_block_single_product'))){
            $blocks = luxsa_get_option('woo_custom_block_single_product');
            if(!empty($blocks) && is_array($blocks)){
                foreach ($blocks as $k => $block){
                    $block_content = !empty($block['content']) ? $block['content'] : '';
                    $block_position = !empty($block['position']) ? $block['position'] : '';

                    if(!empty($block_content) && !empty($block_position) && is_array($position_detect[$block_position]) ){
                        $hooks = $position_detect[$block_position];
                        $hook_name = $hooks['hook_name'];
                        $priority = $hooks['priority'];

                        add_action( $hook_name, function() use( $block, $hook_name, $priority ) {  luxsa_callback_func_to_show_custom_block($block, $hook_name, $priority); }, $priority );
                    }
                }
            }
        }
    }
    add_action('wp_head', 'luxsa_add_custom_block_to_single_product_page');
}

if( !function_exists('luxsa_calculator_free_shipping_thresholds')){
    function luxsa_calculator_free_shipping_thresholds(){
        if( ! luxsa_string_to_bool(luxsa_get_option('freeshipping_thresholds', 'off')) ){
            return;
        }

        if ( WC()->cart->is_empty() ) {
            return;
        }
        // Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts
        $default_zone = new WC_Shipping_Zone( 0 );

        $default_methods = $default_zone->get_shipping_methods();
        foreach ( $default_methods as $key => $value ) {
            if ( $value->id === "free_shipping" ) {
                if ( $value->min_amount > 0 ) {
                    $min_amounts[] = $value->min_amount;
                }
            }
        }
        // Get Free Shipping Methods for all other ZONES & populate array $min_amounts
        $delivery_zones = WC_Shipping_Zones::get_zones();
        foreach ( $delivery_zones as $key => $delivery_zone ) {
            foreach ( $delivery_zone['shipping_methods'] as $key => $value ) {
                if ( $value->id === "free_shipping" ) {
                    if ( $value->min_amount > 0 ) {
                        $min_amounts[] = $value->min_amount;
                    }
                }
            }
        }
        // Find lowest min_amount
        if ( isset( $min_amounts ) ) {
            if ( is_array( $min_amounts ) && $min_amounts ) {
                $min_amount = min( $min_amounts );
                // Get Cart Subtotal inc. Tax excl. Shipping
                $current = WC()->cart->subtotal;
                // If Subtotal < Min Amount Echo Notice
                // and add "Continue Shopping" button
                if ( $current > 0 ) {
                    $percent = round( ( $current / $min_amount ) * 100, 2 );
                    $percent >= 100 ? $percent = '100' : '';
                    if ( $percent < 40 ) {
                        $parse_class = 'first-parse';
                    }
                    elseif ( $percent >= 40 && $percent < 80 ) {
                        $parse_class = 'second-parse';
                    }
                    else {
                        $parse_class = 'final-parse';
                    }
                    $parse_class .= ' free-shipping-required-notice';
                    $text1 = luxsa_get_option('thresholds_text1', esc_html__('[icon]Spend [amount] to get Free Shipping', 'luxsa'));
                    $text2 = luxsa_get_option('thresholds_text2', esc_html__('[icon]Congratulations! You\'ve got free shipping!', 'luxsa'));
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="62" height="45" viewBox="0 0 62 45"><g fill="currentColor" fill-rule="evenodd"><path d="M21 38a2 2 0 1 1-4 0 2 2 0 0 1 4 0m29 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0"></path><path d="M19 33.19A4.816 4.816 0 0 0 14.19 38 4.816 4.816 0 0 0 19 42.81 4.816 4.816 0 0 0 23.81 38 4.816 4.816 0 0 0 19 33.19M19 45c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7"></path><path d="M38 37H24.315v-2.145h11.544V2.145H2.14v32.71h11.544V37H0V0h38zm11-3.81A4.816 4.816 0 0 0 44.19 38 4.816 4.816 0 0 0 49 42.81 4.816 4.816 0 0 0 53.81 38 4.816 4.816 0 0 0 49 33.19M49 45c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7"></path><path d="M62 37h-7.607v-2.154h5.47V22.835l-8.578-12.681H38.137v24.692h5.465V37H36V8h16.415L62 22.17z"></path><path d="M42.147 19.932h10.792l-4.15-5.864h-6.642v5.864zM57 22H40V12h9.924L57 22z"></path></g></svg>';
                    $text1 = str_replace('[icon]', $icon, $text1);
                    $text2 = str_replace('[icon]', $icon, $text2);
                    $added_text='';
                    if ( $current < $min_amount ) {
                        $text1 = str_replace('[amount]', wc_price( $min_amount - $current ), $text1);
                        $added_text .= $text1;
                    } else {
                        $added_text = $text2;
                    }
                    $html = '<div class="' . esc_attr( $parse_class ) . '">';
                    $html .= '<div class="la-loading-bar"><div class="load-percent" style="width:' . esc_attr( $percent ) . '%">';
                    $html .= '</div><span class="label-free-shipping">'.$added_text.'</span></div>';
                    $html .= '</div>';
                    echo ent2ncr( $html );
                }
            }
        }
    }
    add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'luxsa_calculator_free_shipping_thresholds', 5 );
    add_action( 'woocommerce_before_cart_table', 'luxsa_calculator_free_shipping_thresholds', 5 );
}

if(!function_exists('luxsa_wc_add_qty_control_plus')){
    function luxsa_wc_add_qty_control_plus(){
        echo '<span class="qty-plus"><i class="lastudioicon-i-add-2"></i></span>';
    }
}

if(!function_exists('luxsa_wc_add_qty_control_minus')){
    function luxsa_wc_add_qty_control_minus(){
        echo '<span class="qty-minus"><i class="lastudioicon-i-delete-2"></i></span>';
    }
}

add_action('woocommerce_after_quantity_input_field', 'luxsa_wc_add_qty_control_plus');
add_action('woocommerce_before_quantity_input_field', 'luxsa_wc_add_qty_control_minus');

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
if(!function_exists('luxsa_wc_add_category_into_product_loop')){
    function luxsa_wc_add_category_into_product_loop(){
        global $product;
        echo wc_get_product_category_list( $product->get_id(), '', '<div class="product_item--category-link">', '</div>' );
    }
}
add_action('woocommerce_shop_loop_item_title', 'luxsa_wc_add_category_into_product_loop', 5);

if(!function_exists('luxsa_wc_change_product_related_count')){
    function luxsa_wc_change_product_related_count( $args ) {
        $product_cols   = luxsa_get_responsive_columns('related_products_columns');
        if( $product_cols['xxl'] > 0 ) {
            $args['posts_per_page'] = $product_cols['xxl'];
        }
        return $args;
    }
}
add_filter('luxsa_wc_change_product_related_count', 'luxsa_wc_change_product_related_count');

if(!function_exists('luxsa_wc_product_bulk_edit_start')){
    function luxsa_wc_product_bulk_edit_start(){
        if(!class_exists('LASF', false)){
            return;
        }
        echo '<div class="lasf-bulk-group lasf-show-all"><div class="lasf-section lasf-onload">';
        LASF::field(array(
            'id'        => 'enable_custom_badge',
            'type'      => 'button_set',
            'default'   => 'nochange',
            'title'     => esc_html_x('Enable Custom Badges', 'admin-view', 'luxsa'),
            'options'   => array(
                'nochange'          => esc_html_x('No Change', 'admin-view', 'luxsa'),
                'addnew'            => esc_html_x('Override', 'admin-view', 'luxsa'),
                'removeall'         => esc_html_x('Remove Existing Data', 'admin-view', 'luxsa')
            )
        ), '', 'la_custom_badge');
        LASF::field(array(
            'id'                => 'product_badges',
            'type'              => 'group',
            'title'             => esc_html_x('Custom Badges', 'admin-view', 'luxsa'),
            'button_title'      => esc_html_x('Add Badge','admin-view', 'luxsa'),
            'max'               => 3,
            'dependency'        => array('enable_custom_badge', '==', 'addnew'),
            'fields'            => array(
                array(
                    'id'            => 'text',
                    'type'          => 'text',
                    'default'       => 'New',
                    'title'         => esc_html_x('Badge Text', 'admin-view', 'luxsa')
                ),
                array(
                    'id'            => 'bg',
                    'type'          => 'color',
                    'default'       => '',
                    'title'         => esc_html_x('Custom Badge Background Color', 'admin-view', 'luxsa')
                ),
                array(
                    'id'            => 'color',
                    'type'          => 'color',
                    'default'       => '',
                    'title'         => esc_html_x('Custom Badge Text Color', 'admin-view', 'luxsa')
                ),
                array(
                    'id'            => 'el_class',
                    'type'          => 'text',
                    'default'       => '',
                    'title'         => esc_html_x('Extra CSS class for badge', 'admin-view', 'luxsa')
                )
            )
        ), '', 'la_custom_badge');
        echo '</div></div>';
?>
        <?php
    }
}
add_action( 'woocommerce_product_bulk_edit_start', 'luxsa_wc_product_bulk_edit_start' );

if(!function_exists('luxsa_wc_product_bulk_edit_save')){
    function luxsa_wc_product_bulk_edit_save( $product ){
        $product_id = $product->get_id();
        if ( isset( $_REQUEST['la_custom_badge'], $_REQUEST['la_custom_badge']['enable_custom_badge'] ) ) {
            $old_data = luxsa_get_post_meta($product_id);
            $enable = $_REQUEST['la_custom_badge']['enable_custom_badge'];
            if( 'removeall' == $enable ) {
                $old_data['product_badges'] = array();
                update_post_meta( $product_id, '_luxsa_post_options', $old_data );
            }
            elseif( 'addnew' == $enable && !empty($_REQUEST['la_custom_badge']['product_badges'])) {
                $product_badges = $_REQUEST['la_custom_badge']['product_badges'];
                $old_data['product_badges'] = $product_badges;
                update_post_meta( $product_id, '_luxsa_post_options', $old_data );
            }
        }
    }
}
add_action( 'woocommerce_product_bulk_edit_save', 'luxsa_wc_product_bulk_edit_save' );

if(!function_exists('luxsa_wc_swatches_attribute_change_type_default')){
    function luxsa_wc_swatches_attribute_change_type_default( $value, $product_id, $is_tax, $product ){
        if($is_tax){
            $value = 'term_options';
        }
        return $value;
    }
}
add_filter( 'LaStudio/swatches/attribute/default_type', 'luxsa_wc_swatches_attribute_change_type_default', 10, 4);

if(!function_exists('luxsa_wc_disable_redirect_single_search_result')){
    function luxsa_wc_disable_redirect_single_search_result( $value ){
        if(isset($_GET['la_doing_ajax'])){
            $value = false;
        }
        return $value;
    }
}
add_filter('woocommerce_redirect_single_search_result', 'luxsa_wc_disable_redirect_single_search_result');

if(!function_exists('luxsa_wc_render_variation_templates')){
    function luxsa_wc_render_variation_templates(){
        wc_get_template('single-product/add-to-cart/variation.php');
    }
}
add_action('wp_footer', 'luxsa_wc_render_variation_templates');

add_filter( 'woocommerce_post_class', function ( $classes, $product ){
    if($product->is_type( 'variable' )){
        if($product->child_is_in_stock()){
            $classes[] = 'child-instock';
        }
    }
    return $classes;
}, 20, 2 );

add_action('woocommerce_before_subcategory_title', function (){
    echo '<span class="woocommerce-loop-category__image">';
}, 0);

add_action('woocommerce_before_subcategory_title', function (){
    echo '</span>';
}, 20);

add_filter('LaStudioElement/products/box_selector', function (){
    return '{{WRAPPER}} ul.products .product_item--inner';
});
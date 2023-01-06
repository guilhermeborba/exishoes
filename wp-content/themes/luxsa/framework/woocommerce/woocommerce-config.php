<?php
/**
 * Perform all main WooCommerce configurations for this theme
 *
 * @package Luxsa WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!class_exists('Luxsa_WooCommerce_Config')){

    class Luxsa_WooCommerce_Config {

        /**
         * Main Class Constructor
         *
         * @since 1.0.0
         */
        public function __construct() {

            // Include helper functions
            require_once get_theme_file_path('/framework/woocommerce/woocommerce-helpers.php');
            require_once get_theme_file_path('/framework/woocommerce/woocommerce-compare.php');
            require_once get_theme_file_path('/framework/woocommerce/woocommerce-wishlist.php');


            add_filter('luxsa/get_site_layout', array( $this, 'set_site_layout') );
            add_filter('luxsa/filter/sidebar_primary_name', array( $this, 'set_sidebar_for_shop'), 20 );

            add_action('init', array( $this, 'set_cookie_default' ), 2 );
            add_action('init', array( $this, 'custom_handling_empty_cart' ), 1 );
            add_filter('loop_shop_per_page', array( $this, 'change_per_page_default'), 10 );

            add_action( 'wp_head', array( $this, 'hook_for_after_init' ) );
            add_filter( 'body_class', array( $this, 'body_classes' ) );

            if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
                add_action( 'init', array( $this, 'register_wc_hooks_for_elementor' ), 5 );
            }

            // Remove WooCommerce default style
            add_filter( 'woocommerce_enqueue_styles', array($this, 'remove_woo_scripts') );

            // Load theme CSS
            add_action( 'wp_enqueue_scripts', array( $this, 'theme_css' ), 20 );

            // Load theme js
            add_action( 'wp_enqueue_scripts', array( $this, 'theme_js' ), 20 );

            // register sidebar widget areas
            add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

            add_action( 'woocommerce_add_to_cart_fragments', array( $this, 'modify_ajax_cart_fragments' ) );

            /**
             * Hooks in plugins
             */
            add_filter('woocommerce_show_page_title', '__return_false');
            add_action('init', array( $this, 'disable_plugin_hooks'));
            add_action('woocommerce_share', array( $this, 'woocommerce_share' ));
            add_filter('template_include', array( $this, 'load_quickview_template'), 20 );

            /**
             * Hooks in plugins
             * WC_Vendors
             */
            if(class_exists('WC_Vendors', false)){
                // Add sold by to product loop before add to cart
                if ( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {
                    remove_action( 'woocommerce_after_shop_loop_item', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 9 );
                    add_action( 'woocommerce_shop_loop_item_title', array('WCV_Vendor_Shop', 'template_loop_sold_by'), 10 );
                }
            }

            /**
             * Hooks in plugins
             * Dokan
             */

            if(function_exists('dokan')){
                add_filter('is_woocommerce', array( $this, 'filter_is_woocommerce_for_dokan') , 99);
            }

            /**
             * Remove default wrappers and add new ones
             */
            remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
            remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
            remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
            add_action( 'woocommerce_before_main_content', array( $this, 'content_wrapper' ), 10 );
            add_action( 'woocommerce_after_main_content', array( $this, 'content_wrapper_end' ), 10 );


            add_filter('subcategory_archive_thumbnail_size', array( $this, 'modify_product_thumbnail_size') );
            add_filter('single_product_archive_thumbnail_size', array( $this, 'modify_product_thumbnail_size') );

            /**
             * For Shop Page & Taxonomies
             */

            add_action('product_cat_class', array( $this, 'add_class_to_product_category_item' ), 10, 3 );
            add_filter('woocommerce_post_class', array( $this, 'add_class_to_product_loop'), 30, 2 );

            add_action('woocommerce_before_shop_loop', array( $this, 'render_toolbar') );
            remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
            remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
            remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


            add_filter('woocommerce_loop_add_to_cart_args', array( $this, 'woocommerce_loop_add_to_cart_args'), 10, 2 );

            add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 1 );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'add_badge_stock_into_loop' ), 10 );
            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'add_product_thumbnails_to_loop' ), 15 );
            add_action('woocommerce_before_shop_loop_item_title', function(){ echo '<div class="item--overlay"></div>'; }, 20 );
            add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 30 );

            add_action('woocommerce_after_shop_loop_item_title', array($this, 'render_attribute_in_list'), 15);
            add_action('woocommerce_after_shop_loop_item_title', array( $this, 'shop_loop_item_excerpt' ), 11 );


            add_action('luxsa/action/add_count_up_timer_in_product_listing', array( $this, 'add_count_up_timer_in_product_listing' ), 1 );

            add_action('luxsa/action/shop_loop_item_action_top', function(){ echo '<div class="wrap-addto">'; }, 5 );
            add_action('luxsa/action/shop_loop_item_action_top', array( $this, 'add_cart_btn' ), 10 );
            add_action('luxsa/action/shop_loop_item_action_top', array( $this, 'add_quick_view_btn' ), 20 );
            add_action('luxsa/action/shop_loop_item_action_top', array( $this, 'add_wishlist_btn' ), 30 );
            add_action('luxsa/action/shop_loop_item_action_top', array( $this, 'add_compare_btn' ), 40 );
            add_action('luxsa/action/shop_loop_item_action_top', function(){ echo '</div>'; }, 50 );

            add_action('luxsa/action/shop_loop_item_action', function(){ echo '<div class="wrap-addto">'; }, 5 );
            add_action('luxsa/action/shop_loop_item_action', array( $this, 'add_cart_btn' ), 10 );
            add_action('luxsa/action/shop_loop_item_action', array( $this, 'add_quick_view_btn' ), 20 );
            add_action('luxsa/action/shop_loop_item_action', array( $this, 'add_wishlist_btn' ), 30 );
            add_action('luxsa/action/shop_loop_item_action', array( $this, 'add_compare_btn' ), 40 );
            add_action('luxsa/action/shop_loop_item_action', function(){ echo '</div>'; }, 50 );


            /**
             * For details page
             */
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

            add_filter('woocommerce_gallery_image_size', function(){ return 'shop_single'; } );
            add_action('woocommerce_before_add_to_cart_button', function(){ echo '<div class="wrap-cart-cta">'; }, 100);
            add_action('woocommerce_after_add_to_cart_button', function(){ echo '</div>'; }, 0);
            add_action('woocommerce_after_add_to_cart_button', array( $this , 'add_hidden_button_to_to_cart_form' ) );

            add_action('woocommerce_after_add_to_cart_button', array( $this , 'add_wishlist_btn' ), 50 );
            add_action('woocommerce_after_add_to_cart_button', array( $this , 'add_compare_btn' ), 55 );


            add_filter('woocommerce_product_tabs', array( $this, 'add_custom_tabs'));
	        add_action('woocommerce_single_product_summary', function(){ echo '<div class="summary-inner">'; }, 0 );
            add_action('woocommerce_single_product_summary', array( $this, 'add_next_prev_product_to_single' ), 4);
	        add_action('woocommerce_single_product_summary', function(){ echo '</div>'; }, 54 );
            add_action('woocommerce_single_product_summary', array( $this, 'add_count_up_timer_in_product_listing' ), 25 );

            add_filter('woocommerce_single_product_image_thumbnail_html', array( $this, 'add_bg_overlay_into_product_image_thumbnail' ), 10, 2 );

            add_action('luxsa/action/after_primary', array( $this, 'move_after_product_summary_to_bottom') );



            /**
             * For Cart
             */
            remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10);

            /**
             * Catalog Mode
             */
            if( luxsa_get_option('catalog_mode', 'off') == 'on' ){
                // In Loop
                add_filter( 'woocommerce_loop_add_to_cart_link', '__return_empty_string', 10 );
                // In Single
                remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);
                // In Page
                add_action( 'wp', array( $this, 'set_page_when_active_catalog_mode' ) );

                if( luxsa_get_option('catalog_mode_price', 'off') == 'on' ){
                    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
                    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
                    add_filter('woocommerce_catalog_orderby', array( $this, 'remove_sortby_price_in_toolbar_when_active_catalog' ));
                    add_filter('woocommerce_default_catalog_orderby_options', array( $this, 'remove_sortby_price_in_toolbar_when_active_catalog' ));
                }
            }
        }

        public function hook_for_after_init(){
            /**
             * Disable product title
             */
            if( luxsa_string_to_bool( luxsa_get_option('product_single_hide_product_title', 'no') ) ){
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
            }
            /**
             * Moving tabs to summary
             */
            if( !luxsa_string_to_bool( luxsa_get_option('move_woo_tabs_to_bottom', 'no')) && empty($_GET['product_quickview'])){
                add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 55);
            }
            /**
             * Disable cross sell
             */
            if( luxsa_string_to_bool(luxsa_get_option('crosssell_products', 'off')) ){
                add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display', 30);
            }
        }

        public function register_sidebars(){
            $heading = 'h4';
            $heading = apply_filters( 'luxsa/filter/sidebar_heading', $heading );

            register_sidebar( array(
                'name'			=> esc_html_x( 'Shop Sidebar Filter', 'admin-view',  'luxsa' ),
                'id'            => 'sidebar-shop-filter',
                'before_widget'	=> '<div id="%1$s" class="sidebar-box widget %2$s">',
                'after_widget'	=> '</div>',
                'before_title'	=> '<'. $heading .' class="widget-title"><span>',
                'after_title'	=> '</span></'. $heading .'>',
            ) );
        }

        /**
         * Support elementor
         */
        public function register_wc_hooks_for_elementor(){
            add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
            add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        }

        public function modify_product_thumbnail_size($size){
            $image_size = luxsa_get_wc_loop_prop('image_size');
            if(!empty($image_size)) {
                return $image_size;
            }
            return $size;
        }

        /**
         * Removes WooCommerce scripts.
         *
         * @access public
         * @since 1.0
         * @param array $scripts The WooCommerce scripts.
         * @return array
         */
        public function remove_woo_scripts($scripts) {
            if (isset($scripts['woocommerce-layout'])) {
                unset($scripts['woocommerce-layout']);
            }
            if (isset($scripts['woocommerce-smallscreen'])) {
                unset($scripts['woocommerce-smallscreen']);
            }
            if (isset($scripts['woocommerce-general'])) {
                unset($scripts['woocommerce-general']);
            }
            return $scripts;
        }

        public function theme_css(){

        }

        public function theme_js(){
            $theme_version = defined('WP_DEBUG') && WP_DEBUG ? time() : LUXSA_THEME_VERSION;
            $ext = apply_filters('luxsa/use_minify_js_file', false) || ( defined('WP_DEBUG') && WP_DEBUG ) ? '' : '.min';

            wp_register_script('luxsa-woo', get_theme_file_uri( '/assets/js/lib/woocommerce'. $ext .'.js' ), array('jquery'), $theme_version, true);
            wp_register_script('luxsa-product-gallery', get_theme_file_uri( '/assets/js/lib/product-gallery'. $ext .'.js' ), array('jquery'), $theme_version, true);
            wp_register_script('luxsa-product-swatches', get_theme_file_uri( '/assets/js/lib/swatches'. $ext .'.js' ), array('jquery'), $theme_version, true);
        }

        /**
         * Content wrapper.
         */
        public function content_wrapper() {
            get_template_part( 'woocommerce/wc-content-wrapper' );
        }

        /**
         * Content wrapper end.
         */
        public function content_wrapper_end() {
            get_template_part( 'woocommerce/wc-content-wrapper-end' );
        }

        /**
         * New Shop Toolbars
         */
        public function render_toolbar(){
            get_template_part( 'woocommerce/loop/toolbar' );
        }

        /**
         * Override the Woo site layout
         * @param $layout
         * @return string
         */
        public function set_site_layout( $layout ) {
            if(is_checkout() || is_cart()){
                $layout = 'col-1c';
            }
            if (!is_user_logged_in() && is_account_page()) {
                $layout = 'col-1c';
            }
            return $layout;
        }

        /**
         *
         * Override the sidebar for shop
         *
         * @param $sidebar
         * @return mixed
         */

        public function set_sidebar_for_shop( $sidebar ) {

            if( is_woocommerce() ){

                if( is_archive() ){

                    $sidebar = luxsa_get_option('shop_sidebar', $sidebar);

                    if(luxsa_get_option('shop_global_sidebar', false)){
                        /*
                         * Return global sidebar if option will be enable
                         * We don't need more checking in context
                         */
                        return $sidebar;
                    }

                    if( is_shop() ){
                        if( ($single_sidebar = luxsa_get_post_meta( wc_get_page_id( 'shop' ), 'sidebar')) && !empty($single_sidebar) ){
                            $sidebar = $single_sidebar;
                        }
                    }
                    if( is_product_taxonomy() ){
                        if( ($tax_sidebar = luxsa_get_term_meta( get_queried_object_id(), 'sidebar')) && !empty($tax_sidebar) ){
                            $sidebar = $tax_sidebar;
                        }
                    }
                }

                elseif( is_product() ){
                    $sidebar = luxsa_get_option('products_sidebar', $sidebar);

                    if(luxsa_get_option('products_global_sidebar', false)){
                        /*
                         * Return global sidebar if option will be enable
                         * We don't need more checking in context
                         */
                        return $sidebar;
                    }
                    if( ($single_sidebar = luxsa_get_post_meta( get_the_ID(), 'sidebar')) && !empty($single_sidebar) ){
                        $sidebar = $single_sidebar;
                    }
                }

            }

            return $sidebar;
        }


        public function woocommerce_loop_add_to_cart_args( $args, $product) {
            if(isset($args['attributes'])){
                $args['attributes']['data-product_title'] = $product->get_title();
            }
            if(isset($args['class'])){
                $args['class'] = $args['class'] . ($product->is_purchasable() && $product->is_in_stock() ? '' : ' add_to_cart_button');
            }
            return $args;
        }

        public function add_badge_stock_into_loop(){
            global $product;
            $availability = $product->get_availability();
            if(!empty($availability['class']) && $availability['class'] == 'out-of-stock' && !empty($availability['availability'])){
                printf('<span class="la-custom-badge badge-out-of-stock">%s</span>', esc_html($availability['availability']));
            }
        }

        public function add_product_thumbnails_to_loop(){
            global $product;
            $with_second_image = false;
            if( 'on' == luxsa_get_option('woocommerce_enable_crossfade_effect') ){
                $with_second_image = true;
            }
            $disable_second_image = luxsa_get_wc_loop_prop('disable_alt_image');
            if($disable_second_image){
                $with_second_image = false;
            }

            $shop_catalog_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );

            $output = '<div class="figure__object_fit p_img-first">'.woocommerce_get_product_thumbnail( $shop_catalog_size ).'</div>';

            if($with_second_image){
                $gallery_image_ids = $product->get_gallery_image_ids();
                if(!empty($gallery_image_ids[0])){
                    $image_url = wp_get_attachment_image_url($gallery_image_ids[0], $shop_catalog_size);
                    $output .= '<div class="figure__object_fit p_img-second">'. sprintf('<div class="la-lazyload-image" data-background-image="%s"></div>', esc_url( $image_url )) .'</div>';
                }
            }
            echo luxsa_render_variable( $output );

        }

        public function render_attribute_in_list(){
            if(class_exists('LaStudio_Swatch', false)){
                global $product;
                $swatches_instance = new LaStudio_Swatch();
                $swatches_instance->render_attribute_in_product_list_loop($product);
            }
        }

        public function shop_loop_item_excerpt(){
            $is_main_loop = luxsa_get_wc_loop_prop('is_main_loop', false);
            $loop_layout = luxsa_get_wc_loop_prop('loop_layout', 'grid');
            $loop_style = luxsa_get_wc_loop_prop('loop_style', '1');
            if( $is_main_loop || $loop_layout == 'list' || ( $loop_layout == 'grid' && ( $loop_style == 3 || $loop_style == 4) )  ) {
                echo '<div class="item--excerpt">';
                the_excerpt();
                echo '</div>';
            }
        }

        public function add_count_up_timer_in_product_listing(){
            global $product;
            if($product->is_on_sale()){
                $sale_price_dates_to = $product->get_date_on_sale_to() && ( $date = $product->get_date_on_sale_to()->getOffsetTimestamp() ) ? $date : '';
                if(!empty($sale_price_dates_to)){

                    $now = current_time('timestamp');

                    ?>
                    <div class="lastudio-countdown--inprod elementor-lastudio-countdown-timer lastudio-elements js-el" data-la_component="CountDownTimer">
                        <div class="lastudio-countdown-timer" data-ct="<?php echo esc_attr($now); ?>" data-due-date="<?php echo esc_attr($sale_price_dates_to); ?>">
                            <?php if($sale_price_dates_to - $now > 86400): ?><div class="lastudio-countdown-timer__item item-days">
                                <div class="lastudio-countdown-timer__item-value" data-value="days"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                                <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Days', 'luxsa') ?></div></div><div class="lastudio-countdown-timer__separator">:</div><?php endif; ?>
                            <div class="lastudio-countdown-timer__item item-hours">
                                <div class="lastudio-countdown-timer__item-value" data-value="hours"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                                <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Hours', 'luxsa');?></div></div><div class="lastudio-countdown-timer__separator">:</div>
                            <div class="lastudio-countdown-timer__item item-minutes">
                                <div class="lastudio-countdown-timer__item-value" data-value="minutes"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                                <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Mins', 'luxsa'); ?></div></div><div class="lastudio-countdown-timer__separator">:</div>
                            <div class="lastudio-countdown-timer__item item-seconds">
                                <div class="lastudio-countdown-timer__item-value" data-value="seconds"><span class="lastudio-countdown-timer__digit">0</span><span class="lastudio-countdown-timer__digit">0</span></div>
                                <div class="lastudio-countdown-timer__item-label"><?php esc_html_e('Secs', 'luxsa'); ?></div></div><div class="lastudio-countdown-timer__separator"><?php esc_html_e('left', 'luxsa'); ?></div>
                        </div>
                    </div>
                    <?php
                    $product = wc_get_product();
                    $stock_available = ($stock = $product->get_stock_quantity()) ? $stock : 0;
                    $stock_sold = ($total_sales = $product->get_total_sales()) ? $total_sales : 0;
                    $percentage = ($stock_available > 0 ? round($stock_sold / $stock_available * 100) : 0);
                    if($stock_available > 0):
                    ?>
                    <div class="product_item--deals-info">
                        <p class="product-available"><?php echo esc_html__('Available', 'luxsa'); ?><span><?php echo esc_html($stock_available); ?></span></p>
                        <p class="product-sold"><?php echo esc_html__('Sold', 'luxsa'); ?><span><?php echo esc_html($stock_sold); ?></span></p>
                        <div class="progress">
                            <div class="progress-bar main-color" role="progressbar" style="width: <?php echo esc_attr($percentage); ?>%" aria-valuenow="<?php echo esc_attr($percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <?php
                    endif;
                }

            }
        }

        public function add_quick_view_btn(){
            if( 'on' == luxsa_get_option('woocommerce_show_quickview_btn', 'off') ){
                global $product;
                printf(
                    '<a class="%s" href="%s" data-href="%s" title="%s"><span class="labtn-icon labtn-icon-quickview"></span><span class="labtn-text">%s</span></a>',
                    'quickview button la-quickview-button',
                    esc_url(get_the_permalink($product->get_id())),
                    esc_url(add_query_arg('product_quickview', $product->get_id(), get_the_permalink($product->get_id()))),
                    esc_attr_x('Quick View', 'front-view', 'luxsa'),
                    esc_attr_x('Quick View', 'front-view', 'luxsa')
                );
            }
        }

        public function add_cart_btn(){
            if( luxsa_get_option('catalog_mode', 'off') != 'on' && luxsa_get_option('woocommerce_show_addcart_btn', 'on') == 'on' ) {
                woocommerce_template_loop_add_to_cart();
            }
        }

        public function add_compare_btn(){
            global $yith_woocompare, $product;
            if( luxsa_get_option('woocommerce_show_compare_btn', 'off') == 'on' ) {
                if ( !empty($yith_woocompare->obj) ) {

                    $action_add = 'yith-woocompare-add-product';

                    $css_class = 'add_compare button';

                    if( $yith_woocompare->obj instanceof YITH_Woocompare_Frontend ){
                        $action_add = $yith_woocompare->obj->action_add;
                        if(!empty($yith_woocompare->obj->products_list) && in_array($product->get_id(), $yith_woocompare->obj->products_list)){
                            $css_class .= ' added';
                        }
                    }
                    $url_args = array('action' => $action_add, 'id' => $product->get_id());
                    $url = apply_filters('yith_woocompare_add_product_url', wp_nonce_url(add_query_arg($url_args), $action_add));

                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s"><span class="labtn-icon labtn-icon-compare"></span><span class="labtn-text">%s</span></a>',
                        esc_attr($css_class),
                        esc_url($url),
                        esc_attr_x('Add to compare','front-view', 'luxsa'),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr_x('Add to compare','front-view', 'luxsa')
                    );
                }
                else{
                    $css_class = 'add_compare button la-core-compare';
                    $url = '#';
                    $text = esc_html_x('Add to compare','front-view', 'luxsa');
                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s"><span class="labtn-icon labtn-icon-compare"></span><span class="labtn-text">%s</span></a>',
                        esc_attr($css_class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }
            }
        }

        public function add_wishlist_btn(){

            if(luxsa_get_option('woocommerce_show_wishlist_btn', 'off') == 'on'){
                global $product;
                if (function_exists('YITH_WCWL')) {
                    $default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists(array('is_default' => true)) : false;
                    if (!empty($default_wishlists)) {
                        $default_wishlist = $default_wishlists[0]['ID'];
                    }
                    else {
                        $default_wishlist = false;
                    }

                    if (YITH_WCWL()->is_product_in_wishlist($product->get_id(), $default_wishlist)) {
                        $text = esc_html_x('View Wishlist', 'front-view', 'luxsa');
                        $class = 'add_wishlist la-yith-wishlist button added';
                        $url = YITH_WCWL()->get_wishlist_url('');
                    }
                    else {
                        $text = esc_html_x('Add to Wishlist', 'front-view', 'luxsa');
                        $class = 'add_wishlist la-yith-wishlist button';
                        $url = add_query_arg('add_to_wishlist', $product->get_id(), YITH_WCWL()->get_wishlist_url(''));
                    }

                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s"><span class="labtn-icon labtn-icon-wishlist"></span><span class="labtn-text">%s</span></a>',
                        esc_attr($class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }

                elseif(class_exists('TInvWL_Public_AddToWishlist', false)){
                    $wishlist = TInvWL_Public_AddToWishlist::instance();
                    $user_wishlist = $wishlist->user_wishlist($product);
                    if(isset($user_wishlist[0], $user_wishlist[0]['in']) && $user_wishlist[0]['in']){
                        $class = 'add_wishlist button la-ti-wishlist added';
                        $url = tinv_url_wishlist_default();
                        $text = esc_html_x('View Wishlist', 'front-view', 'luxsa');
                    }
                    else{
                        $class = 'add_wishlist button la-ti-wishlist';
                        $url = '#';
                        $text = esc_html_x('Add to wishlist', 'front-view', 'luxsa');
                    }
                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s"><span class="labtn-icon labtn-icon-wishlist"></span><span class="labtn-text">%s</span></a>',
                        esc_attr($class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }

                else{

                    if(Luxsa_WooCommerce_Wishlist::is_product_in_wishlist($product->get_id())){
                        $class = 'add_wishlist button la-core-wishlist added';
                        $url = luxsa_get_wishlist_url();
                        $text = esc_html_x('View Wishlist', 'front-view', 'luxsa');
                    }
                    else{
                        $class = 'add_wishlist button la-core-wishlist';
                        $url = '#';
                        $text = esc_html_x('Add to wishlist', 'front-view', 'luxsa');
                    }

                    printf(
                        '<a class="%s" href="%s" title="%s" rel="nofollow" data-product_title="%s" data-product_id="%s"><span class="labtn-icon labtn-icon-wishlist"></span><span class="labtn-text">%s</span></a>',
                        esc_attr($class),
                        esc_url($url),
                        esc_attr($text),
                        esc_attr($product->get_title()),
                        esc_attr($product->get_id()),
                        esc_attr($text)
                    );
                }
            }
        }

        public function add_class_to_product_category_item( $classes, $class, $category ){
            $classes[] = 'grid-item';
            return $classes;
        }

        public function add_class_to_product_loop( $classes, $product ) {
            $with_second_image = false;
            if( 'on' == luxsa_get_option('woocommerce_enable_crossfade_effect') ){
                $with_second_image = true;
            }
            $disable_second_image = luxsa_get_wc_loop_prop('disable_alt_image');
            if($disable_second_image){
                $with_second_image = false;
            }
            if($with_second_image){
                $classes[] = 'thumb-has-effect';
            }
            else{
                $classes[] = 'thumb-no-effect';
            }

            $enable_rating = luxsa_get_option('woocommerce_show_rating_on_catalog', 'off');
            if(get_option( 'woocommerce_enable_review_rating' ) === 'no'){
                $enable_rating = 'off';
            }
            $classes[] = 'prod-rating-' . esc_attr(luxsa_get_option('woocommerce_show_rating_on_catalog', 'off'));

            if(luxsa_string_to_bool( $enable_rating )){
                if($product->get_average_rating() > 0){
                    $classes[] = 'prod-has-rating';
                }
                else{
                    $classes[] = 'prod-no-rating';
                }
            }

            return $classes;
        }


        public function custom_handling_empty_cart(){
            if (isset($_REQUEST['clear-cart'])) {
                WC()->cart->empty_cart();
            }
        }

        public function change_per_page_default($cols){
            $per_page_array = luxsa_woo_get_product_per_page_array();
            $per_page = luxsa_woo_get_product_per_page();
            if(!empty($per_page_array) && ( in_array($per_page, $per_page_array) || count($per_page_array) == 1  )){
                $cols = $per_page;
            }
            else{
                $cols = $per_page;
            }
            return $cols;
        }

        public function set_cookie_default(){
            if (isset($_GET['per_page']) && $per_page = $_GET['per_page']) {
                add_filter('luxsa/filter/get_product_per_page', array( $this, 'get_parameter_per_page'));
            }
        }

        public function get_parameter_per_page($per_page) {
            if (isset($_GET['per_page']) && ($_per_page = $_GET['per_page'])) {
                $param_allow = luxsa_woo_get_product_per_page_array();
                if(!empty($param_allow) && in_array($_per_page, $param_allow)){
                    $per_page = $_per_page;
                }
            }
            return $per_page;
        }

        public function disable_plugin_hooks() {
            global $yith_woocompare;
            if(function_exists('YITH_WCWL_Init')){
                $yith_wcwl_obj = YITH_WCWL_Init();
                remove_action('wp_head', array($yith_wcwl_obj, 'add_button'));
            }
            if( !empty($yith_woocompare->obj) && ($yith_woocompare->obj instanceof YITH_Woocompare_Frontend ) ){
                remove_action('woocommerce_single_product_summary', array($yith_woocompare->obj, 'add_compare_link'), 35);
                remove_action('woocommerce_after_shop_loop_item', array($yith_woocompare->obj, 'add_compare_link'), 20);
            }
        }

        public function woocommerce_share(){
            if(luxsa_get_option('product_sharing') == 'on'){
                $post_link = get_permalink();
                $post_title = get_the_title();
                $image = '';
                if(has_post_thumbnail()){
                    $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                }
                echo '<div class="product-share-box">';
                echo sprintf( '<label>%s</label>', esc_html_x('Share:', 'front-end', 'luxsa') );
                luxsa_social_sharing($post_link,$post_title,$image);
                echo '</div>';
            }
        }

        public function load_quickview_template( $template ){
            if(is_singular('product') && isset($_GET['product_quickview'])){
                $file     = locate_template( array(
                    'woocommerce/single-quickview.php'
                ) );
                if($file){
                    return $file;
                }
            }
            return $template;
        }

        public function filter_is_woocommerce_for_dokan( $boolean ) {

            if(function_exists('dokan_is_store_page') && dokan_is_store_page()){
                $boolean = true;
            }

            return $boolean;
        }

        public function modify_ajax_cart_fragments( $fragments ){
            $fragments['span.la-cart-count'] = sprintf('<span class="header-cart-count-icon component-target-badge la-cart-count">%s</span>', WC()->cart->get_cart_contents_count());
            $text = '<span class="la-cart-text">'. esc_html_x('%s items','front-view', 'luxsa') .'</span>';
            $fragments['span.la-cart-text'] = sprintf($text, WC()->cart->get_cart_contents_count());
            $fragments['span.la-cart-total-price'] = sprintf('<span class="la-cart-total-price">%s</span>', WC()->cart->get_cart_total());
            return $fragments;
        }

        public function add_hidden_button_to_to_cart_form(){
            global $product;
            if($product->is_type('simple')){
                echo '<input type="hidden" name="add-to-cart" value="'.esc_attr($product->get_id()).'"/>';
            }
        }

        public function add_custom_tabs( $tabs ){

            if(luxsa_string_to_bool(luxsa_get_option('woo_enable_custom_tab'))){
                $custom_tabs = luxsa_get_option('woo_custom_tabs');
                if(!empty($custom_tabs) && is_array($custom_tabs)){
                    foreach ($custom_tabs as $k => $custom_tab){
                        if(!empty($custom_tab['title']) && !empty($custom_tab['content'])){
                            $tabs['lasf_tab_' . $k] = array(
                                'title' => esc_html($custom_tab['title']),
                                'priority' => 50 + ($k * 5),
                                'custom_content' => $custom_tab['content'],
                                'el_class'  => isset($custom_tab['el_class']) ? $custom_tab['el_class'] : '',
                                'callback' => array( $this, 'callback_custom_tab_content')
                            );
                        }
                    }
                }
            }

            return $tabs;
        }

        public function callback_custom_tab_content( $tab_key, $tab_instance ){
            if(!empty($tab_instance['custom_content'])){
                echo wp_kses_post( luxsa_transfer_text_to_format($tab_instance['custom_content'], true) );
            }
        }

        public function add_bg_overlay_into_product_image_thumbnail( $html, $attachment_id ){
            if (preg_match('~<img.*?data-?src=["\']+(.*?)["\']+~', $html, $matches)) {
                $overlay = '<span class="g-overlay" style="background-image: url('. esc_url($matches[1]) .')"></span>';
                $html = str_replace('<img', $overlay. '<img data-lanolazy="true" ', $html);
            }
            return $html;
        }

        public function add_next_prev_product_to_single(){
            echo '<div class="product-nextprev">';
            $prev = get_previous_post(false,'','product_cat');
            $tpl = '<a href="%1$s" title="%2$s"%4$s>%3$s</a>';
            $qv_tpl = '';
            if(!empty($prev) && isset($prev->ID)){

                $prev_link = get_the_permalink($prev->ID);
                if(isset($_GET['product_quickview'])){
                    $qv_tpl = sprintf('data-href="%1$s" class="la-quickview-button"', add_query_arg('product_quickview', $prev->ID, $prev_link));
                }
                echo sprintf(
                    $tpl,
                    $prev_link,
                    esc_attr(get_the_title($prev->ID)),
                    is_rtl() ? '<i class="lastudioicon-arrow-right"></i>' : '<i class="lastudioicon-arrow-left"></i>',
                    $qv_tpl
                );
            }
            $next = get_next_post(false,'','product_cat');
            if(!empty($next) && isset($next->ID)){
                $next_link = get_the_permalink($next->ID);
                if(isset($_GET['product_quickview'])){
                    $qv_tpl = sprintf('data-href="%1$s" class="la-quickview-button"', add_query_arg('product_quickview', $next->ID, $next_link));
                }
                echo sprintf(
                    $tpl,
                    $next_link,
                    esc_attr(get_the_title($next->ID)),
                    is_rtl() ? '<i class="lastudioicon-arrow-left"></i>' : '<i class="lastudioicon-arrow-right"></i>',
                    $qv_tpl
                );
            }
            echo '</div>';
            echo '<div class="clearfix"></div>';
        }

        public function move_after_product_summary_to_bottom(){
            if(is_product()){
                get_template_part( 'woocommerce/single-product/after-single-product-summary' );
            }
        }

        public function body_classes( $classes ){
            if(is_product()){
                $product_design = luxsa_get_option('woocommerce_product_page_design', 1);
                $woo_tabs_top_style = luxsa_get_option('woo_tabs_top_style', 'horizontal');
                $classes[] = 'la-psingle-' . $product_design;
                $classes[] = 'la-psingle-tab-' . $woo_tabs_top_style;
                if( luxsa_string_to_bool(luxsa_get_option('move_woo_tabs_to_bottom', 'no')) ){
                    $classes[] = 'la-psingle-tabtop-no';
                }
                else{
                    $classes[] = 'la-psingle-tabtop-yes';
                }
            }
            return $classes;
        }

        /*
         * Catalog Mode
         */
        public function set_page_when_active_catalog_mode(){
            wp_reset_postdata();
            if (is_cart() || is_checkout()) {
                wp_redirect(wc_get_page_permalink('shop'));
                exit;
            }
        }
        public function remove_sortby_price_in_toolbar_when_active_catalog( $array ){
            if( isset($array['price']) ){
                unset( $array['price'] );
            }
            if( isset($array['price-desc']) ){
                unset( $array['price-desc'] );
            }
            return $array;
        }

    }

}

new Luxsa_WooCommerce_Config();
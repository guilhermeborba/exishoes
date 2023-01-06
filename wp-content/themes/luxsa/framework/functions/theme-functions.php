<?php
/**
 * This file includes helper functions used throughout the theme.
 *
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if(!function_exists('luxsa_add_meta_into_head_tag')){
    function luxsa_add_meta_into_head_tag(){
        do_action('luxsa/action/head');
    }
}

/**
 * Adds classes to the body tag
 *
 * @since 1.0.0
 */
if (!function_exists('luxsa_body_classes')) {
    function luxsa_body_classes($classes) {
        $classes[] = is_rtl() ? 'rtl' : 'ltr';
        $classes[] = 'luxsa-body';
        $classes[] = 'lastudio-luxsa';
        $site_layout = luxsa_get_site_layout();
        $header_layout = luxsa_get_header_layout();
        $page_title_bar_layout = luxsa_get_page_header_layout();
        $main_fullwidth = luxsa_get_theme_option_by_context('main_full_width', 'no');
        $header_full_width = luxsa_get_theme_option_by_context('header_full_width', 'no');
        $header_sticky = luxsa_get_theme_option_by_context('header_sticky', 'no');
        $header_transparency = luxsa_get_theme_option_by_context('header_transparency', 'no');
        $footer_full_width = luxsa_get_theme_option_by_context('footer_full_width', 'no');
        $body_boxed = luxsa_get_option('body_boxed', 'no');
        $mobile_footer_bar = (luxsa_get_option('enable_header_mb_footer_bar', 'no') == 'yes') ? true : false;
        $mobile_footer_bar_items = luxsa_get_option('header_mb_footer_bar_component', array());
        $custom_body_class = luxsa_get_theme_option_by_context('body_class', '');
        if (!empty($custom_body_class)) {
            $classes[] = esc_attr($custom_body_class);
        }
        if ($body_boxed == 'yes') {
            $classes[] = 'body-boxed';
        }
        if (is_404()) {
            $classes[] = 'body-col-1c';
            $classes['page_title_bar'] = 'page-title-vhide';
            $header_transparency_404 = luxsa_get_option('header_transparency_404');
            if($header_transparency_404 == 'yes'){
                $classes[] = 'enable-header-transparency';
            }
            elseif ($header_transparency_404 == 'no'){
                $header_transparency = 'no';
            }
        }
        else {
            $classes[] = esc_attr('body-' . $site_layout);
            $classes['page_title_bar'] = esc_attr('page-title-v' . $page_title_bar_layout);
        }

        $header_transparency_blog = luxsa_get_option('header_transparency_blog' );
        if( luxsa_is_blog() ){
            if($header_transparency_blog != 'inherit'){
                $header_transparency = $header_transparency_blog;
            }
        }

        $classes[] = 'header-v-' . esc_attr($header_layout);
        if ($header_transparency == 'yes') {
            $classes[] = 'enable-header-transparency';
        }
        if ($header_sticky != 'no') {
            $classes[] = 'enable-header-sticky';
            if ($header_sticky == 'auto') {
                $classes[] = 'header-sticky-type-auto';
            }
        }
        if (is_singular('page')) {
            global $post;
            if (strpos($post->post_content, 'la_wishlist') !== false) {
                $classes[] = 'woocommerce-page';
                $classes[] = 'woocommerce-page-wishlist';
            }
            if (strpos($post->post_content, 'la_compare') !== false) {
                $classes[] = 'woocommerce-page';
                $classes[] = 'woocommerce-compare';
            }
            if (strpos($post->post_content, 'dokan-') !== false) {
                $classes[] = 'woocommerce-page';
                $classes[] = 'woocommerce-dokan-page';
            }
        }
        if ($header_full_width == 'yes') {
            $classes[] = 'enable-header-fullwidth';
        }
        if ($main_fullwidth == 'yes') {
            $classes[] = 'enable-main-fullwidth';
        }
        if ($footer_full_width == 'yes') {
            $classes[] = 'enable-footer-fullwidth';
        }
        $classes[] = 'body-loading';
        if (luxsa_get_option('page_loading_animation', 'off') == 'on') {
            $classes[] = 'site-loading';
            $classes[] = 'active_page_loading';
        }
        if ($mobile_footer_bar && !empty($mobile_footer_bar_items)) {
            $classes[] = 'enable-footer-bars';
            $classes[] = 'footer-bars--visible-' . esc_attr(luxsa_get_option('enable_header_mb_footer_bar_sticky', 'always'));
        }
        if ($site_layout == 'col-1c') {
            $blog_small_layout = luxsa_get_option('blog_small_layout', 'off');
            if ( is_singular('post') ) {
                $single_small_layout_global = luxsa_get_option('single_small_layout', 'off');
                $single_small_layout = luxsa_get_post_meta(get_queried_object_id(), 'small_layout');
                if ($single_small_layout == 'on') {
                    $classes[] = 'enable-small-layout';
                }
                else {
                    if ($single_small_layout_global == 'on' && $single_small_layout != 'off') {
                        $classes[] = 'enable-small-layout';
                    }
                    else {
                        if ($blog_small_layout == 'on') {
                            $classes[] = 'enable-small-layout';
                        }
                    }
                }
            }
            elseif ( is_singular() ){
                $single_small_layout = luxsa_get_post_meta(get_queried_object_id(), 'small_layout');
                if ($single_small_layout == 'on') {
                    $classes[] = 'enable-small-layout';
                }
            }
            elseif ( is_category() || is_tag()) {
                $blog_archive_small_layout = luxsa_get_term_meta(get_queried_object_id(), 'small_layout');
                if ($blog_archive_small_layout == 'on') {
                    $classes[] = 'enable-small-layout';
                }
                else {
                    if ($blog_small_layout == 'on' && $blog_archive_small_layout != 'off') {
                        $classes[] = 'enable-small-layout';
                    }
                }
            }
            elseif ( is_home() ) {
                $single_small_layout = luxsa_get_post_meta(get_option( 'page_for_posts', true ), 'small_layout');
                if ( $single_small_layout == 'on') {
                    $classes[] = 'enable-small-layout';
                }
                elseif ($blog_small_layout == 'on' && $single_small_layout != 'off'){
                    $classes[] = 'enable-small-layout';
                }
            }
        }
        else{
            $classes[] = 'has-sidebar-layout';
        }
        if (function_exists('dokan_get_option')) {
            $page_id = dokan_get_option('dashboard', 'dokan_pages');
            if ($page_id) {
                if (dokan_is_store_page() || is_page($page_id) || (get_query_var('edit') && is_singular('product'))) {
                    $classes[] = 'woocommerce-page';
                }
            }
        }
        if (is_404()) {
            $content_404 = luxsa_get_option('404_page_content');
            if (!empty($content_404)) {
                $classes[] = 'has-customized-404';
            }
            else{
                $classes[] = 'has-default-404';
            }
        }
        if (class_exists('LAHB', false)) {
            $data = false;
            if (!empty($header_layout) && $header_layout != 'inherit') {
                if (!is_admin() && !isset($_GET['lastudio_header_builder'])) {
                    $data = LAHB_Helper::get_data_frontend_component_with_preset($header_layout, $data);
                }
            }
            $data = $data ? $data : LAHB_Helper::get_data_frontend_components();
            if (isset($data['desktop-view']['row1']['settings']['header_type'])) {
                $header_type = $data['desktop-view']['row1']['settings']['header_type'];
                if ($header_type == 'vertical') {
                    $vertical_component_id = $data['desktop-view']['row1']['settings']['uniqueId'];
                    $vertical_toggle = false;
                    if (!empty($data['components'][$vertical_component_id]['vertical_toggle']) && $data['components'][$vertical_component_id]['vertical_toggle'] == 'true') {
                        $vertical_toggle = true;
                    }
                    $classes[] = 'header-type-vertical';
                    if ($vertical_toggle) {
                        $classes[] = 'header-type-vertical--toggle';
                    }
                    else {
                        $classes[] = 'header-type-vertical--default';
                    }
                }
            }
        }
        if(is_singular()){
            if(get_post_meta( get_the_ID(), '_elementor_edit_mode', true )){
                $classes[] = 'page-use-builder';
            }
            else{
                if(is_singular(array('post', 'page'))){
                    $classes[] = 'page-use-gutenberg';
                    if( !in_array( 'woocommerce-page', $classes ) && $site_layout == 'col-1c' ){
                        $classes[] = ' page-content--wdefault';
                    }
                    if(!in_array( 'woocommerce-page', $classes )){
                        $classes[] = ' page-content--default';
                    }
                    if(function_exists('luxsa_check_is_mphb') && luxsa_check_is_mphb()){
                        $classes[] = ' use-normal-width';
                    }
                }
            }
        }

        if(luxsa_is_blog() || is_singular(array('post'))) {
            $classes[] = 'luxsa-is-blog';
        }

        if( luxsa_string_to_bool(luxsa_get_option('woocommerce_show_action_btn_mobile', 'off')) ){
            $classes[] = 'active-prod_btn_mb';
        }
        // Return classes
        return $classes;
    }
}

if (!function_exists('luxsa_render_header')) {
    function luxsa_render_header() {
        if (luxsa_get_theme_option_by_context('hide_header') == 'yes') {
            return;
        }
        get_template_part('partials/header/layout');
    }
}

if (!function_exists('luxsa_render_page_header')) {
    function luxsa_render_page_header() {
        $value = luxsa_get_page_header_layout();
        if (!empty($value) && $value != 'hide') {
            get_template_part('partials/page_header/layout', $value);
        }
    }
}

if (!function_exists('luxsa_render_sidebar')){
    function luxsa_render_sidebar (){
        get_sidebar();
    }
}

if (!function_exists('luxsa_render_footer')) {
    function luxsa_render_footer() {
        if (luxsa_get_theme_option_by_context('hide_footer') == 'yes') {
            return;
        }
        $value = luxsa_get_footer_layout();
        if (!empty($value) && $value != 'inherit') {

            $footer_id = 0;
            if(absint($value) > 0){
                $footer_id = absint($value);
            }
            else{
                $obj = get_page_by_path( $value, OBJECT, 'elementor_library');
                if(!empty($obj)){
                    $footer_id = $obj->ID;
                }
            }

            $footer_id = luxsa_wpml_object_id($footer_id, 'elementor_library', true);

            if($footer_id > 0){
                ?>
                <footer id="footer" class="<?php echo esc_attr(luxsa_footer_classes()); ?>"<?php luxsa_schema_markup('footer'); ?>>
                    <?php do_action('luxsa/action/before_footer_inner'); ?>
                    <div id="footer-inner">
                        <div class="container"><?php
                            if( defined('ELEMENTOR_VERSION') ){
                                if( luxsa_string_to_bool(luxsa_get_option('footer_ajax')) && !Elementor\Plugin::instance()->editor->is_edit_mode() && !Elementor\Plugin::instance()->preview->is_preview_mode() && Elementor\Plugin::instance()->frontend->has_elementor_in_page()){
                                    echo '<div data-ajaxtmp="true" data-template-id="'.esc_attr($footer_id).'"></div>';
                                }
                                else{
                                    echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display($footer_id);
                                }
                            }
                            ?></div>
                    </div><!-- #footer-inner -->
                    <?php do_action('luxsa/action/after_footer_inner'); ?>
                </footer><!-- #footer -->
                <?php
            }
        }
        else {
            get_template_part('partials/footer/layout');
        }
    }
}

if (!function_exists('luxsa_render_footer_searchform_overlay')){
    function luxsa_render_footer_searchform_overlay(){
        get_template_part('partials/footer/searchform-overlay');
    }
}

if (!function_exists('luxsa_render_footer_cartwidget_overlay')){
    function luxsa_render_footer_cartwidget_overlay(){
        get_template_part('partials/footer/cart-overlay');
    }
}

if (!function_exists('luxsa_render_footer_newsletter_popup')){
    function luxsa_render_footer_newsletter_popup(){
        get_template_part('partials/footer/newsletter');
    }
}

if (!function_exists('luxsa_render_footer_handheld')){
    function luxsa_render_footer_handheld(){
        get_template_part('partials/footer/handheld');
    }
}

if(!function_exists('luxsa_add_extra_data_into_head')){
    function luxsa_add_extra_data_into_head(){
        if( $la_custom_css = luxsa_get_option('la_custom_css') ){
            echo sprintf( '<%1$s id="luxsa-custom-css">%2$s</%1$s>', 'style', $la_custom_css);
        }
        if( $header_js = luxsa_get_option('header_js') ){
            echo sprintf( '<%1$s>%2$s</%1$s>', 'script', $header_js);
        }
    }
}

if(!function_exists('luxsa_add_pageloader_icon')){
    function luxsa_add_pageloader_icon(){
        if( luxsa_string_to_bool( luxsa_get_option('page_loading_animation', 'off') ) ){
            $loading_style = luxsa_get_option('page_loading_style', 1);
            if($loading_style == 'custom'){
                if(($img = luxsa_get_option('page_loading_custom')) && !empty($img['id']) && wp_attachment_is_image($img['id']) ){
                    add_filter('luxsa/filter/enable_image_lazyload', '__return_false', 10000);
                    add_filter('wp_lazy_loading_enabled', '__return_false', 10000);
                    echo '<div class="la-image-loading spinner-custom"><div class="content"><div class="la-loader">'. wp_get_attachment_image($img['id'], 'full') .'</div><div class="la-loader-ss"></div></div></div>';
                    luxsa_deactive_filter('luxsa/filter/enable_image_lazyload', '__return_false', 10000);
                    luxsa_deactive_filter('wp_lazy_loading_enabled', '__return_false', 10000);
                }
                else{
                    echo '<div class="la-image-loading"><div class="content"><div class="la-loader spinner1"></div><div class="la-loader-ss"></div></div></div>';
                }
            }
            else{
                echo '<div class="la-image-loading"><div class="content"><div class="la-loader spinner'.esc_attr($loading_style).'"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="cube1"></div><div class="cube2"></div><div class="cube3"></div><div class="cube4"></div></div><div class="la-loader-ss"></div></div></div>';
            }
        }
    }
}

if(!function_exists('luxsa_render_footer_custom_js')){
    function luxsa_render_footer_custom_js(){
        if( $footer_js = luxsa_get_option('footer_js') ){
            echo sprintf( '<%1$s>%2$s</%1$s>', 'script', $footer_js);
        }
    }
}


if(!function_exists('luxsa_add_lazyload_to_image_tag')){
    function luxsa_add_lazyload_to_image_tag( $attr ){

        $enable_lazy = luxsa_string_to_bool(luxsa_get_option('activate_lazyload'));

        if( apply_filters('luxsa/filter/enable_image_lazyload', $enable_lazy) && (!is_admin() && !isset($_REQUEST['la_doing_ajax']) && isset($attr['src'])) ) {
            $attr['data-src'] = $attr['src'];
            if(isset($attr['srcset'])){
                $attr['data-srcset'] = $attr['srcset'];
                $attr['srcset'] = get_theme_file_uri('/assets/images/blank.gif');
            }
            if(isset($attr['sizes'])){
                $attr['data-sizes'] = $attr['sizes'];
                unset($attr['sizes']);
            }
            $attr['src'] = get_theme_file_uri('/assets/images/blank.gif');
            $old_class = isset($attr['class']) ? $attr['class'] : '';
            if(strpos($old_class, 'la-lazyload-image') === false){
                $attr['class'] = $old_class . ' la-lazyload-image';
            }
        }
        return $attr;
    }
}

if(!function_exists('luxsa_add_lazyload_height_thumbnail_html')){
    function luxsa_add_lazyload_height_thumbnail_html( $html ){
        $rxp = '/height="([^"]*)"/i';
        $html = preg_replace($rxp, '$0 style="--img-height:$1px"', $html);
        return $html;
    }
}
add_filter('lastudio_thumbnail_html', 'luxsa_add_lazyload_height_thumbnail_html');
add_filter('luxsa_thumbnail_html', 'luxsa_add_lazyload_height_thumbnail_html');
add_filter('post_thumbnail_html', 'luxsa_add_lazyload_height_thumbnail_html');

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

if(!function_exists('luxsa_override_page_title_bar_from_context')){
    function luxsa_override_page_title_bar_from_context( $value, $key ){

        $array_key_allow = array(
            'page_title_bar_style',
            'page_title_bar_layout',
            'page_title_bar_background',
            'page_title_bar_border',
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
            'page_title_bar_background',
            'page_title_bar_border',
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

        if( !is_singular() && !is_tax(get_object_taxonomies( 'la_portfolio' )) && !is_post_type_archive('la_portfolio') && !luxsa_is_blog()){
            return $value;
        }

        $func_name = 'luxsa_get_post_meta';
        $queried_object_id = get_queried_object_id();

        if( is_tax(get_object_taxonomies( 'la_portfolio' ) ) || is_tag() || is_category() ){
            $func_name = 'luxsa_get_term_meta';
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

        if( is_singular('la_portfolio') ) {
            $key_override = 'single_portfolio_override_page_title_bar';
            $new_key = 'single_portfolio_' . $key;
        }
        elseif( is_singular('post') ) {
            $key_override = 'single_post_override_page_title_bar';
            $new_key = 'single_post_' . $key;
        }
        elseif ( is_tax(get_object_taxonomies( 'la_portfolio' )) || is_post_type_archive('la_portfolio') ) {
            $key_override = 'archive_portfolio_override_page_title_bar';
            $new_key = 'archive_portfolio_' . $key;
        }

        elseif( luxsa_is_blog() ){
            $key_override = 'blog_post_override_page_title_bar';
            $new_key = 'blog_post_' . $key;
        }

        if(false != $key_override){
            if( 'on' == luxsa_get_option($key_override, 'off') ){
                return luxsa_get_option($new_key, $value);
            }
        }

        return $value;
    }

}

if(!function_exists('luxsa_override_post_navigation_template')){
    function luxsa_override_post_navigation_template( $output, $format, $link, $post, $adjacent ){
        $image = '';
        if(has_post_thumbnail($post)){
            $image = sprintf('<span class="nav_pnpp__image" style="background-image: url(\'%1$s\');"></span>', get_the_post_thumbnail_url($post));
        }
        $output = str_replace( '%image', $image, $output );
        $output = str_replace( '%author', get_the_author(), $output );
        return $output;
    }
}

if(!function_exists('luxsa_override_sidebar_name_from_context')){

    function luxsa_override_sidebar_name_from_context( $sidebar ){

        if( is_search() ) {
            if( ( $sidebar_search = luxsa_get_option('search_sidebar', $sidebar) ) && !empty($sidebar_search) ) {
                return $sidebar_search;
            }
        }

        if( is_tag() || is_category() ) {

            $sidebar = luxsa_get_option('blog_archive_sidebar', $sidebar );

            if( luxsa_string_to_bool( luxsa_get_option('blog_archive_global_sidebar', false) ) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }

            if( ( $_sidebar = luxsa_get_term_meta(get_queried_object_id(), 'sidebar') ) && !empty( $_sidebar ) ) {
                return $_sidebar;
            }

        }

        if(is_singular('post')){
            $sidebar = luxsa_get_option('posts_sidebar', $sidebar);
            if( luxsa_string_to_bool( luxsa_get_option('posts_global_sidebar', false) ) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }

            if( ( $_sidebar = luxsa_get_post_meta(get_queried_object_id(), 'sidebar') ) && !empty( $_sidebar ) ) {
                return $_sidebar;
            }

        }

        if ( post_type_exists('la_portfolio') ) {
            if ( is_tax() && is_tax( get_object_taxonomies( 'la_portfolio' ) ) ) {
                $sidebar = luxsa_get_option('portfolio_archive_sidebar', $sidebar);
                if( luxsa_string_to_bool(luxsa_get_option('portfolio_archive_global_sidebar', false)) ){
                    /*
                     * Return global sidebar if option will be enable
                     * We don't need more checking in context
                     */
                    return $sidebar;
                }

                if( ( $_sidebar = luxsa_get_term_meta(get_queried_object_id(), 'sidebar') ) && !empty( $_sidebar ) ) {
                    return $_sidebar;
                }
            }

            if(is_singular('la_portfolio')){
                $sidebar = luxsa_get_option('portfolio_sidebar', $sidebar);
                if( luxsa_string_to_bool(luxsa_get_option('portfolio_global_sidebar', false)) ){
                    /*
                     * Return global sidebar if option will be enable
                     * We don't need more checking in context
                     */
                    return $sidebar;
                }

                if( ( $_sidebar = luxsa_get_post_meta(get_queried_object_id(), 'sidebar') ) && !empty( $_sidebar ) ) {
                    return $_sidebar;
                }
            }
        }

        if(is_page()){
            $sidebar = luxsa_get_option('pages_sidebar', $sidebar);
            if( luxsa_string_to_bool(luxsa_get_option('pages_global_sidebar', false)) ){
                /*
                 * Return global sidebar if option will be enable
                 * We don't need more checking in context
                 */
                return $sidebar;
            }

            if( ( $_sidebar = luxsa_get_post_meta(get_queried_object_id(), 'sidebar') ) && !empty( $_sidebar ) ) {
                return $_sidebar;
            }

        }

        return $sidebar;
    }
}

if(!function_exists('luxsa_render_related_posts')){
    function luxsa_render_related_posts(){
        $move_related_to_bottom = luxsa_string_to_bool(luxsa_get_option('move_blog_related_to_bottom', 'off'));
        if( is_singular('post') && $move_related_to_bottom){
            get_template_part('partials/singular/related-posts');
        }
    }
}

if(!function_exists('luxsa_render_comment_for_page')){
    function luxsa_render_comment_for_page(){
        wp_reset_postdata();
        if(is_page() && (comments_open() || get_comments_number())){
            comments_template();
        }
    }
}


if(!function_exists('luxsa_change_excerpt_length')){
    function luxsa_change_excerpt_length( $length ){
        $excerpt_length = absint(luxsa_get_option('blog_excerpt_length', 25));
        if($excerpt_length > 0){
            return $excerpt_length;
        }
        return $length;
    }
}

if(!function_exists('luxsa_filter_show_breadcrumbs')){
    function luxsa_filter_show_breadcrumbs( $visible ){
        return $visible;
    }
}

if(!function_exists('luxsa_filter_page_title')){
    function luxsa_filter_page_title( $visible ){
        if(is_singular('post') && luxsa_get_option('blog_post_page_title', 'blog') == 'none'){
            $visible = false;
        }
        return $visible;
    }
}

if(!function_exists('luxsa_override_portfolio_content_type_args')){
    function luxsa_override_portfolio_content_type_args( $args, $post_type_name ) {
        if($post_type_name == 'la_portfolio'){
            $label = esc_html(luxsa_get_option('portfolio_custom_name'));
            $label2 = esc_html(luxsa_get_option('portfolio_custom_name2'));
            $slug = sanitize_title(luxsa_get_option('portfolio_custom_slug'));
            if(!empty($label)){
                $args['label'] = $label;
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }

        return $args;
    }
}
if(!function_exists('luxsa_override_portfolio_tax_type_args')){
    function luxsa_override_portfolio_tax_type_args( $args, $tax_name ) {

        if( $tax_name == 'la_portfolio_category' ) {
            $label = esc_html(luxsa_get_option('portfolio_cat_custom_name'));
            $label2 = esc_html(luxsa_get_option('portfolio_cat_custom_name2'));
            $slug = sanitize_title(luxsa_get_option('portfolio_cat_custom_slug'));
            if(!empty($label)){
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }
        else if( $tax_name == 'la_portfolio_skill' ) {
            $label = esc_html(luxsa_get_option('portfolio_skill_custom_name'));
            $label2 = esc_html(luxsa_get_option('portfolio_skill_custom_name2'));
            $slug = sanitize_title(luxsa_get_option('portfolio_skill_custom_slug'));
            if(!empty($label)){
                $args['labels']['name'] = $label;
            }
            if(!empty($label2)){
                $args['labels']['singular_name'] = $label2;
            }
            if(!empty($slug)){
                if(!empty($args['rewrite'])){
                    $args['rewrite']['slug'] = $slug;
                }
                else{
                    $args['rewrite'] = array( 'slug' => $slug );
                }
            }
        }

        return $args;
    }
}

if(!function_exists('luxsa_render_custom_block')){
    function luxsa_render_custom_block(){
        $blocks = luxsa_get_option('la_custom_blocks', []);

        if(empty($blocks)){
            return false;
        }
        foreach ($blocks as $id => $block){

            if(empty($block['conditions']) || empty($block['content']) || empty($block['position'])){
                continue;
            }

            $conditions_priority = [];
            $excludes = [];

            $conditions = $block['conditions'];
            foreach ($conditions as $id2 => $condition){
                $include = $condition['type'];
                $name = $condition['name'];
                $sub_name = isset($condition[$name]) ? $condition[$name] : '';
                $sub_id = '';
                $condition_pass = false;

                if($name == 'archive'){
                    if($sub_name == 'author'){
                        $sub_id = $condition['author'];
                    }
                    elseif( $sub_name == 'category' || $sub_name == 'child_of_category' || $sub_name == 'any_child_of_category'){
                        $sub_id = luxsa_wpml_object_id($condition['category'], 'category');
                    }
                    elseif($sub_name == 'post_tag'){
                        $sub_id = luxsa_wpml_object_id($condition['tag'], 'post_tag');
                    }
                }
                elseif ($name == 'singular'){
                    if($sub_name == 'post'){
                        $sub_id = luxsa_wpml_object_id($condition['singular_post'], 'post');
                    }
                    elseif ($sub_name == 'page'){
                        $sub_id = luxsa_wpml_object_id($condition['singular_page'], 'page');
                    }
                    elseif ($sub_name == 'la_portfolio'){
                        $sub_id = luxsa_wpml_object_id($condition['singular_la_portfolio'], 'la_portfolio');
                    }
                    elseif ($sub_name == 'in_category' || $sub_name == 'in_category_children'){
                        $sub_id = luxsa_wpml_object_id($condition['singular_category'], 'category');
                    }
                    elseif ($sub_name == 'in_post_tag'){
                        $sub_id = luxsa_wpml_object_id($condition['singular_tag'], 'post_tag');
                    }
                    elseif ($sub_name == 'post_by_author' || $sub_name == 'page_by_author' || $sub_name == 'la_portfolio_by_author' || $sub_name == 'by_author'){
                        $sub_id = $condition['singular_author'];
                    }
                    elseif ($sub_name == 'in_la_portfolio_category'){
                        $sub_id = luxsa_wpml_object_id($condition['singular_la_portfolio_category'], 'la_portfolio_category');
                    }
                }
                elseif ($name == 'woocommerce'){
                    if($sub_name == 'product_cat' || $sub_name == 'in_product_cat' || $sub_name == 'in_product_cat_children'){
                        $sub_id = luxsa_wpml_object_id($condition['wc_product_cat'], 'product_cat');
                    }
                    elseif($sub_name == 'product_tag' || $sub_name == 'in_product_tag'){
                        $sub_id = luxsa_wpml_object_id($condition['wc_product_tag'], 'product_tag');
                    }
                    elseif ($sub_name == 'product_by_author'){
                        $sub_id = $condition['wc_author'];
                    }
                }

                $is_include = 'include' === $include;

                if($name == 'general'){
                    $condition_pass = true;
                }
                elseif ($name == 'archive'){
                    if(is_archive()){
                        $condition_pass = true;
                    }
                }
                elseif($name == 'singular'){
                    if(is_singular()){
                        $condition_pass = true;
                    }
                    if(($sub_name == 'blog_page' || $sub_name == 'front_page') && ((is_front_page() && is_home()) || (!is_front_page() && is_home()))){
                        $condition_pass = true;
                    }
                }
                elseif($name == 'woocommerce'){
                    if( (function_exists('WC') && (is_woocommerce())) || (is_search() && 'product' === get_query_var( 'post_type' ))){
                        $condition_pass = true;
                    }
                }

                if($condition_pass && !empty($sub_name)){
                    $condition_pass = false;
                    if($sub_name == 'author'){
                        if(empty($sub_id) || (!empty($sub_id) && is_author($sub_id))){
                            $condition_pass = true;
                        }
                    }
                    if($sub_name == 'date'){
                        if(is_date()){
                            $condition_pass = true;
                        }
                    }
                    if($sub_name == 'post_archive'){
                        if(is_post_type_archive('post')){
                            $condition_pass = true;
                        }
                    }
                    if($sub_name == 'category'){
                        if(empty($sub_id) || (!empty($sub_id) && is_category($sub_id))){
                            $condition_pass = true;
                        }
                    }
                    if($sub_name == 'child_of_category'){
                        if(is_category()){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                $current = get_queried_object();
                                if($sub_id == $current->parent){
                                    $condition_pass = true;
                                }
                            }
                        }
                    }
                    if($sub_name == 'any_child_of_category'){
                        if(is_category()){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                $current = get_queried_object();
                                if($sub_id == $current->parent){
                                    $condition_pass = true;
                                }
                                if($current->parent > 0){
                                    $current_parent = get_term_by( 'id', $current->parent, 'category' );
                                    if($sub_id == $current_parent->parent){
                                        $condition_pass = true;
                                    }
                                }

                            }
                        }
                    }
                    if($sub_name == 'post_tag'){
                        if(empty($sub_id) || (!empty($sub_id) && is_tag($sub_id))){
                            $condition_pass = true;
                        }
                    }
                    if($sub_name == 'front_page'){
                        if(is_front_page()){
                            $condition_pass = true;
                        }
                    }
                    if($sub_name == 'blog_page') {
                        if ((is_front_page() && is_home()) || (!is_front_page() && is_home())) {
                            $condition_pass = true;
                        }
                    }
                    if( is_singular() && in_array(get_post_type(get_the_ID()), ['post', 'page', 'la_portfolio']) ){

                        if($sub_name == 'post' || $sub_name == 'page' || ($sub_name == 'la_portfolio' && post_type_exists('la_portfolio'))){
                            if(empty($sub_id)){
                                if(is_singular($sub_name)){
                                    $condition_pass = true;
                                }
                            }
                            else{
                                if(is_single($sub_id) || is_page($sub_id)){
                                    $condition_pass = true;
                                }
                            }

                        }
                        if($sub_name == 'in_category'){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                if(has_term($sub_id, 'category')){
                                    $condition_pass = true;
                                }
                            }
                        }
                        if($sub_name == 'in_category_children'){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                $child_terms = get_term_children( $sub_id, 'category' );
                                if(! empty( $child_terms ) && has_term( $child_terms, 'category' )){
                                    $condition_pass = true;
                                }
                            }
                        }
                        if($sub_name == 'in_post_tag'){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                if(has_term($sub_id, 'post_tag')){
                                    $condition_pass = true;
                                }
                            }
                        }
                        if($sub_name == 'post_by_author' || $sub_name == 'page_by_author' || $sub_name == 'la_portfolio_by_author' || $sub_name == 'by_author'){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                if(get_post_field( 'post_author' ) == $sub_id){
                                    $condition_pass = true;
                                }
                            }
                        }
                        if($sub_name == 'in_la_portfolio_category' && taxonomy_exists('in_la_portfolio_category')){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                if(is_tax('la_portfolio_category', $sub_id)){
                                    $condition_pass = true;
                                }
                            }
                        }
                        if($sub_name == 'child_of'){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                $parent_id = wp_get_post_parent_id( get_the_ID() );
                                if( ( 0 == $sub_id && 0 < $parent_id ) || ( $parent_id == $sub_id ) ){
                                    $condition_pass = true;
                                }
                            }
                        }
                        if($sub_name == 'any_child_of'){
                            if(empty($sub_id)){
                                $condition_pass = true;
                            }
                            else{
                                $parents = get_post_ancestors( get_the_ID() );
                                if( ( 0 === $sub_id && ! empty( $parents ) ) || in_array( $sub_id, $parents ) ){
                                    $condition_pass = true;
                                }
                            }
                        }
                    }

                    if(function_exists('WC')){
                        if($sub_name == 'product_archive'){
                            if(is_shop() || is_product_taxonomy() || (is_search() && 'product' === get_query_var( 'post_type' ))){
                                $condition_pass = true;
                            }
                        }
                        if($sub_name == 'shop_page'){
                            if(is_shop()){
                                $condition_pass = true;
                            }
                        }
                        if($sub_name == 'product_search'){
                            if(is_search() && 'product' === get_query_var( 'post_type' )){
                                $condition_pass = true;
                            }
                        }
                        if($sub_name == 'product_cat'){
                            if( is_product_category() && ( (empty($sub_id)) || ( !empty($sub_id) && is_product_category($sub_id)) ) ){
                                $condition_pass = true;
                            }
                            if( is_product_tag() && ( (empty($sub_id)) || ( !empty($sub_id) && is_product_tag($sub_id)) ) ){
                                $condition_pass = true;
                            }
                        }
                        if(is_product()){
                            if($sub_name == 'product'){
                                if( (empty($sub_id)) || ( !empty($sub_id) && is_single($sub_id)) ){
                                    $condition_pass = true;
                                }
                            }
                            if($sub_name == 'product_by_author'){
                                if( (empty($sub_id)) || ( !empty($sub_id) && get_post_field( 'post_author' ) == $sub_id ) ){
                                    $condition_pass = true;
                                }
                            }
                            if($sub_name == 'in_product_tag'){
                                if( (empty($sub_id)) || ( !empty($sub_id) && has_term($sub_id, 'product_tag')) ){
                                    $condition_pass = true;
                                }
                            }
                            if($sub_name == 'in_product_cat'){
                                if( (empty($sub_id)) || ( !empty($sub_id) && has_term($sub_id, 'product_cat')) ){
                                    $condition_pass = true;
                                }
                            }
                            if($sub_name == 'in_product_cat_children'){
                                if(empty($sub_id)){
                                    $condition_pass = true;
                                }
                                else{
                                    $child_terms = get_term_children( $sub_id, 'product_cat' );
                                    if(! empty( $child_terms ) && has_term( $child_terms, 'product_cat' )){
                                        $condition_pass = true;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($sub_name == 'not_found404'){
                    if(is_404()){
                        $condition_pass = true;
                    }
                }

                if($condition_pass){
                    if ( $is_include ) {
                        $conditions_priority[$id] = $id;
                    }
                    else{
                        $excludes[] = $id;
                    }
                }
            }

            if(!empty($excludes)){
                foreach ( $excludes as $exclude_id ) {
                    unset( $conditions_priority[ $exclude_id ] );
                }
            }

            if(!empty($conditions_priority)){
                $priority = 10;
                $hook_name = 'luxsa/action/' . $block['position'];
                add_action( $hook_name, function() use( $block, $hook_name, $priority ) {  luxsa_callback_func_to_show_custom_block($block, $hook_name, $priority); }, $priority );
            }
        }
    }
}

if(!function_exists('luxsa_setup_post_per_page_for_portfolio')){
    function luxsa_setup_post_per_page_for_portfolio( $query ){
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }
        if ( is_post_type_archive( 'la_portfolio' ) || (is_tax() && is_tax(get_object_taxonomies( 'la_portfolio' ) ))) {
            $query->set( 'posts_per_page', luxsa_get_option('portfolio_per_page', 9) );
        }
    }
}
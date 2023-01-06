<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('luxsa_dokan_override_current_title')){

    function luxsa_dokan_override_current_title( $title ){
        if(function_exists('dokan_is_store_page') && dokan_is_store_page()){
            $store_user         = dokan()->vendor->get( get_query_var( 'author' ) );
            return $store_user->get_shop_name();
        }
        return $title;
    }

    add_filter('luxsa/filter/current_title', 'luxsa_dokan_override_current_title');
}

if(!function_exists('luxsa_dokan_override_breadcrumbs')){

    function luxsa_dokan_override_breadcrumbs( $items, $args ){

        if (  function_exists('dokan_is_store_page') && dokan_is_store_page() ) {
            $store_user   = dokan()->vendor->get( get_query_var( 'author' ) );

            if ( is_paged() ){
                $tmp = '';
                if( count($items) > 1 ){
                    $tmp = $items[(count($items) - 1)];
                    unset($items[(count($items) - 1)]);
                }
                $items[] = sprintf( '<a href="%s" %s>%s</a>', esc_url( dokan_get_store_url( $store_user->get_id() ) ), luxsa_get_schema_markup('item'), $store_user->get_shop_name() );
                $items[] = $tmp;
            }
            else{
                $items[] = $store_user->get_shop_name();
            }
        }

        return $items;
    }

    add_filter('breadcrumb_trail_items', 'luxsa_dokan_override_breadcrumbs', 10, 2);
}

if(!function_exists('luxsa_override_dokan_main_query')){
    function luxsa_override_dokan_main_query( $query ) {
        if(function_exists('dokan_is_store_page') && dokan_is_store_page() && isset($query->query['term_section'])){
            $query->set('posts_per_page', 0);

            // fixed for WC 3.7
            if( ! empty( $_GET['orderby'] ) ) {
                $query->set('page_id', 0);
                $query->is_page = false;
                $query->is_singular = false;
            }

            WC()->query->product_query( $query );
        }
    }
    add_action('pre_get_posts', 'luxsa_override_dokan_main_query', 11);
}

if(!function_exists('luxsa_dokan_render_page_header')){
    function luxsa_dokan_render_page_header(){
        if(function_exists('dokan_is_store_page') && dokan_is_store_page()){
            if(dokan_get_option('enable_theme_store_header_tpl', 'dokan_general') == 'on'){
                get_template_part('dokan/store-custom-header');
            }
        }
    }
    add_action( 'luxsa/action/before_content_wrap', 'luxsa_dokan_render_page_header' );
}

if(!function_exists('luxsa_dokan_add_field_to_admin_setting_panels')){
    function luxsa_dokan_add_field_to_admin_setting_panels( $fields ){

        if(isset($fields['dokan_general'])){
            $fields['dokan_general']['store_banner_width'] = array(
                'name'    => 'store_banner_width',
                'label'   => esc_html__( 'Store Banner width', 'luxsa' ),
                'type'    => 'text',
                'default' => 625
            );
            $fields['dokan_general']['store_banner_height'] = array(
                'name'    => 'store_banner_height',
                'label'   => esc_html__( 'Store Banner height', 'luxsa' ),
                'type'    => 'text',
                'default' => 300
            );
            $fields['dokan_general']['enable_theme_store_header_tpl'] = array(
                'name'    => 'enable_theme_store_header_tpl',
                'label'   => esc_html__( 'Store Header Template', 'luxsa' ),
                'desc'    => esc_html__( 'Use Store Header Template from the theme', 'luxsa' ),
                'type'    => 'checkbox',
                'default' => 'on'
            );
        }
        return $fields;
    }

    add_filter('dokan_settings_fields', 'luxsa_dokan_add_field_to_admin_setting_panels', 0);
}

if(!function_exists('luxsa_dokan_add_store_banner_image_size_for_getter')){
    function luxsa_dokan_add_store_banner_image_size_for_getter( $data ){
        $data['store_banner_width_dokan_appearance']  = array( 'store_banner_width', 'dokan_general' );
        $data['store_banner_height_dokan_appearance']  = array( 'store_banner_height', 'dokan_general' );
        return $data;
    }
    add_filter('dokan_admin_settings_rearrange_map', 'luxsa_dokan_add_store_banner_image_size_for_getter');
}

if(!function_exists('luxsa_dokan_add_shop_sidebar')){
    function luxsa_dokan_add_shop_sidebar( $sidebar ){
        if(function_exists('dokan_is_store_page') && dokan_is_store_page()){
            $enable_theme_store_sidebar = dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' );
            if($enable_theme_store_sidebar == 'on'){
                $sidebar = luxsa_get_option('shop_sidebar', $sidebar);
            }
            else{
                $sidebar = 'sidebar-store';
            }
        }
        return $sidebar;
    }
    add_filter('luxsa/filter/sidebar_primary_name', 'luxsa_dokan_add_shop_sidebar', 30 );
}

if(!function_exists('luxsa_dokan_override_vendor_site_layout')){
    function luxsa_dokan_override_vendor_site_layout( $layout ){

        if(function_exists('dokan_is_store_page') && dokan_is_store_page()){
            $layout = 'col-2cl';
        }

        return $layout;
    }
    add_filter('luxsa/get_site_layout', 'luxsa_dokan_override_vendor_site_layout', 20 );
}

if(!function_exists('luxsa_dokan_override_widget_args')){
    function luxsa_dokan_override_widget_args( $args ) {
        $args['before_widget'] = '<aside id="%1$s" class="sidebar-box widget dokan-store-widget %2$s">';
        return $args;
    }
    add_filter('dokan_store_widget_args', 'luxsa_dokan_override_widget_args');
}

if(!function_exists('luxsa_dokan_profile_social_fields')){
    function luxsa_dokan_profile_social_fields( $fields ){
        if(isset($fields['fb'])){
            $fields['fb']['icon'] = 'facebook';
        }
        if(isset($fields['twitter'])){
            $fields['twitter']['icon'] = 'twitter';
        }
        if(isset($fields['pinterest'])){
            $fields['pinterest']['icon'] = 'pinterest';
        }
        if(isset($fields['linkedin'])){
            $fields['linkedin']['icon'] = 'linkedin';
        }
        if(isset($fields['youtube'])){
            $fields['youtube']['icon'] = 'youtube';
        }
        if(isset($fields['instagram'])){
            $fields['instagram']['icon'] = 'instagram';
        }
        if(isset($fields['flickr'])){
            $fields['flickr']['icon'] = 'flickr';
        }
        return $fields;
    }
    add_filter('dokan_profile_social_fields', 'luxsa_dokan_profile_social_fields');
}

if(!function_exists('luxsa_dokan_dashboard_wrap_before')){
    function luxsa_dokan_dashboard_wrap_before(){
        echo '<div id="content-wrap" class="container"><div id="primary" class="content-area"><div id="content" class="site-content"><article class="single-content-article single-page-article"><div class="entry">';
    }
    add_filter('dokan_dashboard_wrap_before', 'luxsa_dokan_dashboard_wrap_before');
}

if(!function_exists('luxsa_dokan_dashboard_wrap_after')){
    function luxsa_dokan_dashboard_wrap_after(){
        echo '</div></article></div></div></div>';
    }
    add_filter('dokan_dashboard_wrap_after', 'luxsa_dokan_dashboard_wrap_after');
}
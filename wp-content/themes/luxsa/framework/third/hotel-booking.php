<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('luxsa_check_is_mphb')){
    function luxsa_check_is_mphb( $force = true ){
        $flag = false;
        $page_lists = [
            MPHB()->settings()->pages()->getSearchResultsPageId(),
            MPHB()->settings()->pages()->getCheckoutPageId(),
            MPHB()->settings()->pages()->getTermsAndConditionsPageId(),
            MPHB()->settings()->pages()->getBookingConfirmedPageId(),
            MPHB()->settings()->pages()->getUserCancelRedirectPageId(),
            MPHB()->settings()->pages()->getReservationReceivedPageId(),
            MPHB()->settings()->pages()->getPaymentFailedPageId()
        ];
        if(is_post_type_archive('mphb_room_type') || is_singular('mphb_room_type') || ( is_tax() && !empty(get_object_taxonomies( 'mphb_room_type' )) && is_tax(get_object_taxonomies( 'mphb_room_type' )) ) ){
            $flag = true;
        }
        elseif(is_post_type_archive('mphb_room_service') || is_singular('mphb_room_service') || ( is_tax() && !empty(get_object_taxonomies( 'mphb_room_service' )) && is_tax(get_object_taxonomies( 'mphb_room_service' )) ) ){
            $flag = true;
        }
        elseif ( is_page($page_lists) ){
            $flag = true;
        }
        $backup_value = $flag;
        if(apply_filters('luxsa/filter/force_is_mphb', $force)){
            $flag = true;
        }
        return apply_filters('luxsa/filter/is_mphb', $flag, $backup_value, $force);
    }
}

if(!function_exists('luxsa_mphb_add_body_class')){
    function luxsa_mphb_add_body_class( $classes ) {
        if(luxsa_check_is_mphb(false)){
            $classes[] = 'is-mphb-page';
        }
        return $classes;
    }
}
add_filter('body_class', 'luxsa_mphb_add_body_class');;

if(!function_exists('luxsa_mphb_add_wrap_before_search_results')){
    function luxsa_mphb_add_wrap_before_search_results(){
        echo '<div class="mphb-search-recommendations-wrapper">';
    }
}
add_action('mphb_sc_search_results_recommendation_before', 'luxsa_mphb_add_wrap_before_search_results');

if(!function_exists('luxsa_mphb_add_wrap_after_search_results')){
    function luxsa_mphb_add_wrap_after_search_results(){
        echo '</div>';
    }
}
add_action('mphb_sc_search_results_recommendation_after', 'luxsa_mphb_add_wrap_after_search_results');

if(!function_exists('luxsa_mphb_add_price_into_room_loop_item')){
    function luxsa_mphb_add_price_into_room_loop_item( $room_id ){
        if( get_post_type($room_id) === 'mphb_room_type' && mphb_tmpl_has_room_type_default_price( $room_id )){
            echo '<div class="post-meta post-meta-p meta-mphb">';
            mphb_tmpl_the_room_type_default_price( $room_id );
            echo '</div>';
        }
    }
}
add_action('luxsa/posts/loop/before_meta', 'luxsa_mphb_add_price_into_room_loop_item', 10, 1);

if(!function_exists('luxsa_mphb_add_wrap_before_reservation_form')){
    function luxsa_mphb_add_wrap_before_reservation_form(){
        echo '<div class="single-room-reservation-form"><div class="single-room-reservation-form-wrapper">';
    }
}
add_action('mphb_render_single_room_type_before_reservation_form', 'luxsa_mphb_add_wrap_before_reservation_form', 0);

if(!function_exists('luxsa_mphb_add_wrap_after_reservation_form')){
    function luxsa_mphb_add_wrap_after_reservation_form(){
        echo '</div></div>';
    }
}
add_action('mphb_render_single_room_type_after_reservation_form', 'luxsa_mphb_add_wrap_after_reservation_form', 100);


add_filter('mphb_loop_room_type_gallery_use_nav_slider', '__return_false');
add_filter('mphb_single_room_type_gallery_use_magnific', '__return_false');
remove_action( 'mphb_render_loop_room_type_before_attributes', ['\MPHB\Views\LoopRoomTypeView', '_renderAttributesTitle'], 10 );
add_action('mphb_render_single_room_type_before_reservation_form', ['\MPHB\Views\SingleRoomTypeView', 'renderPrice'], 5);



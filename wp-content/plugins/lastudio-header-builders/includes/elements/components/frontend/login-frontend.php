<?php
if(!function_exists('lahb_login')) {
    function lahb_login($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [Login ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'login_type' => 'icon',
            'login_text' => 'Login / Register',
            'logout_text' => '',
            'login_text_icon' => '',
            'open_form' => 'modal',
            'show_arrow' => 'false',
            'show_avatar' => 'false',
            'show_tooltip' => 'false',
            'show_form' => 'false',
            'tooltip_text' => 'Login',
            'tooltip_position' => 'tooltip-on-bottom',
            'extra_class' => '',
            'custom_icon' => 'lastudioicon-single-01-2'
        ), $atts));
        /**
         * login_type
         * tooltip_text
         * extra_class
         */

        $currentUser = wp_get_current_user();

        $force_display_loginform = false;
        $is_frontend_builder = !empty($_GET['lastudio_header_builder']) ? true : false;

        if($show_form == 'true' && (is_admin() || $is_frontend_builder)){
            $force_display_loginform = true;
        }
        $is_logged_in = is_user_logged_in() ? true : false;
        if($force_display_loginform){
            $is_logged_in = false;
        }

        $out = $modal = $wrap_class = '';
        $icon_alignment = $login_text_icon == 'true' ? 'icon-right ' : '';

        $custom_icon = !empty($custom_icon) ? '<i class="'.esc_attr($custom_icon).'"></i>' : '';

        $login_text_icon = $login_type == 'icon_text' || $login_type == 'icon' ? $custom_icon : '';
        $tmp_login_text = !empty($login_text) ? LAHB_Helper::translate_string($login_text, 'Login Text'. $com_uniqid) : '';
        $tmp_logout_text = !empty($logout_text) ? LAHB_Helper::translate_string($logout_text, 'Logout Text' . $com_uniqid) : '';
        $tmp_logout_text = str_replace(['[first_name]', '[last_name]', '[display]'], [$currentUser->user_firstname, $currentUser->user_lastname, $currentUser->display_name], $tmp_logout_text);
        // tooltip
        $tooltip = $tooltip_class = '';
        if ($show_tooltip == 'true' && !empty($tooltip_text)) :
            $tooltip_position = (isset($tooltip_position) && $tooltip_position) ? $tooltip_position : 'tooltip-on-bottom';
            $tooltip_class = ' lahb-tooltip ' . $tooltip_position;
            $tooltip = ' data-tooltip=" ' . esc_attr(LAHB_Helper::translate_string($tooltip_text, 'Tooltip Text' . $com_uniqid)) . ' "';
        endif;
        if (!empty($currentUser->ID)) {
            $show_avatar = $show_avatar == 'true' ? '<span class="la-header-avatar">' . get_avatar($currentUser->ID, $size = '50') . '</span>' : $login_text_icon;
        }
        else {
            $show_avatar = $login_type == 'icon' ? $custom_icon : $login_text_icon;
        }
        // login
        if ($is_logged_in) {
            $login_text = $show_avatar . '<span class="lahb-login-text-modal">' . esc_html($tmp_logout_text) . '</span>';
        }
        else {
            $login_text = $show_avatar . '<span class="lahb-login-text-modal">' . esc_html($tmp_login_text) . '</span>';
        }
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'text', '#lastudio-header-builder .com_login_' . esc_attr($uniqid) . ' .lahb-icon-element span', '#lastudio-header-builder .com_login_' . esc_attr($uniqid) . ':hover .lahb-icon-element span');
            $dynamic_style .= lahb_styling_tab_output($atts, 'icon', '#lastudio-header-builder .com_login_' . esc_attr($uniqid) . ' .lahb-icon-element i', '#lastudio-header-builder .com_login_' . esc_attr($uniqid) . ':hover .lahb-icon-element i');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .com_login_' . esc_attr($uniqid) . '');
            $dynamic_style .= lahb_styling_tab_output($atts, 'form', '#lahb_login_' . esc_attr($uniqid) . ' .lahb-login-form');
            $dynamic_style .= lahb_styling_tab_output($atts, 'userinfo', '#lahb_login_' . esc_attr($uniqid) . ' .lahb-user-logged');
            $dynamic_style .= lahb_styling_tab_output($atts, 'tooltip', '#lastudio-header-builder .com_login_' . esc_attr($uniqid) . '.lahb-tooltip[data-tooltip]:before');
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        if ($open_form == 'dropdown') {
            $show_arrow = $show_arrow == 'true' ? 'with-arrow' : ' no-arrow';
            $wrap_class = ' login-dropdown-element lahb-header-dropdown';
        }
        else {
            $show_arrow = '';
        }
        if (empty($currentUser->ID)) {
            $wrap_class .= ' wrap-login-elm';
        }

        $profile_url = home_url('/');
        if (function_exists('wc_get_account_endpoint_url')) {
            $profile_url = wc_get_account_endpoint_url('dashboard');
        }

        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-wrap lahb-login ' . $show_arrow . $wrap_class . esc_attr($tooltip_class . $extra_class) . ' com_login_' . esc_attr($uniqid) . '" ' . $tooltip . ' ' . $modal . '>';
        if ($open_form == 'modal') {
            if (!$is_logged_in) {
                $out .= '<a class="la-no-opacity la-inline-popup lahb-modal-element lahb-modal-target-link" href="'.esc_url($profile_url).'" data-href="#lahb_login_' . esc_attr($uniqid) . '" data-component_name="la-login-popup">'.esc_html($tmp_login_text).'</a>';
            }
            else {
                $out .= '<a class="la-no-opacity lahb-modal-element lahb-modal-target-link" href="' . esc_url($profile_url) . '" data-component_name="la-login-popup">'.esc_html($tmp_logout_text).'</a>';
            }
        }
        $out .= '<div class="' . $icon_alignment . 'lahb-icon-element hcolorf">';
        if ($login_type == 'text' || $login_type == 'icon_text') {
            $out .= $login_text;
        }
        else {
            $out .= $show_avatar;
        }
        $out .= '</div>';
        if ($open_form == 'dropdown') {
            $out .= '<a class="la-no-opacity lahb-trigger-element js-login_trigger_dropdown" href="'.esc_url($profile_url).'" data-href="#lahb_login_' . esc_attr($uniqid) . '">'.esc_html($tmp_login_text).'</a>';
        }
        if ($once_run_flag) {
            if ($open_form == 'modal') {
                $out .= '<div id="lahb_login_' . esc_attr($uniqid) . '" class="lahb-element--dontcopy lahb-modal-login modal-login">';
            }
            elseif ($open_form == 'dropdown') {
                $out .= '<div id="lahb_login_' . esc_attr($uniqid) . '" class="lahb-element--dontcopy lahb-modal-login la-element-dropdown">';
            }
            ob_start();
            if (function_exists('lahb_login_form')) {
                lahb_login_form($force_display_loginform);
            }
            $out .= ob_get_clean();
            $out .= '</div>';
        }
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'login', 'lahb_login' , ['login_text', 'tooltip_text']);

<?php
if(!function_exists('lahb_logo')) {
    function lahb_logo($atts, $uniqid, $once_run_flag = true) {
        $screen_view_index = 'desktop-view';
        if (isset($atts['screen_view_index'])) {
            $screen_view_index = $atts['screen_view_index'];
        }
        if (!$once_run_flag && $screen_view_index != 'mobiles-view' && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [Logo ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'type' => 'image',
            'logo' => '',
            'transparent_logo' => '',
            'logo_text' => '',
            'extra_class' => '',
        ), $atts));
        $out = $styles = '';
        $has_lg_value = false;
        $has_lgt_value = false;
        $tmp_logo = apply_filters('LaStudio_Builder/logo_id', false);
        $tmp_logo_transparency = apply_filters('LaStudio_Builder/logo_transparency_id', $tmp_logo);
        if (!empty($tmp_logo) && !is_attachment($tmp_logo)) {
            $logo = $tmp_logo;
            $has_lg_value = true;
        }
        if (!empty($tmp_logo_transparency) && !is_attachment($tmp_logo_transparency)) {
            $transparent_logo = $tmp_logo_transparency;
            $has_lgt_value = true;
        }
        $logo_width = $logo_width2 = 200;
        $logo_height = $logo_height2 = 50;
        $logo_info = wp_get_attachment_image_src($logo);
        if (!empty($logo_info)) {
            $logo_width = $logo_info[1];
            $logo_height = $logo_info[2];
        }
        $logo_info2 = wp_get_attachment_image_src($transparent_logo);
        if (!empty($logo_info2)) {
            $logo_width2 = $logo_info2[1];
            $logo_height2 = $logo_info2[2];
        }
        $logo_text = !empty($logo_text) ? LAHB_Helper::translate_string($logo_text, 'Text' . $com_uniqid) : get_bloginfo('name');
        $logo = $logo ? wp_get_attachment_url($logo) : get_theme_file_uri('/assets/images/logo.svg');
        $transparent_logo = $transparent_logo ? wp_get_attachment_url($transparent_logo) : $logo;
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'logo', '.logo_' . esc_attr($uniqid) . ' img.lahb-logo');
            $dynamic_style .= lahb_styling_tab_output($atts, 'transparent_logo', '.logo_' . esc_attr($uniqid) . ' img.logo--transparency');
            $dynamic_style .= lahb_styling_tab_output($atts, 'text', '#lastudio-header-builder .logo_' . esc_attr($uniqid) . ' .la-site-name');
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        $logo = apply_filters('LaStudio_Builder/logo_url', $logo, $has_lg_value);
        $transparent_logo = apply_filters('LaStudio_Builder/logo_transparency_url', $transparent_logo, $has_lgt_value);
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-logo' . esc_attr($extra_class) . ' logo_' . esc_attr($uniqid) . '"><a href="' . esc_url(home_url('/')) . '" rel="home">';
        if ((!empty($logo) || !empty($transparent_logo)) && $type == 'image') {
            $src = $logo;
            $src_transparency = $transparent_logo;
            $out .= '<img class="lahb-logo logo--normal" src="' . esc_url($src) . '" alt="' . esc_attr($logo_text) . '" width="' . esc_attr($logo_width) . '" height="' . esc_attr($logo_height) . '"/><img class="lahb-logo logo--transparency" src="' . esc_url($src_transparency) . '" alt="' . esc_attr($logo_text) . '"  width="' . esc_attr($logo_width2) . '" height="' . esc_attr($logo_height2) . '"/>';
        }
        else {
            $out .= '<span class="la-site-name">' . $logo_text . '</span>';
        }
        $out .= '</a></div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'logo', 'lahb_logo', ['logo_text'] );

<?php
if(!function_exists('lahb_icon_content')) {
    function lahb_icon_content($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [IconContent ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'content' => '',
            'icon' => '',
            'extra_class' => '',
            'link' => '',
            'link_new_tab' => 'false',
        ), $atts));
        $out = '';
        $content = !empty($content) ? '<div class="content_el">' . LAHB_Helper::translate_string($content, 'Content' . $com_uniqid) . '</div>' : '';
        $icon = !empty($icon) ? '<i class="icon_el ' . lahb_rename_icon($icon) . '" ></i>' : '';
        $link_new_tab = $link_new_tab == 'true' ? 'target="_blank"' : '';
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'text', '#lastudio-header-builder .icon_content_' . esc_attr($uniqid) . ' .content_el', '#lastudio-header-builder .icon_content_' . esc_attr($uniqid) . ':hover .content_el');
            $dynamic_style .= lahb_styling_tab_output($atts, 'icon', '#lastudio-header-builder .icon_content_' . esc_attr($uniqid) . ' i.icon_el', '#lastudio-header-builder .icon_content_' . esc_attr($uniqid) . ':hover i.icon_el');
            $dynamic_style .= lahb_styling_tab_output($atts, 'background', '#lastudio-header-builder .icon_content_' . esc_attr($uniqid) . '');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .icon_content_' . esc_attr($uniqid) . '');
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-content' . esc_attr($extra_class) . ' icon_content_' . esc_attr($uniqid) . '">';
        if (!empty ($link)) {
            $out .= '<a href="' . esc_attr(LAHB_Helper::translate_string($link, 'Link' . $com_uniqid)) . '" ' . $link_new_tab . '>';
        }
        $out .= $icon . $content;
        if (!empty ($link)) {
            $out .= '</a>';
        }
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'icon-content', 'lahb_icon_content', ['content','link']);

<?php
if(!function_exists('lahb_icon_menu')) {
    function lahb_icon_menu($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [IconMenu ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'menu' => '',
            'show_tooltip' => 'false',
            'tooltip_text' => 'Search',
            'tooltip_position' => 'tooltip-on-bottom',
            'extra_class' => '',
            'menu_icon' => '',
            'menu_text' => '',
            'menu_text_pos' => 'left',
        ), $atts));
        $out = '';
        $menu_out = '';
        if (!empty($menu)) {
            $menu = LAHB_Helper::translate_string($menu, 'MenuID' . $com_uniqid);
            if (is_nav_menu($menu)) {
                $menu_out = wp_nav_menu(array(
                    'menu' => $menu,
                    'container' => false,
                    'depth' => '2',
                    'fallback_cb' => array(
                        'LAHB_Nav_Walker',
                        'fallback'
                    ),
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'echo' => false,
                    'walker' => new LAHB_Nav_Walker()
                ));
            }
        }
        $main_icon = !empty($menu_icon) ? '<i class="' . lahb_rename_icon($menu_icon) . '"></i>' : '';
        if (!empty($menu_text)) {
            if ($menu_text_pos == 'left') {
                $main_icon = '<span>' . LAHB_Helper::translate_string($menu_text, 'Menu Text' . $com_uniqid) . '</span>' . $main_icon;
            }
            else {
                $main_icon .= '<span>' . LAHB_Helper::translate_string($menu_text, 'Menu Text' . $com_uniqid) . '</span>';
            }
        }
        // tooltip
        $tooltip = $tooltip_class = '';
        if ($show_tooltip == 'true' && !empty($tooltip_text)) :
            $tooltip_position = (isset($tooltip_position) && $tooltip_position) ? $tooltip_position : 'tooltip-on-bottom';
            $tooltip_class = ' lahb-tooltip ' . $tooltip_position;
            $tooltip = ' data-tooltip=" ' . esc_attr(LAHB_Helper::translate_string($tooltip_text, 'Tooltip Text' . $com_uniqid)) . ' "';
        endif;
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'icon', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ' .la-icon-menu-icon i', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ':hover .la-icon-menu-icon i');
            $dynamic_style .= lahb_styling_tab_output($atts, 'text', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ' .la-icon-menu-icon span', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ':hover .la-icon-menu-icon span');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid));
            $dynamic_style .= lahb_styling_tab_output($atts, 'dropdown_box', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ' .lahb-icon-menu-content');
            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_item', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ' .lahb-icon-menu-content .menu li');
            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_item_text', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ' .lahb-icon-menu-content li > a', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ':hover .lahb-icon-menu-content li:hover > a');
            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_item_icon', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ' .lahb-icon-menu-content .menu li > a .mm-icon', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . ':hover .lahb-icon-menu-content li:hover > a .mm-icon');
            $dynamic_style .= lahb_styling_tab_output($atts, 'tooltip', '#lastudio-header-builder .icon_menu_' . esc_attr($uniqid) . '.lahb-tooltip[data-tooltip]:before');
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-header-dropdown lahb-icon-menu-wrap lahb-icon-menu ' . esc_attr($tooltip_class . $extra_class) . ' icon_menu_' . esc_attr($uniqid) . '"' . $tooltip . '>';
        $out .= '<a href="#" class="lahb-trigger-element js-icon_menu_trigger"></a><div class="la-icon-menu-icon lahb-icon-element hcolorf ">' . $main_icon . '</div>';
        $out .= '<div class="la-element-dropdown lahb-icon-menu-content">';
        $out .= $menu_out;
        $out .= '</div>';
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'icon-menu', 'lahb_icon_menu', ['menu','tooltip_text', 'menu_text'] );

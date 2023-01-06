<?php
if(!function_exists('lahb_cart')) {
    function lahb_cart($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [Cart ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT).']';
        extract(LAHB_Helper::component_atts(array(
            'cart_icon' => 'lastudioicon-shopping-cart-1',
            'cart_text' => '',
            'show_tooltip' => 'false',
            'tooltip' => 'Cart',
            'tooltip_position' => 'tooltip-on-bottom',
            'extra_class' => '',
        ), $atts));
        $out = '';
        if (strlen($cart_icon) < 2) {
            $cart_icon = 'lastudioicon-shopping-cart-1';
        }
        $icon = lahb_rename_icon($cart_icon);
        // tooltip
        $tooltip = $tooltip_class = '';
        if ($show_tooltip == 'true' && !empty($tooltip_text)) :
            $tooltip_position = (isset($tooltip_position) && $tooltip_position) ? $tooltip_position : 'tooltip-on-bottom';
            $tooltip_class = ' lahb-tooltip ' . $tooltip_position;
            $tooltip = ' data-tooltip=" ' . esc_attr(LAHB_Helper::translate_string($tooltip_text, 'Tooltip Text' . $com_uniqid)) . ' "';
        endif;
        $cart_count = '';
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'icon', '#lastudio-header-builder .cart_' . esc_attr($uniqid) . ' > .la-cart-modal-icon > i', '#lastudio-header-builder .cart_' . esc_attr($uniqid) . ':hover > .la-cart-modal-icon i');
            $dynamic_style .= lahb_styling_tab_output($atts, 'text', '#lastudio-header-builder .cart_' . esc_attr($uniqid) . ' .header-cart-text');
            $dynamic_style .= lahb_styling_tab_output($atts, 'counter', '#lastudio-header-builder .cart_' . esc_attr($uniqid) . ' .header-cart-count-icon');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .cart_' . esc_attr($uniqid) . '');
            $dynamic_style .= lahb_styling_tab_output($atts, 'tooltip', '#lastudio-header-builder .cart_' . esc_attr($uniqid) . '.lahb-tooltip[data-tooltip]:before');
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        $cart_url = '';
        if (LAHB_Helper::is_frontend_builder()) {
            $cart_count = 0;
        }
        else {
            if (function_exists('WC')) {
                $cart_count = (!empty(WC()->cart) && !WC()->cart->is_empty()) ? WC()->cart->get_cart_contents_count() : 0;
                $cart_url = wc_get_cart_url();
            }
            else {
                $cart_count = 0;
            }
        }
        $c_text = '';
        if (!empty($cart_text)) {
            $c_text = '<span class="header-cart-text">' . esc_html(LAHB_Helper::translate_string($cart_text, 'Cart Text' . $com_uniqid)) . '</span>';
        }
        // render
        $out = '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-wrap lahb-cart' . esc_attr($tooltip_class . $extra_class) . ' lahb-header-woo-cart-toggle cart_' . esc_attr($uniqid) . '"' . $tooltip . '><a href="' . esc_url($cart_url) . '" class="la-cart-modal-icon lahb-icon-element hcolorf "><span class="header-cart-count-icon colorb component-target-badge la-cart-count" data-cart_count= ' . $cart_count . ' >';
        $out .= $cart_count;
        $out .= '</span><i data-icon="' . $icon . '" class="cart-i_icon ' . $icon . '"></i>' . $c_text . '</a>';
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'cart', 'lahb_cart', ['tooltip'] );
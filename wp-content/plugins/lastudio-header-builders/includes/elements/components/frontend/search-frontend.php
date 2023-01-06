<?php
if(!function_exists('lahb_search')) {
    function lahb_search($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [Search ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'type' => 'simple',
            'icon_type' => 'la',
            'search_icon' => 'lastudioicon-zoom-1',
            'placeholder_text' => 'Search',
            'show_tooltip' => 'false',
            'tooltip_text' => 'Search',
            'tooltip_position' => 'tooltip-on-bottom',
            'top_arrow' => 'false',
            'searchbox_icon' => 'false',
            'text_beside_icon' => 'Search',
            'text_before_form' => 'Start typing and press Enter to search',
            'extra_class' => '',
            'is_product_search' => 'false',
            'show_category_dropdown' => 'false',
            'category_exclude' => ''
        ), $atts));
        $out = '';
        $extra_html = '';
        $dropdown_html = '';
        if ($show_category_dropdown == 'true') {
            $cat_dropdown_args = array(
                'show_option_all' => esc_html__('All Categories', 'lastudio-header-builder'),
                'name' => 'category_name',
                'id' => 'lasf_dropdown_category',
                'hierarchical' => true,
                'value_field' => 'slug',
                'echo' => 0,
                'selected' => get_query_var('category_name')
            );
            if ($is_product_search == 'true') {
                $cat_dropdown_args['name'] = 'product_cat';
                $cat_dropdown_args['taxonomy'] = 'product_cat';
                $cat_dropdown_args['selected'] = get_query_var('product_cat');
            }
            $dropdown_html = wp_dropdown_categories($cat_dropdown_args);
            $dropdown_html = str_replace("id='lasf_dropdown_category'", '', $dropdown_html);
        }
        if ($is_product_search == 'true') {
            $extra_html = '<input type="hidden" value="product" name="post_type" />';
        }
        $extra_html .= '<button type="reset" class="search-button search-reset"><i class="lastudioicon-e-remove"></i></button><button class="search-button" type="submit"><i class="' . esc_attr($search_icon) . '"></i></button>';
        // login
        $placeholder_text = !empty($placeholder_text) ? LAHB_Helper::translate_string($placeholder_text, 'Placeholder' . $com_uniqid) : __('Search', 'lastudio-header-builder');
        $text_beside_icon = !empty($text_beside_icon) ? '<span class="search-toggle-txt">' . LAHB_Helper::translate_string($text_beside_icon, 'Text Beside Icon' . $com_uniqid) . '</span>' : '';
        $text_beside_icon = (!empty($text_beside_icon) && $type == 'toggle') ? $text_beside_icon : '';
        // tooltip
        $tooltip = $tooltip_class = '';
        if ($show_tooltip == 'true' && !empty($tooltip_text)) :
            $tooltip_position = (isset($tooltip_position) && $tooltip_position) ? $tooltip_position : 'tooltip-on-bottom';
            $tooltip_class = ' lahb-tooltip ' . $tooltip_position;
            $tooltip = ' data-tooltip=" ' . esc_attr(LAHB_Helper::translate_string($tooltip_text, 'Tooltip Text' . $com_uniqid)) . ' "';
        endif;
        // styles
        if ($once_run_flag) {
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'icon', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > a > i, #lastudio-header-builder .search_' . esc_attr($uniqid) . ' > a > i:before, #lastudio-header-builder .search_' . esc_attr($uniqid) . ' form .search-button', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ':hover > a > i, #lastudio-header-builder .search_' . esc_attr($uniqid) . ':hover form .search-button, #lastudio-header-builder .search_' . esc_attr($uniqid) . ':hover a i:before');
            $dynamic_style .= lahb_styling_tab_output($atts, 'custom_text', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > a > span.search-toggle-txt, #lastudio-header-builder .search_' . esc_attr($uniqid) . ' > a:hover > span.search-toggle-txt');
            $dynamic_style .= lahb_styling_tab_output($atts, 'background', '#lastudio-header-builder .search_' . esc_attr($uniqid) . '');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .search_' . esc_attr($uniqid) . '');
            $dynamic_style .= lahb_styling_tab_output($atts, 'tooltip', '#lastudio-header-builder .search_' . esc_attr($uniqid) . '.lahb-tooltip[data-tooltip]:before');
            $dynamic_style .= lahb_styling_tab_output($atts, 'search_form', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .lahb-search-form-box,.header-search-full-wrap,.main-slide-toggle #header-search-modal, .main-slide-toggle #header-search-modal .header-search-content,#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .header-search-simple-wrap');
            $dynamic_style .= lahb_styling_tab_output($atts, 'search_form_dropdown', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .lahb-search-form-box .postform,#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .header-search-simple-wrap .postform,.header-search-full-wrap > form input, #header-search-modal .postform');
            $dynamic_style .= lahb_styling_tab_output($atts, 'search_form_input', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .lahb-search-form-box .search-field,#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .header-search-simple-wrap .search-field,.header-search-full-wrap > form input, #header-search-modal .search-field');
            $dynamic_style .= lahb_styling_tab_output($atts, 'full_page_search', '.searchform-fly-overlay');
            $dynamic_style .= lahb_styling_tab_output($atts, 'arrow', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' > .lahb-search-form-box:before');
            $dynamic_style .= lahb_styling_tab_output($atts, 'search_box_icon', '#lastudio-header-builder .search_' . esc_attr($uniqid) . ' form .search-button');
            if ($dynamic_style) {
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            }
            if ($top_arrow == 'true') {
                LAHB_Helper::set_dynamic_styles('#lastudio-header-builder .lahb-search-form-box:after, #lastudio-header-builder .lahb-search-form-box:before { display: none; }');
            }
            if ($searchbox_icon == 'true') {
                LAHB_Helper::set_dynamic_styles('#lastudio-header-builder .lahb-search form .search-button { display: none; }');
            }
        }
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        $toggle_trigger = ($type == 'toggle') ? 'lahb-icon-element-toggle' : 'lahb-icon-element-slide';
        if ($type == 'toggle') {
            $toggle_trigger = 'lahb-icon-element-toggle js-search_trigger_toggle';
        }
        elseif ($type == 'slide') {
            $toggle_trigger = 'lahb-icon-element-slide js-search_trigger_slide';
        }
        elseif ($type == 'full') {
            $toggle_trigger = 'lahb-icon-element-full js-search_trigger_full';
        }
        else {
            $toggle_trigger = 'simple';
        }

        $ajax_result_html = '<div class="search-results"><div class="loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div><div class="results-container"></div></div>';

        $search_icon_html = '<i class="' . esc_attr($search_icon) . '"></i>';
        $search_form_html = '<form class="search-form" role="search" action="' . esc_url(home_url('/')) . '" method="get" >' . $dropdown_html . '<input autocomplete="off" name="s" type="text" class="search-field" placeholder="' . esc_attr($placeholder_text) . '">' . $extra_html . '</form>';
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-wrap lahb-search ' . esc_attr($tooltip_class . $extra_class) . ' lahb-header-' . $type . ' search_' . esc_attr($uniqid) . '"' . $tooltip . '>';
        if ($type != 'simple') {
            $out .= '<a href="#" class="lahb-icon-element ' . $toggle_trigger . ' hcolorf ">' . $search_icon_html . $text_beside_icon . '</a>';
            $out .= '';
        }
        if ($type == 'toggle') {
            $out .= '<div id="lahb-search-form-box" class="lahb-search-form-box js-contentToggle__content la-ajax-searchform">' . $search_form_html . $ajax_result_html . '</div>';
        }
        elseif ($type == 'slide') {
            $out .= '<div class="header-search-modal-wrap">';
            if ($once_run_flag) {
                $out .= '<div id="header-search-modal" class="la-header-search"><div class="header-search-content la-ajax-searchform"><div class="col-md-12">' . $search_form_html . $ajax_result_html . '</div></div></div>';
            }
            $out .= '</div>';
        }
        elseif ($type == 'simple') {
            $out .= '<div class="header-search-simple-wrap la-ajax-searchform">' . $search_form_html . $ajax_result_html . '</div>';
        }
        elseif ($type == 'full') {
            $out .= '<div class="header-search-full-wrap lahb-element--dontcopy">';
            $out .= '<p class="searchform-fly-text">' . (!empty($text_before_form) ? LAHB_Helper::translate_string($text_before_form, 'Text Before Form' . $com_uniqid) : '') . '</p>';
            $out .= $search_form_html . '</div>';
        }
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'search', 'lahb_search' , ['placeholder_text', 'tooltip_text', 'text_beside_icon', 'text_before_form']);
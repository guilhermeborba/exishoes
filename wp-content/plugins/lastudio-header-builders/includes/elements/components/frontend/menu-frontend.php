<?php
if(!function_exists('lahb_menu_f')) {
    function lahb_menu_f($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            $tmp = isset($atts['show_mobile_menu']) && $atts['show_mobile_menu'] == 'false' ? false : true;
            $before_output = '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
            if (filter_var($tmp, FILTER_VALIDATE_BOOLEAN)) {
                $before_output .= '<div data-element2-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder2"></div>';
            }
            return $before_output;
        }
        $com_uniqid = ' [Menu ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) .']';
        static $has_run_primary_menu = true;
        $is_vertical = $vertical_text = '';
        extract(LAHB_Helper::component_atts(array(
            'menu' => '',
            'desc_item' => 'false',
            'full_menu' => 'false',
            'height_100' => 'false',
            'extra_class' => '',
            'show_mobile_menu' => 'true',
            'show_tablet_menu' => 'false',
            'mobile_menu_display_width' => '',
            'show_parent_arrow' => 'true',
            'parent_arrow_direction' => 'bottom',
            'show_megamenu' => 'false',
            'hamburger_icon' => '',
            'screen_view_index' => '',
            'is_vertical' => 'false',
            'vertical_text' => '',
            'hm_box_pos' => 'left',
        ), $atts));


        if (filter_var($is_vertical, FILTER_VALIDATE_BOOLEAN)) {
            //$show_mobile_menu = 'false';
        }

	    // extra class
	    $extra_class = !empty($extra_class) ? ' ' . $extra_class : '';

        $extra_class2 = '';
        $out = $parent_arrow = '';
        $toggle_html = '';
	    if (filter_var($desc_item, FILTER_VALIDATE_BOOLEAN)) {
	        $extra_class .= ' has-desc-item';
        }
	    if (filter_var($full_menu, FILTER_VALIDATE_BOOLEAN)) {
	        $extra_class .= ' full-width-menu';
        }
        $show_mobile_menu_class = $show_mobile_menu == 'false' ? ' hide-menu-on-mobile' : '';
        if ( empty($hamburger_icon) || $hamburger_icon == 'none' ) {
            $hamburger_icon = 'lastudioicon-menu-4-1';
        }
        $hamburger_icon = !empty($hamburger_icon) ? '<i class="' . lahb_rename_icon($hamburger_icon) . '"></i>' : '';
        if (filter_var($show_megamenu, FILTER_VALIDATE_BOOLEAN)) {
	        $extra_class .= ' has-megamenu';
        }
        if (filter_var($show_parent_arrow, FILTER_VALIDATE_BOOLEAN)) {
	        $extra_class .= ' has-parent-arrow';
            switch ($parent_arrow_direction) {
                case 'top':
	                $extra_class .= ' arrow-top';
                    break;
                case 'right':
	                $extra_class .= ' arrow-right';
                    break;
                case 'bottom':
	                $extra_class .= ' arrow-bottom';
                    break;
                case 'left':
	                $extra_class .= ' arrow-left';
                    break;
            }
        }
        $menu_d_args = array(
            'container' => false,
            'depth' => '5',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'echo' => false
        );
	    if(function_exists('lastudio_get_theme_support') && lastudio_get_theme_support('header-builder::menu')){
		    $menu_d_args['show_megamenu'] = $show_megamenu;
	    }
        if ($once_run_flag) :
            $has_nav = false;
            if (!empty($menu)) {
                $menu = LAHB_Helper::translate_string($menu, 'MenuID' . $com_uniqid);
            }
            if (is_nav_menu($menu)) {
                $menu_d_args['menu'] = $menu;
                $has_nav = true;
            }
            else {
                if ($has_run_primary_menu) {
                    $has_nav = true;
                    $menu_d_args['theme_location'] = 'main-nav';
                }
            }
            if ($has_nav) {
                $menu_out = wp_nav_menu(array_merge($menu_d_args, array(
                    'show_megamenu' => $show_megamenu,
                    'fallback_cb' => array(
                        'LAHB_Nav_Walker',
                        'fallback'
                    ),
                    'walker' => new LAHB_Nav_Walker()
                )));
                if ($show_mobile_menu == 'true') {
                    $responsive_menu_out = '';
                    $menu_d_args2 = array_merge($menu_d_args, array(
                        'menu_class' => 'responav menu'
                    ));
                    if(!has_filter('lastudio/header-builder/lahb_menu_responsive_output')){
                        $responsive_menu_out = wp_nav_menu(array_merge($menu_d_args2, array(
                            'fallback_cb' => array(
                                'LAHB_Nav_Walker',
                                'fallback'
                            ),
                            'walker' => new LAHB_Nav_Walker()
                        )));
                    }
                    $responsive_menu_out = apply_filters('lastudio/header-builder/lahb_menu_responsive_output', $responsive_menu_out, $menu_d_args2);
                }
            }
            else {
                $menu_out = '<div class="lahb-element"><span>' . esc_html__('Your menu is empty or not selected! ', 'lastudio-header-builder') . '<a href="https://codex.wordpress.org/Appearance_Menus_Screen" class="sf-with-ul hcolorf" target="_blank">' . esc_html__('How to config a menu', 'lastudio-header-builder') . '</a></span></div>';
                $responsive_menu_out = $show_mobile_menu == 'true' ? $menu_out : '';
            }
            $dynamic_style = '';

            if(function_exists('lastudio_get_theme_support') && lastudio_get_theme_support('header-builder::menu')){
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_item', '.nav__wrap_' . esc_attr($uniqid) . ' li.mm-menu-item > .top-level-link, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .top-level-link', '.nav__wrap_' . esc_attr($uniqid) . ' li.mm-menu-item:hover > .top-level-link,.lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover > .top-level-link');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'current_menu_item', '.nav__wrap_' . esc_attr($uniqid) . ' li.current > .top-level-link,.lahb-responsive-menu-' . esc_attr($uniqid) . ' li.current > .top-level-link', '.nav__wrap_' . esc_attr($uniqid) . ' li.current:hover > .top-level-link,.lahb-responsive-menu-' . esc_attr($uniqid) . ' li.current:hover > .top-level-link');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'parent_menu_arrow', '.nav__wrap_' . esc_attr($uniqid) . '.has-parent-arrow .menu-item-has-children > .top-level-link:before, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .top-level-link .lahb_icon--accordion', '.nav__wrap_' . esc_attr($uniqid) . '.has-parent-arrow .menu-item-has-children:hover > .top-level-link:before, .lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover .top-level-link .lahb_icon--accordion');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_icon', '.nav__wrap_' . esc_attr($uniqid) . ' .top-level-link .mm-icon, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .top-level-link .mm-icon', '.nav__wrap_' . esc_attr($uniqid) . ' li:hover .top-level-link .mm-icon, .lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover .top-level-link .mm-icon');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_menu_icon', '.nav__wrap_' . esc_attr($uniqid) . ' .sub-level-link .mm-icon, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .sub-level-link .mm-icon', '.nav__wrap_' . esc_attr($uniqid) . ' li:hover > .sub-level-link .mm-icon, .lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover > .sub-level-link .mm-icon');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_description', '.nav__wrap_' . esc_attr($uniqid) . ' .la-menu-desc, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .la-menu-desc', '.nav__wrap_' . esc_attr($uniqid) . ' li:hover > a .la-menu-desc, .lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover > a .la-menu-desc');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_badge', '.nav__wrap_' . esc_attr($uniqid) . ' .menu-item-badge, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .menu-item-badge', '.nav__wrap_' . esc_attr($uniqid) . ' li:hover > a .menu-item-badge, .lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover > a .menu-item-badge');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_item', '.nav__wrap_' . esc_attr($uniqid) . ' .sub-level-link, .lahb-responsive-menu-' . esc_attr($uniqid) . ' .sub-level-link', '.nav__wrap_' . esc_attr($uniqid) . ' li:hover > .sub-level-link,.lahb-responsive-menu-' . esc_attr($uniqid) . ' li:hover > .sub-level-link');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_current_item', '.nav__wrap_' . esc_attr($uniqid) . ' li.current > .sub-level-link,.lahb-responsive-menu-' . esc_attr($uniqid) . ' li.current > .sub-level-link', '.nav__wrap_' . esc_attr($uniqid) . ' li.current:hover > .sub-level-link,.lahb-responsive-menu-' . esc_attr($uniqid) . ' li.current:hover > .sub-level-link');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_box', '.nav__wrap_' . esc_attr($uniqid) . ' ul.mm-sub-menu, .lahb-responsive-menu-'.esc_attr($uniqid).' ul.mm-sub-menu');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ',.nav__res_hm_icon_' . esc_attr($uniqid));
	            $dynamic_style .= lahb_styling_tab_output($atts, 'responsive_menu_box', '.lahb-responsive-menu-' . esc_attr($uniqid));
	            $dynamic_style .= lahb_styling_tab_output($atts, 'responsive_hamburger_icon', '.nav__res_hm_icon_' . esc_attr($uniqid) . ' a');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'toggle_button', '.nav__wrap_' . esc_attr($uniqid) . ' .lahb-vertital-menu_button > button');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'vertical_dropdown', '.nav__wrap_' . esc_attr($uniqid) . '.vertital-menu_nav-hastoggle > .menu');
	            if ($dynamic_style) {
		            LAHB_Helper::set_dynamic_styles($dynamic_style);
	            }
	            if (filter_var($height_100, FILTER_VALIDATE_BOOLEAN) && !filter_var($is_vertical, FILTER_VALIDATE_BOOLEAN)) {
		            LAHB_Helper::set_dynamic_styles('#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ', #lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu, .nav__wrap_' . esc_attr($uniqid) . ' .menu > li, .nav__wrap_' . esc_attr($uniqid) . ' .menu > li > a { height: 100%; }');
	            }
            }
            else{
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_item', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' > ul > li > a,.lahb-responsive-menu-' . esc_attr($uniqid) . ' .responav li.menu-item > a:not(.button)', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' > ul > li:hover > a,.lahb-responsive-menu-' . esc_attr($uniqid) . ' .responav li.menu-item:hover > a:not(.button)');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'current_menu_item', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li.current > a, #lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li.menu-item > a.active, #lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu ul.sub-menu li.current > a,.lahb-responsive-menu-' . esc_attr($uniqid) . ' .responav li.current-menu-item > a:not(.button)');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'current_item_shape', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li.current > a:after', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li.current:hover > a:after');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'parent_menu_arrow', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . '.has-parent-arrow > ul > li.menu-item-has-children:before,#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . '.has-parent-arrow > ul > li.mega > a:before');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_icon', '#lastudio-header-builder .lahb-responsive-menu-' . esc_attr($uniqid) . ' .responav > li > a .la-menu-icon, #lastudio-header-builder .lahb-responsive-menu-' . esc_attr($uniqid) . ' .responav > li:hover > a .la-menu-icon, #lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li > a .la-menu-icon', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li > a:hover .la-menu-icon');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_menu_icon', '#lastudio-header-builder .lahb-responsive-menu-' . esc_attr($uniqid) . ' .responav > li > ul.sub-menu a .la-menu-icon, #lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu .sub-menu .la-menu-icon', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu .sub-menu li a:hover .la-menu-icon');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_description', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .la-menu-desc');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_badge', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu a span.menu-item-badge');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_item', '.lahb-nav-wrap.nav__wrap_' . esc_attr($uniqid) . ' .menu ul li.menu-item a, .lahb-responsive-menu-'.esc_attr($uniqid).' .responav li.menu-item li.menu-item > a');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_current_item', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu ul.sub-menu li.current > a, .lahb-responsive-menu-'.esc_attr($uniqid).' .responav li.menu-item li.menu-item.current > a');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_box', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu > li:not(.mega) ul, .lahb-responsive-menu-'.esc_attr($uniqid).' .responav li.menu-item > ul.sub-menu');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ',.nav__res_hm_icon_' . esc_attr($uniqid));
	            $dynamic_style .= lahb_styling_tab_output($atts, 'responsive_menu_box', '.lahb-responsive-menu-' . esc_attr($uniqid));
	            $dynamic_style .= lahb_styling_tab_output($atts, 'responsive_hamburger_icon', '.nav__res_hm_icon_' . esc_attr($uniqid) . ' a');
	            $dynamic_style .= lahb_styling_tab_output($atts, 'toggle_button', '.nav__wrap_' . esc_attr($uniqid) . ' .lahb-vertital-menu_button > button');
	            if ($dynamic_style) {
		            LAHB_Helper::set_dynamic_styles($dynamic_style);
	            }
	            if (filter_var($height_100, FILTER_VALIDATE_BOOLEAN) && !filter_var($is_vertical, FILTER_VALIDATE_BOOLEAN)) {
		            LAHB_Helper::set_dynamic_styles('#lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ', #lastudio-header-builder .nav__wrap_' . esc_attr($uniqid) . ' .menu, .nav__wrap_' . esc_attr($uniqid) . ' .menu > li, .nav__wrap_' . esc_attr($uniqid) . ' .menu > li > a { height: 100%; }');
	            }
            }
        endif;

        if (filter_var($is_vertical, FILTER_VALIDATE_BOOLEAN)) {
            if (!empty($vertical_text)) {
                $toggle_html = '<div class="lahb-vertital-menu_button"><button>' . LAHB_Helper::translate_string($vertical_text, 'Vertical Text'. $com_uniqid) . '</button></div>';
            }
            $extra_class .= ' lahb-vertital-menu_nav';
            if (empty($vertical_text)) {
                $extra_class .= ' vertital-menu_nav-notoggle';
            }
            else {
                $extra_class .= ' vertital-menu_nav-hastoggle';
            }
        }
        if (filter_var($show_tablet_menu, FILTER_VALIDATE_BOOLEAN)) {
            $extra_class2 = ' keep-menu-on-tablet';
            $extra_class .= $extra_class2;
        }
	    $nav_schema = '';
        // render
        if (filter_var($show_mobile_menu, FILTER_VALIDATE_BOOLEAN)) {
            if ($once_run_flag) {
                // responsive menu
                $out .= '<div class="lahb-element--dontcopy hm-res_m-pos--'.esc_attr($hm_box_pos).' lahb-responsive-menu-wrap lahb-responsive-menu-' . esc_attr($uniqid). ((filter_var($desc_item, FILTER_VALIDATE_BOOLEAN)) ? ' has-desc-item' : '') . '" data-uniqid="' . esc_attr($uniqid) . '"><div class="close-responsive-nav"><div class="lahb-menu-cross-icon"></div></div>' . $responsive_menu_out . '</div>';
                // normal menu
                $out .= '<nav data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-nav-wrap' . esc_attr($extra_class) . $show_mobile_menu_class . ' nav__wrap_' . esc_attr($uniqid) . '" data-uniqid="' . esc_attr($uniqid) . '"' . $nav_schema . '>' . $toggle_html . $menu_out . '</nav>';
            }
            $out .= '<div data-element2-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-responsive-menu-icon-wrap nav__res_hm_icon_' . esc_attr($uniqid) . $extra_class2 . '" data-uniqid="' . esc_attr($uniqid) . '"><a href="#">' . $hamburger_icon . '</a></div>';
        }
        else {
            $menu_out = $toggle_html;
            $menu_out .= wp_nav_menu(array_merge($menu_d_args, array(
                'menu' => $menu,
                'show_megamenu' => $show_megamenu,
                'fallback_cb' => array(
                    'LAHB_Nav_Walker',
                    'fallback'
                ),
                'walker' => new LAHB_Nav_Walker()
            )));
            // normal menu
            $out .= '<nav data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-nav-wrap' . esc_attr($extra_class). $show_mobile_menu_class . ' nav__wrap_' . esc_attr($uniqid) . '" data-uniqid="' . esc_attr($uniqid) . '"' . $nav_schema . '>' . $menu_out . '</nav>';
        }
        if (!filter_var($is_vertical, FILTER_VALIDATE_BOOLEAN)) {
            $has_run_primary_menu = false;
        }
        return $out;
    }
}
LAHB_Helper::add_element( 'menu', 'lahb_menu_f', ['menu','vertical_text'] );
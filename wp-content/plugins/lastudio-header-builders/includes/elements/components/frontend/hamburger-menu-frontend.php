<?php
if(!function_exists('lahb_hamburger_menu')) {
    function lahb_hamburger_menu($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [HamburgerMenu ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'menu' => '',
            'display_menu' => 'true',
            'hamburger_type' => 'toggle',
            'hamburger_icon' => 'lastudioicon-menu-4-1',
            'hamburger_text' => '',
            'hm_style' => 'light',
            'toggle_from' => 'right',
            'image_logo' => '',
            'socials' => 'false',
            'search' => 'false',
            'placeholder' => 'Search ...',
            'content' => 'false',
            'text_content' => '',
            'copyright' => '',
            'extra_class' => '',
            'extra_class_panel' => '',
        ), $atts));
        $out = $menu_out = '';
        $dark_wrap = ($hm_style == 'dark') ? 'dark-wrap' : 'light-wrap';
        $menu_style = ($hm_style == 'dark') ? 'hm-dark' : '';
        $hamburger_type = $hamburger_type ? $hamburger_type : 'toggle';
        $menu_list_style = ($hamburger_type == 'toggle') ? 'toggle-menu' : 'full-menu';
	    if(function_exists('lastudio_get_theme_support') && lastudio_get_theme_support('header-builder::menu')){
		    $menu_list_style .= ' menu';
	    }
        $image_logo = $image_logo ? wp_get_attachment_image($image_logo, 'full', false, ['alt' => get_bloginfo('name'), 'class' => 'hamburger-logo-image']) : '';
        if ($hamburger_icon == '4line' || $hamburger_icon == '3line') {
            $hamburger_icon = 'lastudioicon-menu-4-1';
        }
        $hamburger_icon = !empty($hamburger_icon) ? '<i class="' . lahb_rename_icon($hamburger_icon) . '" ></i>' : '';
        if (!empty($hamburger_text)) {
            $hamburger_icon .= '<span>' . LAHB_Helper::translate_string($hamburger_text, 'Hamburger Text' . $com_uniqid) . '</span>';
        }
        if ($hamburger_type == 'toggle') {
            $toggle_from = ($toggle_from == 'right') ? 'toggle-right' : 'toggle-left';
        }
        else {
            $toggle_from = '';
        }
        if (!empty($menu) && $display_menu== 'true') {
            $menu = LAHB_Helper::translate_string($menu, 'MenuID'. $com_uniqid);
            if (is_nav_menu($menu)) {

                $menu_d_args = [
                    'menu' => $menu,
                    'container' => 'nav',
                    'container_class' => 'hamburger-main',
                    'menu_class' => 'hamburger-nav ' . $menu_list_style,
                    'depth' => '5',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'echo' => false
                ];
                if(!has_filter('lastudio/header-builder/lahb_menu_responsive_output')){
                    $menu_out = wp_nav_menu(array_merge($menu_d_args, array(
                        'fallback_cb' => array(
                            'LAHB_Nav_Walker',
                            'fallback'
                        ),
                        'walker' => new LAHB_Nav_Walker()
                    )));
                }
                $menu_out = apply_filters('lastudio/header-builder/lahb_menu_responsive_output', $menu_out, $menu_d_args);
            }
        }
        else {
            $menu_out = '<div class="lahb-element"><span>' . esc_html__('Your menu is empty or not selected! ', 'lastudio-header-builder') . '<a href="https://codex.wordpress.org/Appearance_Menus_Screen" class="sf-with-ul hcolorf" target="_blank">' . esc_html__('How to config a menu', 'lastudio-header-builder') . '</a></span></div>';
        }

        if($display_menu != 'true'){
            $menu_out = '';
            $menu_style .= ' no-menu-el';
        }

        // styles
        if ($once_run_flag) :
            $elm_uniqid = '.la-hamburger-wrap-' . esc_attr($uniqid);
            $css_el_icon_box = '.lahb-element.hbgm_' . esc_attr($uniqid) . ' > a';
            $css_el_hm_box = 'body .la-hamuburger-bg' . $elm_uniqid;
            $css_el_hm_menu_box = 'body ' . $elm_uniqid . ' .hamburger-nav';
            $css_el_hm_menu_item = 'body ' . $elm_uniqid . ' .hamburger-nav > li > a';
            $css_el_hm_menu_item_hover = 'body ' . $elm_uniqid . ' .hamburger-nav > li:hover > a';
            $css_el_hm_menu_item_current = 'body ' . $elm_uniqid . ' .hamburger-nav > li.current > a';
            $css_el_hm_menu_item_current_hover = 'body ' . $elm_uniqid . ' .hamburger-nav > li.current:hover > a';
            $css_el_hm_menu_sub_item = 'body ' . $elm_uniqid . ' .hamburger-nav li li a';
            $css_el_hm_menu_sub_item_hover = 'body ' . $elm_uniqid . ' .hamburger-nav li li:hover > a';
            $css_el_hm_element_box = 'body ' . $elm_uniqid . ' .hamburger-elements';
            $css_el_hm_content = 'body ' . $elm_uniqid . ' .lahmb-text-content';
            $css_el_hm_content_hover = 'body ' . $elm_uniqid . ' .lahmb-text-content:hover';
            $css_el_hm_social = 'body ' . $elm_uniqid . ' .hamburger-social-icons a';
            $css_el_hm_social_hover = 'body ' . $elm_uniqid . ' .hamburger-social-icons a:hover';
            $css_el_hm_logo = 'body ' . $elm_uniqid . ' .hamburger-logo-image-wrap';
            $css_el_hm_copyright = 'body ' . $elm_uniqid . ' .hamburger-copyright';
            $css_el_hm_copyright_hover = 'body ' . $elm_uniqid . ' .hamburger-copyright';
            $css_el_hm_search_box = 'body ' . $elm_uniqid . ' form.search-form';
            $css_el_hm_search_input = 'body ' . $elm_uniqid . ' form.search-form .search-field';
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'hamburger_icon_color', '.hbgm_' . esc_attr($uniqid) . ' .hamburger-op-icon', '.hbgm_' . esc_attr($uniqid) . ' .hamburger-op-icon:hover');
            $dynamic_style .= lahb_styling_tab_output($atts, 'hamburger_icon_text', '.hbgm_' . esc_attr($uniqid) . ' .hamburger-op-icon span');
            $dynamic_style .= lahb_styling_tab_output($atts, 'hamburger_icon_box', $css_el_icon_box);
            $dynamic_style .= lahb_styling_tab_output($atts, 'hamburger_box', $css_el_hm_box);
            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_box', $css_el_hm_menu_box);
            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_item', $css_el_hm_menu_item, $css_el_hm_menu_item_hover);
            $dynamic_style .= lahb_styling_tab_output($atts, 'current_menu_item', $css_el_hm_menu_item_current, $css_el_hm_menu_item_current_hover);
            $dynamic_style .= lahb_styling_tab_output($atts, 'submenu_item', $css_el_hm_menu_sub_item, $css_el_hm_menu_sub_item_hover);
            $dynamic_style .= lahb_styling_tab_output($atts, 'elements_box', $css_el_hm_element_box);
            $dynamic_style .= lahb_styling_tab_output($atts, 'content', $css_el_hm_content, $css_el_hm_content_hover);
            $dynamic_style .= lahb_styling_tab_output($atts, 'socials', $css_el_hm_social, $css_el_hm_social_hover);
            $dynamic_style .= lahb_styling_tab_output($atts, 'copyright', $css_el_hm_copyright, $css_el_hm_copyright_hover);
            $dynamic_style .= lahb_styling_tab_output($atts, 'search_input', $css_el_hm_search_input);
            $dynamic_style .= lahb_styling_tab_output($atts, 'search_box', $css_el_hm_search_box);
            $dynamic_style .= lahb_styling_tab_output($atts, 'logo_box', $css_el_hm_logo);
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '.lahb-body .lahb-element.hbgm_' . esc_attr($uniqid));
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-wrap lahb-hamburger-menu ' . esc_attr($extra_class) . ' hamburger-type-' . $hamburger_type . ' ' . $dark_wrap . ' hbgm_' . esc_attr($uniqid) . '"><a href="#" data-id="' . esc_attr($uniqid) . '" class="js-hamburger_trigger lahb-icon-element close-button hcolorf hamburger-op-icon">' . $hamburger_icon . '</a>';
        if ($once_run_flag) {
            if ($hamburger_type == 'full') {
                $out .= '<div class="lahb-element--dontcopy la-hamburger-wrap-' . esc_attr($uniqid) . ' la-hamburger-wrap la-hamuburger-bg ' . esc_attr($menu_style) . ' ' . esc_attr($extra_class_panel) . '">';
                $out .= '<div class="hamburger-full-wrap">';
                $out .= '<a href="javascript:;" class="btn-close-hamburger-menu-full"><i class="lastudioicon-e-remove"></i></a>';
                if($display_menu == 'true'){
                    $out .= '<div class="lahb-hamburger-top">';
                    $out .= $menu_out;
                    $out .= '</div>';
                }
				$out .= '<div class="lahb-hamburger-bottom hamburger-elements">';
                if (!empty($image_logo)) {
                    $out .= sprintf('<div class="hamburger-logo-image-wrap"><a href="%1$s">%2$s</a></div>', home_url('/'), $image_logo);
                }
                if ($content == 'true' && !empty($text_content)) {
                    ob_start();
                    echo '<div class="lahmb-text-content">' . LAHB_Helper::remove_js_autop(LAHB_Helper::translate_string($text_content, 'Text Content' . $com_uniqid)) . '</div>';
                    $out .= ob_get_clean();
                }
                if ($socials == 'true') {
                    ob_start();
                    echo '<div class="hamburger-social-icons">';
                    do_action('lastudio/header-builder/render-social');
                    echo '</div>';
                    $out .= ob_get_clean();
                }
                $out .= '</div></div></div>';
            }
            elseif ($hamburger_type == 'toggle') {
                $out .= '<div class="lahb-element--dontcopy hamburger-menu-wrap la-hamuburger-bg hamburger-menu-content ' . esc_attr($menu_style) . ' la-hamburger-wrap-' . esc_attr($uniqid) . ' ' . $toggle_from . ' ' . esc_attr($extra_class_panel) . '">';
                $out .= '<a href="javascript:;" class="btn-close-hamburger-menu"><i class="lastudioicon-e-remove"></i></a>';
                $out .= '<div class="hamburger-menu-main">';

                $out .= '<div class="lahb-hamburger-top">';
                if (!empty($image_logo)) {
                    $out .= sprintf('<div class="hamburger-logo-image-wrap"><a href="%1$s">%2$s</a></div>', home_url('/'), $image_logo);
                }
                if($display_menu == 'true') {
                    $out .= $menu_out;
                }

                if ($search == 'true') {
                    $out .= '<div class="hm-search-form-outer">';
                    $out .= '<form role="search" class="search-form" action="' . esc_url(home_url('/')) . '" method="get"><input name="s" type="text" class="search-field hamburger-search-text-box" placeholder="' . (!empty($placeholder) ? LAHB_Helper::translate_string($placeholder, 'Placeholder' . $com_uniqid) : '') . '"><button class="search-button" type="submit"><i class="lastudioicon-zoom-1"></i></button></form>';
                    $out .= '</div>';
                }
                $out .= '</div>';
                $out .= '<div class="lahb-hamburger-bottom hamburger-elements">';
                if ($content == 'true' && !empty($text_content)) {
                    $out .= '<div class="lahmb-text-content">' . LAHB_Helper::remove_js_autop(LAHB_Helper::translate_string($text_content, 'Text Content' . $com_uniqid)) . '</div>';
                }
                if ($socials == 'true') {
                    ob_start(); ?>
                    <div class="hamburger-social-icons"><?php do_action('lastudio/header-builder/render-social'); ?></div>
                    <?php
                    $out .= ob_get_contents();
                    ob_end_clean();
                }
                if (!empty($copyright)) {
                    $out .= '<div class="lahb-hamburger-bottom hamburger-copyright">' . LAHB_Helper::translate_string($copyright, 'Copyright' . $com_uniqid) . '</div>';
                }
                $out .= '</div>'; // Close .hamburger-elements
                $out .= '</div>'; // Close .hamburger-menu-main
                $out .= '</div>';
            }
        }
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'hamburger-menu', 'lahb_hamburger_menu', ['menu', 'hamburger_text','placeholder', 'text_content', 'copyright'] );

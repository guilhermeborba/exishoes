<?php
if(!function_exists('lahb_social')) {
    function lahb_social($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [Social ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) . ']';
        extract(LAHB_Helper::component_atts(array(
            'main_social_icon' => 'icon',
            'main_icon_text' => 'Socials',
            'social_type' => 'simple',
            'toggle_text' => 'Social Network',
            'has_tooltip' => 'false',
            'tooltip_text' => 'Socials',
            'tooltip_position' => 'tooltip-on-bottom',
            'social_format' => 'icon',
            'inline' => 'true',
            'dash' => 'false',
            'social_icon_1' => 'facebook',
            'social_text_1' => 'Facebook',
            'social_url_1' => 'https://www.facebook.com/',
            'social_icon_2' => 'none',
            'social_text_2' => '',
            'social_url_2' => '',
            'social_icon_3' => 'none',
            'social_text_3' => '',
            'social_url_3' => '',
            'social_icon_4' => 'none',
            'social_text_4' => '',
            'social_url_4' => '',
            'social_icon_5' => 'none',
            'social_text_5' => '',
            'social_url_5' => '',
            'social_icon_6' => 'none',
            'social_text_6' => '',
            'social_url_6' => '',
            'social_icon_7' => 'none',
            'social_text_7' => '',
            'social_url_7' => '',
            'extra_class' => '',
            'show_tooltip' => 'false',
            'default_icon_bg' => 'false',
        ), $atts));
        $out = $link_to = '';
        // login
        $dash = $dash == 'true' ? '- ' : '';
        // tooltip
        $tooltip_position = !empty($tooltip_position) ? $tooltip_position : 'tooltip-on-bottom';
        $lastudio_socials = array(
            'dribbble' => 'lastudioicon-b-dribbble',
            'facebook' => 'lastudioicon-b-facebook',
            'flickr' => 'lastudioicon-b-flickr',
            'foursquare' => 'lastudioicon-b-foursquare',
            'github' => 'lastudioicon-b-github-circled',
            'instagram' => 'lastudioicon-b-instagram',
            'lastfm' => 'lastudioicon-b-lastfm',
            'linkedin' => 'lastudioicon-b-linkedin',
            'pinterest' => 'lastudioicon-b-pinterest',
            'reddit' => 'lastudioicon-b-reddit',
            'soundcloud' => 'lastudioicon-b-soundcloud',
            'spotify' => 'lastudioicon-b-spotify',
            'tumblr' => 'lastudioicon-b-tumblr',
            'twitter' => 'lastudioicon-b-twitter',
            'vimeo' => 'lastudioicon-b-vimeo',
            'vine' => 'lastudioicon-b-vine',
            'yelp' => 'lastudioicon-b-yelp',
            'yahoo' => 'lastudioicon-b-yahoo-1',
            'youtube' => 'lastudioicon-b-youtube-play',
            'wordpress' => 'lastudioicon-b-wordpress',
            'dropbox' => 'lastudioicon-b-dropbox',
            'evernote' => 'lastudioicon-b-evernote',
            'skype' => 'lastudioicon-b-skype',
            'telegram' => 'lastudioicon-b-telegram',
            'envato' => 'lastudioicon-envato',
        );
        // Get Social Icons
        $display_socials = '';
        for ($i = 1; $i < 8; $i++) {
            $item_text = ${"social_text_" . $i} ? ${"social_text_" . $i} : '';
            $item_url = ${"social_url_" . $i} ? ${"social_url_" . $i} : '';
            $item_icon = ${"social_icon_" . $i} ? ${"social_icon_" . $i} : 'none';
            if ($item_icon != 'none') {
                $display_socials .= '<div class="header-social-icons social-icon-' . $i . '">';
                if (!empty($item_url)) {
                    $display_socials .= '<a href="' . $item_url . '"';
                    if ($show_tooltip == 'true' && !empty($item_text)) {
                        $display_socials .= ' class="lahb-tooltip ' . $tooltip_position . '"';
                        $display_socials .= ' data-tooltip="' . esc_attr($item_text) . '"';
                    }
                    $display_socials .= ' target="_blank">';
                }
                $display_socials .= $dash;
                if ($social_format != 'text') {
                    $display_socials .= '<i class="header-social-icon ' . $lastudio_socials[$item_icon] . '"></i>';
                }
                if ($social_format != 'icon') {
                    $display_socials .= '<span class="header-social-text">' . $item_text . '</span>';
                }
                if (!empty($item_url)) {
                    $display_socials .= '</a>';
                }
                $display_socials .= '</div>';
            }
        }
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'menu_icon_text', '#lastudio-header-builder .social_' . esc_attr($uniqid) . '  .la-header-social-icon i:before,#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .la-header-social-icon span', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ':hover .la-header-social-icon i:before,#lastudio-header-builder .social_' . esc_attr($uniqid) . ':hover  .la-header-social-icon span');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder .social_' . esc_attr($uniqid) . '');
            $dynamic_style .= lahb_styling_tab_output($atts, 'social_box', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lastudio-social-icons-box,#header-social-full-wrap-' . esc_attr($uniqid) . ',.main-slide-toggle #header-social-modal');
            $dynamic_style .= lahb_styling_tab_output($atts, 'social_icon_text_box', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lastudio-social-icons-box .header-social-icons a, .lastudio-social-icons-box .header-social-icons a, #header-social-modal .header-social-icons a');
            $dynamic_style .= lahb_styling_tab_output($atts, 'social_icon', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lastudio-social-icons-box .header-social-icons a i, #header-social-modal .header-social-icons a i', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lastudio-social-icons-box .header-social-icons:hover a i, #header-social-modal .header-social-icons:hover a i');
            $dynamic_style .= lahb_styling_tab_output($atts, 'social_text', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lastudio-social-icons-box .header-social-icons a span, #header-social-modal .header-social-icons a span', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lastudio-social-icons-box .header-social-icons:hover a span, #header-social-modal .header-social-icons:hover a span');
            $dynamic_style .= lahb_styling_tab_output($atts, 'tooltip', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lahb-tooltip[data-tooltip]:before, #lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lahb-tooltip[data-tooltip]:after', '#lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lahb-tooltip[data-tooltip]:hover:before, #lastudio-header-builder .social_' . esc_attr($uniqid) . ' .lahb-tooltip[data-tooltip]:hover:after');
            $dynamic_style .= lahb_styling_tab_output($atts, 'full_page_social', 'body.la-social-popup.popup_id_' . esc_attr($uniqid) . ' .featherlight');
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
            if ($inline == 'true') :
                LAHB_Helper::set_dynamic_styles('#lastudio-header-builder .header-social-icons,#header-social-full-wrap-' . esc_attr($uniqid) . ' .header-social-icons { display: inline-block; }');
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        if ($social_type == 'slide') {
            $link_to = 'href="#"';
        }
        elseif ($social_type == 'full') {
            $link_to = 'href="#header-social-full-wrap-' . esc_attr($uniqid) . '"';
        }
        elseif ($social_type == 'dropdown') {
            $link_to = 'href="#"';
        }
        if ($main_social_icon == 'text' && !empty($main_icon_text)) {
            $text_icon = '<span>' . LAHB_Helper::translate_string($main_icon_text, 'Text' . $com_uniqid) . '</span>';
        }
        else {
            $text_icon = $default_icon_bg == 'false' ? '<i class="lastudioicon-ic_share_24px"></i>' : '';
        }
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-wrap lahb-social lahb-social-type-' . esc_attr($social_type) . ' ' . esc_attr($extra_class) . ' lahb-header-dropdown social_' . esc_attr($uniqid) . '">';
        if ($social_type != 'simple') {
            $out .= '<a ' . $link_to . ' class="lahb-icon-element js-social_trigger_' . esc_attr($social_type) . ' hcolorf' . ($social_type == 'full' ? ' la-inline-popup' : '') . '"' . ($social_type == 'full' ? ' data-component_name="la-social-popup popup_id_' . esc_attr($uniqid) . '"' : '') . '></a>
				 <div class="la-header-social-icon">' . $text_icon . '</div>';
        }
        if ($social_type == 'simple') {
            $out .= '<div class="lastudio-social-icons-box header-social-simple-wrap">' . $display_socials . '</div>';
        }
        elseif ($social_type == 'slide') {
            $out .= '<div class="header-social-modal-wrap">
			<div id="header-social-modal" class="la-header-social">
				<div class="header-social-content container">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<h3 class="header-social-modal-title">
							' . (!empty($toggle_text) ? LAHB_Helper::translate_string($toggle_text, 'Tooltip Text' . $com_uniqid) : '') . '
						</h3>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<div class="socialfollow">
						' . $display_socials . '
						</div>
					</div>
				</div>
			</div>
		</div>';
        }
        elseif ($social_type == 'full') {
            $out .= '<div id="header-social-full-wrap-' . esc_attr($uniqid) . '" class="lastudio-social-icons-box header-social-full-wrap">' . $display_socials . '</div>';
        }
        elseif ($social_type == 'dropdown') {
            $out .= '<div class="lastudio-social-icons-box header-social-dropdown-wrap">' . $display_socials . '</div>';
        }
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'social', 'lahb_social', ['main_icon_text', 'toggle_text', 'tooltip_text']);

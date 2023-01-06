<?php
if(!function_exists('lahb_profile')) {
    function lahb_profile($atts, $uniqid, $once_run_flag = false) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        $com_uniqid = ' [Profile ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) .']';
        extract(LAHB_Helper::component_atts(array(
            'avatar' => '',
            'profile_name' => 'David Hamilton James',
            'socials' => 'true',
            'social_text_1' => 'Facebook',
            'social_url_1' => 'https://www.facebook.com/',
            'social_text_2' => '',
            'social_url_2' => '',
            'social_text_3' => '',
            'social_url_3' => '',
            'social_text_4' => '',
            'social_url_4' => '',
            'social_text_5' => '',
            'social_url_5' => '',
            'social_text_6' => '',
            'social_url_6' => '',
            'social_text_7' => '',
            'social_url_7' => '',
            'extra_class' => '',
        ), $atts));
        $out = '';
        $avatar = $avatar ? wp_get_attachment_url($avatar) : '';
        $profile_name = $profile_name ? LAHB_Helper::translate_string($profile_name, 'Profile Name' . $com_uniqid) : '';
        // Get Social Icons
        $display_socials = '';
        for ($i = 1; $i < 8; $i++) {
            ${"social_text_" . $i} = ${"social_text_" . $i} ? ${"social_text_" . $i} : '';
            ${"social_url_" . $i} = ${"social_url_" . $i} ? ${"social_url_" . $i} : '';
            if (!empty(${"social_text_" . $i})) {
                $display_socials .= '<div class="profile-social-icons social-icon-' . $i . '">';
                if (!empty(${"social_url_" . $i})) {
                    $display_socials .= '<a href="' . ${"social_url_" . $i} . '" target="_blank">';
                }
                $display_socials .= '- <span class="profile-social-text">' . ${"social_text_" . $i} . '</span>';
                if (!empty(${"social_url_" . $i})) {
                    $display_socials .= '</a>';
                }
                $display_socials .= '</div>';
            }
        }
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'image', '#lastudio-header-builder #lahb-profile-' . esc_attr($uniqid) . ' .lahb-profile-image-wrap');
            $dynamic_style .= lahb_styling_tab_output($atts, 'name', '#lastudio-header-builder #lahb-profile-' . esc_attr($uniqid) . ' .lahb-profile-name');
            $dynamic_style .= lahb_styling_tab_output($atts, 'socials_text', '#lastudio-header-builder #lahb-profile-' . esc_attr($uniqid) . ' .lahb-profile-socials-icons a');
            $dynamic_style .= lahb_styling_tab_output($atts, 'socials_box', '#lastudio-header-builder #lahb-profile-' . esc_attr($uniqid) . ' .lahb-profile-socials-icons');
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder #lahb-profile-' . esc_attr($uniqid));
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element-wrap lahb-profile-wrap lahb-profile' . esc_attr($extra_class) . '" id="lahb-profile-' . esc_attr($uniqid) . '">';
        $out .= '<div class="clearfix">';
        if (!empty($avatar)) {
            $out .= '<div class="lahb-profile-image-wrap">
					<img class="lahb-profile-image" src="' . esc_url($avatar) . '" alt="' . esc_attr($profile_name) . '">
				 </div>';
        }
        $out .= '<div class="lahb-profile-content">';
        if (!empty($profile_name)) {
            $out .= '<span class="lahb-profile-name">' . $profile_name . '</span>';
        }
        if ($socials == 'true') {
            $out .= '<div class="lahb-profile-socials-wrap">
						<div class="lahb-profile-socials-text-wrap">
							<span class="lahb-profile-socials-divider"></span>
							<div class="lahb-profile-socials-text">' . esc_html__('SOCIALS', 'lastudio-header-builder') . ' <i class="lastudioicon-down-arrow"></i>
								<div class="lahb-profile-socials-icons profile-socials-hide">
								' . $display_socials . '
								</div>
							</div>
						</div>
						
					</div>';
        }
        $out .= '</div>';
        $out .= '</div>'; // End clearfix
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'profile', 'lahb_profile' , ['profile_name']);

<?php
if(!function_exists('lahb_language')) {
    function lahb_language($atts, $uniqid, $once_run_flag = true) {
        if (!$once_run_flag && apply_filters('lastudio/header-builder/use_element_placeholder', true) ) {
            return '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-element--placeholder"></div>';
        }
        extract(LAHB_Helper::component_atts(array(
            'p_type' => 'wpml',
            'type' => 'dropdown',
            'extra_class' => '',
        ), $atts));
        $out = '';
        // styles
        if ($once_run_flag) :
            $dynamic_style = '';
            $dynamic_style .= lahb_styling_tab_output($atts, 'typography', '#lastudio-header-builder #lahb-language-' . esc_attr($uniqid) . ' a');
            $dynamic_style .= lahb_styling_tab_output($atts, 'background', '#lastudio-header-builder #lahb-language-' . esc_attr($uniqid));
            $dynamic_style .= lahb_styling_tab_output($atts, 'box', '#lastudio-header-builder #lahb-language-' . esc_attr($uniqid));
            if ($dynamic_style) :
                LAHB_Helper::set_dynamic_styles($dynamic_style);
            endif;
        endif;
        // extra class
        $extra_class = $extra_class ? ' ' . $extra_class : '';
        // render
        $out .= '<div data-element-id="' . esc_attr($uniqid) . '" class="lahb-element lahb-icon-wrap lahb-language ' . esc_attr($extra_class) . ' " id="lahb-language-' . esc_attr($uniqid) . '" >';
        //$out .= wpml_language_switcher ( $type );
        if ($p_type == 'wpml') {
            ob_start(); ?>
            <div class="la-wpml-switcher header-language-list">
                <?php do_action('wpml_add_language_selector'); ?>
            </div>
            <?php $out .= ob_get_contents();
            ob_end_clean();
        }
        if ($p_type == 'polylang' & $type == 'dropdown') {
            ob_start(); ?>
            <div class="la-polylang-switcher-dropdown header-language-list">
                <?php
                lahb_pll_the_languages(array('dropdown' => 1));
                ?>
            </div>
            <?php $out .= ob_get_contents();
            ob_end_clean();
        }
        if ($p_type == 'polylang' & $type == 'name_flag') {
            ob_start(); ?>
            <div class="la-polylang-switcher-inline header-language-list">
                <ul>
                    <?php
                    lahb_pll_the_languages(array(
                        'show_flags' => 1,
                        'show_names' => 1
                    ));
                    ?>
                </ul>
            </div>
            <?php $out .= ob_get_contents();
            ob_end_clean();
        }
        if ($p_type == 'polylang' & $type == 'flag') {
            ob_start(); ?>
            <div class="la-polylang-switcher-inline header-language-list">
                <ul>
                    <?php
                    lahb_pll_the_languages(array(
                        'show_flags' => 1,
                        'show_names' => 0
                    ));
                    ?>
                </ul>
            </div>
            <?php $out .= ob_get_contents();
            ob_end_clean();
        }
        $out .= '</div>';
        return $out;
    }
}
LAHB_Helper::add_element( 'language', 'lahb_language' );

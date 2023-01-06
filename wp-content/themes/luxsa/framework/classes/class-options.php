<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Luxsa_Options {

    public static $color_default = array();

    public function __construct() {

        self::$color_default = array(
            'text_color' => '#3e3e3e',
            'body_color' => '#3e3e3e',
            'heading_color' => '#1A1A1A',
            'primary_color' => '#D4876C',
            'secondary_color' => '#1A1A1A',
            'three_color' => '#979797',
            'border_color' => '#ebebeb'
        );
    }

    public static function get_color_default( $key ){
        if(!empty($key)){
            if(!empty(self::$color_default) && array_key_exists( $key, self::$color_default )){
                return self::$color_default[$key];
            }
        }
        return '';
    }

    public static function get_config_main_layout_opts( $image_select = true, $inherit = false ){
        $options =  array(
            'col-1c'    => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/col-1c.png')    : esc_attr_x('1 column', 'admin-view', 'luxsa'),
            'col-2cl'   => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/col-2cl.png')   : esc_attr_x('2 columns left (3-9)', 'admin-view', 'luxsa'),
            'col-2cr'   => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/col-2cr.png')   : esc_attr_x('2 columns right (9-3)', 'admin-view', 'luxsa')
        );
        if($inherit){
            $inherit = array(
                'inherit' => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/inherit.png') : esc_attr_x('Inherit','admin-view', 'luxsa')
            );
            $options = $inherit + $options;
        }
        return $options;
    }

    public static function get_config_header_layout_opts( $image_select = true, $inherit = false ){
		
        $options =  array(
            '1'     => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/header-1.jpg')            : esc_attr_x('Header Layout 01 ( Logo + Menu + Access Icon )', 'admin-view', 'luxsa')
        );

        if(class_exists('LAHB_Helper', false)){
            $options = LAHB_Helper::get_all_prebuild_header_for_dropdown();
        }

        if($inherit){
            $inherit = array(
                'inherit' => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/header-inherit.png') : esc_attr_x('Inherit','admin-view', 'luxsa')
            );
            $options = $inherit + $options;
        }
        return $options;
    }

    public static function get_config_radio_opts( $inherit = true ){
        $options = array();
        if($inherit){
            $options['inherit'] = esc_html_x('Inherit', 'admin-view', 'luxsa');
        }
        $options['yes'] = esc_html_x('Yes', 'admin-view', 'luxsa');
        $options['no'] = esc_html_x('No', 'admin-view', 'luxsa');
        return $options;
    }

    public static function get_config_radio_onoff( $inherit = true ){
        $options = array();
        if($inherit){
            $options['inherit'] = esc_html_x('Inherit', 'admin-view', 'luxsa');
        }
        $options['on'] = esc_html_x('On', 'admin-view', 'luxsa');
        $options['off'] = esc_html_x('Off', 'admin-view', 'luxsa');
        return $options;
    }

    public static function get_config_page_title_bar_opts( $image_select = true, $inherit = false ) {
        $options =  array(
            '1'     => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-style-1.jpg')    : esc_attr_x('Title & Breadcrumbs Centered', 'admin-view', 'luxsa'),
            '2'     => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-style-2.jpg')    : esc_attr_x('Title & Breadcrumbs In Left', 'admin-view', 'luxsa'),
            '3'     => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-style-3.jpg')    : esc_attr_x('Title & Breadcrumbs In Right', 'admin-view', 'luxsa'),
            '4'     => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-style-4.jpg')    : esc_attr_x('Title Left & Breadcrumbs Right', 'admin-view', 'luxsa'),
            '5'     => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-style-5.jpg')    : esc_attr_x('Title Right & Breadcrumbs Left', 'admin-view', 'luxsa')
        );
        if($inherit){
            $inherit = array(
                'inherit' => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-inherit.png') : esc_attr_x('Inherit','admin-view', 'luxsa'),
                'hide' => $image_select ? esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/title-bar-inherit.png') : esc_attr_x('Hidden','admin-view', 'luxsa')
            );
            $options = $inherit + $options;
        }
        return $options;
    }
	
}
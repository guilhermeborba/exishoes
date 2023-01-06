<?php

/**
 * Header Builder - Text Field.
 *
 * @author	LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

/**
 * Text field function.
 *
 * @since	1.0.0
 */
function lahb_textfield( $settings ) {

	$title		 = isset( $settings['title'] ) ? $settings['title'] : '';
	$id			 = isset( $settings['id'] ) ? $settings['id'] : '';
	$default	 = isset( $settings['default'] ) ? $settings['default'] : '';
	$place_class = isset( $settings['placeholder'] ) ? ' lahb-placeholder lahb-text-placeholder' : '';
	$placeholder = isset( $settings['placeholder'] ) ? $settings['placeholder'] : '';
	$desc        = isset( $settings['description'] ) ? $settings['description'] : '';

	$output = '<div class="lahb-field w-col-sm-12' . esc_attr( $place_class ) . '"><h5>' . esc_html($title) . '</h5><input type="text" class="lahb-field-input lahb-field-textfield' . esc_attr( $place_class ) . '" placeholder="'.esc_attr($placeholder).'" data-field-name="' . esc_attr( $id ) . '" data-field-std="' . esc_attr($default) . '">'.$desc.'</div>';

	if ( ! isset( $settings['get'] ) ) :
		echo '' . $output;
	else :
		return $output;
	endif;

}

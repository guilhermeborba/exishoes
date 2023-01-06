<?php

/**
 * Header Builder - Help Field.
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
 * Help field function.
 *
 * @since	1.0.0
 */
function lahb_help( $settings ) {

	$title		 = isset( $settings['title'] ) ? $settings['title'] : '';
	$default	 = isset( $settings['default'] ) ? $settings['default'] : '';

	$output = '<div class="lahb-field w-col-sm-12">'.(!empty($title) ? sprintf('<h5>%s</h5>', $title) : '').(!empty($default) ? sprintf('<div>%s</div>', $default) : '').'</div>';

	if ( ! isset( $settings['get'] ) ){
		echo '' . $output;
	}
	else{
		return $output;
	}

}

<?php

/**
 * Header Builder - Icon Field.
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
 * Icon field function.
 *
 * @since	1.0.0
 */
function lahb_icon( $settings ) {

	$title		= isset( $settings['title'] ) ? $settings['title'] : '';
	$id			= isset( $settings['id'] ) ? $settings['id'] : '';
	$default	= isset( $settings['default'] ) ? $settings['default'] : '';

	if(class_exists('LASF')){
        LASF::field(array(
            'id' => $id,
            'type' => 'icon',
            'class' => 'lahb-field w-col-sm-12',
            'in_header_builder' => true,
            'attributes' => [
                'data-field-name' => $id,
                'data-field-std' => $default,
                'class' => 'lasf-icon-value lahb-field-input lahb-icon-field'
            ],
            'title' => $title
        ), $default);
    }
	else{
	    echo 'field not found!';
    }
}

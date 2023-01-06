<?php
namespace Add_Chat_App_Button\Includes;

use Add_Chat_App_Button\Plugin;

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
    exit;
}

class Scripts_Manager {

	public function __construct() {
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue Scripts
	 *
	 * Enqueue the plugin's needed scripts
	 *
	 * @since 2.0.0
	 */
	public function enqueue_scripts() {
		$this->localize_and_enqueue_main_script();

		if ( ! is_admin() ) {
			$this->enqueue_jquery_ui_draggable();
		}
	}

	/**
	 * Localize and Enqueue Main Script
	 *
	 * Enqueue the main script for the WhatsApp button's functionality, and localize the PHP variables needed for it.
	 *
	 * @since 2.0.0
	 */
	private function localize_and_enqueue_main_script() {
		$options = Plugin::$instance->get_plugin_options();

		wp_enqueue_script( 'wab-main-script', plugins_url( '../js/main.js', __FILE__ ), array( 'jquery' ) );

		// Give the startHour and endHour variables default values
		$startHour = ! empty( $options['startHour'] ) ? $options['startHour'] : '8';
		$endHour = ! empty( $options['endHour'] ) ? $options['endHour'] : '22';
		$awb_limitHours = ! empty( $options['limit_hours'] ) ? $options['limit_hours'] : 0;
		$hideButtonType = ( ! empty( $options['hide_button'] ) && $options['enable_hide_button'] == '1' ) ? $options['hide_button'] : null;
		$button_location = ( isset( $options['button_location'] ) ) ? $options['button_location'] : 'left';
		$buttonType = isset( $options['button_type'] ) ? $options['button_type'] : 'wab-side-rectangle';

		// Create an array of the data we want to pass to the JS script
		$dataToBePassed = array(
			'startHour'       => $startHour,
			'endHour'         => $endHour,
			'limitHours'      => $awb_limitHours,
			'hideButtonType'  => $hideButtonType,
			'button_location' => $button_location,
			'button_type'	  => $buttonType,
			'plugins_url'	  => plugins_url()
		);

		wp_localize_script( 'wab-main-script', 'php_vars', $dataToBePassed );
	}

	/**
	 * Enqueue jQuery-UI draggable
	 *
	 * Enqueue jQuery-UI draggable functionality + Touch Punch for Mobile Support.
	 *
	 * @since 2.0.0
	 */
	private function enqueue_jquery_ui_draggable() {
		wp_enqueue_script( 'jquery_draggable', plugins_url( '../js/jquery-ui.drag.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'jquery_touch_punch', plugins_url( '../js/jquery.ui.touch-punch.min.js', __FILE__ ), array( 'jquery' ) );
	}
}

<?php
namespace Add_Chat_App_Button\Includes;

use Add_Chat_App_Button\Plugin;
use Add_Chat_App_Button\Includes\Style_Templates\Frontend as Frontend_Styles;
use Add_Chat_App_Button\Includes\Style_Templates\Admin as Admin_Styles;

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
    exit;
}

class Styles_Manager {

	public function __construct() {
		require __DIR__ . '/style-templates/frontend-css.php';

		// Print the Front End CSS
		add_action( 'wp_head', [ Frontend_Styles::class, 'print_styles' ] );

		if ( is_admin() ) {
			require __DIR__ . '/style-templates/admin-css.php';

			// Print the Admin CSS
			add_action( 'admin_head', [ Admin_Styles::class, 'print_styles' ] );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_device_mockups_in_admin' ] );
		}
	}

	/**
	 * Enqueue Device Mockups in Admin
	 *
	 * Enqueue device mockup style in Admin area only.
	 *
	 * @since 2.0.0
	 */
	public function enqueue_device_mockups_in_admin() {
		if ( is_rtl() ) {
			$file_path = '../css/awb-admin-styles-rtl.css';
		} else {
			$file_path = '../css/awb-admin-styles.css';
		}

		wp_enqueue_style( 'device-mockups', plugins_url( $file_path, __FILE__ ) );
	}
}

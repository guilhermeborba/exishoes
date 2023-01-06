<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LaStudio
 * @subpackage LaStudio/admin
 * @author     Duy Pham <dpv.0990@gmail.com>
 */
class LaStudio_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LaStudio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LaStudio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_style('jquery-chosen');

		if(wp_style_is('font-awesome', 'registered')) {
			wp_deregister_style('font-awesome');
		}

		wp_enqueue_style( 'font-awesome', plugin_dir_url( dirname(__FILE__) ) . 'public/css/font-awesome.min.css', array(), '5.9.0');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LaStudio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LaStudio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_script('jquery-chosen');
		// admin utilities
		wp_enqueue_media();

	}

	public function admin_customize_enqueue(){

	}

}

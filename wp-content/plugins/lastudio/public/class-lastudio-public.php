<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LaStudio
 * @subpackage LaStudio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    LaStudio
 * @subpackage LaStudio/public
 * @author     Your Name <email@example.com>
 */
class LaStudio_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

	}

	/**
	 * Add async to theme javascript file for performance
	 *
	 * @param string $tag The script tag.
	 * @param string $handle The script handle.
	 */

	public function add_async($tag, $handle, $src) {
		$defer_scripts = apply_filters('lastudio/theme/defer_scripts', array(
			'googleapis',
			'wp-embed',
			'contact-form-7',
			'tp-tools',
			'revmin',
			'wc-add-to-cart',
			'woocommerce',
			'jquery-blockui',
			'js-cookie',
			'wc-cart-fragments',
			'prettyphoto',
			'jquery-selectbox',
			'jquery-yith-wcwl',
			'photoswipe',
			'photoswipe-ui-default',
			'waypoints',
			'yikes-easy-mc-ajax',
			'form-submission-helpers',
			'wpb_composer_front_js',
			'vc_accordion_script',
			'vc_tta_autoplay_script',
			'vc_tabs_script',

			'wp-mediaelement',
			'jquery-cue',
			'lpm-mejs',
			'lpm-app'
		));

		$async_scripts = apply_filters('lastudio/theme/async_scripts', array());

		$tag = str_replace(" type='text/javascript'", '', $tag);

		if (!empty($defer_scripts) && in_array( strtolower($handle), $defer_scripts ) ) {
			return preg_replace('/(><\/[a-zA-Z][^0-9](.*)>)$/', ' defer $1 ', $tag);
		}

		if (!empty($async_scripts) && in_array( strtolower($handle), $async_scripts ) ) {
			return preg_replace('/(><\/[a-zA-Z][^0-9](.*)>)$/', ' async $1 ', $tag);
		}

		return $tag;
	}

	public function remove_style_attr($tag, $handler) {
		return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
	}

	public function remove_woocommerce_hook(){
		remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
	}

	public function widgets_init(){
		require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-recent-posts.php';

		register_widget('LaStudio_Widget_Recent_Posts');

		if(class_exists('WC_Widget')){

			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-product-sort-by.php';
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-price-filter-list.php';
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-price-slider-filter.php';
			require_once plugin_dir_path( __FILE__ ) . 'widgets/class-lastudio-widget-product-tag.php';

			register_widget('LaStudio_Widget_Product_Sort_By');
			register_widget('LaStudio_Widget_Price_Filter_List');
			register_widget('LaStudio_Widget_Product_Tag');

//            unregister_widget( 'WC_Widget_Price_Filter' );
//            register_widget( 'LaStudio_Widget_Price_Slider_Filter' );
		}

	}
}

<?php
namespace Add_Chat_App_Button;

use Add_Chat_App_Button\Includes\Scripts_Manager;
use Add_Chat_App_Button\Includes\Styles_Manager;
use Add_Chat_App_Button\Admin\Admin_Settings;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Plugin {

	/**
	 * Plugin Folder Name.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	const PLUGIN_NAME = 'add-whatsapp-button';

	/**
	 * Plugin Options.
	 *
	 * Holds the scripts manager.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public $plugin_options;

	/**
	 * Scripts manager.
	 *
	 * Holds the scripts manager.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @var AWB_Scripts_Manager
	 */
	public $scripts_manager;

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	public function __construct() {
		// Initialize all of the plugin's functionality.
		add_action( 'init', [ $this, 'init' ], 0 );
		// Load Plugin Textdomain
		add_action( 'plugins_loaded', [ $this, 'load_awb_textdomain' ] );

		// Load Plugin Settings in the Admin Settings Page 
		if ( is_admin() ) {
			$this->admin_actions();
		}
	}

	/**
	 * Instance.
	 *
	 * Implements a Singleton pattern - Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 2.0.0
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			/**
			 * Add Chat App loaded.
			 *
			 * Fires when Add Chat App Button was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'add_chat_app_button_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Admin Actions
	 *
	 * Run actions for the Admin Dashboard.
	 *
	 * @since 2.0.0
	 */
	private function admin_actions() {
		require_once( __DIR__ . '/admin/settings.php' );

		//Load admin notice dismissal management library
		require_once( plugin_dir_path( __FILE__ ) . '/vendors/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php' );
		add_action( 'admin_init', array( 'PAnD', 'init' ) );

		// Add a link to the plugin's Settings page from the Plugins Page
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'add_plugin_action_link' ], 10, 5 );

		new Admin_Settings();
	}

	// Load Plugin Textdomain
	public function load_awb_textdomain() {
		load_plugin_textdomain( 'add-whatsapp-button', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add Plugin Action Link
	 * 
	 * Add a link to the plugin settings page to the list of action links displayed for a specific plugin in the Plugins list table.
	 * 
	 * @since 2.0.0
	 */
	public function add_plugin_action_link( $links ) {
		$settings = array(
			'settings' => '<a href="options-general.php?page=awb-options">' . esc_html__( 'Settings', 'add-whatsapp-button' ) . '</a>'
		);

		return array_merge( $settings, $links );
	}

	/**
	 * Init
	 * 
	 * Initializes the plugin's functionality.
	 * 
	 * @since 2.0.0
	 */
	public function init() {
		require_once( plugin_dir_path( __FILE__ ) . '/includes/scripts-manager.php' );
		require_once( plugin_dir_path( __FILE__ ) . '/includes/styles-manager.php' );

		$this->scripts_manager = new Scripts_Manager();
		$this->styles_manager = new Styles_Manager();

		if ( $this->get_plugin_options( 'enable' ) && ! is_admin() ) {
			// Print the WhatsApp button
			add_action('wp_footer', [ $this, 'print_button' ] );
		}
	}

	public function print_button() {
		$settings = $this->get_plugin_options();

		$button_text = isset( $settings['button_text'] ) ? sanitize_text_field( $settings['button_text'] ) : _e('Message Us on WhatsApp', 'add-whatsapp-button');
		$displayNoneIfIcon = ( $settings['button_type'] == 'wab-icon-plain' || $settings['button_type'] == 'wab-icon-styled' ) ? 'awb-displaynone' : '';
		$button_style = !empty( $settings['button_type'] ) ? $settings['button_type'] : 'wab-side-rectangle';
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$close_button_icon = '';
	
		if ( 'wab-bottom-rectangle' !== $settings['button_type'] ) {
			$button_location = isset( $settings['button_location'] ) ? 'wab-pull-'.$settings['button_location'] : 'wab-pull-left';
		}
	
		if ( 'full' === $settings['hide_button'] ) {
			$close_button_icon = '<span class="wab-x">x</span>';
		}
		else if ( 'hide' === $settings['hide_button'] ) {
			if ( 'right' === $settings['button_location'] && 'wab-bottom-rectangle' !== $settings['button_type'] ) {
				$close_button_icon = '<img class="wab-chevron wab-right" src="' . plugins_url( '/img/chevron-right.svg', __FILE__ ) . '" />';
			} else if ( 'left' === $settings['button_location'] && 'wab-bottom-rectangle' !== $settings['button_type'] ) {
				$close_button_icon = '<img class="wab-chevron wab-left" src="' . plugins_url( '/img/chevron-left.svg', __FILE__ ) . '" />';
			} else if ( 'wab-bottom-rectangle' === $settings['button_type'] ) {
				$close_button_icon = '<img class="wab-chevron wab-down" src="' . plugins_url( '/img/chevron-down.svg', __FILE__ ) . '" />';
			}
		}
	
		ob_start(); 
		?>
	
			<div id="wab_cont"  class="wab-cont ui-draggable <?php echo $button_style; ?> <?php echo $button_location; ?>">
				<a id="whatsAppButton" href="https://wa.me/<?php echo esc_html( $settings['phone_number'] ); ?><?php echo ( !empty($settings['default_message']) && $settings['enable_message'] == '1' ) ? '/?text='. rawurlencode($settings['default_message']) : ''; ?>" target="_blank"><span class="<?php echo $displayNoneIfIcon; ?>"><?php echo $button_text; ?></span></a>
				<?php if ( isset( $settings['enable_hide_button'] ) && ( isset( $settings['hide_button'] ) ) ) : ?>
					<div id="wab_close"><?php echo $close_button_icon; ?></div>
				<?php endif; ?>
			</div>
			
		<?php 
		echo ob_get_clean();	
	}

	/**
	 * Get Plugin Options
	 * 
	 * Retrieves either a passed option, if it exists, or the entire options array for the plugin.
	 * 
	 * @since 2.0.0
	 * 
	 * @param string $option_name
	 */
	public function get_plugin_options( $option_name = null ) {
		// Cache the options for this page load.
		if ( ! $this->plugin_options ) {
			$this->plugin_options = get_option( 'awb_settings' );
		}

		if ( ! empty( $option_name ) ) {
			// If an option name is passed, check if it exists. If it does, return it. Otherwise, return false.
			if ( isset( $this->plugin_options[ $option_name ] ) ) {
				return $this->plugin_options[ $option_name ];
			}
			
			return false;
		}

		return $this->plugin_options;
	}
}	

Plugin::instance();
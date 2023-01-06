<?php
/**
 * Plugin Name:       LA-Studio Core
 * Plugin URI:        https://themeforest.net/user/la-studio/?ref=la-studio
 * Description:       This plugin use only for LA-Studio theme with Elementor
 * Version:           2.1.5.2
 * Author:            LA-Studio
 * Author URI:        https://themeforest.net/user/la-studio/?ref=la-studio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lastudio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'LASTUDIO_VERSION', '2.1.5.2' );
define( 'LASTUDIO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LASTUDIO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function lastudio_core_activate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lastudio-activator.php';
	LaStudio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function lastudio_core_deactivate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lastudio-deactivator.php';
	LaStudio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'lastudio_core_activate_plugin' );
register_deactivation_hook( __FILE__, 'lastudio_core_deactivate_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

function lastudio_core_message_fail_php(){
    $message = __( 'LA-Studio Core requires PHP version 5.6+ to work properly. The plugins is deactivated for now.', 'lastudio' );

    printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );

    if (isset($_GET['activate'])) {
        unset($_GET['activate']);
    }
}

function lastudio_core_need_to_deactivate_plugin(){
    deactivate_plugins( plugin_basename( __FILE__ ) );
}

// Check for required PHP version
if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
    add_action( 'admin_notices', 'lastudio_core_message_fail_php' );
    add_action( 'admin_init', 'lastudio_core_need_to_deactivate_plugin' );
}

require plugin_dir_path( __FILE__ ) . 'includes/class-lastudio.php';

add_action('script_loader_src', function( $src, $handler ){
	if($handler == 'jquery-slick'){
		$ext = ( defined('WP_DEBUG') && WP_DEBUG ) ? '' : '.min';
		$src = get_theme_file_uri('/assets/js/lib/slick'.$ext.'.js');
	}
	return $src;
}, 10, 2);

if ( defined('ELEMENTOR_VERSION' ) ) {
	require plugin_dir_path( __FILE__ ) . 'includes/extensions/elementor/manager.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lastudio() {
	$plugin = new LaStudio();
	$plugin->run();
}
run_lastudio();
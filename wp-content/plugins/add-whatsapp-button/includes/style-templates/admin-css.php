<?php
namespace Add_Chat_App_Button\Includes\Style_Templates;

use Add_Chat_App_Button\Plugin;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin {

	/**
	 * Print Styles
	 *
	 * Prints the Admin CSS based on the plugin settings array.
	 *
	 * @since 2.0.0
	 */
	public static function print_styles() {
		$settings = Plugin::$instance->get_plugin_options();

		$distance_from_bottom = isset( $settings['distance_from_bottom'] ) && is_numeric( $settings['distance_from_bottom'] ) ? $settings['distance_from_bottom'] : '10';
		$distance_from_bottom_mu = isset( $settings['distance_from_bottom_mu'] ) ? $settings['distance_from_bottom_mu'] : '%'; 
		$button_bg_color = ! empty( $settings['button_bg_color'] ) ? $settings['button_bg_color'] : '#20B038';
		$button_text_color = ! empty( $settings['button_text_color'] ) ? $settings['button_text_color'] : '#ffffff';
		$button_location = isset( $settings['button_location'] ) ? $settings['button_location'] : 'right';
		$wp_text_direction = is_rtl() ? 'rtl' : 'ltr';
		$icon_size = ! empty( $settings['icon_size'] ) ? sanitize_text_field( $settings['icon_size'] ) : '80';
		$icon_size_mu = ! empty( $settings['icon_size_mu'] ) ? $settings['icon_size_mu'] : 'px';

		?>
		<style type="text/css">
			.device-wrapper {
				max-width: 250px;
			}
			
			/* Side Rectangle */

			.wab-side-rectangle .wp-admin #whatsAppButton svg path {
				fill: #fff;
			} */
			
			.wab-side-rectangle.wab-pull-right, .wab-icon-styled.wab-pull-right, .wab-icon-plain.wab-pull-right {
				right: 0;
				left: initial !important;
			}

			.wab-side-rectangle.wab-pull-left, .wab-icon-styled.wab-pull-left, .wab-icon-plain.wab-pull-left {
				left: 0;
				right: initial !important;
			}

			.wp-admin .wab-side-rectangle.wab-cont {
				position: absolute;
				<?php echo $button_location; ?>: 0;
				bottom: <?php echo $distance_from_bottom; echo $distance_from_bottom_mu; ?>;
				z-index: 99999;
			}

			.wp-admin .wab-side-rectangle #whatsAppButton {
				display: block;
				position: relative;
				direction: <?php echo $wp_text_direction; ?>;
				z-index: 9999;
				cursor: pointer;
				min-width: 50px;
				max-width: 236px;
				color: <?php echo $button_text_color; ?>;
				text-align: center;
				text-decoration: none;
				padding: 10px;
				margin: 0 auto 0 auto;
				background: <?php echo $button_bg_color; ?>;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
			
			.wp-admin .wab-side-rectangle #whatsAppButton:after {
				margin-left: 5px;
				margin-right: 5px;
				max-width: 20px;
				max-height: 20px;
				fill: currentColor;
			}

			.wp-admin .wab-side-rectangle #whatsAppButton svg path {
				fill: #fff;
			}
			
			/* Bottom Rectangle */

			.wp-admin .wab-bottom-rectangle.wab-cont {
				position: absolute;
				bottom: 0;
				z-index: 99999;
				width: 100%;
			}

			.wp-admin .wab-bottom-rectangle #whatsAppButton {
				display: block;
				position: relative;
				direction: <?php echo $wp_text_direction; ?>;
				z-index: 9999;
				cursor: pointer;
				color: <?php echo $button_text_color; ?>;
				text-align: center;
				text-decoration: none;
				padding: 10px;
				margin: 0 auto 0 auto;
				background: <?php echo $button_bg_color; ?>;
			}

			/* Icon */
			
			.wp-admin .wab-icon-styled.wab-cont, .wp-admin .wab-icon-plain.wab-cont {
				position: absolute;
				<?php echo $button_location; ?>: 10px;
				bottom: <?php echo $distance_from_bottom; echo $distance_from_bottom_mu; ?>;
				z-index: 99999;
			}

			.wp-admin .wab-icon-styled #whatsAppButton, .wp-admin .wab-icon-plain #whatsAppButton {
				display: block;
				width: <?php echo $icon_size . $icon_size_mu; ?>;
				height: <?php echo $icon_size . $icon_size_mu; ?>;
				background-position: center center;
				background-size: cover;
			}

			.wp-admin .wab-icon-styled.wab-cont.wab-pull-left, .wp-admin .wab-icon-plain.wab-cont.wab-pull-left {
				left: 10px;
			}

			.wp-admin .wab-icon-styled.wab-cont.wab-pull-right, .wp-admin .wab-icon-plain.wab-cont.wab-pull-right {
				right: 10px;
			}

			.wp-admin .wab-icon-plain.wab-pull-left #whatsAppButton, .wp-admin .wab-icon-plain.wab-pull-right #whatsAppButton {
				background-image: url(<?php echo plugins_url( '../../img/wa-icon-original.png', __FILE__ ); ?>);
			}
			
			.awb-displaynone {
				display: none;
			}
		</style>

		<?php
	}
}
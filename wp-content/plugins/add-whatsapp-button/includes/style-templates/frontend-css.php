<?php
namespace Add_Chat_App_Button\Includes\Style_Templates;

use Add_Chat_App_Button\Plugin;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Frontend {

	/**
	 * Print Styles
	 *
	 * Prints the Frontend CSS based on the plugin settings array.
	 *
	 * @since 2.0.0
	 */
	public static function print_styles() {
		$settings = Plugin::$instance->get_plugin_options();

		$breakpoint = !empty( $settings['breakpoint'] ) && is_numeric( $settings['breakpoint'] ) ? $settings['breakpoint'] : '600';
		$distance_from_bottom = isset( $settings['distance_from_bottom'] ) && is_numeric( $settings['distance_from_bottom'] ) ? $settings['distance_from_bottom'] : '10';
		$distance_from_bottom_mu = isset( $settings['distance_from_bottom_mu'] ) ? $settings['distance_from_bottom_mu'] : '%'; 
		$button_bg_color = !empty( $settings['button_bg_color'] ) ? $settings['button_bg_color'] : '#20B038';
		$button_text_color = !empty( $settings['button_text_color'] ) ? $settings['button_text_color'] : '#ffffff';
		$button_location = isset( $settings['button_location'] ) ? $settings['button_location'] : 'right';
		$wp_text_direction = is_rtl() ? 'rtl' : 'ltr';
		$show_close_button = isset( $settings['enable_hide_button'] ) ? 'flex' : 'none';;
		$close_button_location = (isset( $settings['button_location'] ) && $settings['button_location'] == 'left') ? 'right' : 'left';;
		$close_button_ilh = ( isset( $settings['enable_hide_button'] ) && $settings['hide_button'] == 'full' ) ? '1' : '1.2'; //inner line height
		$icon_size = ! empty( $settings['icon_size'] ) ? sanitize_text_field( $settings['icon_size'] ) : '80';
		$icon_size_mu = ! empty( $settings['icon_size_mu'] ) ? $settings['icon_size_mu'] : 'px';

		ob_start();
		?>
		<style type="text/css">
			<?php if ( isset( $settings['enable_breakpoint'] ) ) { ?>
				@media only screen and (min-width: <?php echo $breakpoint.'px'; ?>) {
					.wab-cont {
						display: none;
					}
				}
			<?php } ?>

			img.wab-chevron {
				height: 12px;
			}

			img.wab-chevron.wab-right {
				margin-left: 1px;
			}

			img.wab-chevron.wab-left {
				margin-right: 2px;
			}

			/**
			 * Side Rectangle
			 */ 
			.wab-side-rectangle.wab-pull-right {
				right: 0;
				left: initial !important;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			.wab-side-rectangle.wab-pull-left {
				left: 0;
				right: initial !important;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			.wab-side-rectangle.wab-cont {
				position: fixed;
				bottom: <?php echo $distance_from_bottom; echo $distance_from_bottom_mu; ?>;
				z-index: 99997;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
			
			.wab-side-rectangle.wab-cont .wab-pull-right {
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			.wab-side-rectangle #whatsAppButton {
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
				padding: 10px 14px;
				margin: 0 auto 0 auto;
				background: <?php echo $button_bg_color; ?>;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
			
			.wab-side-rectangle #whatsAppButton:after {
				margin-left: 5px;
				margin-right: 5px;
				/* content: url(/wp-content/themes/html5blanknew/img/whatsapp-logo2.svg); */
				max-width: 20px;
				max-height: 20px;
				fill: currentColor;
			}

			.wab-side-rectangle #whatsAppButton svg path {
				fill: #fff;
			}

			.wab-side-rectangle #wab_close {
				display: <?php echo $show_close_button; ?>;
				align-items: center;
    			justify-content: center;
				position: absolute;
				top: -10px;
				<?php echo $close_button_location; ?>: -9px;
				z-index: 999999;
				background-color: #fff;
				font-weight: bold;
				font-size: 14px;
				border: 2px solid;
				border-radius: 12px;
				height: 20px;
				width: 20px;
				line-height: <?php echo $close_button_ilh ?>;
				text-align: center;
				cursor: pointer;
			}

			.wab-x {
				position: absolute;
    			top: 1px;
		    	font-size: 15px;
			}
			
			#wab_cont.wab-side-rectangle.wab-hidden {
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			/**
			 * Bottom Rectangle
			 */
			.wab-bottom-rectangle.wab-cont {
				position: fixed;
				bottom: 0;
				z-index: 99999;
				width: 100%;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
		
			.wab-bottom-rectangle #whatsAppButton {
				display: block;
				/* position: relative; */
				position: absolute;
				bottom: 0;
				width: 100%;
				direction: <?php echo $wp_text_direction; ?>;
				z-index: 9999;
				cursor: pointer;
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

			.wab-bottom-rectangle #wab_close {
				display: <?php echo $show_close_button; ?>;
				align-items: center;
    			justify-content: center;
				position: absolute;
				bottom: 38px;
				<?php echo $close_button_location; ?>: 10px;
				z-index: 999999;
				background-color: #fff;
				font-weight: bold;
				font-size: 14px;
				border: 2px solid;
				border-radius: 10px;
				height: 20px;
				width: 20px;
				line-height: <?php echo $close_button_ilh ?>;
				text-align: center;
				cursor: pointer;
			}
			
			.wab-bottom-rectangle img.wab-chevron.wab-down {
				max-width: 64%;
				position: absolute;
				top: 20%;
				left: 18%;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
			
			.wab-bottom-rectangle img.wab-chevron.wab-up {
				max-width: 64%;
				position: absolute;
				top: 12%;
				left: 18%;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
			
			#wab_cont.wab-bottom-rectangle.wab-hidden {
				/* bottom: -36px; */
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}
			
			/* Icon */
			
			.wab-icon-styled.wab-cont, .wab-icon-plain.wab-cont {
				position: fixed;
				<?php echo $button_location; ?>: 10px;
				bottom: <?php echo $distance_from_bottom; echo $distance_from_bottom_mu; ?>;
				z-index: 99999;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			.wab-icon-styled #whatsAppButton, .wab-icon-plain #whatsAppButton {
				display: block;
				width: <?php echo $icon_size . $icon_size_mu; ?>;
				height: <?php echo $icon_size . $icon_size_mu; ?>;
				background-position: center center;
				background-size: cover;
				background-image: url(<?php echo plugins_url( '../../img/wa-icon-original.png', __FILE__ ); ?>);
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			.wab-icon-styled.wab-cont.wab-pull-left, .wab-icon-plain.wab-cont.wab-pull-left {
				left: 10px;
			}

			.wab-icon-styled.wab-cont.wab-pull-right, .wab-icon-plain.wab-cont.wab-pull-right {
				right: 10px;
			}

			.wab-icon-styled #wab_close, .wab-icon-plain #wab_close {
				display: <?php echo $show_close_button; ?>;
				align-items: center;
    			justify-content: center;
				position: absolute;
				top: -2px;
				<?php echo $close_button_location; ?>: -5px;
				z-index: 999999;
				background-color: #fff;
				font-weight: bold;
				font-size: 14px;
				border: 2px solid;
				border-radius: 10px;
				height: 20px;
				width: 20px;
				line-height: <?php echo $close_button_ilh ?>;
				text-align: center;
				cursor: pointer;
			}
			
			#wab_cont.wab-icon-styled.wab-hidden, #wab_cont.wab-icon-plain.wab-hidden {
				<?php echo $button_location ?>: -64px;
				-webkit-transition: All 0.5s ease;
				-moz-transition: All 0.5s ease;
				-o-transition: All 0.5s ease;
				-ms-transition: All 0.5s ease;
				transition: All 0.5s ease;
			}

			.awb-displaynone {
				display: none;
			}
		</style>

		<?php
		echo ob_get_clean();
	}
}
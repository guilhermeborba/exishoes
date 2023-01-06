<?php
namespace Add_Chat_App_Button\Admin;

use Add_Chat_App_Button\Plugin;
use Add_Chat_App_Button\Includes\Style_Templates\Admin as Admin_Styles;
use PAnD;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Settings {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'options_menu_link' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// Unless the user chose to never show again, add admin notice to give 5-star review
		add_action( 'admin_notices', [ $this, 'maybe_show_five_star_review_notice' ] );
	}
	
	/**
	 * Create Admin Menu Link
	 *
	 * @since 2.0.0
	 */
	public function options_menu_link() {
		$options_page = add_options_page(
			__( 'Add WhatsApp Button Options', 'add-whatsapp-button' ), // title
			__( 'Add WhatsApp Button', 'add-whatsapp-button' ), // title of the menu link
			'manage_options', // capabilities credentials, at least able to X
			'awb-options', // menu URL slug
			[ $this, 'print_options_page_content' ] // name of the function that displays the option page content
		);
	
		// Load the JS only in the pages where it is used.
		add_action( 'load-' . $options_page, [ $this, 'load_admin_js' ] );
		add_action( 'load-' . $options_page, [ $this, 'load_admin_styles' ] );
	}

	public function load_admin_js() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_js' ] );
    }

	/**
	 * Load Admin Styles
	 *
	 * @since 2.0.0
	 */
	public function load_admin_styles() {
        add_action( 'admin_enqueue_scripts', [ Admin_Styles::class, 'print_styles' ] );
    }

	/**
	 * Enqueue Admin Color Picker JS and CSS
	 *
	 * @since 2.0.0
	 */
	public function enqueue_admin_js() {
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script(
			'awb-admin-script',
			plugins_url( '../js/admin.js', __FILE__ ),
			array( 'jquery', 'wp-color-picker' ),
			'',
			true
		);
    }

	/**
	 * Register Settings
	 *
	 * @since 2.0.0
	 */
	public function register_settings() {
		register_setting( 'awb_settings_group', 'awb_settings', [ $this, 'sanitize_inputs' ] );
	}

	/**
	 * Validate Inputs
	 *
	 * This is a callback, receiving an array of options from the settings page, a
	 *
	 * @since 2.0.0
	 */
	public function sanitize_inputs( $input ) {
		// Reset all settings to default when reset button is clicked
		if ( isset( $_POST['reset_awb_options'] ) ) {
			update_option( 'awb_settings', [] );
	
			add_settings_error(
				'reset_awb_settings',
				esc_attr( 'settings_deleted' ),
				esc_html__( 'Your settings have been successfully deleted and reset to defaults.', 'add-whatsapp-button' ),
				'updated'
			);
	
			return array(); // Default settings
		}
	
		// Create our array for storing the validated options
		$output = array();
	
		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[ $key ] ) ) {
				// Sanitize phone number
				if ( $key == 'phone_number' ) {
					preg_replace('/[^0-9]/', '', $input[$key]);
	
					if ( !preg_match("/^\d+$/", $input[$key]) ) {
						add_settings_error( 'awb_phone_number_invalid', esc_attr( 'settings_updated' ), esc_html__( 'The value you entered in the phone number field is invalid. Please enter a valid number.', 'add-whatsapp-button' ) );
						add_action( 'admin_notices', 'print_errors' );
						$input[$key] = '';
					}
				}
	
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );
			} // end if
		} // end foreach
	
		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'awb_sanitize_inputs', $output, $input );
	}

	/**
	 * Validate Limiting Hours
	 *
	 * @since 2.0.0
	 *
	 * @param $hour
	 */
	private function validate_limiting_hours( $hour ) {
		if ( is_numeric( $hour ) && $hour >= 0 && $hour <= 24 ) {
			return $hour;
		}
		else {
			return '';
		}
	}

	/** 
	 * Print Options Page Content
	 *
	 * Called as a callback in `add_options_page()` for the plugin's settings page.
	 *
	 * @since 2.0.0
	 */ 
	public function print_options_page_content() {
		$settings = Plugin::$instance->get_plugin_options();

		// Set default button style for Settings Page Preview
		$button_style = ! empty( $settings['button_type'] ) ? $settings['button_type'] : 'wab-side-rectangle';
		// Create default button text
		$button_text = ! empty( $settings['button_text'] ) ? sanitize_text_field( $settings['button_text'] ) : __('Message Us on WhatsApp', 'add-whatsapp-button');
		// Hide Text if selected button style is "Icon"
		$displayNoneIfIcon = ( $settings['button_type'] == 'wab-icon-plain' || $settings['button_type'] == 'wab-icon-styled' ) ? 'class="awb-displaynone"' : '';
		// Set default icon size if the button type is WhatsApp icon
		$icon_size = ! empty( $settings['icon_size'] ) ? sanitize_text_field( $settings['icon_size'] ) : '80';
		// If the breakpoint setting is inactive (the "enable breakpoint" checkbox is checked), hide the breakpoint settings.
		$bp_no_show_class = empty( $settings['enable_breakpoint'] ) ? ' class="awb-hide"' : '';
		// If the 'limit hours' setting is inactive, hide the hour controls.
		$lh_no_show_class = empty( $settings['limit_hours'] ) ? ' class="awb-hide"' : '';
		// If the 'Hide Button' setting is inactive, hide the radio buttons with the hiding settings.
		$hb_no_show_class = empty( $settings['enable_hide_button'] ) ? ' class="awb-hide"' : '';
		// If the 'Enable Message' setting is inactive, hide the textarea.
		$em_no_show_class = empty( $settings['enable_message'] ) ? ' class="awb-hide"' : '';
		// If the saved button type is not a plain WhatsApp icon, hide the icon size control.
		$is_no_show_class = ! empty( $settings['button_type'] ) && 'wab-icon-plain' !== $settings['button_type'] ? ' class="awb-hide"' : '';

		$button_inline_styles = '';
		// Inline Style
		if ( $settings['button_bg_color'] || $settings['button_text_color'] ) {
			$button_inline_styles = ' style="';

			// If there is a saved background color, add it to the button with a style tag.
			if ( ! empty( $settings['button_bg_color'] ) ) {
				$button_inline_styles .= 'background-color: ' . $settings['button_bg_color'] . ';';
			}

			// If there is a saved text color, add it to the button with a style tag.
			if ( ! empty( $settings['button_text_color'] ) ) {
				$button_inline_styles .= ' color: ' . $settings['button_text_color'] . ';';
			}

			$button_inline_styles .= '"';
		}

		ob_start(); ?>

		<div class="wrap">
			<?php //settings_errors(); ?>

			<h2><?php echo esc_html__( 'Add WhatsApp Button Settings', 'add-whatsapp-button'); ?></h2>
			<p>
				<?php echo esc_html__( 'Settings page for the Add WhatsApp Button plugin. Check out the preview screen in the "Button Design" tab to see how your button would look on a smartphone before saving your settings to the database.', 'add-whatsapp-button'); ?><br />
				<a href="https://wordpress.org/support/plugin/add-whatsapp-button/reviews/" target="_blank"><?php echo esc_html__( 'Rate "Add WhatsApp Button" at wordpress.org!', 'add-whatsapp-button'); ?></a>
			</p>

			<form method="POST" action="options.php">
				<?php settings_fields( 'awb_settings_group' ); ?>

				<div class="nav-tab-wrapper">
					<a href="#" id="gstablink" class="nav-tab nav-tab-active"><?php echo esc_html__( 'General Settings', 'add-whatsapp-button'); ?></a>
					<a href="#" id="bdtablink" class="nav-tab"><?php echo esc_html__( 'Button Design', 'add-whatsapp-button'); ?></a>
				</div>

				<div id="gstab" class="awb-tab-content-wrapper awb-tab-active">
					<table class="form-table">
						<tbody>
							<tr>
								<th colspan="2">
									<h2 class="awb-button-design-title"><?php echo esc_html__( 'General Settings', 'add-whatsapp-button'); ?></h2>
								</th>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[enable]"><?php echo esc_html__( 'Enable WhatsApp Button', 'add-whatsapp-button'); ?></label></th>
								<td><input name="awb_settings[enable]" type="checkbox" id="awb_settings[enable]" value="1" <?php isset( $settings['enable'] ) ? checked('1', $settings['enable'] ) : ''; ?>></td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[button_text]"><?php echo esc_html__( 'Button Text', 'add-whatsapp-button'); ?></label></th>
								<td>
									<input name="awb_settings[button_text]" type="text" id="awb_settings[button_text]" value="<?php echo $button_text; ?>" class="regular-text">
									<p class="description"><?php echo esc_html__( 'Enter the text you want the button to show. Recommended: up to 18 characters.', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[phone_number]"><?php echo esc_html__( 'Target Phone Number', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input required name="awb_settings[phone_number]" type="text" id="awb_settings[phone_number]" value="<?php echo sanitize_text_field( $settings['phone_number'] ); ?>" placeholder="12345678910" class="regular-text">
									<p class="description"><?php echo esc_html__( 'Enter the phone number you want the WhatsApp message to be sent to, with your country code, WITHOUT a "+" (PLUS) SIGN. For example, if you wanted to send WhatsApp messages to the number +1-770-123-4567, you would enter: 17701234567 in the input box.', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[enable_message]"><?php echo esc_html__( 'Default Message', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[enable_message]" type="checkbox" id="awb_settings[enable_message]" value="1" <?php isset($settings['enable_message'] ) ? checked('1', $settings['enable_message'] ) : ''; ?>>
									<p class="description"><?php echo esc_html__( 'Check this box in order to set a default message to be pre-written when users click the button. For example: "Hi, I\'m interested in your product".', 'add-whatsapp-button'); ?></p>
									<div id="awb_enable_message"<?php echo $em_no_show_class; ?>>
										<textarea name="awb_settings[default_message]" type="number" id="awb_settings[default_message]" class="small-text"><?php echo isset($settings['default_message'] ) ? sanitize_textarea_field( $settings['default_message'] ) : ''; ?></textarea>
										<p class="description"><?php echo esc_html__( 'Enter the message you want to pre-enter for the users when they click on your WhatsApp button.', 'add-whatsapp-button'); ?></p>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[hide_button]"><?php echo esc_html__( 'Add Hide Button', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[enable_hide_button]" type="checkbox" id="awb_settings[enable_hide_button]" value="1" <?php isset($settings['enable_hide_button'] ) ? checked('1', $settings['enable_hide_button'] ) : ''; ?>>
									<p class="description"><?php echo esc_html__( 'Check this box in order to add a small "Hide" button at the far right corner of the WhatsApp button.', 'add-whatsapp-button'); ?></p>
									<div id="awb_hide_button"<?php echo $hb_no_show_class; ?>>
										<input type="radio" name="awb_settings[hide_button]" value="full" <?php isset($settings['hide_button'] ) ? checked('full', $settings['hide_button'] ) : ''; ?> /> <strong>Full Remove</strong>
										<p class="description radio-description"><?php echo esc_html__( 'Choose this option to make the WhatsApp button disappear completely on click.', 'add-whatsapp-button'); ?></p>
										<input type="radio" name="awb_settings[hide_button]" value="hide" <?php isset($settings['hide_button'] ) ? checked('hide', $settings['hide_button'] ) : ''; ?> /> <strong>Hide with toggle button</strong>
										<p class="description radio-description">
											<?php echo esc_html__( 'Choose this option to make the WhatsApp button slide almost entirely off screen, while keeping the toggle button visible.', 'add-whatsapp-button'); ?><br />
											<?php echo esc_html__( 'Clicking the toggle button again will slide the WhatsApp button back into view.', 'add-whatsapp-button'); ?>
										</p>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[breakpoint]"><?php echo esc_html__( 'Breakpoint', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[enable_breakpoint]" type="checkbox" id="awb_settings[enable_breakpoint]" value="1" <?php isset( $settings['enable_breakpoint'] ) ? checked('1', $settings['enable_breakpoint'] ) : ''; ?>>
									<p class="description"><?php echo esc_html__( 'Check this box in order to only display the WhatsApp button up to a certain screen width.', 'add-whatsapp-button'); ?></p>
									<div id="awb_breakpoint"<?php echo $bp_no_show_class; ?>>
										<input name="awb_settings[breakpoint]" type="number" id="awb_settings[breakpoint]" value="<?php echo sanitize_text_field( $settings['breakpoint'] ); ?>" class="small-text"><?php echo esc_html__( 'px', 'add-whatsapp-button'); ?>
										<p class="description"><?php echo esc_html__( 'Enter your desired screen width breakpoint here. Default is 600px.', 'add-whatsapp-button'); ?></p>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[limit_hours]"><?php echo esc_html__( 'Limit Display Time', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[limit_hours]" type="checkbox" id="awb_settings[limit_hours]" value="1" <?php isset( $settings['limit_hours'] ) ? checked('1', $settings['limit_hours'] ) : ''; ?>>
									<p class="description"><?php echo esc_html__( 'Check this box in order to only display the WhatsApp button in certain hours of the day.', 'add-whatsapp-button'); ?></p>
									<div id="awb_limit_hours"<?php echo $lh_no_show_class; ?>>
										<span class="awb-hours"><?php echo esc_html__( 'Start Hour:', 'add-whatsapp-button'); ?> </span>
										<select name="awb_settings[startHour]" id="awb_settings[startHour]">
											<?php for ($i = 0; $i<=24; $i++) { ?>
												<option value="<?php echo $i; ?>" <?php selected( $this->validate_limiting_hours( $settings['startHour'] ), $i ); ?>><?php echo ( strlen( (string) $i ) == 2 ) ? $i : '0' . $i; ?>:00</option>
											<?php } ?>
										</select>
										<p class="description"><?php echo esc_html__( 'The WhatsApp button will be displayed starting this hour (24 hour clock). If no time is chosen, default is 8:00 (8AM). Make sure your starting hour is before your ending hour.', 'add-whatsapp-button'); ?></p>

										<span class="awb-hours"><?php echo esc_html__( 'End Hour:', 'add-whatsapp-button'); ?> </span>
										<select name="awb_settings[endHour]" id="awb_settings[endHour]">
											<?php for ($i = 0; $i<=24; $i++) { ?>
												<option value="<?php echo $i; ?>" <?php selected( $this->validate_limiting_hours( $settings['endHour'] ), $i ); ?>><?php echo ( strlen( (string) $i ) == 2 ) ? $i : '0' . $i; ?>:00</option>
											<?php } ?>
										</select>
										<p class="description"><?php echo esc_html__( 'The WhatsApp button will be displayed up until this hour (24 hour clock). If no time is chosen, default is 22 (10PM).', 'add-whatsapp-button'); ?></p>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="bdtab" class="awb-tab-content-wrapper">
					<table class="form-table" id="awb_design_settings_wrapper">
						<tbody>
							<tr>
								<th colspan="2">
									<h2 class="awb-button-design-title"><?php echo esc_html__( 'Button Design', 'add-whatsapp-button'); ?></h2>
								</th>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[button_bg_color]"><?php echo esc_html__( 'Button Background Color', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[button_bg_color]" type="text" id="awb_settings[button_bg_color]"  value="<?php echo sanitize_text_field( $settings['button_bg_color'] ); ?>" class="udi-bg-color-picker" />
									<p class="description"><?php echo esc_html__( 'Choose a background color for your button. Default is green (#20B038)', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[button_text_color]"><?php echo esc_html__( 'Button Text Color', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[button_text_color]" type="text" id="awb_settings[button_text_color]"  value="<?php echo sanitize_text_field( $settings['button_text_color'] ); ?>" class="udi-text-color-picker" />
									<p class="description"><?php echo esc_html__( 'Choose a text color for your button. Default is white (#ffffff)', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[distance_from_bottom]"><?php echo esc_html__( 'Button Distance from Bottom', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[distance_from_bottom]" type="number" id="awb_settings[distance_from_bottom]"  value="<?php echo sanitize_text_field( $settings['distance_from_bottom'] ); ?>" class="small-text" />

									<select class="awb-mu-select" id="awb_settings[distance_from_bottom_mu]" name="awb_settings[distance_from_bottom_mu]" style="vertical-align: baseline;">
										<option value="%" <?php selected( $settings['distance_from_bottom_mu'], '%' ); ?>>%</option>
										<option value="px" <?php selected( $settings['distance_from_bottom_mu'], 'px' ); ?>>px</option>
									</select>

									<p class="description"><?php echo esc_html__( 'Choose your button\'s Distance from the bottom of the screen, in percentages or pixels. Default is 10%.', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="awb_settings[button_type]"><?php echo esc_html__( 'Button Style', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<select class="awb-bt-select" id="awb_settings[button_type]" name="awb_settings[button_type]" style="vertical-align: baseline;">
										<option disabled selected value> -- Select Button Type -- </option>
										<option value="wab-icon-plain" <?php selected( $settings['button_type'], 'wab-icon-plain' ); ?>>Plain WhatsApp Icon</option>
										<option value="wab-side-rectangle" <?php selected( $settings['button_type'], 'wab-side-rectangle' ); ?>>Side-Floating Rectangle with Text</option>
										<option value="wab-bottom-rectangle" <?php selected( $settings['button_type'], 'wab-bottom-rectangle' ); ?>>Fixed-Bottom Rectangle with Text</option>
									</select>
									<p class="description"><?php echo esc_html__( 'Choose your button\'s Style: Round WhatsApp icon, a floating rectangle with text, or a full-width fixed bottom button.', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<tr id="iconSizeSettingRow"<?php echo $is_no_show_class; ?>>
								<th scope="row"><label for="awb_settings[icon_size]"><?php echo esc_html__( 'Icon Button Size', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<input name="awb_settings[icon_size]" type="number" id="awb_settings[icon_size]"  value="<?php echo $icon_size; ?>" class="small-text" />

									<select class="awb-mu-select" id="awb_settings[icon_size_mu]" name="awb_settings[icon_size_mu]" style="vertical-align: baseline;">
										<option value="px" <?php selected( $settings['icon_size_mu'], 'px' ); ?>>px</option>
										<option value="em" <?php selected( $settings['icon_size_mu'], 'em' ); ?>>em</option>
										<option value="rem" <?php selected( $settings['icon_size_mu'], 'rem' ); ?>>rem</option>
									</select>

									<p class="description"><?php echo esc_html__( 'Choose your button\'s Distance from the bottom of the screen, in percentages or pixels. Default is 10%.', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
							<th scope="row"><label for="awb_settings[button_location]"><?php echo esc_html__( 'Button Location on Screen', 'add-whatsapp-button' ); ?></label></th>
								<td>
									<select id="awb_settings[button_location]" name="awb_settings[button_location]" style="vertical-align: baseline;">
										<option value="right" <?php selected( $settings['button_location'], 'right' ); ?>>right</option>
										<option value="left" <?php selected( $settings['button_location'], 'left' ); ?>>left</option>
									</select>
									<p class="description"><?php echo esc_html__( 'Choose whether your button will appear on the left side or right side of the screen', 'add-whatsapp-button'); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
				
					<div class="device-wrapper"> <!-- Mockup Container -->
					<h2><?php echo esc_html__( 'Button Preview', 'add-whatsapp-button'); ?></h2>
						<div class="device" data-device="iPhone7" data-orientation="portrait" data-color="black">
							<div class="screen">
								<div class="mockup-top-spacer"></div>
								<div class="gray-logo"></div>
								<div class="gray-menu"></div>
								<div class="gray-row"></div>
								<div class="gray-row"></div>
								<div class="gray-row-cont">
									<div class="gray-row-cont-inner">
										<div class="gray-row gray-row-half" style="margin-top: 0;"></div>
										<div class="gray-row gray-row-half"></div>
										<div class="gray-row gray-row-half"></div>
										<div class="gray-row gray-row-half"></div>
									</div>
									<div class="gray-row-cont-inner">
										<div class="gray-row-half-img"></div>
									</div>
								</div>

								<div class="gray-row gray-row-box"></div>
								<div id="admin_wab_cont" class="wab-cont <?php echo $button_style; ?> <?php echo ( $button_style !== 'wab-bottom-rectangle' ) ? 'wab-pull-' . $settings['button_location'] : ''; ?>"> <!-- Button Preview HTML -->
									<a id="whatsAppButton"<?php echo $button_inline_styles; ?> href="https://wa.me/<?php echo $settings['phone_number'] . ( ! empty( $settings['default_message'] ) && $settings['enable_message'] == '1' ) ? '/?text='. rawurlencode( $settings['default_message'] ) : ''; ?>" target="_blank">
										<span id="wab-text" <?php echo $displayNoneIfIcon; ?>><?php echo $button_text; ?></span>
									</a>
								</div>

							</div> <!-- /screen -->
							<div class="button">
							<!-- You can hook the "home button" to some JavaScript events or just remove it -->
							</div>
						</div> <!-- /device -->
					</div> <!-- /device-wrapper -->
				</div> <!-- /bdtab -->
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__( 'Save Changes', 'add-whatsapp-button' ); ?>">
				</p>
				<h2><?php echo esc_html__( 'Reset All Settings', 'add-whatsapp-button' ); ?></h2>
				<p>
					<?php submit_button( esc_html__( 'Reset All Settings', 'add-whatsapp-button' ), 'secondary', 'reset_awb_options', false); ?>
				</p>
			</form>
		</div> <!-- /wrap -->

		<?php
		echo ob_get_clean();
	}

	public function maybe_show_five_star_review_notice() {
		if ( ! PAnD::is_admin_notice_active( 'disable-done-notice-forever' ) ) {
			return;
		}
		
		$screen = get_current_screen();
		
		if ( $screen->id == 'settings_page_awb-options' ) {
			?>
			<div data-dismissible="disable-done-notice-forever" class="notice notice-info is-dismissible">
				<p>
					<?php echo esc_html( 'Thanks for installing Add WhatsApp Button! Liked the plugin? We\'d really appreciate it if you could help us out with', 'add-whatsapp-button'); ?> 
					<a href="https://wordpress.org/support/plugin/add-whatsapp-button/reviews/" target="_blank"><?php echo esc_html( 'a 5-star rating and review!', 'add-whatsapp-button'); ?></a>
				</p>
			</div>
			<?php
		}
	}
}
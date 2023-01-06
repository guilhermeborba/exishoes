<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://la-studioweb.com/
 * @since             1.0.0
 * @package           LaStudio_Header_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       LA-Studio Header Builder
 * Plugin URI:        https://la-studioweb.com/
 * Description:       This plugin use only for LA-Studio theme
 * Version:           1.2.2.1
 * Author:            LA-Studio
 * Author URI:        https://la-studioweb.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lastudio-header-builder
 * Domain Path:       /languages
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit;
}

if ( ! class_exists( 'LaStudio_Header_Builder' ) ) :
	class LaStudio_Header_Builder {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     LaStudio_Header_Builder
		 */
		private static $instance;

		/**
		 * The modules variable holds all modules of the plugin.
		 *
		 * @since	1.0.0
		 * @access	private
		 * @var		object
		 */
		private static $modules = array();

		/**
		 * Main path.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     string
		 */
		private static $path;

		/**
		 * Absolute url.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     string
		 */
		private static $url;

		private $html_output = '';

		/**
		 * The current version of the LaStudio Header Footer Builder.
		 *
		 * @since    1.0.0
		 */
		const VERSION		= '1.2.2.1';

		/**
		 * The LaStudio Header Footer Builder prefix to reference classes inside it.
		 *
		 * @since	1.0.0
		 */
		const CLASS_PREFIX	= 'LAHB_';

		/**
		 * The LaStudio Header Footer Builder prefix to reference files and prefixes inside it.
		 *
		 * @since	1.0.0
		 */
		const FILE_PREFIX	= 'lahb-';

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return	object
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Define the core functionality of the LaStudio Header Footer Builder.
		 *
		 * Load the dependencies.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {
			self::$path	= plugin_dir_path( __FILE__ );
			self::$url	= plugin_dir_url( __FILE__ );

			require_once( self::$path . 'includes/functions/functions.php' );
			require_once( self::$path . 'includes/class-loader.php' );

			self::$modules['LAHB_Loader']			= LAHB_Loader::get_instance();
			self::$modules['LAHB_Helper']			= LAHB_Helper::get_instance();
			// LAHB_Helper::clearHeaderData();
			LAHB_Helper::setHeaderDefaultData();
			self::$modules['LAHB_Enqueue']			= LAHB_Enqueue::get_instance();
			self::$modules['LAHB_Ajax']			    = LAHB_Ajax::get_instance();
			self::$modules['LAHB_Field']			= LAHB_Field::get_instance();
			self::$modules['LAHB_Element']			= LAHB_Element::get_instance();
			self::$modules['LAHB_Frontend_Builder'] = LAHB_Frontend_Builder::get_instance();

			load_plugin_textdomain(
				'lastudio-header-builder',
				false,
				basename( dirname( __FILE__ ) ) . '/languages'
			);

			add_action( 'after_setup_theme', ['LAHB_Update', 'instance'], 99 );

			add_action( 'wp_head', array( $this, 'prepare_dynamic_style' ) );
			add_action( 'lastudio/header-builder/render-output', array( $this, 'register_action_output' ), 10 );
		}

		/**
		 * Get the LaStudio Header Footer Builder absolute path.
		 *
		 * @since	1.0.0
		 */
		public static function get_path() {
			return self::$path;
		}

		/**
		 * Get the LaStudio Header Footer Builder absolute url.
		 *
		 * @since	1.0.0
		 */
		public static function get_url() {
			return self::$url;
		}

		private function _html_output( $is_frontend_builder = false, $lahb_data = array(), $include_html_tag = true ){
			$is_frontend_builder = $is_frontend_builder ? $is_frontend_builder : LAHB_Helper::is_frontend_builder();

			$header_show = '';

			$vertical_output = '';

			// header visibility
			if ( $header_show === '1') {
				$header_show = true;
			}
			elseif ( $header_show === '0' ) {
				$header_show = false;
			}
			elseif ( $header_show === false || empty( $header_show ) ) {
				$header_show = true;
			}

			if ( ! ( $is_frontend_builder || $header_show ) ) {
				return;
			}

			LAHB_Helper::set_dynamic_styles('', true);

			$lahb_data = $lahb_data ? $lahb_data :  LAHB_Helper::get_data_frontend_components();

			$prepare_data = LAHB_Helper::convertOldHeaderData( $lahb_data );

			$header_components = LAHB_Helper::get_only_components_from_settings($prepare_data);
			$panels_settings = LAHB_Helper::get_only_panels_from_settings($prepare_data);

			/**
			 * What we need to do now is
			 * 1) Render all the components - this will save more time
			 * 2) Then we need render panel to match with screen view
			 */
			$registered_components = LAHB_Helper::get_elements();

			$components_has_run = array();

			// Start render header output
			$class_frontend_builder = $is_frontend_builder ? ' lahb-frontend-builder' : '';
			if($include_html_tag){
				$output = '<header id="lastudio-header-builder" class="lahb-wrap' . esc_attr( $class_frontend_builder ) . '">';
			}
			else{
				$output = '';
			}

			$output .= '<div class="lahbhouter">';
			$output .= '<div class="lahbhinner">';
			$output .= '<div class="main-slide-toggle"></div>';

			if(!empty($panels_settings)){

				/**
				 * We need to check header type vertical first !!
				 * if this is vertical type ==> remove others areas on desktop-view except 'row1'
				 */
				$__detect_header_type = '';
				if(isset($panels_settings['desktop-view']['row1']['settings']['header_type'])){
					$__detect_header_type = $panels_settings['desktop-view']['row1']['settings']['header_type'];
				}

				// Screen
				foreach ( $panels_settings as $screen_view_index => $panels_setting ) {
					$output .= '<div class="lahb-screen-view lahb-' . esc_attr( $screen_view_index  ) . '">';

					$vertical_header = '';

					foreach ($panels_setting as $_r_idx => &$_r){
						if(!isset($_r['settings']['order'])){
							$_r['settings']['order'] = 1;
						}
					}

					uasort($panels_setting, function ($a, $b){
						return $a['settings']['order'] - $b['settings']['order'];
					});

					$screen_view = $panels_setting;

					// Rows
					foreach ( $screen_view as $row_index => $rows ) {
						if($screen_view_index == 'desktop-view' && $__detect_header_type == 'vertical'){
							if($row_index != 'row1' && $row_index != 'topbar'){
								continue;
							}
						}

						// check visibility
						$hidden_area = $rows['settings']['hidden_element'];
						if ( $hidden_area === 'false' ) {
							$hidden_area = false;
						}
						elseif ( $hidden_area === 'true' ) {
							$hidden_area = true;
						}

						// check vertical header
						if ( $screen_view_index == 'desktop-view' ) {
							$header_type = !empty($rows['settings']['header_type']) ? $rows['settings']['header_type'] : '';
							if ($row_index != 'row1') {
								if ($header_type == 'vertical'){
									continue;
								}
							}
							else {
								if ($header_type == 'vertical') {
									$vertical_header = ' lahb-vertical lahb-vcom';
								}
							}
						}

						// start render area
						if ( ! $hidden_area ) {

							$tmp_output = '';

							$area_settings      = isset( $rows['settings'] ) ? $rows['settings'] : '';
							$areas              = array();
							$areas['left']      = isset( $rows['left'] ) ? $rows['left'] : '';
							$areas['center']    = isset( $rows['center'] ) ? $rows['center'] : '';
							$areas['right']     = isset( $rows['right'] ) ? $rows['right'] : '';

							$full_container = $container_padd = $content_position = $extra_class = $extra_id = '';
							if(isset($area_settings['uniqueId']) && isset($header_components[$area_settings['uniqueId']])){
								$area_settings = LAHB_Helper::component_atts( $area_settings, $header_components[ $area_settings['uniqueId'] ] );
							}
							extract( LAHB_Helper::component_atts( array(
								'full_container'	=> 'false',
								'container_padd'	=> 'true',
								'content_position'	=> 'middle',
								'extra_class'   	=> '',
								'extra_id'      	=> ''
							), $area_settings ));

							// once fire

							$is_header_vertical = false;

							if ( $header_type == 'vertical' && $screen_view_index == 'desktop-view' ) {

                                $tmp_output = $output;
								$output = '';

								if ($header_type == 'vertical') {

									$is_header_vertical = true;

									$vertical_toggle = $vertical_toggle_icon = $logo = $vertical_box_width = $vertical_box_width_small = '';

									if(isset($area_settings['heightbox_md'])){
										unset($area_settings['heightbox_md']);
									}
									if(isset($area_settings['widthbox_md'])){
										unset($area_settings['widthbox_md']);
									}

									extract(LAHB_Helper::component_atts(array(
										'vertical_toggle' => 'false',
										'vertical_toggle_icon' => 'lastudioicon-menu-7',
										'logo' => '',
										'vertical_box_width' => '',
										'vertical_box_width_small' => '',
									), $area_settings));

									$area_settings['area_screen_index'] = $screen_view_index;
									$area_settings['area_row_index'] = $row_index;
									$area_settings['area_vertical'] = true;

									// Render Custom Style
									if(!empty($vertical_box_width) || !empty($vertical_box_width_small)){
										$vertical_dynamic_extra_css = '';
										if(!empty($vertical_box_width)){
											$vertical_dynamic_extra_css .= ':root{--theme-header-vertical-width:' .$vertical_box_width . '}';
										}
										if(!empty($vertical_box_width_small)){
											$vertical_dynamic_extra_css .= ':root{--theme-header-vertical-width-small:' .$vertical_box_width_small . '}@media(max-width: 1700px){:root{--theme-header-vertical-width:' .$vertical_box_width_small . '}}';
										}
										LAHB_Helper::set_dynamic_styles($vertical_dynamic_extra_css);
									}
									$vertical_dynamic_style = lahb_styling_tab_output($area_settings, 'logo', '#lastudio-header-builder .lahb-vertical-logo-wrap');
									$vertical_dynamic_style .= lahb_styling_tab_output($area_settings, 'toggle_bar', '#lastudio-header-builder .lahb-vertical-toggle-wrap', '#lastudio-header-builder .lahb-varea:hover .lahb-vertical-toggle-wrap');
									$vertical_dynamic_style .= lahb_styling_tab_output($area_settings, 'toggle_icon_box', '#lastudio-header-builder .vertical-toggle-icon', '#lastudio-header-builder .vertical-toggle-icon:hover');
									$vertical_dynamic_style .= lahb_styling_tab_output($area_settings, 'box', '#lastudio-header-builder.lahb-wrap .lahb-vertical');

									if ( !empty($vertical_dynamic_style) ) {
										LAHB_Helper::set_dynamic_styles('@media (min-width: 1280px) { ' . $vertical_dynamic_style . ' } ');
									}
								}

								if ($vertical_toggle == 'true') {
									$logo = $logo ? lahb_wp_get_attachment_url($logo) : '';
									// Render Toggle Wrap
									$output .= '<div class="lahb-vcom lahb-vertical-toggle-wrap">';
									if (!empty($logo)) {
										$output .= sprintf(
											'<div class="lahb-vertical-logo-wrap"><a href="%s"><img class="lahb-vertical-logo" src="%s" alt="%s"></a></div>',
											esc_url(home_url('/')),
											esc_url($logo),
											get_bloginfo('name')
										);
									}

									$output .= '<a href="#" class="vertical-toggle-icon"><i class="' . lahb_rename_icon($vertical_toggle_icon) . '" ></i></a>';

									$toggle_bar_rows = isset($panels_settings['desktop-view']['row2']) ? $panels_settings['desktop-view']['row2'] : [];

									if( !empty($toggle_bar_rows) && isset($toggle_bar_rows['settings']['hidden_element']) && !filter_var($toggle_bar_rows['settings']['hidden_element'], FILTER_VALIDATE_BOOLEAN)){
										$output .= '<div class="lahb-vertical--extras">';
										foreach ($toggle_bar_rows as $t_key => $t_col){
											if($t_key == 'settings'){
												continue;
											}
											if(!empty($t_col)){
												foreach ($t_col as $t_el_index => $t_el){
													if ($t_el_index === 'settings') {
														continue;
													}
													$t_hidden_el = $t_el['hidden_element'];
													if (!$t_hidden_el) {
														$t_uniqid = $t_el['uniqueId'];
														$t_component_name = $t_el['name'];

														$once_run_flag = false;
														//make component as loaded
														if(!array_key_exists($t_uniqid, $components_has_run)){
															$components_has_run[$t_uniqid] = $t_component_name;
															$once_run_flag = true;
														}

														if(isset($registered_components[$t_component_name])){
															$t_func_name_comp = $registered_components[$t_component_name];
															$output .= call_user_func( $t_func_name_comp, $header_components[$t_uniqid], $t_uniqid, $once_run_flag );
														}

													}
												}
											}
										}
										$output .= '</div>';
									}

									$output .= '</div>';

								}

							}

							// height
							if ( ! empty( $area_height ) ) {
								$area_height = ! empty( $area_height ) ? $area_height : '';
								$area_height = 'height: ' . LAHB_Helper::css_sanatize( $area_height ) . ';';
								LAHB_Helper::set_dynamic_styles( '#lastudio-header-builder .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area { ' . $area_height . ' }');
							}

							$dynamic_style = '';

							if(!$is_header_vertical){
								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'typography', '.lahb-wrap .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area' );
								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'background', '.lahb-wrap .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area' );
								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'box', '.lahb-wrap .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area' );
								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'boxcontainer', '.lahb-wrap .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area > .container' );

								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'transparency_background', '.enable-header-transparency .lahb-wrap:not(.is-sticky) .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area' );
								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'transparency_text_color', '.enable-header-transparency .lahb-wrap:not(.is-sticky) .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area .lahb-element, .enable-header-transparency .lahb-wrap:not(.is-sticky) .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area .lahb-search .search-field' );
								$dynamic_style .= lahb_styling_tab_output( $area_settings, 'transparency_link_color', '.enable-header-transparency .lahb-wrap:not(.is-sticky) .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area .lahb-element:not(.lahb-nav-wrap) a' );
							}

							// width
							if ( ! empty( $area_width ) ) {
								$area_width = 'width: ' . LAHB_Helper::css_sanatize( $area_width ) . ';';
								LAHB_Helper::set_dynamic_styles( '@media (min-width: 1280px) { .lahb-wrap .lahb-'.$screen_view_index.' .lahb-' . $row_index . '-area > .container { ' . $area_width . ' } }');
							}

							if ( !empty($dynamic_style) ) {
								LAHB_Helper::set_dynamic_styles( $dynamic_style );
							}

							// Classes
							$area_classes   = '';
							$area_classes   .= ! empty($content_position) ? ' lahb-content-' . $content_position : '' ;
							$area_classes   .= ! empty($extra_class) ? ' ' . $extra_class : '' ;
							$container_padd = $container_padd == 'true' ? '' : ' la-no-padding';
							if( $full_container != 'false' ) {
								$container_padd .= ' la-container-full';
							}
							// Id
							$extra_id = ! empty( $extra_id ) ? ' id="' . esc_attr( $extra_id ) . '"' : '' ;

							// Toggle vertical
							if($screen_view_index == 'mobiles-view'){
								$row_layout = ' lahb-area__' . ( !empty($area_settings['row_layoutrow_layout_xs']) ? $area_settings['row_layoutrow_layout_xs'] : 'auto' );
							}
							elseif ($screen_view_index == 'tablets-view'){
								$row_layout = ' lahb-area__' . ( !empty($area_settings['row_layoutrow_layout_sm']) ? $area_settings['row_layoutrow_layout_sm'] : 'auto' );
							}
							else{
								$row_layout = ' lahb-area__' . ( !empty($area_settings['row_layoutrow_layout_md']) ? $area_settings['row_layoutrow_layout_md'] : 'auto' );
								if(!empty($vertical_header)){
									$row_layout = ' lahb-area__auto';
								}
							}

							$output .= '<div class="lahb-area lahb-' . $row_index . '-area' . $vertical_header . $area_classes . $row_layout . '"' . $extra_id . '>';

							if(!$is_header_vertical){
								$output .= '<div class="container' . $container_padd . '">';
							}

							$output .= '<div class="lahb-content-wrap'. esc_attr($row_layout) .'">';

							// Columns
							foreach ( $areas as $area_key => $components ) {
								$output .= '<div class="lahb-col lahb-col__' . esc_attr($area_key) . '">';
								if ($components) {
									foreach ($components as $component_index => $component) {
										if ($component_index === 'settings') {
											continue;
										}
										$hidden_el = $component['hidden_element'];
										if (!$hidden_el) {
											$uniqid = $component['uniqueId'];
											$component_name = $component['name'];

											$once_run_flag = false;

											//make component as loaded
											if(!array_key_exists($uniqid, $components_has_run)){
												$components_has_run[$uniqid] = $component_name;
												$once_run_flag = true;
											}

											if(isset($registered_components[$component_name])){
												$func_name_comp = $registered_components[$component_name];
												$component_args = $header_components[$uniqid];
												if($component_name == 'logo'){
													$component_args['screen_view_index'] = $screen_view_index;
												}
												$output .= call_user_func( $func_name_comp, $component_args, $uniqid, $once_run_flag );
											}

										}

									} // end components loop
								}
								$output .= '</div>';

							} // end areas loop

							$output .= '</div><!-- .lahb-content-wrap -->';

							if(!$is_header_vertical) {
								$output .= '</div><!-- .container -->';
							}

							$output .= '</div><!-- .lahb-area -->';

							if($is_header_vertical && function_exists('lastudio_get_theme_support') && lastudio_get_theme_support('header-builder::header-vertical')){
								$vertical_output = $output;
							}
							else{
								$tmp_output .= $output;
							}

							$output = $tmp_output;

						}
					}
					$output .= '</div>';
				}
			}

			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="lahb-wrap-sticky-height"></div>';
			if(!empty($vertical_output)){
				$output .= '<div class="lahb-screen-view lahb-desktop-view lahb-varea">';
				$output .= $vertical_output;
				$output .= '</div>';
			}

			if($include_html_tag) {
				$output .= '</header>';
			}

			if( $is_frontend_builder ) {
				$output .= sprintf('<style id="lahb-frontend-styles-inline-css">%s</style>', LAHB_Helper::get_styles());
			}

			do_action('lastudio/header-builder/components_has_run', $components_has_run);

			return $output;
		}

		public function prepare_dynamic_style(){
			$data = apply_filters('lastudio/header-builder/setup-data-preset', array());
			$this->set_html_output( false, $data );
			$styles = '.mm-popup-wide.mm--has-bgsub > .sub-menu > .mm-mega-li > .mm-mega-ul{background: none}';
            LAHB_Helper::set_dynamic_styles($styles, false);
			printf('<style id="lahb-frontend-styles-inline-css">%s</style>', LAHB_Helper::get_styles());
		}

		public function set_html_output($is_frontend_builder = false, $lahb_data = array(), $include_html_tag = false){
			$this->html_output = $this->_html_output($is_frontend_builder, $lahb_data, $include_html_tag);
		}

		public function get_html_output(){
			return $this->html_output;
		}

		public function register_action_output(){
			echo $this->get_html_output();
		}
	}

	// Create a simple alias
	class_alias( 'LaStudio_Header_Builder', 'LAHB' );

endif;

// Run LaStudio Header Footer Builder
add_action('plugins_loaded', array('LAHB', 'get_instance'));
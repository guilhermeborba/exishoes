<?php
/**
 * Header Builder - Enqueue Class.
 *
 * @author  LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHB_Enqueue' ) ) :

    class LAHB_Enqueue {

        /**
         * Instance of this class.
         *
         * @since	1.0.0
         * @access	private
         * @var		LAHB_Enqueue
         */
        private static $instance;

        /**
         * Provides access to a single instance of a module using the singleton pattern.
         *
         * @since	1.0.0
         * @return	object
         */
        public static function get_instance() {

            if ( self::$instance === null ) {
                self::$instance = new self();
            }

            return self::$instance;

        }

        /**
         * Constructor.
         *
         * @since	1.0.0
         */
        public function __construct() {

            // Enqueue editor scripts
            add_action( 'admin_enqueue_scripts',    array( $this, 'editor_scripts'), 1 );

            // Enqueue frontend scripts
            add_action( 'wp_enqueue_scripts',       array( $this, 'frontend_scripts' ) );
        }

        /**
         * Enqueue editor scripts.
         *
         * @since	1.0.0
         */
        public function editor_scripts() {

            if ( LAHB_Helper::is_backend_builder() ) {
                // admin utilities
                wp_enqueue_media();

                $script_dependencies = array(
                    'jquery',
                    'wp-color-picker',
                    'jquery-ui-sortable',
                    'jquery-ui-droppable',
                    'wp-color-picker-alpha'
                );

                $min = (apply_filters('lasf_dev_mode', false) || WP_DEBUG) ? '' : '.min';

                wp_register_script( 'wp-color-picker-alpha', LAHB_Helper::get_file_uri( 'assets/js/wp-color-picker-alpha.js' ), array( 'wp-color-picker' ), '2.1.3', true );
                // JavaScripts
                wp_register_script( 'lahb-nicescroll-script', LAHB_Helper::get_file_uri( 'assets/js/jquery.nicescroll.js' ) , $script_dependencies, null, true );
                wp_enqueue_script( 'lahb-editor-scripts', LAHB_Helper::get_file_uri('assets/src/editor/editor'.$min.'.js' ), array('lahb-nicescroll-script'), LAHB::VERSION, true );

                $header_preset_key = !empty($_GET['prebuild_header']) ? esc_attr($_GET['prebuild_header']) : '';
                $frontend_components = LAHB_Helper::get_data_frontend_components();

                $uniqid = uniqid();

                $default_components = LAHB_Helper::get_default_components($uniqid);

                $localize_data = array(
                    'nonce'                 => wp_create_nonce( 'lahb-nonce' ),
                    'ajaxurl'               => admin_url( 'admin-ajax.php', 'relative' ),
                    'assets_url'            => LAHB_Helper::get_file_uri( 'assets/' ),
                    'prebuilds_url'         => LAHB_Helper::get_file_uri( 'includes/prebuilds/headers/' ),
                    'components'            => LAHB_Helper::get_only_components_from_settings($frontend_components),
                    'editor_components'     => LAHB_Helper::get_only_panels_from_settings($frontend_components),
                    'prebuild_header_key'   => LAHB_Helper::is_prebuild_header_exists($header_preset_key) ? $header_preset_key : '',
                    'frontend_components'   => $frontend_components,
                    'default_data'          => array(
                        'uniqid'                => $uniqid,
                        'components'            => LAHB_Helper::get_only_components_from_settings($default_components),
                        'editor_components'     => LAHB_Helper::get_only_panels_from_settings($default_components),
                        'frontend_components'   => $default_components
                    ),
                    'backend_setting_page'  => admin_url( 'admin.php?page=lastudio_header_builder_setting' ),
                    'i18n'                  => array(
                        'save_text'                 => esc_attr__('Save Changes', 'lastudio-header-builder'),
                        'clear_data_text'           => esc_attr__('Are you sure?', 'lastudio-header-builder'),
                        'saved_text'                => esc_attr__('Saved', 'lastudio-header-builder'),
                        'horizontal_header_text'    => esc_attr__('Horizontal Header', 'lastudio-header-builder'),
                        'vertical_header_text'      => esc_attr__('Vertical Header', 'lastudio-header-builder'),
                    )
                );
                wp_localize_script( 'lahb-editor-scripts', 'lahb_localize', $localize_data );

                // Styles
                wp_enqueue_style( 'lahb-editor-styles', LAHB_Helper::get_file_uri( 'assets/src/editor/css/editor'.$min.'.css' ), array(),LAHB::VERSION );
            }

        }

        /**
         * Enqueue frontend scripts.
         *
         * @since	1.0.0
         */
        public function frontend_scripts() {
            $load_assets_frontend = apply_filters('LAHB/load_assets_frontend', false);
            if($load_assets_frontend){
                $asset_url = apply_filters('LAHB/asset_url', LAHB::get_url());

                // JavaScripts
                wp_register_script( 'lahb-jquery-plugins', $asset_url . 'assets/src/frontend/jquery-plugins.js' , array( 'jquery' ), LAHB::VERSION, true );
                wp_enqueue_script( 'lahb-frontend-scripts', $asset_url . 'assets/src/frontend/frontend.js', array( 'lahb-jquery-plugins' ), LAHB::VERSION, true );
                // Styles
                wp_enqueue_style( 'lahb-frontend-styles', $asset_url . 'assets/src/frontend/header-builder.css' , array(), LAHB::VERSION );
            }
        }
    }

endif;
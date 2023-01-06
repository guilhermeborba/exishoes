<?php
/**
 * Header Builder - Field Class.
 *
 * @author  LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ( ! class_exists( 'LAHB_Field' ) ) :

    class LAHB_Field {

		/**
		 * Instance of this class.
         *
		 * @since	1.0.0
		 * @access	private
		 * @var		LAHB_Field
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
            // Load all fields
            $this->load_fields();

            // Load styling tab
            $this->load_styling_tab();

            // Load styling tab output
            $this->load_styling_tab_output();
		}

		/**
		 * Load all fields.
		 *
		 * @since	1.0.0
		 */
        public function load_fields() {

            foreach ( glob( LAHB_Helper::get_file( 'includes/fields/*.php' ) ) as $file ) {
                include_once $file;
            }

        }

		/**
		 * Load styling tab.
		 *
		 * @since	1.0.0
		 */
        public function load_styling_tab() {

            include_once LAHB_Helper::get_file( 'includes/fields/styling-tab/styling-tab.php' );

        }

		/**
		 * Load styling tab output.
		 *
		 * @since	1.0.0
		 */
        public function load_styling_tab_output() {

            include_once LAHB_Helper::get_file( 'includes/fields/styling-tab/styling-tab-output.php' );

        }

    }

endif;

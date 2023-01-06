<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'lasf_get_icons' ) ) {
  function lasf_get_icons() {

    if( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'lasf_icon_nonce' ) ) {

      ob_start();

      LASF::include_plugin_file( 'fields/icon/default-icons.php' );

      $icon_lists = apply_filters( 'lasf_field_icon_add_icons', lasf_get_default_icons() );

      if( ! empty( $icon_lists ) ) {

        foreach ( $icon_lists as $list ) {

          echo ( count( $icon_lists ) >= 2 ) ? '<div class="lasf-icon-title">'. $list['title'] .'</div>' : '';

          foreach ( $list['icons'] as $icon ) {
            echo '<a class="lasf-icon-tooltip" data-lasf-icon="'. $icon .'" title="'. $icon .'"><span class="lasf-icon lasf-selector"><i class="'. $icon .'"></i></span></a>';
          }

        }

      } else {

        echo '<div class="lasf-text-error">'. esc_html__( 'No data provided by developer', 'lastudio' ) .'</div>';

      }

      wp_send_json_success( array( 'success' => true, 'content' => ob_get_clean() ) );

    } else {

      wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving.', 'lastudio' ), 'debug' => $_REQUEST ) );

    }

  }
  add_action( 'wp_ajax_lasf-get-icons', 'lasf_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'lasf_export' ) ) {
  function lasf_export() {

    if( ! empty( $_GET['export'] ) && ! empty( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'lasf_backup_nonce' ) ) {

      header('Content-Type: application/json');
      header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
      header('Content-Transfer-Encoding: binary');
      header('Pragma: no-cache');
      header('Expires: 0');

      echo json_encode( get_option( wp_unslash( $_GET['export'] ) ) );

    }

    die();
  }
  add_action( 'wp_ajax_lasf-export', 'lasf_export' );
}


/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'lasf_import_ajax' ) ) {
  function lasf_import_ajax() {

    if( ! empty( $_POST['import_data'] ) && ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'lasf_backup_nonce' ) ) {

      $import_data = json_decode( wp_unslash( trim( $_POST['import_data'] ) ), true );

      if( is_array( $import_data ) ) {

        update_option( wp_unslash( $_POST['unique'] ), wp_unslash( $import_data ) );
        wp_send_json_success( array( 'success' => true ) );

      }

    }

    wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving.', 'lastudio' ), 'debug' => $_REQUEST ) );

  }
  add_action( 'wp_ajax_lasf-import', 'lasf_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'lasf_reset_ajax' ) ) {
  function lasf_reset_ajax() {

    if( ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'lasf_backup_nonce' ) ) {
      delete_option( wp_unslash( $_POST['unique'] ) );
      wp_send_json_success( array( 'success' => true ) );
    }

    wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving.', 'lastudio' ), 'debug' => $_REQUEST ) );
  }
  add_action( 'wp_ajax_lasf-reset', 'lasf_reset_ajax' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'lasf_set_icons' ) ) {
  function lasf_set_icons() {
    ?>
    <div id="lasf-modal-icon" class="lasf-modal lasf-modal-icon">
      <div class="lasf-modal-table">
        <div class="lasf-modal-table-cell">
          <div class="lasf-modal-overlay"></div>
          <div class="lasf-modal-inner">
            <div class="lasf-modal-title">
              <?php esc_html_e( 'Add Icon', 'lastudio' ); ?>
              <div class="lasf-modal-close lasf-icon-close"></div>
            </div>
            <div class="lasf-modal-header lasf-text-center">
              <input type="text" placeholder="<?php esc_html_e( 'Search a Icon...', 'lastudio' ); ?>" class="lasf-icon-search" />
            </div>
            <div class="lasf-modal-content">
              <div class="lasf-modal-loading"><div class="lasf-loading"></div></div>
              <div class="lasf-modal-load"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  add_action( 'admin_footer', 'lasf_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'lasf_set_icons' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'lasf_chosen_ajax' ) ) {
    function lasf_chosen_ajax() {

        if( ! empty( $_POST['term'] ) && ! empty( $_POST['type'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'lasf_chosen_ajax_nonce' ) ) {

            $capability = apply_filters( 'lasf_chosen_ajax_capability', 'manage_options' );

            if( current_user_can( $capability ) ) {

                $type       = $_POST['type'];
                $term       = $_POST['term'];
                $query_args = ( ! empty( $_POST['query_args'] ) ) ? $_POST['query_args'] : array();
                $options    = LASF_Fields::field_data( $type, $term, $query_args );

                wp_send_json_success( $options );

            } else {
                wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'lastudio' ) ) );
            }

        } else {
            wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'lastudio' ) ) );
        }

    }
    add_action( 'wp_ajax_lasf-chosen', 'lasf_chosen_ajax' );
}

/**
 * Support Custom Fonts plugin
 * Since 2.0.18.1
 */
add_filter('lasf_field_typography_customwebfonts', function ( $fonts ){
    if(class_exists('Bsf_Custom_Fonts_Taxonomy')){
	    $all_fonts = Bsf_Custom_Fonts_Taxonomy::get_fonts();
	    if ( ! empty( $all_fonts ) ) {
		    foreach ( $all_fonts as $font_family_name => $fonts_url ) {
			    $fonts[] = $font_family_name;
		    }
	    }
    }
	return $fonts;
});

/**
 * Support Upload font type format
 * @since 2.0.18.1
 */

if(!function_exists('lasf_registered_file_types')){
    function lasf_registered_file_types(){
	    return [
		    'woff' => 'font/woff|application/font-woff|application/x-font-woff|application/octet-stream',
		    'woff2' => 'font/woff2|application/octet-stream|font/x-woff2',
		    'ttf' => 'application/x-font-ttf|application/octet-stream|font/ttf',
		    'svg' => 'image/svg+xml|application/octet-stream|image/x-svg+xml',
		    'eot' => 'application/vnd.ms-fontobject|application/octet-stream|application/x-vnd.ms-fontobject',
	    ];
    }
}

/**
 * Allowed mime types and file extensions
 * @since 2.0.18.1
 */
if(!function_exists('lasf_fonts_to_allowed_mimes')){
	function lasf_fonts_to_allowed_mimes( $mine_types ) {
		if ( current_user_can( 'manage_options' )  ) {
			foreach ( lasf_registered_file_types() as $type => $mine ) {
				if ( ! isset( $mine_types[ $type ] ) ) {
					$mine_types[ $type ] = $mine;
				}
			}
		}
		return $mine_types;
	}
}
add_filter( 'upload_mimes', 'lasf_fonts_to_allowed_mimes' );

/**
 * Correct the mome types and extension for the font types.
 * @since 2.0.18.1
 */
if(!function_exists('lasf_update_mime_types')){
	function lasf_update_mime_types( $data, $file, $filename, $mimes ) {
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}

		$registered_file_types = lasf_registered_file_types();
		$filetype = wp_check_filetype( $filename, $mimes );

		if ( ! isset( $registered_file_types[ $filetype['ext'] ] ) ) {
			return $data;
		}
		// Fix incorrect file mime type
		$filetype['type'] = explode( '|', $filetype['type'] )[0];

		return [
			'ext' => $filetype['ext'],
			'type' => $filetype['type'],
			'proper_filename' => $data['proper_filename'],
		];
	}
}
add_filter( 'wp_check_filetype_and_ext', 'lasf_update_mime_types', 10, 4 );

/**
 * Add group `custom` into the Elementor font field
 * @since 2.0.18.1
 */
add_filter('elementor/fonts/groups', function ( $groups ){
    if(!isset($groups['custom'])){
	    $groups = array_merge(['custom' => __( 'Custom Fonts', 'lastudio' )], $groups);
    }
    return $groups;
});

add_filter('lasf_setting_fragments_element_reload', function ( $fragments, $unique, $instance ){
	$customwebfonts = apply_filters('lasf_field_typography_customwebfonts', array());
    $fragments['customwebfonts'] = $customwebfonts;
    return $fragments;
}, 10, 3);
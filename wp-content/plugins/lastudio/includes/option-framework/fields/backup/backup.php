<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'LASF_Field_backup' ) ) {
  class LASF_Field_backup extends LASF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'lasf_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'lasf-export', 'export' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo $this->field_before();

      echo '<textarea name="lasf_transient[lasf_import_data]" class="lasf-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary lasf-confirm lasf-import" data-unique="'. $unique .'" data-nonce="'. $nonce .'">'. esc_html__( 'Import', 'lastudio' ) .'</button>';
      echo '<small>( '. esc_html__( 'copy-paste your backup string here', 'lastudio' ).' )</small>';

      echo '<hr />';
      echo '<textarea readonly="readonly" class="lasf-export-data">'. json_encode( get_option( $unique ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary lasf-export" target="_blank">'. esc_html__( 'Export and Download Backup', 'lastudio' ) .'</a>';

      echo '<hr />';
      echo '<button type="submit" name="lasf_transient[lasf_reset_all]" value="lasf_reset_all" class="button button-primary lasf-warning-primary lasf-confirm lasf-reset" data-unique="'. $unique .'" data-nonce="'. $nonce .'">'. esc_html__( 'Reset All', 'lastudio' ) .'</button>';
      echo '<small class="lasf-text-error">'. esc_html__( 'Please be sure for reset all of options.', 'lastudio' ) .'</small>';

      echo $this->field_after();

    }

  }
}

<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'LASF_Field_icon' ) ) {
  class LASF_Field_icon extends LASF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'lastudio' ),
        'remove_title' => esc_html__( 'Remove Icon', 'lastudio' ),
      ) );

      echo $this->field_before();

      $in_header_builder = isset($this->field['in_header_builder']) ? true : false;

      $nonce  = wp_create_nonce( 'lasf_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="lasf-icon-select">';
      echo '<span class="lasf-icon-preview'. $hidden .'"><i class="'. $this->value .'"></i></span>';
      echo '<a href="#" class="button button-primary lasf-icon-add" data-nonce="'. $nonce .'">'. $args['button_title'] .'</a>';
      echo '<a href="#" class="button lasf-warning-primary lasf-icon-remove'. $hidden .'">'. $args['remove_title'] .'</a>';
      if($in_header_builder){
          echo '<input type="text" name="'. $this->field_name() .'" value="'. $this->value .'" '. $this->field_attributes() .' />';
      }
      else{
          echo '<input type="text" name="'. $this->field_name() .'" value="'. $this->value .'" class="lasf-icon-value"'. $this->field_attributes() .' />';
      }
      echo '</div>';

      echo $this->field_after();

    }

  }
}

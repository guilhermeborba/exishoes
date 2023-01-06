<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'LASF_Field_repeater' ) ) {
  class LASF_Field_repeater extends LASF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'          => 0,
        'min'          => 0,
        'button_title' => '<i class="dashicons dashicons-plus-alt"></i>',
      ) );

      $fields    = $this->field['fields'];
      $unique_id = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];

      if( $this->parent && preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->parent ) ) {

        echo '<div class="lasf-notice lasf-notice-danger">'. esc_html__( 'Error: Nested field id can not be same with another nested field id.', 'lastudio' ) .'</div>';

      } else {

        echo $this->field_before();

        echo '<div class="lasf-repeater-item lasf-repeater-hidden">';
        echo '<div class="lasf-repeater-content">';
        foreach ( $fields as $field ) {

          $field_parent  = $this->parent .'['. $this->field['id'] .']';
          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

          LASF::field( $field, $field_default, '_nonce', 'field/repeater', $field_parent );

        }
        echo '</div>';
        echo '<div class="lasf-repeater-helper">';
        echo '<div class="lasf-repeater-helper-inner">';
        echo '<i class="lasf-repeater-sort dashicons dashicons-move"></i>';
        echo '<i class="lasf-repeater-clone dashicons dashicons-admin-page"></i>';
        echo '<i class="lasf-repeater-remove lasf-confirm dashicons dashicons-no-alt" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'lastudio' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="lasf-repeater-wrapper lasf-data-wrapper" data-unique-id="'. $this->unique .'" data-field-id="['. $this->field['id'] .']" data-max="'. $args['max'] .'" data-min="'. $args['min'] .'">';

        if( ! empty( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            echo '<div class="lasf-repeater-item">';

            echo '<div class="lasf-repeater-content">';
            foreach ( $fields as $field ) {

              $field_parent = $this->parent .'['. $this->field['id'] .']';
              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              LASF::field( $field, $field_value, $field_unique, 'field/repeater', $field_parent );

            }
            echo '</div>';

            echo '<div class="lasf-repeater-helper">';
            echo '<div class="lasf-repeater-helper-inner">';
            echo '<i class="lasf-repeater-sort dashicons dashicons-move"></i>';
            echo '<i class="lasf-repeater-clone dashicons dashicons-admin-page"></i>';
            echo '<i class="lasf-repeater-remove lasf-confirm dashicons dashicons-no-alt" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'lastudio' ) .'"></i>';
            echo '</div>';
            echo '</div>';

            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="lasf-repeater-alert lasf-repeater-max">'. esc_html__( 'You can not add more than', 'lastudio' ) .' '. $args['max'] .'</div>';
        echo '<div class="lasf-repeater-alert lasf-repeater-min">'. esc_html__( 'You can not remove less than', 'lastudio' ) .' '. $args['min'] .'</div>';

        echo '<a href="#" class="button button-primary lasf-repeater-add">'. $args['button_title'] .'</a>';

        echo $this->field_after();

      }

    }

    public function enqueue() {

      if( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}

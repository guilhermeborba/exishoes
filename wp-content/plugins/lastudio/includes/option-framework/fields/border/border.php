<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: border
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'LASF_Field_border' ) ) {
  class LASF_Field_border extends LASF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'top_icon'           => '<i class="dashicons dashicons-arrow-up-alt"></i>',
        'left_icon'          => '<i class="dashicons dashicons-arrow-left-alt"></i>',
        'bottom_icon'        => '<i class="dashicons dashicons-arrow-down-alt"></i>',
        'right_icon'         => '<i class="dashicons dashicons-arrow-right-alt"></i>',
        'all_icon'           => '<i class="dashicons dashicons-editor-expand"></i>',
        'top_placeholder'    => esc_html__( 'top', 'lastudio' ),
        'right_placeholder'  => esc_html__( 'right', 'lastudio' ),
        'bottom_placeholder' => esc_html__( 'bottom', 'lastudio' ),
        'left_placeholder'   => esc_html__( 'left', 'lastudio' ),
        'all_placeholder'    => esc_html__( 'all', 'lastudio' ),
        'top'                => true,
        'left'               => true,
        'bottom'             => true,
        'right'              => true,
        'all'                => false,
        'color'              => true,
        'style'              => true,
        'unit'               => 'px',
      ) );

      $default_value = array(
        'top'        => '',
        'right'      => '',
        'bottom'     => '',
        'left'       => '',
        'color'      => '',
        'style'      => 'solid',
        'all'        => '',
      );

      $border_props = array(
        'solid'     => esc_html__( 'Solid', 'lastudio' ),
        'dashed'    => esc_html__( 'Dashed', 'lastudio' ),
        'dotted'    => esc_html__( 'Dotted', 'lastudio' ),
        'double'    => esc_html__( 'Double', 'lastudio' ),
        'inset'     => esc_html__( 'Inset', 'lastudio' ),
        'outset'    => esc_html__( 'Outset', 'lastudio' ),
        'groove'    => esc_html__( 'Groove', 'lastudio' ),
        'ridge'     => esc_html__( 'ridge', 'lastudio' ),
        'none'      => esc_html__( 'None', 'lastudio' )
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $value = wp_parse_args( $this->value, $default_value );

      echo $this->field_before();

      if( ! empty( $args['all'] ) ) {

        $placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="'. $args['all_placeholder'] .'"' : '';

        echo '<div class="lasf--left lasf--input">';
        echo ( ! empty( $args['all_icon'] ) ) ? '<span class="lasf--label lasf--label-icon">'. $args['all_icon'] .'</span>' : '';
        echo '<input type="text" name="'. $this->field_name('[all]') .'" value="'. $value['all'] .'"'. $placeholder .' class="lasf-number" />';
        echo ( ! empty( $args['unit'] ) ) ? '<span class="lasf--label lasf--label-unit">'. $args['unit'] .'</span>' : '';
        echo '</div>';

      } else {

        $properties = array();

        foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
          if( ! empty( $args[$prop] ) ) {
            $properties[] = $prop;
          }
        }

        $properties = ( $properties === array( 'right', 'left' ) ) ? array_reverse( $properties ) : $properties;

        foreach( $properties as $property ) {

          $placeholder = ( ! empty( $args[$property.'_placeholder'] ) ) ? ' placeholder="'. $args[$property.'_placeholder'] .'"' : '';

          echo '<div class="lasf--left lasf--input">';
          echo ( ! empty( $args[$property.'_icon'] ) ) ? '<span class="lasf--label lasf--label-icon">'. $args[$property.'_icon'] .'</span>' : '';
          echo '<input type="text" name="'. $this->field_name('['. $property .']') .'" value="'. $value[$property] .'"'. $placeholder .' class="lasf-number" />';
          echo ( ! empty( $args['unit'] ) ) ? '<span class="lasf--label lasf--label-unit">'. $args['unit'] .'</span>' : '';
          echo '</div>';

        }

      }

      if( ! empty( $args['style'] ) ) {
        echo '<div class="lasf--left lasf--input">';
        echo '<select name="'. $this->field_name('[style]') .'">';
        foreach( $border_props as $border_prop_key => $border_prop_value ) {
          $selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
          echo '<option value="'. $border_prop_key .'"'. $selected .'>'. $border_prop_value .'</option>';
        }
        echo '</select>';
        echo '</div>';
      }

      if( ! empty( $args['color'] ) ) {
        $default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="'. $default_value['color'] .'"' : '';
        echo '<div class="lasf--left lasf-field-color">';
        echo '<input type="text" name="'. $this->field_name('[color]') .'" value="'. $value['color'] .'" class="lasf-color"'. $default_color_attr .' />';
        echo '</div>';
      }

      echo '<div class="clear"></div>';

      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // properties
      $top     = ( isset( $this->value['top'] )    && $this->value['top']    !== '' ) ? $this->value['top']    : '';
      $right   = ( isset( $this->value['right'] )  && $this->value['right']  !== '' ) ? $this->value['right']  : '';
      $bottom  = ( isset( $this->value['bottom'] ) && $this->value['bottom'] !== '' ) ? $this->value['bottom'] : '';
      $left    = ( isset( $this->value['left'] )   && $this->value['left']   !== '' ) ? $this->value['left']   : '';
      $style   = ( isset( $this->value['style'] )  && $this->value['style']  !== '' ) ? $this->value['style']  : '';
      $color   = ( isset( $this->value['color'] )  && $this->value['color']  !== '' ) ? $this->value['color']  : '';
      $all     = ( isset( $this->value['all'] )    && $this->value['all']    !== '' ) ? $this->value['all']    : '';

      if( ! empty( $this->field['all'] ) && ( $all !== '' || $color !== '' ) ) {

        $output  = $element .'{';
        $output .= ( $all   !== '' ) ? 'border-width:'. $all . $unit . $important .';' : '';
        $output .= ( $color !== '' ) ? 'border-color:'. $color . $important .';'       : '';
        $output .= ( $style !== '' ) ? 'border-style:'. $style . $important .';'       : '';
        $output .= '}';

      } else if( $top !== '' || $right !== '' || $bottom !== '' || $left !== '' || $color !== '' ) {

        $output  = $element .'{';
        $output .= ( $top    !== '' ) ? 'border-top-width:'. $top . $unit . $important .';'       : '';
        $output .= ( $right  !== '' ) ? 'border-right-width:'. $right . $unit . $important .';'   : '';
        $output .= ( $bottom !== '' ) ? 'border-bottom-width:'. $bottom . $unit . $important .';' : '';
        $output .= ( $left   !== '' ) ? 'border-left-width:'. $left . $unit . $important .';'     : '';
        $output .= ( $color  !== '' ) ? 'border-color:'. $color . $important .';'                 : '';
        $output .= ( $style  !== '' ) ? 'border-style:'. $style . $important .';'                 : '';
        $output .= '}';

      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}

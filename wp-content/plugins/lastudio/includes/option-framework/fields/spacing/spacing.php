<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: spacing
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'LASF_Field_spacing' ) ) {
  class LASF_Field_spacing extends LASF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'top_icon'           => '<i class="dashicons dashicons-arrow-up-alt"></i>',
        'right_icon'         => '<i class="dashicons dashicons-arrow-right-alt"></i>',
        'bottom_icon'        => '<i class="dashicons dashicons-arrow-down-alt"></i>',
        'left_icon'          => '<i class="dashicons dashicons-arrow-left-alt"></i>',
        'all_text'           => '<i class="dashicons dashicons-move"></i>',
        'top_placeholder'    => esc_html__( 'top', 'lastudio' ),
        'right_placeholder'  => esc_html__( 'right', 'lastudio' ),
        'bottom_placeholder' => esc_html__( 'bottom', 'lastudio' ),
        'left_placeholder'   => esc_html__( 'left', 'lastudio' ),
        'all_placeholder'    => esc_html__( 'all', 'lastudio' ),
        'top'                => true,
        'left'               => true,
        'bottom'             => true,
        'right'              => true,
        'unit'               => true,
        'all'                => false,
        'units'              => array( 'px', '%', 'em' )
      ) );


      $default_values = array(
        'top'    => '',
        'right'  => '',
        'bottom' => '',
        'left'   => '',
        'all'    => '',
        'unit'   => 'px',
      );

      $value = wp_parse_args( $this->value, $default_values );

      echo $this->field_before();

      if( ! empty( $args['all'] ) ) {

        $placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="'. $args['all_placeholder'] .'"' : '';

        echo '<div class="lasf--input">';
        echo ( ! empty( $args['all_text'] ) ) ? '<span class="lasf--label lasf--label-icon">'. $args['all_text'] .'</span>' : '';
        echo '<input type="text" name="'. $this->field_name('[all]') .'" value="'. $value['all'] .'"'. $placeholder .' class="lasf-number" />';
        echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="lasf--label lasf--label-unit">'. $args['units'][0] .'</span>' : '';
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

          echo '<div class="lasf--input">';
          echo ( ! empty( $args[$property.'_icon'] ) ) ? '<span class="lasf--label lasf--label-icon">'. $args[$property.'_icon'] .'</span>' : '';
          echo '<input type="text" name="'. $this->field_name('['. $property .']') .'" value="'. $value[$property] .'"'. $placeholder .' class="lasf-number" />';
          echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="lasf--label lasf--label-unit">'. $args['units'][0] .'</span>' : '';
          echo '</div>';

        }

      }

      if( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
        echo '<select name="'. $this->field_name('[unit]') .'">';
        foreach( $args['units'] as $unit ) {
          $selected = ( $value['unit'] === $unit ) ? ' selected' : '';
          echo '<option value="'. $unit .'"'. $selected .'>'. $unit .'</option>';
        }
        echo '</select>';
      }

      echo '<div class="clear"></div>';

      echo $this->field_after();

    }

    public function render_css_output(){
        $output = '';

        if( !empty($this->field['selectors']) ) {

            $unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
            $all       = isset($this->value['all']) && $this->value['all'] !== '' ? $this->value['all'] : '';
            $top       = isset($this->value['top']) && $this->value['top'] !== '' ? $this->value['top'] : '';
            $bottom    = isset($this->value['bottom']) && $this->value['bottom'] !== '' ? $this->value['bottom'] : '';
            $left      = isset($this->value['left']) && $this->value['left'] !== '' ? $this->value['left'] : '';
            $right     = isset($this->value['right']) && $this->value['right'] !== '' ? $this->value['right'] : '';

            if(!is_array($this->field['selectors'])){
                $selectors = array($this->field['selectors']);
            }
            else{
                $selectors = $this->field['selectors'];
            }
            foreach ($selectors as $selector){
                if(!empty($selector)){
                    $output .= str_replace(array('{{VALUE}}','{{TOP}}', '{{BOTTOM}}', '{{LEFT}}', '{{RIGHT}}', '{{UNIT}}'), array($all, $top, $bottom, $left, $right, $unit), $selector);
                }
            }
        }

        $this->parent->output_css .= $output;

    }

    public function output() {

      $output    = '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';

      $mode = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'padding';
      $mode = ( $mode === 'relative' || $mode === 'absolute' || $mode === 'none' ) ? '' : $mode;
      $mode = ( ! empty( $mode ) ) ? $mode .'-' : '';

      if( ! empty( $this->field['all'] ) && isset( $this->value['all'] ) && $this->value['all'] !== '' ) {

        $output  = $element .'{';
        $output .= $mode .'top:'.    $this->value['all'] . $unit . $important .';';
        $output .= $mode .'right:'.  $this->value['all'] . $unit . $important .';';
        $output .= $mode .'bottom:'. $this->value['all'] . $unit . $important .';';
        $output .= $mode .'left:'.   $this->value['all'] . $unit . $important .';';
        $output .= '}';

      } else {

        $top     = ( isset( $this->value['top']    ) && $this->value['top']    !== '' ) ?  $mode .'top:'.    $this->value['top']    . $unit . $important .';' : '';
        $right   = ( isset( $this->value['right']  ) && $this->value['right']  !== '' ) ?  $mode .'right:'.  $this->value['right']  . $unit . $important .';' : '';
        $bottom  = ( isset( $this->value['bottom'] ) && $this->value['bottom'] !== '' ) ?  $mode .'bottom:'. $this->value['bottom'] . $unit . $important .';' : '';
        $left    = ( isset( $this->value['left']   ) && $this->value['left']   !== '' ) ?  $mode .'left:'.   $this->value['left']   . $unit . $important .';' : '';

        if( $top !== '' || $right !== '' || $bottom !== '' || $left !== '' ) {
          $output = $element .'{'. $top . $right . $bottom . $left .'}';
        }

      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}

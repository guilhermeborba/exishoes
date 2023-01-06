<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'LASF_Field_background' ) ) {
  class LASF_Field_background extends LASF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                             = wp_parse_args( $this->field, array(
        'background_color'              => true,
        'background_image'              => true,
        'background_position'           => true,
        'background_repeat'             => true,
        'background_attachment'         => true,
        'background_size'               => true,
        'background_origin'             => false,
        'background_clip'               => false,
        'background_blend-mode'         => false,
        'background_gradient'           => false,
        'background_gradient_color'     => true,
        'background_gradient_direction' => true,
        'background_image_preview'      => true,
        'background_image_library'      => 'image',
        'background_image_placeholder'  => esc_html__( 'No background selected', 'lastudio' ),
      ) );

      $default_value                    = array(
        'background-color'              => '',
        'background-image'              => '',
        'background-position'           => '',
        'background-repeat'             => '',
        'background-attachment'         => '',
        'background-size'               => '',
        'background-origin'             => '',
        'background-clip'               => '',
        'background-blend-mode'         => '',
        'background-gradient-color'     => '',
        'background-gradient-direction' => '',
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo $this->field_before();

      //
      // Background Color
      if( ! empty( $args['background_color'] ) ) {

        echo '<div class="lasf--block lasf--color">';
        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="lasf--title">'. esc_html__( 'From', 'lastudio' ) .'</div>' : '';

        LASF::field( array(
          'id'      => 'background-color',
          'type'    => 'color',
          'default' => $default_value['background-color'],
        ), $this->value['background-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Color
      if( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="lasf--block lasf--color">';
        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="lasf--title">'. esc_html__( 'To', 'lastudio' ) .'</div>' : '';

        LASF::field( array(
          'id'      => 'background-gradient-color',
          'type'    => 'color',
          'default' => $default_value['background-gradient-color'],
        ), $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Direction
      if( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="lasf--block lasf--gradient">';
        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="lasf--title">'. esc_html__( 'Direction', 'lastudio' ) .'</div>' : '';

        LASF::field( array(
          'id'          => 'background-gradient-direction',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Gradient Direction', 'lastudio' ),
            'to bottom' => esc_html__( '&#8659; top to bottom', 'lastudio' ),
            'to right'  => esc_html__( '&#8658; left to right', 'lastudio' ),
            '135deg'    => esc_html__( '&#8664; corner top to right', 'lastudio' ),
            '-135deg'   => esc_html__( '&#8665; corner top to left', 'lastudio' ),
          ),
        ), $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '<div class="clear"></div>';

      //
      // Background Image
      if( ! empty( $args['background_image'] ) ) {

        echo '<div class="lasf--block lasf--media">';

        LASF::field( array(
          'id'          => 'background-image',
          'type'        => 'media',
          'library'     => $args['background_image_library'],
          'preview'     => $args['background_image_preview'],
          'placeholder' => $args['background_image_placeholder']
        ), $this->value['background-image'], $this->field_name(), 'field/background' );

        echo '</div>';

        echo '<div class="clear"></div>';

      }

      //
      // Background Position
      if( ! empty( $args['background_position'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'              => 'background-position',
          'type'            => 'select',
          'options'         => array(
            ''              => esc_html__( 'Background Position', 'lastudio' ),
            'left top'      => esc_html__( 'Left Top', 'lastudio' ),
            'left center'   => esc_html__( 'Left Center', 'lastudio' ),
            'left bottom'   => esc_html__( 'Left Bottom', 'lastudio' ),
            'center top'    => esc_html__( 'Center Top', 'lastudio' ),
            'center center' => esc_html__( 'Center Center', 'lastudio' ),
            'center bottom' => esc_html__( 'Center Bottom', 'lastudio' ),
            'right top'     => esc_html__( 'Right Top', 'lastudio' ),
            'right center'  => esc_html__( 'Right Center', 'lastudio' ),
            'right bottom'  => esc_html__( 'Right Bottom', 'lastudio' ),
          ),
        ), $this->value['background-position'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Repeat
      if( ! empty( $args['background_repeat'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'          => 'background-repeat',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Background Repeat', 'lastudio' ),
            'repeat'    => esc_html__( 'Repeat', 'lastudio' ),
            'no-repeat' => esc_html__( 'No Repeat', 'lastudio' ),
            'repeat-x'  => esc_html__( 'Repeat Horizontally', 'lastudio' ),
            'repeat-y'  => esc_html__( 'Repeat Vertically', 'lastudio' ),
          ),
        ), $this->value['background-repeat'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Attachment
      if( ! empty( $args['background_attachment'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'       => 'background-attachment',
          'type'     => 'select',
          'options'  => array(
            ''       => esc_html__( 'Background Attachment', 'lastudio' ),
            'scroll' => esc_html__( 'Scroll', 'lastudio' ),
            'fixed'  => esc_html__( 'Fixed', 'lastudio' ),
          ),
        ), $this->value['background-attachment'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Size
      if( ! empty( $args['background_size'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'        => 'background-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Background Size', 'lastudio' ),
            'cover'   => esc_html__( 'Cover', 'lastudio' ),
            'contain' => esc_html__( 'Contain', 'lastudio' ),
          ),
        ), $this->value['background-size'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Origin
      if( ! empty( $args['background_origin'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'            => 'background-origin',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Origin', 'lastudio' ),
            'padding-box' => esc_html__( 'Padding Box', 'lastudio' ),
            'border-box'  => esc_html__( 'Border Box', 'lastudio' ),
            'content-box' => esc_html__( 'Content Box', 'lastudio' ),
          ),
        ), $this->value['background-origin'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Clip
      if( ! empty( $args['background_clip'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'            => 'background-clip',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Clip', 'lastudio' ),
            'border-box'  => esc_html__( 'Border Box', 'lastudio' ),
            'padding-box' => esc_html__( 'Padding Box', 'lastudio' ),
            'content-box' => esc_html__( 'Content Box', 'lastudio' ),
          ),
        ), $this->value['background-clip'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Blend Mode
      if( ! empty( $args['background_blend_mode'] ) ) {
        echo '<div class="lasf--block lasf--select">';

        LASF::field( array(
          'id'            => 'background-blend-mode',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Blend Mode', 'lastudio' ),
            'normal'      => esc_html__( 'Normal', 'lastudio' ),
            'multiply'    => esc_html__( 'Multiply', 'lastudio' ),
            'screen'      => esc_html__( 'Screen', 'lastudio' ),
            'overlay'     => esc_html__( 'Overlay', 'lastudio' ),
            'darken'      => esc_html__( 'Darken', 'lastudio' ),
            'lighten'     => esc_html__( 'Lighten', 'lastudio' ),
            'color-dodge' => esc_html__( 'Color Dodge', 'lastudio' ),
            'saturation'  => esc_html__( 'Saturation', 'lastudio' ),
            'color'       => esc_html__( 'Color', 'lastudio' ),
            'luminosity'  => esc_html__( 'Luminosity', 'lastudio' ),
          ),
        ), $this->value['background-blend-mode'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '<div class="clear"></div>';

      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Background image and gradient
      $background_color        = ( ! empty( $this->value['background-color']              ) ) ? $this->value['background-color']              : '';
      $background_gd_color     = ( ! empty( $this->value['background-gradient-color']     ) ) ? $this->value['background-gradient-color']     : '';
      $background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
      $background_image        = ( ! empty( $this->value['background-image']['url']       ) ) ? $this->value['background-image']['url']       : '';


      if( $background_color && $background_gd_color ) {
        $gd_direction   = ( $background_gd_direction ) ? $background_gd_direction .',' : '';
        $bg_image[] = 'linear-gradient('. $gd_direction . $background_color .','. $background_gd_color .')';
      }

      if( $background_image ) {
        $bg_image[] = 'url('. $background_image .')';
      }

      if( ! empty( $bg_image ) ) {
        $output .= 'background-image:'. implode( ',', $bg_image ) . $important .';';
      }

      // Common background properties
      $properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

      foreach( $properties as $property ) {
        $property = 'background-'. $property;
        if( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}

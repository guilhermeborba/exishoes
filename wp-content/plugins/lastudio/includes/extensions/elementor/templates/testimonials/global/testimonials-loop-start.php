<?php
/**
 * Testimonials start template
 */

$data_settings = '';

$use_comment_corner = $this->get_settings( 'use_comment_corner' );

$class_array = [];

if( $this->get_settings('enable_carousel') == 'true' ) {
    $class_array[] = 'lastudio-carousel la-slick-slider js-el lastudio-testimonials__instance';
    $data_settings = 'data-slider_config="'.htmlspecialchars( json_encode( $this->get_advanced_carousel_options() ) ).'"';
    $data_settings .= ' data-la_component=AutoCarousel';
}

$class_array[] = 'grid-items';

$class_array[] = lastudio_element_render_grid_classes([
    'desktop'   => $this->get_settings( 'slides_to_show' ),
    'laptop'    => $this->get_settings( 'slides_to_show_laptop' ),
    'tablet'    => $this->get_settings( 'slides_to_show_tablet' ),
    'mobile'    => $this->get_settings( 'slides_to_show_mobile_extra' ),
    'xmobile'   => $this->get_settings( 'slides_to_show_mobile' )
]);

if ( filter_var( $use_comment_corner, FILTER_VALIDATE_BOOLEAN ) ) {
	$class_array[] = 'lastudio-testimonials--comment-corner';
}

$classes = implode( ' ', $class_array );

$dir = is_rtl() ? 'rtl' : 'ltr';

?>
<div class="<?php echo $classes; ?>" <?php echo $data_settings; ?> dir="<?php echo $dir; ?>">
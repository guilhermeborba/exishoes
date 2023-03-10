<?php
/**
 * Play button template
 */

$this->add_render_attribute( 'play_button', 'class', 'lastudio-video__play-button' );
$this->add_render_attribute( 'play_button', 'role', 'button' );

if ( ! empty( $settings['play_button_hover_animation'] ) ) {
	$this->add_render_attribute( 'play_button', 'class', 'lastudio-video__play-button--animation-' . esc_attr( $settings['play_button_hover_animation'] ) );
}
?>

<div <?php $this->print_render_attribute_string( 'play_button' ); ?>><?php
	if ( 'icon' === $settings['play_button_type'] ) {
		printf( '<i class="lastudio-video__play-button-icon %s" aria-hidden="true"></i>', esc_attr( $settings['play_button_icon'] ) );
	}
	elseif ( 'image' === $settings['play_button_type'] ) {
		echo la_get_image_by_url(
			$settings['play_button_image']['url'],
			array(
				'class' => 'lastudio-video__play-button-image',
				'alt'   => esc_html__( 'Play Video', 'lastudio' ),
			)
		);
	} ?>
	<span class="elementor-screen-only"><?php esc_html_e( 'Play Video', 'lastudio-elements' ); ?></span>
</div>
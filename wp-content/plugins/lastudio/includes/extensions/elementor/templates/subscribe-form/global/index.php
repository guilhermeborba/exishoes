<?php
/**
 * Subscribe Form main template
 */

use Elementor\Icons_Manager;

$submit_button_text = $this->get_settings( 'submit_button_text' );
$submit_placeholder = $this->get_settings( 'submit_placeholder' );
$layout             = $this->get_settings( 'layout' );
$button_icon        = $this->get_settings( 'button_icon' );
$use_icon           = $this->get_settings( 'add_button_icon' );

$this->add_render_attribute( 'main-container', 'class', array(
	'lastudio-subscribe-form',
	'lastudio-subscribe-form--' . $layout . '-layout',
) );

$this->add_render_attribute( 'main-container', 'data-settings', $this->generate_setting_json() );

$instance_data = apply_filters( 'LaStudioElement/subscribe-form/input-instance-data', array(), $this );

$instance_data = json_encode( $instance_data );

$this->add_render_attribute( 'form-input',
	array(
		'class'       => array(
			'lastudio-subscribe-form__input lastudio-subscribe-form__mail-field',
		),
		'type'               => 'email',
		'name'               => 'email',
		'placeholder'        => $submit_placeholder,
		'data-instance-data' => htmlspecialchars( $instance_data ),
	)
);

?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<form method="POST" action="#" class="lastudio-subscribe-form__form">
		<div class="lastudio-subscribe-form__input-group">
			<div class="lastudio-subscribe-form__fields">
				<input <?php echo $this->get_render_attribute_string( 'form-input' ); ?>/><?php $this->generate_additional_fields();
            ?></div>
            <a class="lastudio-subscribe-form__submit elementor-button elementor-size-md" href="#"><?php if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) { echo '<span class="elementor-icon">'; Icons_Manager::render_icon( $button_icon, [ 'aria-hidden' => 'true' , 'class' => 'lastudio-subscribe-form__submit-icon' ] ); echo '</span>'; } ?><span class="lastudio-subscribe-form__submit-text"><?php echo $submit_button_text ?></span></a>
		</div>
		<div class="lastudio-subscribe-form__message"><div class="lastudio-subscribe-form__message-inner"><span></span></div></div>
	</form>
</div>

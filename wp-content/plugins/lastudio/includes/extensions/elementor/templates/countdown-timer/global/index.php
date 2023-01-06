<?php $items_size = $this->get_settings('items_size'); ?>
<div class="elementor-lastudio-countdown-timer lastudio-elements js-el" data-la_component="CountDownTimer">
	<div class="lastudio-countdown-timer timer-<?php echo $items_size; ?>" data-due-date="<?php echo $this->due_date(); ?>">
		<?php $this->__glob_inc_if( '00-days', array( 'show_days' ) ); ?>
		<?php $this->__glob_inc_if( '01-hours', array( 'show_hours' ) ); ?>
		<?php $this->__glob_inc_if( '02-minutes', array( 'show_min' ) ); ?>
		<?php $this->__glob_inc_if( '03-seconds', array( 'show_sec' ) ); ?>
	</div>
</div>

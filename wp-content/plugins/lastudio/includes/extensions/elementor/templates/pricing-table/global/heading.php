<?php
/**
 * Pricing table heading template
 */
?>
<div class="pricing-table__heading">
    <?php echo $this->__generate_icon(); ?>
	<?php $this->__html( 'title', '<h3 class="pricing-table__title">%s</h3>' ); ?>
	<?php $this->__html( 'subtitle', '<div class="pricing-table__subtitle">%s</div>' ); ?>
</div>
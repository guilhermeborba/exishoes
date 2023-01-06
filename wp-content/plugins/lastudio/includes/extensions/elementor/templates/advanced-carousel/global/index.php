<?php
/**
 * Advanced carousel template
 */
$layout     = $this->get_settings_for_display( 'item_layout' );
$equal_cols = $this->get_settings_for_display( 'equal_height_cols' );
$cols_class = ( 'true' === $equal_cols ) ? ' lastudio-equal-cols' : '';

$cols_class .= ' lastudio-advance-carousel-layout-' . $layout;

?>
<div class="lastudio-carousel-wrap<?php echo $cols_class; ?>" style="--elm-slide-item: <?php echo $this->get_settings_for_display('slides_to_show'); ?>">
	<?php $this->__get_global_looped_template( esc_attr( $layout ) . '/items', 'items_list' ); ?>
</div>
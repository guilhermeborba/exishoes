<?php
/**
 * Images Layout template
 */
$settings           = $this->get_settings_for_display();
$layout             = $settings['layout_type'];
$classes_list[]     = 'layout-type-' . $layout;
$columns            = $settings['columns'];
$enable_carousel    = !empty($settings['enable_carousel']) ? true : false;
if($layout == 'grid' && $enable_carousel){
    $classes_list[] = 'lastudio-carousel-wrap';
}
$classes            = implode( ' ', $classes_list );
?>

<div class="lastudio-images-layout <?php echo $classes; ?>" style="--elm-slide-item: <?php echo $columns; ?>">
	<?php $this->__get_global_looped_template( 'images-layout', 'image_list' ); ?>
</div>
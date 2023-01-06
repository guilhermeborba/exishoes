<?php
/**
 * Features list start template
 */

$settings = $this->get_settings_for_display();

$columns       = $settings['columns'];
$columnsLaptop = !empty($settings['columns_laptop']) ? $settings['columns_laptop'] : $columns;
$columnsTablet = !empty($settings['columns_tablet']) ? $settings['columns_tablet'] : $columnsLaptop;
$columnsTabletPortrait = !empty($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : $columnsTablet;
$columnsMobile = !empty($settings['columns_mobile']) ? $settings['columns_mobile'] : $columnsTabletPortrait;

$enable_carousel = !empty($settings['enable_carousel']) ? true : false;

$class_array[] = 'lastudio-images-layout__list';
$attr_array = [];

if ( 'grid' === $settings['layout_type'] ) {
    $class_array[] = 'grid-items';
    $class_array[] = lastudio_element_render_grid_classes([
        'desktop'   => $columns,
        'laptop'    => $columnsLaptop,
        'tablet'    => $columnsTablet,
        'mobile'    => $columnsTabletPortrait,
        'xmobile'   => $columnsMobile
    ]);
    if($enable_carousel){
        $carousel_options = $this->get_advanced_carousel_options();
        $class_array[] = 'lastudio-carousel js-el';
        $attr_array[]   = 'dir="'.(is_rtl() ? 'rtl' : 'ltr').'"';
        $attr_array[]   = 'data-la_component="AutoCarousel"';
        $attr_array[]   = 'data-slider_config="'.htmlspecialchars( json_encode( $carousel_options ) ).'"';
    }
}

if ( 'masonry' === $settings['layout_type'] ) {
    $class_array[]  = 'js-el la-isotope-container';
    $attr_array[]   = 'data-item_selector=".lastudio-images-layout__item"';
    $attr_array[]   = 'data-la-effect="sequencefade"';
    if(!empty($settings['enable_custom_masonry_layout'])){
        $attr_array[]   = 'data-la_component="AdvancedMasonry"';
        $attr_array[]   = 'data-container-width="'.$settings['container_width']['size'].'"';
        $attr_array[]   = 'data-item-width="'.$settings['masonry_item_width']['size'].'"';
        $attr_array[]   = 'data-item-height="'.$settings['masonry_item_height']['size'].'"';
        $attr_array[]   = 'data-md-col="' . $columnsTablet . '"';
        $attr_array[]   = 'data-sm-col="' . $columnsTabletPortrait . '"';
        $attr_array[]   = 'data-xs-col="' . $columnsMobile . '"';
        $attr_array[]   = 'data-mb-col="' . $columnsMobile . '"';
    }
    else{
        $attr_array[]   = 'data-la_component="DefaultMasonry"';
        $class_array[]  = 'grid-items';
        $class_array[]  = lastudio_element_render_grid_classes([
            'desktop'   => $columns,
            'laptop'    => $columnsLaptop,
            'tablet'    => $columnsTablet,
            'mobile'    => $columnsTabletPortrait,
            'xmobile'   => $columnsMobile
        ]);
    }
}

$classes = implode( ' ', $class_array );
$attrs = implode( ' ', $attr_array );

?>
<div class="<?php echo $classes; ?>" <?php echo $attrs; ?>>

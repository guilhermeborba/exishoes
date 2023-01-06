<?php
$settings = $this->get_settings_for_display();
$layout        = $settings['layout_type'];


if ( filter_var( $settings['show_on_hover'], FILTER_VALIDATE_BOOLEAN ) ) {
	$class_array[] = 'show-overlay-on-hover';
}

$columns       = $settings['columns'];
$columnsLaptop = !empty($settings['columns_laptop']) ? $settings['columns_laptop'] : $columns;
$columnsTablet = !empty($settings['columns_tablet']) ? $settings['columns_tablet'] : $columnsLaptop;
$columnsTabletPortrait = !empty($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : $columnsTablet;
$columnsMobile = !empty($settings['columns_mobile']) ? $settings['columns_mobile'] : $columnsTabletPortrait;


$this->add_render_attribute( 'main-container', 'id', 'ig_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
    'lastudio-instagram-gallery',
    'layout-type-' . $layout
) );

$this->add_render_attribute( 'list-container', 'class', array(
    'lastudio-instagram-gallery__list',
    'lastudio-instagram-gallery__instance'
) );

if ( filter_var( $settings['show_on_hover'], FILTER_VALIDATE_BOOLEAN ) ) {
    $this->add_render_attribute( 'list-container', 'class', array(
        'show-overlay-on-hover'
    ) );
}

if( isset($settings['enable_custom_image_height']) && $settings['enable_custom_image_height'] ) {
    $this->add_render_attribute( 'list-container', 'class', array(
        'active-object-fit'
    ) );
}

$this->add_render_attribute( 'list-container', 'data-item_selector', array(
    '.loop__item'
) );

if ( 'masonry' == $layout || 'grid' == $layout ) {
    $this->add_render_attribute( 'main-container', 'class', 'playout-grid' );
}

if('masonry' == $layout){
    $this->add_render_attribute( 'list-container', 'class', array('js-el', 'la-isotope-container'));
    if(!empty($settings['enable_custom_masonry_layout'])){
        $this->add_render_attribute( 'list-container', 'data-la_component', 'AdvancedMasonry');
        $this->add_render_attribute( 'list-container', 'data-container-width', $settings['container_width']['size'] );
        $this->add_render_attribute( 'list-container', 'data-item-width', $settings['masonry_item_width']['size'] );
        $this->add_render_attribute( 'list-container', 'data-item-height', $settings['masonry_item_height']['size'] );

        $this->add_render_attribute( 'list-container', 'data-md-col', $columnsTablet);
        $this->add_render_attribute( 'list-container', 'data-sm-col', $columnsTabletPortrait);
        $this->add_render_attribute( 'list-container', 'data-xs-col', $columnsMobile);
    }
    else{
        $this->add_render_attribute( 'list-container', 'data-la_component', 'DefaultMasonry');
        $this->add_render_attribute( 'list-container', 'class', array(
            'grid-items',
            'block-grid-' . $columns,
            'laptop-block-grid-' . $columnsLaptop,
            'tablet-block-grid-' . $columnsTablet,
            'mobile-block-grid-' . $columnsTabletPortrait,
            'xmobile-block-grid-' . $columnsMobile
        ));
    }
}

if('grid' == $layout){
    $this->add_render_attribute( 'list-container', 'class', array(
        'grid-items',
        'block-grid-' . $columns,
        'laptop-block-grid-' . $columnsLaptop,
        'tablet-block-grid-' . $columnsTablet,
        'mobile-block-grid-' . $columnsTabletPortrait,
        'xmobile-block-grid-' . $columnsMobile
    ));
}

if( 'grid' == $layout || 'list' == $layout ) {
    $slider_options = $this->generate_carousel_setting_json();
    if(!empty($slider_options)){
        $this->add_render_attribute( 'list-container', 'data-slider_config', $slider_options );
        $this->add_render_attribute( 'list-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
        $this->add_render_attribute( 'list-container', 'class', 'js-el la-slick-slider lastudio-carousel' );
        $this->add_render_attribute( 'list-container', 'data-la_component', 'AutoCarousel');
    }
}

?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
    <div class="lastudio-instagram-gallery__list_wrapper">
        <div <?php echo $this->get_render_attribute_string( 'list-container' ); ?>>
            <?php $this->render_gallery(); ?>
        </div>
    </div>
</div>
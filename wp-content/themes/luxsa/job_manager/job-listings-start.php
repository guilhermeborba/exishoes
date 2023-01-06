<?php
/**
 * Content shown before job listings in `[jobs]` shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-listings-start.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.15.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $lastudio_listing_loop;

$ul_classes = array('grid-items');

if(!empty($lastudio_listing_loop['columns'])){
    $ul_classes[] = 'block-grid-' . $lastudio_listing_loop['columns'];
}
if(!empty($lastudio_listing_loop['columns_laptop'])){
    $ul_classes[] = 'laptop-block-grid-' . $lastudio_listing_loop['columns_laptop'];
}
if(!empty($lastudio_listing_loop['columns_tablet'])){
    $ul_classes[] = 'tablet-block-grid-' . $lastudio_listing_loop['columns_tablet'];
}
if(!empty($lastudio_listing_loop['columns_tabletportrait'])){
    $ul_classes[] = 'mobile-block-grid-' . $lastudio_listing_loop['columns_tabletportrait'];
}

if(!empty($lastudio_listing_loop['columns_mobile'])){
    $ul_classes[] = 'xmobile-block-grid-' . $lastudio_listing_loop['columns_mobile'];
}

if(!empty($lastudio_listing_loop['layout_type'])){
    $ul_classes[] = 'listing-layout-' . $lastudio_listing_loop['layout_type'];
}

if(!empty($lastudio_listing_loop['preset'])){
    $ul_classes[] = 'listing-preset-' . $lastudio_listing_loop['preset'];
}

$slider_options = isset($lastudio_listing_loop['la_carousel_settings']) ? $lastudio_listing_loop['la_carousel_settings'] : '';

if(!empty($slider_options)){
    $ul_classes[] = 'js-el la-slick-slider lastudio-carousel';
}

?>
<ul class="job_listings <?php echo esc_attr( join(' ', $ul_classes) ) ?>"<?php
if(!empty($slider_options)){
    echo ' data-slider_config="'.esc_attr($slider_options).'"';
    echo ' data-la_component="AutoCarousel"';
}
?>>
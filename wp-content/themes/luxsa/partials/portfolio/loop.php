<?php
/**
 * Portfolio template
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$layout        = luxsa_get_option('portfolio_display_type', 'grid');
$preset        = luxsa_get_option('portfolio_display_style', 'type-1');

if($layout == 'list'){
    $preset        = luxsa_get_option('portfolio_display_list_style', 'type-list-1');
}

$portfolio_column = luxsa_get_responsive_column_classes('portfolio_column', array(
    'mobile' => 1,
    'tablet' => 1
));

$portfolio_thumbnail_height_mode = luxsa_get_option('portfolio_thumbnail_height_mode', 'original');
$enable_custom_masonry_layout = luxsa_get_option('portfolio_custom_masonry_layout', 'no');

$main_atts = [];
$main_atts['id'] = 'pf_main_archive';
$main_atts['class'] = 'lastudio-portfolio layout-type-'.$layout . ' preset-' . $preset;

$list_atts = [];
$list_atts['class'] = 'lastudio-portfolio__list';
$list_atts['data-item_selector'] = '.loop__item';

if(!empty($portfolio_thumbnail_height_mode) && $portfolio_thumbnail_height_mode != 'original'){
    $list_atts['class'] .= ' active-object-fit';
}

if ( 'masonry' == $layout || 'grid' == $layout ) {
    $main_atts['class'] .= ' playout-grid';
}

if('masonry' == $layout){
    $list_atts['class'] .= ' js-el la-isotope-container';
    if(luxsa_string_to_bool($enable_custom_masonry_layout)){
        $list_atts['data-la_component'] = 'AdvancedMasonry';
        $list_atts['data-container-width'] = luxsa_get_option('portfolio_advmansory_container_width', 1170);
        $list_atts['data-item-width'] = luxsa_get_option('portfolio_advmansory_item_width', 1170);
        $list_atts['data-item-height'] = luxsa_get_option('portfolio_advmansory_item_height', 1170);
        $list_atts['data-md-col'] = '';
        $list_atts['data-sm-col'] = '';
        $list_atts['data-xs-col'] = '';
    }
    else{
        $list_atts['data-la_component'] = 'DefaultMasonry';
        $list_atts['class'] .= ' '. $portfolio_column;
    }
}

if('grid' == $layout){
    $list_atts['class'] .= ' grid-items';
    $list_atts['class'] .= ' '. $portfolio_column;
}


$pagination_extra_attr = '';
$pagination_extra_cssclass = '';

?>

<div<?php foreach ($main_atts as $_k => $_v){ echo sprintf( ' %1$s="%2$s"', $_k, $_v ); } ?>><?php

    ?>
    <div class="lastudio-portfolio__list_wrapper">
        <div <?php foreach ($list_atts as $_k => $_v){ echo sprintf( ' %1$s="%2$s"', $_k, $_v ); } ?>>
            <?php

            while (have_posts()){

                the_post();

                get_template_part('partials/portfolio/loop-item', $preset);

            }

            if($layout == 'masonry') {
                echo '<div class="la-isotope-loading"><span></span></div>';
            }
            ?>
        </div>
    </div>


    <?php
    // Display post pagination
    luxsa_the_pagination(array(
        'pagi_data' => array(
            'class' => $pagination_extra_cssclass,
            'attr'  => $pagination_extra_attr,
        )
    ));
    ?>

</div>
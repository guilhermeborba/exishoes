<?php
/**
 * Images list item template
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


$read_more_label  = '';
$custom_excerpt_length  = luxsa_get_option('portfolio_custom_excerpt_length', 0);

$show_excerpt  = luxsa_get_option('portfolio_show_excerpt', 'no');
$show_category  = luxsa_get_option('portfolio_show_category', 'yes');
$show_readmore_btn  = luxsa_get_option('portfolio_show_readmore_btn', 'no');
$show_divider  = luxsa_get_option('portfolio_show_divider', 'no');
$image_size  = luxsa_get_option('portfolio_thumbnail_size', 'full');

if(empty($read_more_label)){
    $read_more_label = esc_html__('Read More', 'luxsa');
}

$layout_type        = luxsa_get_option('portfolio_display_type', 'grid');
$preset        = luxsa_get_option('portfolio_display_style', 'type-1');

if($layout_type == 'list'){
    $preset        = luxsa_get_option('portfolio_display_list_style', 'type-list-1');
}

$item_atts = [];
$item_atts['class'] = join(' ', get_post_class( array(
    'loop__item',
    'grid-item',
    'lastudio-portfolio__item',
    'visible-status'
), get_the_ID() ));

if ( 'masonry' == $layout_type ) {
    $item_sizes = [
        'item_width' => 1,
        'item_height' => 1
    ];
    $item_atts['data-width'] = $item_sizes['item_width'];
    $item_atts['data-height'] = $item_sizes['item_height'];
}

$term_obj = get_the_terms(get_the_ID(), 'la_portfolio_category');
$slug_list = array('all');
$cat_list = array();
if(!is_wp_error($term_obj) && !empty($term_obj)){
    foreach ($term_obj as $term){
        $slug_list[] = $term->slug;
    }
    $cat_list = wp_list_pluck($term_obj, 'name');
}

$item_image_url_full = get_the_post_thumbnail_url(get_the_ID(), 'full');
$item_button_url = get_the_permalink();
$collection_id = 'myCollection:main_pf';
$link_atts = [];
$link_atts['class'] = 'lastudio-images-layout__link';
$link_atts['href'] = $item_button_url;

?>
<article<?php foreach ($item_atts as $_k => $_v){ echo sprintf( ' %1$s="%2$s"', $_k, $_v ); } ?>>
    <div class="lastudio-portfolio__inner">
        <div class="lastudio-portfolio__image_wrap">
            <a<?php foreach ($link_atts as $_k => $_v){ echo sprintf( ' %1$s="%2$s"', $_k, $_v ); } ?>>
                <figure class="figure__object_fit lastudio-portfolio__image">
                    <?php the_post_thumbnail($image_size, array('class' => 'lastudio-portfolio__image-instance'));?>
                </figure>
            </a>
            <div class="lastudio-portfolio__icons">
                <a data-elementor-lightbox-title="<?php echo esc_attr(get_the_title()); ?>" data-elementor-lightbox-slideshow="<?php echo esc_attr($collection_id); ?>" href="<?php echo esc_url($item_image_url_full); ?>" class="la-popup lastudio-portfolio__icon_gallery"><i class="lastudioicon-search-zoom-in"></i></a>
                <a href="<?php echo esc_url($item_button_url); ?>" class="lastudio-portfolio__icon_link"><i class="lastudioicon-web-link"></i></a>
            </div>
        </div>
        <div class="lastudio-portfolio__content">
            <div class="lastudio-portfolio__content-inner">
                <div class="lastudio-portfolio__content-inner2"><?php

                    if( luxsa_string_to_bool($show_category) && !empty($cat_list)){
                        echo sprintf('<p class="lastudio-portfolio__category">%s</p>', join(', ', $cat_list));
                    }

                    $title_tag = 'h3';
                    echo sprintf(
                        '<%1$s class="lastudio-portfolio__title"><a href="%2$s">%3$s</a></%1$s>',
                        esc_attr($title_tag),
                        esc_url($item_button_url),
                        esc_html(get_the_title())
                    );


                    if( luxsa_string_to_bool($show_excerpt) && !empty($custom_excerpt_length)){
                        if(function_exists('la_excerpt')){
                            echo sprintf(
                                '<p class="lastudio-portfolio__desc">%1$s</p>',
                                la_excerpt(intval( $custom_excerpt_length ))
                            );
                        }
                        else{
                            the_excerpt();
                        }
                    }
                    if( luxsa_string_to_bool($show_readmore_btn) ) {

                        $btn_attr = 'class="lastudio-portfolio__button button"';

                        echo sprintf(
                            '<div class="lastudio-portfolio__button_wrap"><a %1$s href="%2$s"><span class="lastudio-portfolio__button-text">%3$s</span></a></div>',
                            $btn_attr,
                            $item_button_url,
                            $read_more_label
                        );
                    }
                    if( luxsa_string_to_bool($show_divider) ){
                        echo '<div class="lastudio-portfolio__divider"><span></span></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</article>
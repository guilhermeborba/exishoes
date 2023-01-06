<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$enable_related = luxsa_get_option('blog_related_posts', 'off');
$related_style  = luxsa_get_option('blog_related_design', 1);
$max_related    = luxsa_get_option('blog_related_max_post', 1);
$related_by     = luxsa_get_option('blog_related_by', 'random');
$move_related_to_bottom = luxsa_string_to_bool(luxsa_get_option('move_blog_related_to_bottom', 'off'));


$blog_excerpt_length = $move_related_to_bottom ? 10 : 10;

if(!luxsa_string_to_bool($enable_related)){
    return;
}

$related_columns = luxsa_get_responsive_columns('blog_related_post_columns');

wp_reset_postdata();

$related_args = array(
	'meta_key' => '_thumbnail_id',
    'posts_per_page' => $max_related,
    'post__not_in' => array(get_the_ID())
);
if ($related_by == 'random') {
    $related_args['orderby'] = 'rand';
}
if ($related_by == 'category') {
    $cats = wp_get_post_terms(get_the_ID(), 'category');
    if (is_array($cats) && isset($cats[0]) && is_object($cats[0])) {
        $related_args['category__in'] = array($cats[0]->term_id);
    }
}
if ($related_by == 'tag') {
    $tags = wp_get_post_terms(get_the_ID(), 'tag');
    if (is_array($tags) && isset($tags[0]) && is_object($tags[0])) {
        $related_args['tag__in'] = array($tags[0]->term_id);
    }
}
if ($related_by == 'both') {
    $cats = wp_get_post_terms(get_the_ID(), 'category');
    if (is_array($cats) && isset($cats[0]) && is_object($cats[0])) {
        $related_args['category__in'] = array($cats[0]->term_id);
    }
    $tags = wp_get_post_terms(get_the_ID(), 'tag');
    if (is_array($tags) && isset($tags[0]) && is_object($tags[0])) {
        $related_args['tag__in'] = array($tags[0]->term_id);
    }
}


$related_query = new WP_Query($related_args);

$loop_classes = array('la-loop', 'lastudio-posts', 'layout-type-grid', 'lastudio-posts--grid');
$loop_classes[] = 'preset-grid-related';
$loop_list_classes = 'active-object-fit grid-items lastudio-posts__list js-el la-slick-slider lastudio-carousel';
$slidesToShow = array(
    'desktop'           => $related_columns['xxl'],
    'laptop'            => $related_columns['xl'],
    'tablet'            => $related_columns['lg'],
    'tabletportrait'    => $related_columns['md'],
    'mobile'            => $related_columns['sm'],
    'mobileportrait'   => $related_columns['xs']
);

$slide_config = array(
    'slidesToShow'   => $slidesToShow,
    'arrows'   => false,
    'prevArrow'=> '<span class="lastudio-arrow prev-arrow"><i class="lastudioicon-left-arrow"></i></span>',
    'nextArrow'=> '<span class="lastudio-arrow next-arrow"><i class="lastudioicon-right-arrow"></i></span>',
    'rtl' => is_rtl()
);

$title_tag = 'h4';

if($related_query->have_posts()){
    ?>
    <div class="section-related-posts related-posts-design-<?php echo esc_attr($related_style); ?><?php if($move_related_to_bottom){ echo ' related-post-after-main'; } ?>">
        <?php if($move_related_to_bottom){ echo '<div class="container">'; } ?>
        <h3 class="related-posts-heading theme-heading"><?php esc_html_e('Related Posts', 'luxsa'); ?></h3>
        <div class="<?php echo esc_attr(join(' ', $loop_classes)); ?>">
            <div class="lastudio-posts__list_wrapper">
                <div class="<?php echo esc_attr($loop_list_classes) ?>" data-slider_config="<?php echo esc_attr(json_encode($slide_config)); ?>" data-la_component="AutoCarousel">
                    <?php
                    while ($related_query->have_posts()){
                        $related_query->the_post();
                        ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'lastudio-posts__item loop__item grid-item' ); ?>>
                            <div class="lastudio-posts__inner-box">
                                <?php

                                if(has_post_thumbnail()){
                                    ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink() ?>">
                                            <figure class="blog_item--thumbnail figure__object_fit">
                                                <?php the_post_thumbnail(); ?>
                                            </figure>
                                            <span class="pf-icon pf-icon-standard"></span>
                                        </a>
                                    </div>
                                    <?php
                                }

                                echo '<div class="lastudio-posts__inner-content">';

                                get_template_part('partials/entry/meta', 'cat');

                                echo sprintf(
                                    '<header class="entry-header"><%1$s class="entry-title"><a href="%2$s" title="%3$s" rel="bookmark">%3$s</a></%1$s></header>',
                                    esc_attr($title_tag),
                                    esc_url(get_the_permalink()),
                                    esc_html(get_the_title())
                                );

                                get_template_part('partials/entry/meta', 'date-author');
                                echo '</div>';

                                ?></div>
                        </article><!-- #post-## -->

                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php if($move_related_to_bottom){ echo '</div>'; } ?>
    </div>
    <?php
}

wp_reset_postdata();
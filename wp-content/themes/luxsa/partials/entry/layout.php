<?php
/**
 * Default post entry layout
 *
 * @package Luxsa WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$preset = luxsa_get_option('blog_design', '');
if(is_search()){
    $blog_design = 'search';
}
$blog_excerpt_length = luxsa_get_option('blog_excerpt_length', 30);
$blog_thumbnail_size = luxsa_get_option('blog_thumbnail_size', 'full');

$title_tag = 'h2';

$classes = array('lastudio-posts__item', 'loop__item', 'grid-item');

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
    <div class="lastudio-posts__inner-box"><?php

        luxsa_single_post_thumbnail($blog_thumbnail_size);

        echo '<div class="lastudio-posts__inner-content">';

        do_action('luxsa/posts/loop/before_metatop', get_the_ID());

        if($preset == 'grid-4'){
	        get_template_part('partials/entry/meta', 'date-author');
        }
        else{
	        if(is_home() && !is_paged()){
		        get_template_part('partials/entry/meta', 'cat-sticky');
	        }
	        else{
		        get_template_part('partials/entry/meta', 'cat');
	        }
        }

        do_action('luxsa/posts/loop/before_title', get_the_ID());

        echo sprintf(
            '<header class="entry-header"><%1$s class="entry-title"><a href="%2$s" rel="bookmark">%3$s</a></%1$s></header>',
            esc_attr($title_tag),
            esc_url(get_the_permalink()),
            get_the_title()
        );

        do_action('luxsa/posts/loop/before_meta', get_the_ID());

	    if($preset != 'grid-4') {
		    get_template_part( 'partials/entry/meta', 'meta2' );
	    }

        do_action('luxsa/posts/loop/before_excerpt', get_the_ID());

        if($blog_excerpt_length > 0){
            echo '<div class="entry-excerpt">';
            the_excerpt();
            echo '</div>';
        }

        do_action('luxsa/posts/loop/before_readmore', get_the_ID());

        echo sprintf(
            '<div class="lastudio-more-wrap"><a href="%2$s" class="elementor-button lastudio-more" title="%3$s" rel="bookmark"><span class="btn__text">%1$s</span><i class="lastudio-more-icon no-icon"></i></a></div>',
            esc_html__('Continue Reading', 'luxsa'),
            esc_url(get_the_permalink()),
            esc_html(get_the_title())
        );

        echo '</div>';

        ?></div>
</article><!-- #post-## -->
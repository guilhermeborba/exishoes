<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_post_type = get_post_type();

$blog_comments = luxsa_get_option('blog_comments', 'off');

$post_css_class = array('single-content-article');
$post_css_class[] = 'single-' . $current_post_type . '-article';

?>
<article class="<?php echo esc_attr(join(' ', $post_css_class)); ?>">
    <div class="entry"<?php luxsa_schema_markup( 'entry_content' ); ?>>

        <?php do_action( 'luxsa/action/before_single_entry' ); ?>
        <?php the_content();
        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'luxsa' ),
            'after'  => '</div>',
        ) ); ?>
        <?php do_action( 'luxsa/action/after_single_entry' ); ?>
    </div>

    <?php

    // Display comments

    if ( (comments_open() || get_comments_number()) && luxsa_string_to_bool($blog_comments)) {
	    comments_template();
    }
 ?>

</article>
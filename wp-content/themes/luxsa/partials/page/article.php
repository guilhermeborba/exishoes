<?php
/**
 * Outputs page article
 *
 * @package Luxsa WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<div class="entry"<?php luxsa_schema_markup( 'entry_content' ); ?>>

    <?php do_action( 'luxsa/action/before_page_entry' ); ?>

	<?php the_content();

	wp_link_pages( array(
		'before' => '<div class="clearfix"></div><div class="page-links">' . esc_html__( 'Pages:', 'luxsa' ),
		'after'  => '</div>',
	) );
	?>
    <div class="clearfix"></div>

    <?php do_action( 'luxsa/action/after_page_entry' ); ?>

</div>
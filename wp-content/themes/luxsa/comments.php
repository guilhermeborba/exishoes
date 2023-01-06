 <?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments and the comment
 * form. The actual display of comments is handled by a callback to
 * luxsa_comment() which is located at functions/comments-callback.php
 *
 * @package Luxsa WordPress theme
 */

// Return if password is required
if ( post_password_required() ) {
	return;
}

// Add classes to the comments main wrapper
$classes = 'comments-area';

if ( get_comments_number() != 0 ) {
	$classes .= ' has-comments';
}

if ( ! comments_open() && get_comments_number() < 1 ) {
	$classes .= ' empty-closed-comments';
	return;
}

?>

<section id="comments" class="<?php echo esc_attr( $classes ); ?>">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) :

		// Get comments title
		$comments_number = number_format_i18n( get_comments_number() );
		if ( '1' == $comments_number ) {
			$comments_title = esc_html__( 'This post has one comment', 'luxsa' );
		} else {
			$comments_title = sprintf( esc_html__( 'This post has %s comments', 'luxsa' ), $comments_number );
		}
		$comments_title = apply_filters( 'luxsa_comments_title', $comments_title ); ?>

		<h3 class="theme-heading comments-title">
			<span class="text"><?php echo esc_html( $comments_title ); ?></span>
		</h3>

		<ol class="comment-list">
			<?php
			// List comments
			wp_list_comments( array(
				'callback' 	=> 'luxsa_comment',
				'style'     => 'ol',
				'format'    => 'html5',
			) ); ?>
		</ol><!-- .comment-list -->

		<?php

        the_comments_navigation( array(
            'prev_text' => '<i class="lastudioicon-left-arrow"></i>'. esc_html__( 'Previous', 'luxsa' ),
            'next_text' => esc_html__( 'Next', 'luxsa' ) .'<i class="lastudioicon-right-arrow"></i>',
        ) );

		?>

		<?php
		// Display comments closed message
		if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'luxsa' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php
	comment_form(
		array(
			'must_log_in'			=> '<p class="must-log-in">'.  sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a comment.', 'luxsa' ), '<a href="'. wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) .'">', '</a>' ) .'</p>',
			'logged_in_as'			=> '<p class="logged-in-as">'. esc_html__( 'Logged in as', 'luxsa' ) .' <a href="'. admin_url( 'profile.php' ) .'">'. esc_html($user_identity) .'</a>. <a href="' . wp_logout_url( get_permalink() ) .'" title="'. esc_attr__( 'Log out of this account', 'luxsa' ) .'">'. esc_html__( 'Log out &raquo;', 'luxsa' ) .'</a></p>',
			'comment_notes_before'	=> false,
			'comment_notes_after'	=> false,
			'comment_field'			=> '<div class="comment-textarea"><textarea name="comment" id="comment" cols="39" rows="4" tabindex="100" class="textarea-comment" placeholder="'. esc_attr__( 'Your Comment Here...', 'luxsa' ) .'"></textarea></div>',
			'id_submit'				=> 'comment-submit',
			'label_submit'			=> esc_html__( 'Post Comment', 'luxsa' ),
		)
	); ?>

</section><!-- #comments -->
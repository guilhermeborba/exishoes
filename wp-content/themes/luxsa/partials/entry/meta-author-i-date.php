<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
echo '<div class="post-meta post-meta-p">';
echo sprintf(
    '<span class="posted-by post-meta__item"%4$s>%6$s<span>%1$s</span><a href="%2$s" class="posted-by__author" rel="author"%5$s>%3$s</a></span>',
    esc_html__( 'by ', 'luxsa' ),
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    esc_html( get_the_author() ),
    luxsa_get_schema_markup('author_name'),
    luxsa_get_schema_markup('author_link'),
    get_avatar( get_the_author_meta( 'user_email' ), 50 )
);
echo sprintf(
    '<span class="post__date post-meta__item"%3$s><time datetime="%1$s" title="%1$s">%2$s</time></span>',
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() ),
    luxsa_get_schema_markup('publish_date')
);
if(comments_open()){
    echo '<span class="post__comments post-meta__item"><i class="lastudioicon-b-meeting"></i>';
    comments_popup_link(__('0 comment', 'luxsa'),__('1 comment', 'luxsa'),false, 'post__comments-link');
    echo '</span>';
}
echo '</div>';
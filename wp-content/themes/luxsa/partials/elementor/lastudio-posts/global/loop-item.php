<?php
/**
 * Posts loop start template
 */

$preset = $this->get_settings_for_display('preset');

$show_image     = $this->get_settings_for_display('show_image');
$show_meta      = $this->get_settings_for_display('show_meta');
$show_author    = $this->get_settings_for_display('show_author');
$show_date      = $this->get_settings_for_display('show_date');
$show_comments  = $this->get_settings_for_display('show_comments');
$show_categories= $this->get_settings_for_display('show_categories');
$show_title     = $this->get_settings_for_display('show_title');
$show_more      = $this->get_settings_for_display('show_more');
$show_excerpt   = $this->get_settings_for_display('show_excerpt');
$excerpt_length = intval( $this->get_settings_for_display( 'excerpt_length' ) );

$title_html_tag = $this->get_settings_for_display('title_html_tag');


?>
<article class="lastudio-posts__item loop__item grid-item<?php if(has_post_thumbnail()) echo ' has-post-thumbnail'; ?>">
    <div class="lastudio-posts__inner-box"><?php
        if( $show_image == 'yes' && has_post_thumbnail() ) {
            ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail__link"><figure class="blog_item--thumbnail figure__object_fit"><?php
                    the_post_thumbnail($this->get_settings_for_display( 'thumb_size' ), array(
                        'class' => 'post-thumbnail__img wp-post-image la-lazyload-image'
                    ));
                ?></figure></a>
            </div>
            <?php
        }

        echo '<div class="lastudio-posts__inner-content">';

        do_action('luxsa/posts/loop/before_metatop', get_the_ID());

        if( $show_meta == 'yes') {
            echo '<div class="post-meta post-meta--top'. ($show_categories != 'yes' ? ' cat-is-hidden' : '') .'">';
            if($show_categories == 'yes'){
                luxsa_entry_meta_item_category_list('<div class="post-terms post-meta__item">', '</div>');
            }
	        if( $preset == 'grid-2' ){
		        if(filter_var($show_date, FILTER_VALIDATE_BOOLEAN)){
			        echo sprintf(
				        '<span class="post__date post-meta__item"%3$s><time datetime="%1$s" title="%1$s">%2$s</time></span>',
				        esc_attr( get_the_date( 'c' ) ),
				        esc_html( get_the_date() ),
				        luxsa_get_schema_markup('publish_date')
			        );
		        }
		        if(filter_var($show_author, FILTER_VALIDATE_BOOLEAN)){
			        echo sprintf(
				        '<span class="posted-by post-meta__item"%4$s>%6$s<span>%1$s</span><a href="%2$s" class="posted-by__author" rel="author"%5$s>%3$s</a></span>',
				        esc_html__( 'by ', 'luxsa' ),
				        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				        esc_html( get_the_author() ),
				        luxsa_get_schema_markup('author_name'),
				        luxsa_get_schema_markup('author_link'),
				        ''
			        );
		        }
	        }
            echo '</div>';
        }

        do_action('luxsa/posts/loop/before_title', get_the_ID());

        if($show_title == 'yes'){
            $title_length = -1;
            $title_ending = $this->get_settings_for_display( 'title_trimmed_ending_text' );

            if ( filter_var( $this->get_settings_for_display( 'title_trimmed' ), FILTER_VALIDATE_BOOLEAN ) ) {
                $title_length = $this->get_settings_for_display( 'title_length' );
            }

            $title = get_the_title();
            if($title_length > 0){
                $title = wp_trim_words( $title, $title_length, $title_ending );
            }

            echo sprintf(
                '<%1$s class="entry-title"><a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></%1$s>',
                esc_attr($title_html_tag),
                esc_url(get_the_permalink()),
                esc_html(get_the_title()),
                esc_html($title)
            );
        }

	    if( $show_meta == 'yes' && $preset != 'grid-2' && ( filter_var($show_author, FILTER_VALIDATE_BOOLEAN) || filter_var($show_date, FILTER_VALIDATE_BOOLEAN) ) || filter_var($show_comments, FILTER_VALIDATE_BOOLEAN) )  {
		    echo '<div class="post-meta post-meta--top">';
		    if(filter_var($show_date, FILTER_VALIDATE_BOOLEAN)){
			    echo sprintf(
				    '<span class="post__date post-meta__item"%3$s><time datetime="%1$s" title="%1$s">%2$s</time></span>',
				    esc_attr( get_the_date( 'c' ) ),
				    esc_html( get_the_date() ),
				    luxsa_get_schema_markup('publish_date')
			    );
		    }
		    if(filter_var($show_author, FILTER_VALIDATE_BOOLEAN)){
			    echo sprintf(
				    '<span class="posted-by post-meta__item"%4$s>%6$s<span>%1$s</span><a href="%2$s" class="posted-by__author" rel="author"%5$s>%3$s</a></span>',
				    esc_html__( 'by ', 'luxsa' ),
				    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				    esc_html( get_the_author() ),
				    luxsa_get_schema_markup('author_name'),
				    luxsa_get_schema_markup('author_link'),
				    ''
			    );
		    }
		    if(filter_var($show_comments, FILTER_VALIDATE_BOOLEAN)){
			    echo '<span class="post__comments post-meta__item">';
			    comments_popup_link(__('0 comment', 'luxsa'),__('1 comment', 'luxsa'),false, 'post__comments-link');
			    echo '</span>';
		    }
		    echo '</div>';
	    }

        do_action('luxsa/posts/loop/before_meta', get_the_ID());

        do_action('luxsa/posts/loop/before_excerpt', get_the_ID());

        if($show_excerpt){

            echo sprintf(
                '<div class="entry-excerpt">%1$s</div>',
                luxsa_excerpt( $excerpt_length )
            );
        }

        do_action('luxsa/posts/loop/before_readmore', get_the_ID());

        if($show_more == 'yes'){

            echo sprintf(
                '<div class="lastudio-more-wrap"><a href="%2$s" class="elementor-button lastudio-more" title="%3$s" rel="bookmark"><span class="btn__text">%1$s</span><i class="lastudio-more-icon %4$s"></i></a></div>',
                $this->get_settings_for_display( 'more_text' ),
                esc_url(get_the_permalink()),
                esc_html(get_the_title()),
                esc_attr($this->get_settings_for_display( 'more_icon' ) ? $this->get_settings_for_display( 'more_icon' ) : 'no-icon')
            );
        }

        echo '</div>';

        ?></div>
</article>
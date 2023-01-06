<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

if(!has_term('footer', 'elementor_library_type', get_the_ID())){
    ?>
        <div id="content-wrap-editor" class="container">
            <div id="primary" class="content-area">
                <div id="content" class="site-content">
                    <article class="single-content-article">
                        <div class="entry">
					        <?php
					        the_content();
					        ?>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    <?php
}

else{

    ?>
    <div id="content-wrap-editor" class="container">
        <div id="primary" class="content-area">
            <div id="content" class="site-content">
                <article class="single-content-article">
                    <div class="entry">
                        <div class="elementor-theme-builder-content-area"><?php esc_html_e('Content Area', 'lastudio'); ?></div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <footer id="colophon" class="site-footer la-footer-builder">
        <div class="container">
        <?php the_content(); ?>
        </div>
    </footer>
    <?php
}
get_footer();
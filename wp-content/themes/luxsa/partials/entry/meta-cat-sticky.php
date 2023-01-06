<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
echo '<div class="post-meta post-meta--top">';
if(is_sticky()){
    echo sprintf('<span class="sticky-post">%s</span>', esc_html__('Featured', 'luxsa'));
}
luxsa_entry_meta_item_category_list('<div class="post-terms post-meta__item">', '</div>');
echo '</div>';
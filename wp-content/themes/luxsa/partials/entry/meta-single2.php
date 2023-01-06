<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
echo '<div class="post-meta post-meta--top">';
luxsa_entry_meta_item_category_list('<div class="post-terms post-meta__item">', '</div>');
echo '</div>';
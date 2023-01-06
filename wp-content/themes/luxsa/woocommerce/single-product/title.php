<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version    4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$tag = 'h2';

if(luxsa_string_to_bool( luxsa_get_option('product_single_hide_page_title', 'no') )){
    $tag = 'h1';
}
the_title( sprintf('<%s class="product_title entry-title" %s>', $tag, luxsa_get_schema_markup('name')), sprintf('</%s>', $tag));

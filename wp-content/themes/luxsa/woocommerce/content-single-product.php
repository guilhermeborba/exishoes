<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}


$product_design = luxsa_get_option('woocommerce_product_page_design', 1);
$site_layout = luxsa_get_site_layout();
$cssclass_left = 'col-xs-12 col-sm-6 p-left product-main-image';
$cssclass_right = 'col-xs-12 col-sm-6 p-right product--summary';

$class = 'la-p-single-wrap la-p-single-'. $product_design;


if( luxsa_string_to_bool(luxsa_get_option('move_woo_tabs_to_bottom', 'no')) ){
	$class .= ' wc_tabs_at_bottom';
}
else{
	$class .= ' wc_tabs_at_top';
}

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $class, get_the_ID() ); ?>>

	<div class="row s_product_content_top la-single-product-page">
		<div class="<?php echo esc_attr($cssclass_left) ?>">
			<div class="p---large">
				<?php
				/**
				 * woocommerce_before_single_product_summary hook.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );

				?>
			</div>
		</div><!-- .product-images -->
		<div class="<?php echo esc_attr($cssclass_right) ?>">
            <?php
            do_action( 'luxsa/action/before_woocommerce_single_product_summary' );
            ?>
			<div class="la-custom-pright">
				<div class="summary entry-summary">
					<?php

					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 50
					 */
					do_action( 'woocommerce_single_product_summary' );

					?>
					
					
				</div>
			</div>
            <?php
            do_action( 'luxsa/action/after_woocommerce_single_product_summary' );
            ?>
		</div><!-- .product-summary -->
	</div>

    <?php

    do_action( 'luxsa/action/before_wc_tabs' );

        if(luxsa_string_to_bool( luxsa_get_option('move_woo_tabs_to_bottom', 'no'))) {
            echo '<div class="row s_product_content_middle"><div class="col-xs-12">';
            woocommerce_output_product_data_tabs();
            echo '</div></div>';
        }

    do_action( 'luxsa/action/after_wc_tabs' );
    ?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

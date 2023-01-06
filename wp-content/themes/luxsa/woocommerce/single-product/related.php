<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( $related_products ) : ?>

	<?php

	global $post;

    $product_cols   = luxsa_get_responsive_columns('related_products_columns');
	$design         = luxsa_get_option('shop_catalog_grid_style', '1');
	$loopCssClass   = array('products','grid-items','la-slick-slider lastudio-carousel js-el');
	$loopCssClass[] = 'products-grid';
	$loopCssClass[] = 'grid-space-default';
	$loopCssClass[] = 'carousel-equal-height';
	$loopCssClass[] = 'products-grid-' . $design;
    $loopCssClass[] = luxsa_get_responsive_column_classes('related_products_columns', array(
        'mobile' => 1,
        'tablet' => 1
    ));

	$title = luxsa_get_option('related_product_title');
	$sub_title = luxsa_get_option('related_product_subtitle');


    $slidesToShow = array(
        'desktop'           => $product_cols['xxl'],
        'laptop'            => $product_cols['xl'],
        'tablet'            => $product_cols['lg'],
        'tabletportrait'   => $product_cols['md'],
        'mobile'            => $product_cols['sm'],
        'mobileportrait'   => $product_cols['xs']
    );

    $slide_configs  = json_encode(array(
        'slidesToShow'   => $slidesToShow,
        'dots'    => true,
        'arrows'  => true,
        'prevArrow'=> '<span class="lastudio-arrow prev-arrow"><i class="lastudioicon-left-arrow"></i></span>',
        'nextArrow'=> '<span class="lastudio-arrow next-arrow"><i class="lastudioicon-right-arrow"></i></span>',
        'rtl' => is_rtl()
    ));

	?>

	<div class="custom-product-wrap related">
		<div class="custom-product-ul">
			<div class="row block_heading">
				<div class="col-xs-12">
					<h2 class="block_heading--title"><span><?php
                           if(!empty($title)){
                               echo esc_html($title);
                           }
                           else{
                               echo _x( 'Related Products', 'front-view', 'luxsa' );
                           }
                    ?></span></h2>
					<?php if(!empty($sub_title)): ?><div class="block_heading--subtitle"><?php echo esc_html($sub_title); ?></div><?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<ul class="<?php echo esc_attr(implode(' ', $loopCssClass)) ?>" data-la_component="AutoCarousel" data-slider_config="<?php echo esc_attr($slide_configs)?>">

						<?php foreach ( $related_products as $related_product ) : ?>

							<?php
							$post_object = get_post( $related_product->get_id() );

							$post = $post_object;
							setup_postdata($post);

							wc_get_template_part( 'content', 'product' ); ?>

						<?php endforeach; ?>

					</ul>
				</div>
			</div>
		</div>
	</div>
<?php endif;

wp_reset_postdata();
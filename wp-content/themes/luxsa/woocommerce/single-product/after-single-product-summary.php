<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="clearfix"></div>
<div class="container-fluid s_product_content_bottom"><?php
    /**
     * woocommerce_after_single_product_summary hook.
     *
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action( 'woocommerce_after_single_product_summary' );
?></div>
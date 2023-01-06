<?php

$view_mode = luxsa_get_option('shop_catalog_display_type', 'grid');

$view_mode = apply_filters('luxsa/filter/catalog_view_mode', $view_mode);

$per_page_array = luxsa_woo_get_product_per_page_array();
$per_row_array = luxsa_woo_get_product_per_row_array();
$per_page = luxsa_woo_get_product_per_page();
$current_url = add_query_arg(null, null);
$current_url = remove_query_arg(array('page', 'paged', 'mode_view', 'la_doing_ajax'), $current_url);
$current_url = preg_replace('/\/page\/\d+/', '', $current_url);

$active_shop_filter = luxsa_get_option('active_shop_filter', 'off');
$hide_shop_toolbar = luxsa_get_option('hide_shop_toolbar', 'off');
$woocommerce_toggle_grid_list = luxsa_get_option('woocommerce_toggle_grid_list', 'off');


if(luxsa_string_to_bool($hide_shop_toolbar) && !luxsa_string_to_bool($active_shop_filter) && !luxsa_string_to_bool($woocommerce_toggle_grid_list)){
    return;
}

?>
<div class="wc-toolbar-container<?php if ( luxsa_string_to_bool($active_shop_filter) && is_active_sidebar('sidebar-shop-filter')): ?> has-adv-filters<?php endif; ?><?php if(luxsa_string_to_bool($hide_shop_toolbar)){ echo ' --hide-toolbar'; } ?>">
    <div class="wc-toolbar wc-toolbar-top clearfix">
        <?php if(!is_product()): ?>
            <?php if($hide_shop_toolbar != 'on'): ?>
            <div class="wc-toolbar-left">
                <?php woocommerce_result_count();?>
            </div>
            <div class="wc-toolbar-right">
	            <?php if(!empty($per_row_array)): ?>
                    <div class="lasf-custom-dropdown wc-view-item">
                        <button><span data-text="<?php echo esc_html__('View', 'luxsa') ?>"><?php echo esc_html__('View', 'luxsa') ?></span></button>
                        <ul><?php
				            foreach ($per_row_array as $val){?><li><a data-col="<?php echo esc_attr($val) ?>" href="javascript:;"><?php echo sprintf( esc_html__('View %s', 'luxsa'), ($val < 10 ? '0' . $val : $val) ) ?></a></li>
				            <?php }
                        ?></ul>
                    </div>
	            <?php endif ;?>
	            <?php if(!empty($per_page_array)): ?>
                    <div class="lasf-custom-dropdown wc-view-count">
                        <button><span><?php echo sprintf( esc_html__('Show %s', 'luxsa'), $per_page ) ?></span></button>
                        <ul><?php
				            foreach ($per_page_array as $val){?><li
					            <?php if($per_page == $val) { echo ' class="active"'; } ?>><a href="<?php echo esc_url(add_query_arg('per_page', $val, $current_url)); ?>"><?php echo sprintf( esc_html__('Show %s', 'luxsa'), $val ) ?></a></li>
				            <?php }
				            ?></ul>
                    </div>
	            <?php endif ;?>
                <?php if (luxsa_string_to_bool($active_shop_filter) && is_active_sidebar('sidebar-shop-filter')): ?>
                    <div class="lasf-custom-dropdown wc-custom-filters">
                        <button class="btn-advanced-shop-filter"><span>Filtros</span></button>
                    </div>
                <?php endif; ?>
                <?php
                woocommerce_catalog_ordering();
                ?>

	            <?php if( luxsa_string_to_bool($woocommerce_toggle_grid_list) ): ?>
                    <div class="wc-view-toggle">
                        <button data-view_mode="list"<?php
	                    if ($view_mode == 'list') {
		                    echo ' class="active"';
	                    }
	                    ?>><i title="<?php echo esc_attr_x('List view', 'front-view', 'luxsa') ?>" class="lastudioicon-list-bullet-2"></i></button>
                        <button data-view_mode="grid"<?php
			            if ($view_mode == 'grid') {
				            echo ' class="active"';
			            }
			            ?>><i title="<?php echo esc_attr_x('Grid view', 'front-view', 'luxsa') ?>" class="lastudioicon-microsoft"></i></button>
                    </div>
	            <?php endif;?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div><!-- .wc-toolbar -->

    <?php if(is_woocommerce() && !is_product()) {
        $layout = luxsa_get_site_layout();
        if (luxsa_string_to_bool($active_shop_filter) && is_active_sidebar('sidebar-shop-filter')) {
            ?>
            <div class="clearfix"></div>
            <div class="la-advanced-product-filters widget-area clearfix">
                <div class="sidebar-inner">
                    <div class="sidebar-inner--filters">
                        <?php dynamic_sidebar('sidebar-shop-filter'); ?>
                    </div>
                    <?php if( is_filtered() || (!is_filtered() && is_product_taxonomy() && isset($_GET['la_doing_ajax'])) ) : ?>
                    <div class="la-advanced-product-filters-result">
                        <?php
                            $base_filter = luxsa_get_base_shop_url();
                            if(isset($_GET['la_preset'])){
                                $base_filter = add_query_arg('la_preset', $_GET['la_preset'], $base_filter);
                            }
                        ?>
                        <a class="reset-all-shop-filter" href="<?php echo esc_url($base_filter) ?>"><i class="lastudioicon-e-remove"></i><span><?php echo esc_html_x('Clear All Filter', 'front-view', 'luxsa'); ?></span></a>
                    </div>
                    <?php endif; ?>
                </div>
                <a class="close-advanced-product-filters hidden visible-xs" href="javascript:;" rel="nofollow"><i class="lastudioicon-e-remove"></i></a>
            </div>
        <?php
        }
    }
    ?>
</div>
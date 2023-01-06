<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     3.3.0
 */

$is_main_loop = wc_get_loop_prop('is_main_loop', false);

$active_shop_masonry = luxsa_get_option('active_shop_masonry', 'off');
$shop_masonry_column_type = luxsa_get_option('shop_masonry_column_type', 'default');
$woocommerce_shop_masonry_columns = luxsa_get_responsive_columns('woocommerce_shop_masonry_columns');

$product_masonry_container_width = luxsa_get_option('product_masonry_container_width', 1170);
$product_masonry_item_width = luxsa_get_option('product_masonry_item_width', 270);
$product_masonry_item_height = luxsa_get_option('product_masonry_item_height', 450);
$woocommerce_shop_masonry_custom_columns = luxsa_get_responsive_columns('woocommerce_shop_masonry_custom_columns');
$shop_masonry_item_setting = luxsa_get_option('shop_masonry_item_setting');


$woocommerce_pagination_type = luxsa_get_option('woocommerce_pagination_type', 'pagination');

$column_tmp = luxsa_get_responsive_columns('woocommerce_shop_page_columns');

if($is_main_loop && luxsa_string_to_bool($active_shop_masonry)){
    $column_tmp = $woocommerce_shop_masonry_columns;
}


$view_mode = luxsa_get_option('shop_catalog_display_type', 'grid');

$view_mode = apply_filters('luxsa/filter/catalog_view_mode', $view_mode);

if($is_main_loop && luxsa_string_to_bool($active_shop_masonry)){
    $view_mode = 'grid';
}

$design = luxsa_get_option("shop_catalog_grid_style", '1');

$loopCssClass = array();
$loopCssClass[] = 'products ul_products';
$loopCssClass[] = 'products-' . $view_mode;
$loopCssClass[] = 'products-grid-' . $design;

$masonry_component_type = array();

if($is_main_loop && luxsa_string_to_bool($active_shop_masonry)){
    luxsa_set_wc_loop_prop('prods_masonry', true);
    $loopCssClass[] = 'prods_masonry';
    $loopCssClass[] = 'js-el la-isotope-container';
    $loopCssClass[] = 'masonry__column-type-' . $shop_masonry_column_type;
    if( $shop_masonry_column_type != 'custom' ){
        $loopCssClass[] = 'grid-items';
        $masonry_component_type[] = 'DefaultMasonry';
        $loopCssClass[] = luxsa_get_responsive_column_classes('woocommerce_shop_masonry_columns', array(
            'mobile' => 1,
            'tablet' => 1
        ));
    }
    else{
        $__new_item_sizes = array();
        if(!empty($shop_masonry_item_setting) && is_array($shop_masonry_item_setting)){
            $__k = 0;
            foreach($shop_masonry_item_setting as $k => $size){
                $__new_item_sizes[$__k] = $size;
                $__k++;
            }
        }
        luxsa_set_wc_loop_prop('item_sizes', $__new_item_sizes);
        $masonry_component_type[] = 'AdvancedMasonry';
        $column_tmp = luxsa_get_responsive_columns('woocommerce_shop_masonry_custom_columns');
    }
    luxsa_set_wc_loop_prop('image_size', luxsa_get_option('product_masonry_image_size', 'shop_catalog'));
    ?>
<?php
}
else{
    $loopCssClass[] = 'grid-items';
    $loopCssClass[] = luxsa_get_responsive_column_classes('woocommerce_shop_page_columns');
}

if($is_main_loop){

    $pagination_extra_attr = '';
    $pagination_extra_cssclass = '';

    if($woocommerce_pagination_type == 'infinite_scroll'){
        $loopCssClass[] = 'js-el la-infinite-container';
        $masonry_component_type[] = 'InfiniteScroll';
        $pagination_extra_cssclass .= ' la-ajax-pagination active-infinite-loadmore';
        $pagination_extra_attr .= ' data-parent-container=".la-shop-products" data-container=".la-shop-products .ul_products.la-infinite-container" data-item-selector=".product_item" data-ajax_request_id="main-shop" data-infinite-flag=".la-shop-products .wc-infinite-flag"';
    }
    elseif($woocommerce_pagination_type == 'load_more'){
        $loopCssClass[] = 'la-infinite-container infinite-show-loadmore';
        $pagination_extra_cssclass .= ' la-ajax-pagination';
        $pagination_extra_attr .= ' data-parent-container=".la-shop-products" data-container=".la-shop-products .ul_products.la-infinite-container" data-item-selector=".product_item" data-ajax_request_id="main-shop"';
    }

    luxsa_set_wc_loop_prop('pagi_data', array(
        'class' => $pagination_extra_cssclass,
        'attr'  => $pagination_extra_attr
    ));
}


?>
<div class="row">
    <div class="col-xs-12">
        <ul class="<?php echo esc_attr(implode(' ', $loopCssClass)) ?>"<?php
echo ' data-grid_layout="products-grid-'.$design.'"';
echo ' data-item_selector=".product_item"';
echo ' data-item_margin="0"';
echo ' data-container-width="'.esc_attr($product_masonry_container_width).'"';
echo ' data-item-width="'.esc_attr($product_masonry_item_width).'"';
echo ' data-item-height="'.esc_attr($product_masonry_item_height).'"';
echo ' data-md-col="'.esc_attr($column_tmp['lg']).'"';
echo ' data-sm-col="'.esc_attr($column_tmp['md']).'"';
echo ' data-xs-col="'.esc_attr($column_tmp['sm']).'"';
echo ' data-mb-col="'.esc_attr($column_tmp['xs']).'"';
echo ' data-la_component="'.esc_attr(json_encode($masonry_component_type)).'"';
echo ' data-pagination=".la-shop-products .la-pagination"';
?>>
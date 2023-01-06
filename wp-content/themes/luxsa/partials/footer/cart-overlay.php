<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}?>
<?php if(function_exists('woocommerce_mini_cart')): ?>
<div class="cart-flyout">
    <div class="cart-flyout--inner">
        <a href="javascript:;" class="btn-close-cart"><i class="lastudioicon-e-remove"></i></a>
        <div class="cart-flyout__content">
            <div class="cart-flyout__heading theme-heading"><?php echo esc_html_x('Carrinho de compras', 'front-view', 'luxsa') ?></div>
            <div class="cart-flyout__loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
            <div class="widget_shopping_cart_content"><?php
                woocommerce_mini_cart();
                ?></div>
            <?php
            $aside_cart_widget = apply_filters('luxsa/filter/aside_cart_widget', 'aside-cart-widget');
            if(is_active_sidebar($aside_cart_widget)){
                echo '<div class="aside_cart_widget">';
                dynamic_sidebar($aside_cart_widget);
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>
<?php endif; ?>
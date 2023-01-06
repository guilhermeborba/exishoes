<?php
/**
 * Loop item template
 */

$banner_url = $this->get_advanced_carousel_img_src();

?>
<div class="lastudio-carousel__item<?php echo $this->__loop_item( array('item_css_class'), ' %s' )?>">
    <div class="lastudio-carousel__item-inner">
        <figure class="lastudio-banner<?php if(!empty($banner_url)){ echo ' la-lazyload-image'; } ?> lastudio-effect-<?php echo esc_attr( $this->get_settings_for_display( 'animation_effect' ) ); ?><?php if( $this->get_settings_for_display( 'custom_banner_height' ) ) { echo ' image-custom-height'; } ?>"<?php if(!empty($banner_url)){ echo ' data-background-image="'.esc_url($banner_url).'"'; } ?>><?php
            echo '<div class="lastudio-banner__overlay"></div>';
            echo $this->get_advanced_carousel_img( 'lastudio-banner__img' );
            echo '<figcaption class="lastudio-banner__content">';
            echo '<div class="lastudio-banner__content-wrap">';
            echo $this->__loop_item( array( 'item_title' ), '<h5 class="lastudio-banner__title">%s</h5>' );
            echo $this->__loop_item( array( 'item_text' ), '<div class="lastudio-banner__text">%s</div>' );
            echo $this->__loop_button_item( array( 'item_link', 'item_button_text' ), '<button type="button" class="elementor-button elementor-size-md lastudio-banner__button lastudio-carousel__item-button">%2$s</button>' );
            echo '</div>';

            $target = $this->__loop_item( array( 'item_link_target' ), ' target="%s"' );
            echo $this->__loop_item( array( 'item_link' ), '<a href="%s" class="lastudio-banner__link"' . $target . '>' );
            echo $this->__loop_item( array( 'item_link' ), '</a>' );
            echo '</figcaption>';
            ?></figure>
    </div>
</div>

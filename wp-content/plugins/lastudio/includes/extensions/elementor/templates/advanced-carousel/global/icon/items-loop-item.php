<?php
/**
 * Loop item template
 */
?>
<div class="lastudio-carousel__item<?php echo $this->__loop_item( array('item_css_class'), ' %s' )?>">
	<div class="lastudio-carousel__item-inner"><?php
		$target = $this->__loop_item( array( 'item_link_target' ), ' target="%s"' );

		echo $this->__loop_item( array( 'item_link' ), '<a href="%s" class="lastudio-carousel__item-link"' . $target . '>' );

        echo $this->__loop_item( array( 'item_icon' ), '<div class="lastudio-carousel__icon"><i class="%1$s"></i></div>' );

        echo '<div class="lastudio-carousel__content">';

        echo $this->__loop_item( array( 'item_title' ), '<h5 class="lastudio-carousel__item-title">%s</h5>' );

        echo $this->__loop_item( array( 'item_text' ), '<div class="lastudio-carousel__item-text">%s</div>' );

        echo '</div>';

		echo $this->__loop_item( array( 'item_link' ), '</a>' );
?></div>
</div>

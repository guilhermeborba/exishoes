<?php
/**
 * Images list item template
 */
$settings = $this->get_settings_for_display();
$col_class = $this->__loop_item( array( 'item_css_class' ), '%s' );

if ( 'grid' == $settings['layout_type'] || 'masonry' == $settings['layout_type'] ) {
	$col_class .= ' grid-item';
}

$link_instance = 'link-instance-' . $this->item_counter;


$link_type = $this->__loop_item( array( 'item_link_type' ), '%s' );

$this->add_render_attribute( $link_instance, 'class', array(
	'lastudio-images-layout__link'
) );

$link_tag = 'a';

if ( 'lightbox' === $link_type ) {
	$this->add_render_attribute( $link_instance, 'href', $this->__loop_item( array( 'item_image', 'url' ), '%s' ) );
	$this->add_render_attribute( $link_instance, 'data-elementor-open-lightbox', 'yes' );
	$this->add_render_attribute( $link_instance, 'data-elementor-lightbox-slideshow', $this->get_id()  );
}
else if ('external' === $link_type){
    $target = $this->__loop_item( array( 'item_target' ), '%s' );
    $target = ! empty( $target ) ? $target : '_self';

    $this->add_render_attribute( $link_instance, 'href', $this->__loop_item( array( 'item_url' ), '%s' ) );
    $this->add_render_attribute( $link_instance, 'target', $target );
}
else {
    $link_tag = 'div';
}

$item_instance = 'item-instance-' . $this->item_counter;

$col_class .= ' lastudio-images-layout__item';

$this->add_render_attribute( $item_instance, 'class', $col_class );

if ( 'masonry' == $settings['layout_type'] ) {
    $item_sizes = $this->get_masonry_item_sizes($this->__processed_index);
    $this->add_render_attribute( $item_instance, 'data-width', $item_sizes['item_width'] );
    $this->add_render_attribute( $item_instance, 'data-height', $item_sizes['item_height'] );
}

$this->item_counter++;

?>
<div <?php echo $this->get_render_attribute_string( $item_instance ); ?>>
	<div class="lastudio-images-layout__inner">
		<<?php echo $link_tag; ?> <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="lastudio-images-layout__image"><?php
                echo $this->get_loop_image_item();
				?>
			</div>
			<div class="lastudio-images-layout__content"><?php
                echo $this->__loop_item( array( 'item_icon' ), '<div class="lastudio-images-layout__icon"><div class="lastudio-images-layout-icon-inner"><i class="%s"></i></div></div>' );
                $title_tag = $this->__get_html( 'title_html_tag', '%s' );
                echo $this->__loop_item( array( 'item_title' ), '<' . $title_tag . ' class="lastudio-images-layout__title">%s</' . $title_tag . '>' );
                echo $this->__loop_item( array( 'item_desc' ), '<div class="lastudio-images-layout__desc">%s</div>' );
                if('external' === $link_type){
	                echo $this->__loop_item( array( 'item_link_text' ), '<button class="lastudio-images-layout__button button">%s</button>' );
                }
            ?></div>
		</<?php echo $link_tag; ?>>
	</div>
</div>
<?php
/**
 * Team Member template
 */

$settings = $this->get_settings_for_display();

$preset        = $settings['preset'];
$layout        = $settings['layout_type'];
$columns       = $settings['columns'];
$columnsLaptop = !empty($settings['columns_laptop']) ? $settings['columns_laptop'] : $columns;
$columnsTablet = !empty($settings['columns_tablet']) ? $settings['columns_tablet'] : $columnsLaptop;
$columnsTabletPortrait = !empty($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : $columnsTablet;
$columnsMobile = !empty($settings['columns_mobile']) ? $settings['columns_mobile'] : $columnsTabletPortrait;


$this->add_render_attribute( 'main-container', 'id', 'tm_' . $this->get_id() );

$this->add_render_attribute( 'main-container', 'class', array(
	'lastudio-team-member',
	'layout-type-' . $layout,
	'preset-' . $preset,
) );

$this->add_render_attribute( 'list-container', 'class', array(
    'lastudio-member__list'
) );

if( $settings['enable_custom_image_height'] ) {
    $this->add_render_attribute( 'list-container', 'class', array(
        'active-object-fit'
    ) );
}

$this->add_render_attribute( 'list-container', 'data-item_selector', array(
    '.loop__item'
) );


if('grid' == $layout){
    $grid_css_classes = array('grid-items');
    $this->add_render_attribute( 'list-container', 'class', array(
        'grid-items',
        'block-grid-' . $columns,
        'laptop-block-grid-' . $columnsLaptop,
        'tablet-block-grid-' . $columnsTablet,
        'mobile-block-grid-' . $columnsTabletPortrait,
        'xmobile-block-grid-' . $columnsMobile
    ));
}

$slider_options = $this->generate_carousel_setting_json();
if(!empty($slider_options)){
    $this->add_render_attribute( 'list-container', 'data-slider_config', $slider_options );
    $this->add_render_attribute( 'list-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
    $this->add_render_attribute( 'list-container', 'class', 'js-el la-slick-slider lastudio-carousel' );
    $this->add_render_attribute( 'list-container', 'data-la_component', 'AutoCarousel');
}
?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
    <div class="lastudio-member__list_wrapper">
        <div <?php echo $this->get_render_attribute_string('list-container'); ?>>
            <?php
            $items = !empty($settings['items']) ? $settings['items'] : false;

            $title_tag          = $settings['title_html_tag'];
            $show_role          = $settings['show_role'];
            $show_social        = $settings['show_social'];
            $show_excerpt       = $settings['show_excerpt'];
            $excerpt_length     = absint($settings['excerpt_length']);

            if($items){
                foreach ($items as $index => $item) {

                    $member_link        = !empty($item['link']) ? $item['link'] : [];
                    $member_name        = !empty($item['name']) ? $item['name'] : '';
                    $member_image       = !empty($item['image']) ? $item['image'] : [];
                    $member_role        = !empty($item['role']) ? $item['role'] : '';
                    $member_description = !empty($item['description']) ? $item['description'] : '';

                    $link_key = 'member_link_' . $index;

                    $social_html = $this->__get_member_social($item);

                    ?>
                    <div class="lastudio-team-member__item loop__item grid-item">
                        <div class="lastudio-team-member__inner-box">
                            <div class="lastudio-team-member__inner">
                                <div class="lastudio-team-member__image_wrap">
                                    <?php
                                    $this->add_render_attribute( $link_key, 'class', 'lastudio-images-layout__link' );
                                    $tag_link = 'div';
                                    if ( ! empty( $member_link['url'] ) ) {
                                        $tag_link = 'a';
                                        $this->add_link_attributes( $link_key, $member_link );
                                        $this->add_render_attribute( $link_key, 'title', esc_attr($member_name) );
                                    }
                                    echo sprintf('<%1$s %2$s>%3$s</%1$s>', $tag_link, $this->get_render_attribute_string( $link_key ), $this->__get_member_image( $member_image ));
                                    if(in_array($preset, array('type-1', 'type-2', 'type-3')) && $show_social && !empty($social_html)){
                                        echo '<div class="lastudio-team-member__cover"><div class="lastudio-team-member__socials">' . $social_html . '</div></div>';
                                    }
                                    ?>
                                </div>
                                <div class="lastudio-team-member__content">
                                    <?php
                                    $this->remove_render_attribute($link_key, 'class');
                                    echo sprintf(
                                        '<%1$s class="lastudio-team-member__name"><a %2$s>%3$s</a></%1$s>',
                                        esc_attr($title_tag),
                                        $this->get_render_attribute_string( $link_key ),
                                        esc_html($member_name)
                                    );

                                    if(!empty($member_role) && $show_role){
                                        echo sprintf('<div class="lastudio-team-member__position"><span>%s</span></div>', esc_html($member_role));
                                    }

                                    if($excerpt_length > 0){
                                        echo sprintf(
                                            '<p class="lastudio-team-member__desc">%1$s</p>',
                                            wp_trim_words($member_description, $excerpt_length)
                                        );
                                    }

                                    if(!in_array($preset, array('type-1', 'type-2', 'type-3')) && $show_social && !empty($social_html)){
                                        echo '<div class="lastudio-team-member__socials">' . $social_html . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
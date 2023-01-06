<?php
namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use LaStudio_Element\Controls\Group_Control_Box_Style;

/**
 * Images_Layout Widget
 */
class Images_Layout extends LA_Widget_Base {

    public function get_name() {
        return 'lastudio-images-layout';
    }

    protected function get_widget_title() {
        return esc_html__( 'Images Layout', 'lastudio' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-21';
    }

    public function get_style_depends() {
        return [
            'lastudio-images-layout-elm'
        ];
    }

    /**
     * [$item_counter description]
     * @var integer
     */
    public $item_counter = 0;

    protected function register_controls() {

        $css_scheme = apply_filters(
            'LaStudioElement/images-layout/css-scheme',
            array(
                'instance'          => '.lastudio-images-layout',
                'list_container'    => '.lastudio-images-layout__list',
                'item'              => '.lastudio-images-layout__item',
                'inner'             => '.lastudio-images-layout__inner',
                'image_wrap'        => '.lastudio-images-layout__image',
                'image_instance'    => '.lastudio-images-layout__image-instance',
                'content_wrap'      => '.lastudio-images-layout__content',
                'icon'              => '.lastudio-images-layout__icon',
                'title'             => '.lastudio-images-layout__title',
                'desc'              => '.lastudio-images-layout__desc',
                'button'            => '.lastudio-images-layout__button',
            )
        );

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => array(
                    'masonry' => esc_html__( 'Masonry', 'lastudio' ),
                    'grid'    => esc_html__( 'Grid', 'lastudio' ),
                    'list'    => esc_html__( 'List', 'lastudio' ),
                ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'prefix_class' => 'imagelayout-preset-',
                'options' => apply_filters('LaStudioElement/images-layout/preset', [
                    'default' => esc_html__( 'Default', 'lastudio' )
                ])
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => lastudio_elementor_tools_get_select_range( 6 ),
                'condition' => array(
                    'layout_type' => array( 'masonry', 'grid' ),
                ),
            )
        );

        $this->add_control(
            'enable_custom_masonry_layout',
            array(
                'label'        => esc_html__( 'Enable Custom Masonry Layout', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => '',
                'condition' => array(
                    'layout_type' => 'masonry'
                )
            )
        );

        $this->add_control(
            'container_width',
            array(
                'label' => esc_html__( 'Container Width', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 500,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 1170,
                ],
                'condition' => array(
                    'layout_type' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_width',
            array(
                'label' => esc_html__( 'Masonry Item Width', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 300,
                ],
                'condition' => array(
                    'layout_type' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_height',
            array(
                'label' => esc_html__( 'Masonry Item Height', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 300,
                ],
                'condition' => array(
                    'layout_type' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'enable-c-height-',
                'condition' => array(
                    'layout_type!' => 'list'
                ),
            )
        );

        $this->add_responsive_control(
            'item_height',
            array(
                'label' => esc_html__( 'Image Height', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units' => ['px', '%', 'vh'],
                'default' => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ),
                'condition' => [
                    'layout_type!' => 'list',
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->add_control(
            'enable_carousel',
            array(
                'label'        => esc_html__( 'Enable Carousel', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => '',
                'condition' => array(
                    'layout_type' => 'grid'
                )
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'lastudio' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_icon',
            array(
                'label'       => esc_html__( 'Icon', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'include' => self::get_laicon_default(true),
                'options' => self::get_laicon_default()
            )
        );

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'lastudio' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_link_type',
            array(
                'label'   => esc_html__( 'Link type', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lightbox',
                'options' => array(
                    'lightbox' => esc_html__( 'Lightbox', 'lastudio' ),
                    'external' => esc_html__( 'External', 'lastudio' ),
                    'none'     => esc_html__( 'None', 'lastudio' )
                ),
            )
        );
	    $repeater->add_control(
		    'item_link_text',
		    array(
			    'label'   => esc_html__( 'Button Title', 'lastudio' ),
			    'type'    => Controls_Manager::TEXT,
			    'condition' => array(
				    'item_link_type' => 'external',
			    ),
			    'dynamic' => array( 'active' => true ),
		    )
	    );
        $repeater->add_control(
            'item_url',
            array(
                'label'   => esc_html__( 'External Link', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '#',
                'condition' => array(
                    'item_link_type' => 'external',
                ),
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
            )
        );

        $repeater->add_control(
            'item_target',
            array(
                'label'        => esc_html__( 'Open external link in new window', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => '_blank',
                'default'      => '',
                'condition'    => array(
                    'item_link_type' => 'external',
                ),
            )
        );

        $repeater->add_control(
            'item_css_class',
            array(
                'label'   => esc_html__( 'Item CSS class', 'lastudio' ),
                'type'    => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #1', 'lastudio' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #2', 'lastudio' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #3', 'lastudio' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #4', 'lastudio' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #5', 'lastudio' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                    array(
                        'item_image'       => array(
                            'url' => Utils::get_placeholder_image_src(),
                        ),
                        'item_title'       => esc_html__( 'Image #6', 'lastudio' ),
                        'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'lastudio' ),
                        'item_url'         => '#',
                        'item_target'      => '',
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->add_control(
            'title_html_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'lastudio' ),
                    'h2'   => esc_html__( 'H2', 'lastudio' ),
                    'h3'   => esc_html__( 'H3', 'lastudio' ),
                    'h4'   => esc_html__( 'H4', 'lastudio' ),
                    'h5'   => esc_html__( 'H5', 'lastudio' ),
                    'h6'   => esc_html__( 'H6', 'lastudio' ),
                    'div'  => esc_html__( 'div', 'lastudio' ),
                    'span' => esc_html__( 'span', 'lastudio' ),
                    'p'    => esc_html__( 'p', 'lastudio' ),
                ),
                'default' => 'h5',
                'separator' => 'before',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_layout',
            array(
                'label' => esc_html__( 'Item Layout', 'lastudio' ),
                'condition' => array(
                    'layout_type!' => array(
                        'list',
                        'grid'
                    ),
                    'enable_custom_masonry_layout!' => ''
                )
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_width',
            array(
                'label'   => esc_html__( 'Item Width', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 width',
                    '1-3' => '1,3 width',
                    '1-4' => '1,4 width',
                    '1-5' => '1,5 width',
                    '1-6' => '1,6 width',
                    '1-7' => '1,7 width',
                    '2' => '2 width',
                    '2-5' => '2,5 width',
                    '3-0' => '3 width',
                    '0-5' => '1/2 width',
                    '0-75' => '3/4 width',
                    '0-60' => '3/5 width',
                    '0-80' => '4/5 width'
                ),
            )
        );

        $repeater->add_control(
            'item_height',
            array(
                'label'   => esc_html__( 'Item Height', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 height',
                    '1-4' => '1,4 height',
                    '1-5' => '1,5 height',
                    '1-6' => '1,6 height',
                    '1-7' => '1,7 height',
                    '1-8' => '1,8 height',
                    '1-9' => '1,9 height',
                    '2' => '2 height',
                    '2-5' => '2,5 height',
                    '0-5' => '1/2 height',
                    '0-75' => '3/4 height',
                    '0-60' => '3/5 height',
                    '0-80' => '4/5 height'
                )
            )
        );

        $this->add_control(
            'image_list_layout',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'prevent_empty' => false
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_carousel',
            array(
                'label' => esc_html__( 'Carousel Settings', 'lastudio' ),
                'condition' => array(
                    'layout_type' => 'grid',
                    'enable_carousel!' => ''
                )
            )
        );

        $this->add_control(
            'slides_to_scroll',
            array(
                'label'     => esc_html__( 'Slides to Scroll', 'lastudio'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options' => lastudio_elementor_tools_get_select_range(10),
                'condition' => array(
                    'columns!' => '1',
                ),
            )
        );

        $this->add_control(
            'arrows',
            array(
                'label'        => esc_html__( 'Show Arrows Navigation', 'lastudio'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio'),
                'label_off'    => esc_html__( 'No', 'lastudio'),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'prev_arrow',
            array(
                'label'   => esc_html__( 'Prev Arrow Icon', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lastudioicon-left-arrow',
                'options' => lastudio_elementor_tools_get_nextprev_arrows_list('prev'),
                'condition' => array(
                    'arrows' => 'true'
                )
            )
        );

        $this->add_control(
            'next_arrow',
            array(
                'label'   => esc_html__( 'Next Arrow Icon', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lastudioicon-right-arrow',
                'options' => lastudio_elementor_tools_get_nextprev_arrows_list('next'),
                'condition' => array(
                    'arrows' => 'true'
                )
            )
        );

        $this->add_control(
            'dots',
            array(
                'label'        => esc_html__( 'Show Dots Navigation', 'lastudio'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio'),
                'label_off'    => esc_html__( 'No', 'lastudio'),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'center_mode',
            array(
                'label'        => esc_html__( 'Center Mode', 'lastudio'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio'),
                'label_off'    => esc_html__( 'No', 'lastudio'),
                'return_value' => 'yes',
                'default'      => ''
            )
        );

        $this->add_responsive_control(
            'center_mode_gap_left',
            array(
                'label' => esc_html__( 'Padding Left', 'lastudio'),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vw' => array(
                        'min' => 0,
                        'max' => 50,
                    )
                ),
                'size_units' => ['px', '%', 'vw'],
                'selectors' => array(
                    '{{WRAPPER}} .slick-list' => 'padding-left: {{SIZE}}{{UNIT}} !important;',
                )
            )
        );

        $this->add_responsive_control(
            'center_mode_gap_right',
            array(
                'label' => esc_html__( 'Padding Right', 'lastudio'),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'vw' => array(
                        'min' => 0,
                        'max' => 50,
                    )
                ),
                'size_units' => ['px', '%', 'vw'],
                'selectors' => array(
                    '{{WRAPPER}} .slick-list' => 'padding-right: {{SIZE}}{{UNIT}} !important;',
                )
            )
        );

        $this->add_control(
            'pause_on_hover',
            array(
                'label'        => esc_html__( 'Pause on Hover', 'lastudio'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio'),
                'label_off'    => esc_html__( 'No', 'lastudio'),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => esc_html__( 'Autoplay', 'lastudio'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio'),
                'label_off'    => esc_html__( 'No', 'lastudio'),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'autoplay_speed',
            array(
                'label'     => esc_html__( 'Autoplay Speed', 'lastudio'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5000,
                'condition' => array(
                    'autoplay' => 'true',
                ),
            )
        );

        $this->add_control(
            'infinite',
            array(
                'label'        => esc_html__( 'Infinite Loop', 'lastudio'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio'),
                'label_off'    => esc_html__( 'No', 'lastudio'),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'effect',
            array(
                'label'   => esc_html__( 'Effect', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => array(
                    'slide' => esc_html__( 'Slide', 'lastudio'),
                    'fade'  => esc_html__( 'Fade', 'lastudio'),
                ),
                'condition' => array(
                    'columns' => '1',
                ),
            )
        );

        $this->add_control(
            'speed',
            array(
                'label'   => esc_html__( 'Animation Speed', 'lastudio'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 500,
            )
        );

        $this->end_controls_section();


        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_images_layout_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_margin',
            array(
                'label' => esc_html__( 'Items Margin', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item']          => 'padding: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['list_container'] => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->add_responsive_control(
            'item_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'item_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
            )
        );

        $this->end_controls_section();

        /**
         * Icon Style Section
         */
        $this->start_controls_section(
            'section_images_layout_icon_style',
            array(
                'label'      => esc_html__( 'Icon', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label' => esc_html__( 'Icon Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-images-layout-icon-inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em' ,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Icon Box Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-images-layout-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'icon_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-images-layout-icon-inner',
            )
        );

        $this->add_control(
            'icon_box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-images-layout-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_box_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-images-layout-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .lastudio-images-layout-icon-inner',
            )
        );


        $this->add_control(
            'icon_horizontal_alignment',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Left', 'lastudio' ),
                    'center'        => esc_html__( 'Center', 'lastudio' ),
                    'flex-end'      => esc_html__( 'Right', 'lastudio' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['icon'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'icon_vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'lastudio' ),
                    'center'        => esc_html__( 'Center', 'lastudio' ),
                    'flex-end'      => esc_html__( 'Bottom', 'lastudio' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['icon'] => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_images_layout_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_images_layout_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'desc_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();



	    /**
	     * Button Style Section
	     */
	    $this->start_controls_section(
		    'section_button_style',
		    array(
			    'label'      => esc_html__( 'Button', 'lastudio' ),
			    'tab'        => Controls_Manager::TAB_STYLE,
			    'show_label' => false,
		    )
	    );


	    $this->start_controls_tabs( 'tabs_button_style' );

	    $this->start_controls_tab(
		    'tab_button_normal',
		    array(
			    'label' => esc_html__( 'Normal', 'lastudio' ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'button_bg',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			    'fields_options' => array(
				    'background' => array(
					    'default' => 'classic',
				    ),
				    'color' => array(
					    'label'  => _x( 'Background Color', 'Background Control', 'lastudio' )
				    ),
				    'color_b' => array(
					    'label' => _x( 'Second Background Color', 'Background Control', 'lastudio' ),
				    ),
			    ),
			    'exclude' => array(
				    'image',
				    'position',
				    'attachment',
				    'attachment_alert',
				    'repeat',
				    'size',
			    ),
		    )
	    );

	    $this->add_control(
		    'button_color',
		    array(
			    'label'     => esc_html__( 'Text Color', 'lastudio' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'button_typography',
			    'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
		    )
	    );

	    $this->add_responsive_control(
		    'button_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_margin',
		    array(
			    'label'      => __( 'Margin', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_border_radius',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'button_border',
			    'label'       => esc_html__( 'Border', 'lastudio' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    array(
			    'name'     => 'button_box_shadow',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
		    )
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_button_hover',
		    array(
			    'label' => esc_html__( 'Hover', 'lastudio' ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    array(
			    'name'     => 'button_bg_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			    'fields_options' => array(
				    'background' => array(
					    'default' => 'classic',
				    ),
				    'color' => array(
					    'label' => _x( 'Background Color', 'Background Control', 'lastudio' ),
				    ),
				    'color_b' => array(
					    'label' => _x( 'Second Background Color', 'Background Control', 'lastudio' ),
				    ),
			    ),
			    'exclude' => array(
				    'image',
				    'position',
				    'attachment',
				    'attachment_alert',
				    'repeat',
				    'size',
			    ),
		    )
	    );

	    $this->add_control(
		    'button_color_hover',
		    array(
			    'label'     => esc_html__( 'Text Color', 'lastudio' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'button_typography_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
		    )
	    );

	    $this->add_responsive_control(
		    'button_padding_hover',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_margin_hover',
		    array(
			    'label'      => __( 'Margin', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_responsive_control(
		    'button_border_radius_hover',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'button_border_hover',
			    'label'       => esc_html__( 'Border', 'lastudio' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover'
		    )
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    array(
			    'name'     => 'button_box_shadow_hover',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover'
		    )
	    );

	    $this->end_controls_tab();

	    $this->end_controls_tabs();

	    $this->end_controls_section();


        /**
         * Overlay Style Section
         */
        $this->start_controls_section(
            'section_images_layout_overlay_style',
            array(
                'label'      => esc_html__( 'Overlay', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_overlay_style' );

        $this->start_controls_tab(
            'tabs_overlay_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_background',
                'selector' => '{{WRAPPER}} .lastudio-images-layout__content:before,{{WRAPPER}} .lastudio-images-layout__image:after',
            )
        );

        $this->add_control(
            'overlay_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-images-layout__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .lastudio-images-layout__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_overlay_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'overlay_h_background',
                'selector' => '{{WRAPPER}} .lastudio-images-layout__inner:hover .lastudio-images-layout__content:before,{{WRAPPER}} .lastudio-images-layout__inner:hover .lastudio-images-layout__image:after'
            )
        );

        $this->add_control(
            'overlay_h_opacity',
            array(
                'label'    => esc_html__( 'Opacity', 'lastudio' ),
                'type'     => Controls_Manager::NUMBER,
                'default'  => 0.6,
                'min'      => 0,
                'max'      => 1,
                'step'     => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-images-layout__inner:hover .lastudio-images-layout__content:before' => 'opacity: {{VALUE}};',
                    '{{WRAPPER}} .lastudio-images-layout__inner:hover .lastudio-images-layout__image:after' => 'opacity: {{VALUE}};'
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'overlay_paddings',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order and Alignment', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'item_title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_content_order',
            array(
                'label'   => esc_html__( 'Content Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'item_button_order',
            array(
                'label'   => esc_html__( 'Button Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['button'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'item_content_alignment',
            array(
                'label'   => esc_html__( 'Content Vertical Alignment', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'flex-end',
                'options' => array(
                    'flex-start'    => esc_html__( 'Top', 'lastudio' ),
                    'center'        => esc_html__( 'Center', 'lastudio' ),
                    'flex-end'      => esc_html__( 'Bottom', 'lastudio' ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['content_wrap']  => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'      => esc_html__( 'Carousel Arrows', 'lastudio'),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'layout_type' => 'grid',
                    'enable_carousel!' => ''
                )
            )
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_prev',
            array(
                'label' => esc_html__( 'Normal', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio'),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_next_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_hover_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio'),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow:hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_position',
            array(
                'label'     => esc_html__( 'Prev Arrow Position', 'lastudio'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'prev_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio'),
                    'bottom' => esc_html__( 'Bottom', 'lastudio'),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_vert_position' => 'top',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_vert_position' => 'bottom',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->add_control(
            'prev_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio'),
                    'right' => esc_html__( 'Right', 'lastudio'),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->add_control(
            'next_arrow_position',
            array(
                'label'     => esc_html__( 'Next Arrow Position', 'lastudio'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'next_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio'),
                    'bottom' => esc_html__( 'Bottom', 'lastudio'),
                ),
            )
        );

        $this->add_responsive_control(
            'next_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_vert_position' => 'top',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_bottom_position',
            array(
                'label'      => esc_html__( 'Bottom Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_vert_position' => 'bottom',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->add_control(
            'next_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio'),
                    'right' => esc_html__( 'Right', 'lastudio'),
                ),
            )
        );

        $this->add_responsive_control(
            'next_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_dots_style',
            array(
                'label'      => esc_html__( 'Carousel Dots', 'lastudio'),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'layout_type' => 'grid',
                    'enable_carousel!' => ''
                )
            )
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style',
                'label'          => esc_html__( 'Dots Style', 'lastudio'),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li span',
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_hover',
                'label'          => esc_html__( 'Dots Style', 'lastudio'),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li span:hover',
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio'),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_active',
                'label'          => esc_html__( 'Dots Style', 'lastudio'),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li.slick-active span',
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'dots_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 5,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ),
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'dots_margin',
            array(
                'label'      => esc_html__( 'Dots Box Margin', 'lastudio'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'dots_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio'),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio'),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio'),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }
    /**
     * Get loop image html
     *
     */

    public function get_loop_image_item() {

        $image_data = $this->__loop_image_item('item_image', '', false);

        if(!empty($image_data)){
	        $giflazy = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
	        $giflazy = $image_data[0];
            $srcset = sprintf('width="%d" height="%d" srcset="%s" style="--img-height:%dpx"', $image_data[1], $image_data[2], $giflazy, $image_data[2]);
            return sprintf( apply_filters('LaStudioElement/images-layout/image-format', '<img src="%1$s" data-src="%2$s" alt="" loading="lazy" class="%3$s" %4$s>'), $giflazy, $image_data[0], 'lastudio-images-layout__image-instance' , $srcset);
        }

        return '';
    }

    /**
     * Get loop image html
     *
     */
    protected function __loop_image_item( $key = '', $format = '%s', $html_return = true ) {
        $item = $this->__processed_item;
        $params = [];

        if ( ! array_key_exists( $key, $item ) ) {
            return false;
        }

        $image_item = $item[ $key ];

        if ( ! empty( $image_item['id'] ) && wp_attachment_is_image($image_item['id']) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], 'full' );

            $params[] = apply_filters('lastudio_wp_get_attachment_image_url', $image_data[0]);
            $params[] = $image_data[1];
            $params[] = $image_data[2];
        } else {
            $params[] = $image_item['url'];
            $params[] = 1200;
            $params[] = 800;
        }

        if($html_return){
            return vsprintf( $format, $params );
        }
        else{
            return $params;
        }
    }

    protected function render() {

        $this->__context = 'render';

        $this->__open_wrap();
        include $this->__get_global_template( 'index' );
        $this->__close_wrap();
    }

    protected function content_template() {}

    protected function get_masonry_item_sizes( $idx = 0 ){
        $return = array(
            'item_width' => 1,
            'item_height' => 1,
        );
        $image_list = (array) $this->get_settings_for_display('image_list_layout');
        if(!empty($image_list[$idx]['item_width'])){
            $return['item_width'] = str_replace('-', '.', $image_list[$idx]['item_width']);
        }
        if(!empty($image_list[$idx]['item_height'])){
            $return['item_height'] = str_replace('-', '.', $image_list[$idx]['item_height']);
        }

        return $return;
    }

    public function get_advanced_carousel_options() {

        $settings = $this->get_settings();

        $desktop_col = absint( $this->get_settings_for_display('columns') );
        $laptop_col = absint( $this->get_settings_for_display('columns_laptop') );
        $tablet_col = absint( $this->get_settings_for_display('columns_tablet') );
        $mobile_extra_col = absint( $this->get_settings_for_display('columns_mobile_extra') );
        $mobile_col = absint( $this->get_settings_for_display('columns_mobile') );

        if($laptop_col == 0){
            $laptop_col = $desktop_col;
        }
        if($tablet_col == 0){
            $tablet_col = $laptop_col;
        }
        if($mobile_extra_col == 0){
            $mobile_extra_col = $tablet_col;
        }

        if($mobile_col == 0){
            $mobile_col = 1;
        }

        $slidesToShow = array(
            'desktop'           => $desktop_col,
            'laptop'            => $laptop_col,
            'tablet'            => $tablet_col,
            'mobile_extra'    => $mobile_extra_col,
            'mobile'            => $mobile_col
        );

        $options  = array(
            'slidesToShow'   => $slidesToShow,
            'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
            'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
            'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
            'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
            'speed'          => absint( $settings['speed'] ),
            'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
            'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
            'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
            'prevArrow'      => lastudio_elementor_tools_get_carousel_arrow( [ 'prev-arrow', 'slick-prev' ], [ $settings['prev_arrow'] ] ),
            'nextArrow'      => lastudio_elementor_tools_get_carousel_arrow( ['next-arrow', 'slick-next'], [ $settings['next_arrow'] ] ),
            'rtl'            => is_rtl()
        );

        if ( 1 === absint( $desktop_col ) ) {
            $options['fade'] = ( 'fade' === $settings['effect'] );
        }

        return $options;
    }

}
<?php
namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}

use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use LaStudio_Element\Controls\Group_Control_Box_Style;
use LaStudio_Element\Controls\Group_Control_Query;
use LaStudio_Element\Classes\Products_Renderer;

use Elementor\Controls_Manager;
use Elementor\Repeater;


class Products extends LA_Widget_Base {

    public static $__called_index = 0;
    public static $__called_item = false;

    public function get_name() {
        return 'lastudio-products';
    }

    public function get_title() {
        return __( 'LaStudio Products', 'lastudio' );
    }

    public function get_icon() {
        return 'eicon-products';
    }

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
    }

    public function get_script_depends() {
        return [
            'lastudio'
        ];
    }

    protected function register_query_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'lastudio' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
            Group_Control_Query::get_type(),
            [
                'name' => 'query',
                'post_type' => 'product',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'product',
                        'options' => [
                            'current_query' => __( 'Current Query', 'lastudio' ),
                            'product' => __( 'Latest Products', 'lastudio' ),
                            'sale' => __( 'Sale', 'lastudio' ),
                            'featured' => __( 'Featured', 'lastudio' ),
                            'related' => esc_html__( 'Related', 'lastudio' ),
                            'upsells' => esc_html__( 'Up-Sells', 'lastudio' ),
                            'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date' => __( 'Date', 'lastudio' ),
                            'title' => __( 'Title', 'lastudio' ),
                            'price' => __( 'Price', 'lastudio' ),
                            'popularity' => __( 'Popularity', 'lastudio' ),
                            'rating' => __( 'Rating', 'lastudio' ),
                            'rand' => __( 'Random', 'lastudio' ),
                            'menu_order' => __( 'Menu Order', 'lastudio' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => __( 'Current Post', 'lastudio' ),
                            'manual_selection' => __( 'Manual Selection', 'lastudio' ),
                            'terms' => __( 'Term', 'lastudio' ),
                        ],
                    ],
                    'include' => [
                        'options' => [
                            'terms' => __( 'Term', 'lastudio' ),
                        ],
                    ],
                    'exclude_ids' => [
                        'object_type' => 'product',
                    ],
                    'include_ids' => [
                        'object_type' => 'product',
                    ],
                ],
                'exclude' => [
                    'posts_per_page',
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'ignore_sticky_posts',
                ],
            ]
        );

        $this->add_control(
            'nothing_found_message',
            [
                'label' => esc_html__( 'Nothing Found Message', 'lastudio' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__( 'Custom Heading', 'lastudio' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'html_tag',
            [
                'label' => esc_html__( 'Heading HTML Tag', 'lastudio' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__( 'Alignment', 'lastudio' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'lastudio' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Helper Register Carousel Controls
     */
    protected function _register_carousel_controls( $carousel_condition = array() ){
        $this->start_controls_section(
            'section_carousel',
            array(
                'label' => esc_html__( 'Carousel', 'lastudio' ),
                'condition' => $carousel_condition
            )
        );

        $this->add_control(
            'carousel_enabled',
            array(
                'label'        => esc_html__( 'Enable Carousel', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_control(
            'slides_to_scroll',
            array(
                'label'     => esc_html__( 'Slides to Scroll', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => lastudio_elementor_tools_get_select_range(6),
                'condition' => array(
                    'columns!' => '1',
                ),
            )
        );

        $this->add_control(
            'number_slick_rows',
            array(
                'label'     => esc_html__( 'Rows', 'lastudio' ),
                'description'=> esc_html__( 'This option only works on laptops and desktops', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => lastudio_elementor_tools_get_select_range(5)
            )
        );

        $this->add_control(
            'arrows',
            array(
                'label'        => esc_html__( 'Show Arrows Navigation', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'prev_arrow',
            array(
                'label'   => esc_html__( 'Prev Arrow Icon', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lastudioicon-left-arrow',
                'options' => lastudio_elementor_tools_get_nextprev_arrows_list('prev'),
                'condition' => array(
                    'arrows' => 'true',
                ),
            )
        );

        $this->add_control(
            'next_arrow',
            array(
                'label'   => esc_html__( 'Next Arrow Icon', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lastudioicon-right-arrow',
                'options' => lastudio_elementor_tools_get_nextprev_arrows_list('next'),
                'condition' => array(
                    'arrows' => 'true',
                ),
            )
        );

        $this->add_control(
            'dots',
            array(
                'label'        => esc_html__( 'Show Dots Navigation', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'pause_on_hover',
            array(
                'label'        => esc_html__( 'Pause on Hover', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => esc_html__( 'Autoplay', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'autoplay_speed',
            array(
                'label'     => esc_html__( 'Autoplay Speed', 'lastudio' ),
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
                'label'        => esc_html__( 'Infinite Loop', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'effect',
            array(
                'label'   => esc_html__( 'Effect', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => array(
                    'slide' => esc_html__( 'Slide', 'lastudio' ),
                    'fade'  => esc_html__( 'Fade', 'lastudio' ),
                ),
                'condition' => array(
                    'columns' => '1',
                ),
            )
        );

        $this->add_control(
            'speed',
            array(
                'label'   => esc_html__( 'Animation Speed', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 500,
            )
        );

        $this->add_control(
            'center_mode',
            array(
                'label'        => esc_html__( 'Center Mode', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => ''
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_slick_list',
            array(
                'label' => esc_html__( 'Carousel List', 'lastudio' ),
                'condition' => $carousel_condition
            )
        );

        $this->add_responsive_control(
            'slick_list_padding_left',
            array(
                'label'      => esc_html__( 'Padding Left', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%', 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .slick-list' => 'padding-left: {{SIZE}}{{UNIT}} !important;',
                ),
            )
        );

        $this->add_responsive_control(
            'slick_list_padding_right',
            array(
                'label'      => esc_html__( 'Padding Right', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%', 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .slick-list' => 'padding-right: {{SIZE}}{{UNIT}} !important;',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Helper Register Carousel Controls Style
     */
    protected function _register_carousel_arrows_dots_style(){
        /**
         * Arrow Sections
         */
        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'      => esc_html__( 'Carousel Arrows', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_prev',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_next_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'arrows_hover_style',
                'label'          => esc_html__( 'Arrows Style', 'lastudio' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow:hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_position',
            array(
                'label'     => esc_html__( 'Prev Arrow Position', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'prev_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio' ),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio' ),
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
                'label'      => esc_html__( 'Bottom Indent', 'lastudio' ),
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
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio' ),
                    'right' => esc_html__( 'Right', 'lastudio' ),
                ),
            )
        );

        $this->add_responsive_control(
            'prev_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio' ),
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
                'label'      => esc_html__( 'Right Indent', 'lastudio' ),
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
                'label'     => esc_html__( 'Next Arrow Position', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'next_vert_position',
            array(
                'label'   => esc_html__( 'Vertical Position by', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'    => esc_html__( 'Top', 'lastudio' ),
                    'bottom' => esc_html__( 'Bottom', 'lastudio' ),
                ),
            )
        );

        $this->add_responsive_control(
            'next_top_position',
            array(
                'label'      => esc_html__( 'Top Indent', 'lastudio' ),
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
                'label'      => esc_html__( 'Bottom Indent', 'lastudio' ),
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
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio' ),
                    'right' => esc_html__( 'Right', 'lastudio' ),
                ),
            )
        );

        $this->add_responsive_control(
            'next_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio' ),
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
                'label'      => esc_html__( 'Right Indent', 'lastudio' ),
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
                'label'      => esc_html__( 'Carousel Dots', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style',
                'label'          => esc_html__( 'Dots Style', 'lastudio' ),
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
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_hover',
                'label'          => esc_html__( 'Dots Style', 'lastudio' ),
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
                'label' => esc_html__( 'Active', 'lastudio' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'           => 'dots_style_active',
                'label'          => esc_html__( 'Dots Style', 'lastudio' ),
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots li.slick-active span',
                'exclude' => array(
                    'box_font_color',
                    'box_font_size',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'dots_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio' ),
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
                'label'      => esc_html__( 'Dots Box Margin', 'lastudio' ),
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
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-text-align-right',
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'justify-content: {{VALUE}};',
                )
            )
        );

        $this->end_controls_section();
    }

    protected function _generate_carousel_setting_json(){
        $settings = $this->get_settings();

        $json_data = '';

        if ( 'yes' !== $settings['carousel_enabled'] ) {
            return $json_data;
        }

        $is_rtl = is_rtl();

        $desktop_col = absint( $settings['columns'] );
        $laptop_col = absint( isset($settings['columns_laptop']) ? $settings['columns_laptop'] : 0 );
        $tablet_col = absint( $settings['columns_tablet'] );
        $mobile_extra_col = absint( isset($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : 0 );
        $mobile_col = absint( $settings['columns_mobile'] );

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

        $options = array(
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
            'nextArrow'      => lastudio_elementor_tools_get_carousel_arrow( [ 'next-arrow', 'slick-next' ], [ $settings['next_arrow'] ] ),
            'rtl' => $is_rtl,
            'extras' => array(
                'rows' => absint( isset($settings['number_slick_rows']) ? $settings['number_slick_rows'] : 1 ),
                'slidesPerRow' => absint( isset($settings['slides_per_row']) ? $settings['slides_per_row'] : 1 )
            )
        );

        if(isset($settings['center_mode']) && $settings['center_mode'] == 'yes'){
            $options['centerMode'] = true;
            $options['centerPadding'] = '0px';
        }

        if ( 1 === absint( $settings['columns'] ) ) {
            $options['fade'] = ( 'fade' === $settings['effect'] );
        }

        $json_data = htmlspecialchars( json_encode( $options ) );

        return $json_data;

    }

    protected function register_controls() {

        $grid_style = apply_filters(
            'LaStudioElement/products/control/grid_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio' ),
                '2' => esc_html__( 'Type-2', 'lastudio' )
            )
        );
        $masonry_style = apply_filters(
            'LaStudioElement/products/control/masonry_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio' ),
                '2' => esc_html__( 'Type-2', 'lastudio' )
            )
        );
        $list_style = apply_filters(
            'LaStudioElement/products/control/list_style',
            array(
                '1' => esc_html__( 'Type-1', 'lastudio' ),
                '2' => esc_html__( 'Type-2', 'lastudio' )
            )
        );

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Layout', 'lastudio' ),
            ]
        );

        $this->add_control(
            'layout',
            array(
                'label'     => esc_html__( 'Layout', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'render_type' => 'template',
                'options'   => [
                    'grid'      => __( 'Grid', 'plugin-domain' ),
                    'masonry'   => __( 'Masonry', 'plugin-domain' ),
                    'list'      => __( 'List', 'plugin-domain' ),
                ]
            )
        );

        $this->add_control(
            'grid_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $grid_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'grid'
                ]
            )
        );

        $this->add_control(
            'masonry_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $masonry_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'masonry'
                ]
            )
        );

        $this->add_control(
            'list_style',
            array(
                'label'     => esc_html__( 'Style', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => $list_style,
                'render_type' => 'template',
                'condition' => [
                    'layout' => 'list'
                ]
            )
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'lastudio' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 4,
                'render_type' => 'template',
                'condition' => [
                    'layout!' => 'list'
                ]
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => __( 'Limit', 'lastudio' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'default' => 4,
                'render_type' => 'template'
            ]
        );

        $this->add_control(
            'enable_ajax_load',
            [
                'label' => __( 'Enable Ajax Load', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'enable_custom_image_size',
            [
                'label' => __( 'Enable Custom Image Size', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio' ),
                'default'    => 'shop_catalog',
                'options'    => la_get_all_image_sizes(),
                'condition' => [
                    'enable_custom_image_size' => 'yes'
                ]
            )
        );

        $this->add_control(
            'disable_alt_image',
            [
                'label' => __( 'Disable Crossfade Image Effect', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label' => __( 'Pagination', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate_as_loadmore',
            [
                'label' => __( 'Use Load More', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loadmore_text',
            [
                'label' => __( 'Load More Text', 'lastudio' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'allow_order',
            [
                'label' => esc_html__( 'Allow Order', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                    'query_post_type' => 'current_query',
                ]
            ]
        );

        $this->add_control(
            'show_result_count',
            [
                'label' => esc_html__( 'Show Result Count', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                    'query_post_type' => 'current_query',
                ]
            ]
        );

        $this->end_controls_section();

        $this->register_query_controls();

        $this->_register_carousel_controls(
            [
                'layout' => ['grid','list']
            ]
        );

        $this->start_controls_section(
            'section_products_style',
            [
                'label' => __( 'Products', 'lastudio' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __( 'Columns Gap', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products' => 'margin-right: -{{SIZE}}{{UNIT}}; margin-left: -{{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} ul.products li.product' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __( 'Rows Gap', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products .product_item--inner' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_image_style',
            [
                'label' => __( 'Image', 'lastudio' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
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
			    'prefix_class' => 'active-object-fit active-object-fit-',
		    )
	    );

	    $this->add_responsive_control(
		    'custom_image_height',
		    array(
			    'label' => esc_html__( 'Image Height', 'lastudio' ),
			    'type'  => Controls_Manager::SLIDER,
			    'range' => array(
				    'px' => array(
					    'min' => 50,
					    'max' => 2000,
				    )
			    ),
			    'size_units' => ['px', '%', 'vh', 'vw'],
			    'default' => [
				    'size' => 100,
				    'unit' => '%'
			    ],
			    'selectors' => apply_filters('LaStudioElement/products/thumbnail_height_selector', array(
				    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit' => 'padding-bottom: {{SIZE}}{{UNIT}};'
			    )),
			    'condition' => [
				    'enable_custom_image_height!' => ''
			    ]
		    )
	    );

	    $this->add_control(
		    'image_pos',
		    array(
			    'type'       => 'select',
			    'label'      => esc_html__( 'Images Position', 'lastudio' ),
			    'default'    => 'center',
			    'options'    => [
			    	'center'    => esc_html__( 'Center', 'lastudio' ),
			    	'top'       => esc_html__( 'Top', 'lastudio' ),
			    	'bottom'    => esc_html__( 'Bottom', 'lastudio' ),
			    ],
			    'condition' => [
				    'enable_custom_image_height!' => ''
			    ],
			    'selectors' => apply_filters('LaStudioElement/products/thumbnail_height_selector', array(
				    '{{WRAPPER}} .woocommerce-loop-product__link .figure__object_fit > *' => 'object-position: {{VALUE}}; background-position: {{VALUE}}'
			    )),
		    )
	    );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--thumbnail',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label' => __( 'Title', 'lastudio' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .product_item--title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .product_item--title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_rating_style',
            [
                'label' => __( 'Rating', 'lastudio' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __( 'Star Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => __( 'Empty Star Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'star_size',
            [
                'label' => __( 'Star Size', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 4,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_spacing',
            [
                'label' => __( 'Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  ul.products li.product .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_price_style',
            [
                'label' => __( 'Price', 'lastudio' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price ins' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .price',
            ]
        );

        $this->add_control(
            'heading_old_price_style',
            [
                'label' => __( 'Regular Price', 'lastudio' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => __( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price del' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ul.products li.product .price del .amount' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'old_price_typography',
                'selector' => '{{WRAPPER}} ul.products li.product .price del  ',
            ]
        );

        $this->add_responsive_control(
            'price_spacing',
            [
                'label' => __( 'Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_button_style',
            [
                'label' => __( 'Button', 'lastudio' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'lastudio' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => __( 'Border Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_fz',
            [
                'label' => __( 'Font Size', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'lastudio' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}. ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name' => 'button_border',
                'exclude' => [ 'color' ],
                'selector' => '{{WRAPPER}} ul.products li.product .button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_control(
		    'heading_cat_style',
		    [
			    'label' => __( 'Category', 'lastudio' ),
			    'type' => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'cat_color',
		    [
			    'label' => __( 'Color', 'lastudio' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ul.products li.product .product_item--category-link' => 'color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'cat_typography',
			    'selector' => '{{WRAPPER}} ul.products li.product .product_item--category-link',
		    ]
	    );

	    $this->add_responsive_control(
		    'cat_spacing',
		    [
			    'label' => __( 'Spacing', 'lastudio' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px', 'em' ],
			    'range' => [
				    'em' => [
					    'min' => 0,
					    'max' => 5,
					    'step' => 0.1,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} ul.products li.product .product_item--category-link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_design_box',
            [
                'label' => __( 'Box', 'lastudio' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_border_width',
            [
                'label' => __( 'Border Width', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __( 'Padding', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'box_margin',
            [
                'label' => __( 'Margin', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->start_controls_tabs( 'box_style_tabs' );

        $this->start_controls_tab( 'classic_style_normal',
            [
                'label' => __( 'Normal', 'lastudio' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product'),
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color',
            [
                'label' => __( 'Border Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'classic_style_hover',
            [
                'label' => __( 'Hover', 'lastudio' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_hover',
                'selector' => apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover',
            ]
        );

        $this->add_control(
            'box_bg_color_hover',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color_hover',
            [
                'label' => __( 'Border Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
	                apply_filters('LaStudioElement/products/box_selector', '{{WRAPPER}} ul.products li.product') . ':hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

	    $this->start_controls_section(
		    'section_design_content',
		    [
			    'label' => __( 'Content Box', 'lastudio' ),
			    'tab' => Controls_Manager::TAB_STYLE,
		    ]
	    );

	    $this->add_responsive_control(
		    'content_alignment',
		    array(
			    'label'   => esc_html__( 'Alignment', 'lastudio'),
			    'type'    => Controls_Manager::CHOOSE,
			    'options' => array(
				    'left' => array(
					    'title' => esc_html__( 'Left', 'lastudio'),
					    'icon'  => 'eicon-text-align-left',
				    ),
				    'center' => array(
					    'title' => esc_html__( 'Center', 'lastudio'),
					    'icon'  => 'eicon-text-align-center',
				    ),
				    'right' => array(
					    'title' => esc_html__( 'Right', 'lastudio'),
					    'icon'  => 'eicon-text-align-right',
				    ),
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ul.products .product_item--info' => 'text-align: {{VALUE}};',
			    ),
		    )
	    );

	    $this->add_control(
		    'content_border_width',
		    [
			    'label' => __( 'Border Width', 'lastudio' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px' ],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 50,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} ul.products .product_item--info' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'content_border_radius',
		    [
			    'label' => __( 'Border Radius', 'lastudio' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px', '%' ],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 200,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} ul.products .product_item--info' => 'border-radius: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'content_padding',
		    [
			    'label' => __( 'Padding', 'lastudio' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em' ],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} ul.products .product_item--info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
			    ]
		    ]
	    );

	    $this->add_responsive_control(
		    'content_margin',
		    [
			    'label' => __( 'Margin', 'lastudio' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em' ],
			    'selectors' => [
				    '{{WRAPPER}} ul.products .product_item--info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
			    ]
		    ]
	    );

	    $this->start_controls_tabs( 'content_style_tabs' );

	    $this->start_controls_tab( 'content_style_normal',
		    [
			    'label' => __( 'Normal', 'lastudio' ),
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'content_shadow',
			    'selector' => '{{WRAPPER}} ul.products .product_item--info',
		    ]
	    );

	    $this->add_control(
		    'content_bg_color',
		    [
			    'label' => __( 'Background Color', 'lastudio' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ul.products .product_item--info' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'content_border_color',
		    [
			    'label' => __( 'Border Color', 'lastudio' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ul.products .product_item--info' => 'border-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab( 'content_style_hover',
		    [
			    'label' => __( 'Hover', 'lastudio' ),
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'content_shadow_hover',
			    'selector' => '{{WRAPPER}} ul.products li.product:hover .product_item--info',
		    ]
	    );

	    $this->add_control(
		    'content_bg_color_hover',
		    [
			    'label' => __( 'Background Color', 'lastudio' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ul.products li.product:hover .product_item--info' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'content_border_color_hover',
		    [
			    'label' => __( 'Border Color', 'lastudio' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} ul.products li.product:hover .product_item--info' => 'border-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->end_controls_tabs();

	    $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __( 'Pagination', 'lastudio' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __( 'Alignment', 'lastudio' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );


        $this->add_control(
            'pagination_spacing',
            [
                'label' => __( 'Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'show_pagination_border',
            [
                'label' => __( 'Border', 'lastudio' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'lastudio' ),
                'label_on' => __( 'Show', 'lastudio' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'pagination_border_color',
            [
                'label' => __( 'Border Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_pagination_border' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_padding',
            [
                'label' => __( 'Padding', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => [ 'em' ],
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
            ]
        );

        $this->start_controls_tabs( 'pagination_style_tabs' );

        $this->start_controls_tab( 'pagination_style_normal',
            [
                'label' => __( 'Normal', 'lastudio' ),
            ]
        );

        $this->add_control(
            'pagination_link_color',
            [
                'label' => __( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'pagination_style_hover',
            [
                'label' => __( 'Hover', 'lastudio' ),
            ]
        );

        $this->add_control(
            'pagination_link_color_hover',
            [
                'label' => __( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_hover',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'pagination_style_active',
            [
                'label' => __( 'Active', 'lastudio' ),
            ]
        );

        $this->add_control(
            'pagination_link_color_active',
            [
                'label' => __( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_active',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->_register_carousel_arrows_dots_style();

        $this->_register_masonry_custom_layout();
    }

    protected function _register_masonry_custom_layout(){

        $repeater = new Repeater();

        $repeater->add_control(
            'item_width',
            array(
                'label'   => esc_html__( 'Item Width', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 width',
                    '2' => '2 width',
                    '3' => '3 width',
                    '0-5' => '1/2 width'
                ),
                'dynamic' => array( 'active' => true )
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
                    '2' => '2 height',
                    '0-5' => '1/2 height',
                    '0-75' => '3/4 height'
                ),
                'dynamic' => array( 'active' => true )
            )
        );

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true )
            )
        );

        $this->start_controls_section(
            'section_masonry',
            array(
                'label' => esc_html__( 'Masonry Setting', 'lastudio' ),
                'condition' => [
                    'layout' => 'masonry'
                ]
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
                    'layout' => 'masonry'
                )
            )
        );

        $this->add_control(
            'masonry_container_width',
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
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_base_width',
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
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_base_height',
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
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->add_control(
            'masonry_item_sizes',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'prevent_empty' => false,
                'default'     => array(
                    array(
                        'item_title'  => esc_html__( 'Size 1x1', 'lastudio' ),
                        'item_width'  => 1,
                        'item_height'  => 1
                    ),
                    array(
                        'item_title'  => esc_html__( 'Size 2x2', 'lastudio' ),
                        'item_width'  => 2,
                        'item_height'  => 2
                    ),
                    array(
                        'item_title'  => esc_html__( 'Size 1x1', 'lastudio' ),
                        'item_width'  => 1,
                        'item_height'  => 1
                    ),
                    array(
                        'item_title'  => esc_html__( 'Size 1x1', 'lastudio' ),
                        'item_width'  => 1,
                        'item_height'  => 1
                    )
                ),
                'title_field' => '{{{ item_title }}}',
                'condition' => array(
                    'layout' => 'masonry',
                    'enable_custom_masonry_layout' => 'true'
                )
            )
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_heading_style',
            [
                'label' => esc_html__( 'Heading', 'lastudio' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'heading_border',
                'label' => esc_html__( 'Border', 'lastudio' ),
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'heading_text_shadow',
                'selector' => '{{WRAPPER}} .lakit-heading',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();

        $settings['in_elementor'] = ( \Elementor\Plugin::instance()->editor->is_edit_mode() || is_admin() || wp_doing_ajax()) ? true : false;

        if(self::$__called_item == $this->get_id()){
            self::$__called_index++;
        }
        else{
            self::$__called_item = $this->get_id();
        }

        $settings['el_id'] = self::$__called_index;

        if(\function_exists('wc_print_notices') && \WC()->session){
            \wc_print_notices();
        }

        // For Products_Renderer.
        if ( ! isset( $GLOBALS['post'] ) ) {
            $GLOBALS['post'] = null; // WPCS: override ok.
        }

        $settings['carousel_setting'] = $this->_generate_carousel_setting_json();
        $settings['unique_id'] = $this->get_id();
        if(!isset($settings['allow_order'])){
            $settings['allow_order'] = '';
        }
        if(!isset($settings['show_result_count'])){
            $settings['show_result_count'] = '';
        }

        if(!empty($_GET['per_page'])){
            $settings['limit'] = $_GET['per_page'];
        }

        $shortcode = new Products_Renderer( $settings, 'products' );

        $content = $shortcode->get_content();

        if ( $content ) {
            echo $content;
        }
        elseif ( $this->get_settings( 'nothing_found_message' ) ) {
            echo '<div class="elementor-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
        }

    }

    public function render_plain_content() {}
}
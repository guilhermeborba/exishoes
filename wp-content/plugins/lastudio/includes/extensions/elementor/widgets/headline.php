<?php
namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}


// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

/**
 * Headline Widget
 */
class Headline extends LA_Widget_Base {

    public function __construct($data = [], $args = null) {

        $this->add_style_depends( $this->get_name() . '-elm' );

        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'lastudio-headline';
    }

    protected function get_widget_title() {
        return esc_html__( 'Headline', 'lastudio' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-31';
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'LaStudioElement/headline/css-scheme',
            array(
                'instance'    => '.lastudio-headline',
                'first_part'  => '.lastudio-headline__first',
                'second_part' => '.lastudio-headline__second',
                'divider'     => '.lastudio-headline__divider',
            )
        );

        $this->start_controls_section(
            'section_title',
            array(
                'label' => esc_html__( 'Title', 'lastudio' ),
            )
        );

        $this->add_control(
            'first_part',
            array(
                'label'       => esc_html__( 'Title first part', 'lastudio' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter title first part', 'lastudio' ),
                'default'     => esc_html__( 'Heading', 'lastudio' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'second_part',
            array(
                'label'       => esc_html__( 'Title second part', 'lastudio' ),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter title second part', 'lastudio' ),
                'default'     => esc_html__( 'element', 'lastudio' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link', 'lastudio' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'default' => array(
                    'url' => '',
                ),
                'separator'   => 'before',
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'header_size',
            array(
                'label'   => esc_html__( 'HTML Tag', 'lastudio' ),
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
                'default' => 'h2',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_deco_elements',
            array(
                'label' => esc_html__( 'Decorative Elements', 'lastudio' ),
            )
        );

        $this->add_control(
            'before_deco_heading',
            array(
                'label' => esc_html__( 'Before Deco Element', 'lastudio' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'before_deco_type',
            array(
                'label'   => esc_html__( 'Before Deco Type', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'  => esc_html__( 'None', 'lastudio' ),
                    'icon'  => esc_html__( 'Icon', 'lastudio' ),
                    'image' => esc_html__( 'Image', 'lastudio' ),
                ),
            )
        );

        $this->add_control(
            'before_icon',
            array(
                'label'       => esc_html__( 'Before Icon', 'lastudio' ),
                'type'        => Controls_Manager::ICONS,
                'fa4compatibility' => 'none',
                'label_block' => true,
                'default' => [
                    'value' => 'far fa-star',
                    'library' => 'solid',
                ],
                'condition' => array(
                    'before_deco_type' => 'icon',
                ),
            )
        );

        $this->add_control(
            'before_image',
            array(
                'label'   => esc_html__( 'Before Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'condition' => array(
                    'before_deco_type' => 'image',
                ),
            )
        );

        $this->add_control(
            'after_deco_heading',
            array(
                'label'     => esc_html__( 'After Deco Element', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'after_deco_type',
            array(
                'label'   => esc_html__( 'After Deco Type', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'  => esc_html__( 'None', 'lastudio' ),
                    'icon'  => esc_html__( 'Icon', 'lastudio' ),
                    'image' => esc_html__( 'Image', 'lastudio' ),
                ),
            )
        );

        $this->add_control(
            'after_icon',
            array(
                'label'       => esc_html__( 'After Icon', 'lastudio' ),
                'type'        => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'label_block' => true,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => array(
                    'after_deco_type' => 'icon',
                ),
            )
        );

        $this->add_control(
            'after_image',
            array(
                'label'   => esc_html__( 'After Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'condition' => array(
                    'after_deco_type' => 'image',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'instance_direction',
            array(
                'label'   => esc_html__( 'Direction', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => array(
                    'horizontal' => esc_html__( 'Horizontal', 'lastudio' ),
                    'vertical'   => esc_html__( 'Vertical', 'lastudio' ),
                )
            )
        );

        $this->add_control(
            'use_space_between',
            array(
                'label'        => esc_html__( 'Space Between', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => array(
                    'instance_direction' => 'horizontal',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'f_g',
                'selector' => '{{WRAPPER}} .lastudio-headline__part',
            )
        );

        $this->add_control(
            'instance_alignment_horizontal',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['instance'] => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} '. $css_scheme['instance'] . ' > .lastudio-headline__link' => 'justify-content: {{VALUE}};',
                ),
                'condition' => array(
                    'instance_direction' => 'horizontal',
                ),
            )
        );

        $this->add_control(
            'instance_alignment_vertical',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['instance'] => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} '. $css_scheme['instance'] . ' > .lastudio-headline__link' => 'align-items: {{VALUE}};',
                ),
                'condition' => array(
                    'instance_direction' => 'vertical',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * First Part Style Section
         */
        $this->start_controls_section(
            'section_first_part_style',
            array(
                'label'      => esc_html__( 'First Part', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'first_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part']  . ' .lastudio-headline__label' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'f_t',
                'selector' => '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label',
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'f_tsd',
                'selector' => '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'f_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['first_part'],
            )
        );

        $this->add_control(
            'use_first_text_image',
            array(
                'label'        => esc_html__( 'Use Text Image', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            )
        );

        $this->add_control(
            'first_text_image',
            array(
                'label'   => esc_html__( 'Text Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'condition' => array(
                    'use_first_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label' => 'background-image: url({{URL}})',
                ),
            )
        );

        $this->add_control(
            'first_text_image_position',
            array(
                'label'   =>esc_html__( 'Background Position', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''              => esc_html__( 'Default', 'lastudio' ),
                    'top left'      => esc_html__( 'Top Left', 'lastudio' ),
                    'top center'    => esc_html__( 'Top Center', 'lastudio' ),
                    'top right'     => esc_html__( 'Top Right', 'lastudio' ),
                    'center left'   => esc_html__( 'Center Left', 'lastudio' ),
                    'center center' => esc_html__( 'Center Center', 'lastudio' ),
                    'center right'  => esc_html__( 'Center Right', 'lastudio' ),
                    'bottom left'   => esc_html__( 'Bottom Left', 'lastudio' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'lastudio' ),
                    'bottom right'  => esc_html__( 'Bottom Right', 'lastudio' ),
                ),
                'condition' => array(
                    'use_first_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label' => 'background-position: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'first_text_image_repeat',
            array(
                'label'   =>esc_html__( 'Background Repeat', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''          => esc_html__( 'Default', 'lastudio' ),
                    'no-repeat' => esc_html__( 'No-repeat', 'lastudio' ),
                    'repeat'    => esc_html__( 'Repeat', 'lastudio' ),
                    'repeat-x'  => esc_html__( 'Repeat-x', 'lastudio' ),
                    'repeat-y'  => esc_html__( 'Repeat-y', 'lastudio' ),
                ),
                'condition' => array(
                    'use_first_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label' => 'background-repeat: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'first_text_image_size',
            array(
                'label'   =>esc_html__( 'Background Size', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''        => esc_html__( 'Default', 'lastudio' ),
                    'auto'    => esc_html__( 'Auto', 'lastudio' ),
                    'cover'   => esc_html__( 'Cover', 'lastudio' ),
                    'contain' => esc_html__( 'Contain', 'lastudio' ),
                ),
                'condition' => array(
                    'use_first_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label' => 'background-size: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'f_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['first_part'],
                'separator'   => 'before',
            )
        );

        $this->add_responsive_control(
            'f_bdr',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'f_pd',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'f_mg',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'f_va',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Top', 'lastudio' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'condition' => array(
                    'instance_direction' => 'horizontal',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'f_ha',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'condition' => array(
                    'instance_direction' => 'vertical',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'f_ta',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__label' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Second Part Style Section
         */
        $this->start_controls_section(
            'section_second_part_style',
            array(
                'label'      => esc_html__( 'Second Part', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'second_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 's_t',
                'selector' => '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label',
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 's_tsd',
                'selector' => '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 's_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['second_part'],
            )
        );

        $this->add_control(
            'use_second_text_image',
            array(
                'label'        => esc_html__( 'Use Text Image', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
            )
        );

        $this->add_control(
            'second_text_image',
            array(
                'label'   => esc_html__( 'Text Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'condition' => array(
                    'use_second_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label' => 'background-image: url({{URL}});',
                ),
            )
        );

        $this->add_control(
            'second_text_image_position',
            array(
                'label'   =>esc_html__( 'Background Position', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''              => esc_html__( 'Default', 'lastudio' ),
                    'top left'      => esc_html__( 'Top Left', 'lastudio' ),
                    'top center'    => esc_html__( 'Top Center', 'lastudio' ),
                    'top right'     => esc_html__( 'Top Right', 'lastudio' ),
                    'center left'   => esc_html__( 'Center Left', 'lastudio' ),
                    'center center' => esc_html__( 'Center Center', 'lastudio' ),
                    'center right'  => esc_html__( 'Center Right', 'lastudio' ),
                    'bottom left'   => esc_html__( 'Bottom Left', 'lastudio' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'lastudio' ),
                    'bottom right'  => esc_html__( 'Bottom Right', 'lastudio' ),
                ),
                'condition' => array(
                    'use_second_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label' => 'background-position: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'second_text_image_repeat',
            array(
                'label'   =>esc_html__( 'Background Repeat', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''          => esc_html__( 'Default', 'lastudio' ),
                    'no-repeat' => esc_html__( 'No-repeat', 'lastudio' ),
                    'repeat'    => esc_html__( 'Repeat', 'lastudio' ),
                    'repeat-x'  => esc_html__( 'Repeat-x', 'lastudio' ),
                    'repeat-y'  => esc_html__( 'Repeat-y', 'lastudio' ),
                ),
                'condition' => array(
                    'use_second_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label' => 'background-repeat: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'second_text_image_size',
            array(
                'label'   =>esc_html__( 'Background Size', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => array(
                    ''        => esc_html__( 'Default', 'lastudio' ),
                    'auto'    => esc_html__( 'Auto', 'lastudio' ),
                    'cover'   => esc_html__( 'Cover', 'lastudio' ),
                    'contain' => esc_html__( 'Contain', 'lastudio' ),
                ),
                'condition' => array(
                    'use_second_text_image' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label' => 'background-size: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 's_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['second_part'],
                'separator'   => 'before',
            )
        );

        $this->add_responsive_control(
            's_bdr',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            's_pd',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            's_mg',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            's_va',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Top', 'lastudio' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'condition' => array(
                    'instance_direction' => 'horizontal',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            's_ha',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'condition' => array(
                    'instance_direction' => 'vertical',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            's_ta',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__label' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Decorative Style Section
         */
        $this->start_controls_section(
            'section_deco_style',
            array(
                'label'      => esc_html__( 'Decorative Elements', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'before_deco',
            array(
                'label' => esc_html__( 'Before Deco Element', 'lastudio' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => array(
                    'before_deco_type!' => 'none',
                ),
            )
        );

        $this->add_control(
            'before_icon_color',
            array(
                'label'     => esc_html__( 'Before Icon Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'before_deco_type' => 'icon',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__deco-icon i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'b_ics',
            array(
                'label'      => esc_html__( 'Before Icon Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'condition' => array(
                    'before_deco_type' => 'icon',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__deco-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'b_igws',
            array(
                'label'      => esc_html__( 'Before Image Width Size', 'lastudio' ),
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
                'condition' => array(
                    'before_deco_type' => 'image',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__deco-image' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'b_ighs',
            array(
                'label'      => esc_html__( 'Before Image Height Size', 'lastudio' ),
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
                'condition' => array(
                    'before_deco_type' => 'image',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__deco-image' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'b_d_mg',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__deco' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'before_deco_type!' => 'none',
                ),
            )
        );

        $this->add_responsive_control(
            'b_d_a',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Top', 'lastudio' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'condition' => array(
                    'before_deco_type!' => 'none',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .lastudio-headline__deco' => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'after_deco',
            array(
                'label'     => esc_html__( 'After Deco Element', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'after_deco_type!' => 'none',
                ),
            )
        );

        $this->add_control(
            'after_icon_color',
            array(
                'label'     => esc_html__( 'After Icon Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'after_deco_type' => 'icon',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__deco-icon i' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'a_ics',
            array(
                'label'      => esc_html__( 'After Icon Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 200,
                    ),
                ),
                'condition' => array(
                    'after_deco_type' => 'icon',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__deco-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'a_igws',
            array(
                'label'      => esc_html__( 'After Image Width Size', 'lastudio' ),
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
                'condition' => array(
                    'after_deco_type' => 'image',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__deco-image' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'a_ighs',
            array(
                'label'      => esc_html__( 'After Image Height Size', 'lastudio' ),
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
                'condition' => array(
                    'after_deco_type' => 'image',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__deco-image' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'a_d_mg',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__deco' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'after_deco_type!' => 'none',
                ),
            )
        );

        $this->add_responsive_control(
            'a_d_a',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Top', 'lastudio' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'condition' => array(
                    'after_deco_type!' => 'none',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .lastudio-headline__deco' => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'divider_deco',
            array(
                'label'     => esc_html__( 'Divider Deco Element', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'use_divider_deco',
            array(
                'label'        => esc_html__( 'Use Divider Mode', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => 'no',
            )
        );

        $this->add_responsive_control(
            'divider_deco_height',
            array(
                'label'   => esc_html__( 'Divider Height', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 2,
                'min'     => 1,
                'max'     => 50,
                'step'    => 1,
                'condition' => array(
                    'use_divider_deco' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['divider'] => 'height: {{VALUE}}px;',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_deco_w',
            [
                'label' => __( 'Divider Width', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => array(
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ),
                'selectors' => [
                    '{{WRAPPER}} '. $css_scheme['divider'] => 'flex: {{SIZE}}{{UNIT}} 0 0;',
                ],
                'condition' => [
                    'use_divider_deco' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'divider_deco_space',
            array(
                'label'   => esc_html__( 'Divider Space', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 10,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'condition' => array(
                    'use_divider_deco'   => 'yes',
                    'instance_direction' => 'horizontal',
                ),
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['divider'] . '.lastudio-headline__left-divider' => 'margin-right: {{VALUE}}px;',
                    '{{WRAPPER}} '. $css_scheme['divider'] . '.lastudio-headline__right-divider' => 'margin-left: {{VALUE}}px;',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_deco_divider' );

        $this->start_controls_tab(
            'tab_deco_divider_left',
            array(
                'label' => esc_html__( 'Left', 'lastudio' ),
                'condition' => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->add_control(
            'use_divider_deco_left',
            array(
                'label'        => esc_html__( 'Enable', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'dd_l_bg',
                'label'     => esc_html__( 'Background', 'lastudio' ),
                'selector'  => '{{WRAPPER}} ' . $css_scheme['divider'] . '.lastudio-headline__left-divider',
                'condition' => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'dd_l_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['divider'] . '.lastudio-headline__left-divider',
                'condition'   => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_deco_divider_right',
            array(
                'label' => esc_html__( 'Right', 'lastudio' ),
                'condition' => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->add_control(
            'use_divider_deco_right',
            array(
                'label'        => esc_html__( 'Enable', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'dd_r_bg',
                'label'     => esc_html__( 'Background', 'lastudio' ),
                'selector'  => '{{WRAPPER}} ' . $css_scheme['divider'] . '.lastudio-headline__right-divider',
                'condition' => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'dd_r_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['divider'] . '.lastudio-headline__right-divider',
                'condition'   => array(
                    'use_divider_deco' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( empty( $settings['first_part'] ) && empty( $settings['second_part'] ) ) {
            return;
        }

        $first_part = '';
        $second_part = '';
        $before_deco_html = '';
        $after_deco_html = '';
        $space = '';

        $heading_classes_array = array( 'lastudio-headline' );
        $heading_classes_array[] = 'lastudio-headline--direction-' . $settings['instance_direction'];

        $heading_classes = implode( ' ', $heading_classes_array );

        if ( filter_var( $settings['use_space_between'], FILTER_VALIDATE_BOOLEAN ) && 'horizontal' === $settings['instance_direction'] ) {
            $space = '<span class="lastudio-headline__space">&nbsp;</span>';
        }

        // Before Deco Render
        if ( 'none' !== $settings['before_deco_type'] ) {

            if ( 'icon' === $settings['before_deco_type'] && ! empty( $settings['before_icon'] ) ) {
                ob_start();
                Icons_Manager::render_icon( $settings['before_icon'], [ 'aria-hidden' => 'true' ] );
                $before_deco_icon = ob_get_clean();
                $before_deco_html = sprintf( '<span class="lastudio-headline__deco lastudio-headline__deco-icon">%1$s</span>', $before_deco_icon );
            }

            if ( 'image' === $settings['before_deco_type'] && ! empty( $settings['before_image']['url'] ) ) {
                $before_deco_image = sprintf( '<img src="%s" alt="">', apply_filters('lastudio_wp_get_attachment_image_url', $settings['before_image']['url']) );
                $before_deco_html = sprintf( '<span class="lastudio-headline__deco lastudio-headline__deco-image">%1$s</span>', $before_deco_image );
            }
        }

        // After Deco Render
        if ( 'none' !== $settings['after_deco_type'] ) {

            if ( 'icon' === $settings['after_deco_type'] && ! empty( $settings['after_icon'] ) ) {
                ob_start();
                Icons_Manager::render_icon( $settings['after_icon'], [ 'aria-hidden' => 'true' ] );
                $after_deco_icon = ob_get_clean();
                $after_deco_html = sprintf( '<span class="lastudio-headline__deco lastudio-headline__deco-icon">%1$s</span>', $after_deco_icon );
            }

            if ( 'image' === $settings['after_deco_type'] && ! empty( $settings['after_image']['url'] ) ) {
                $after_deco_image = sprintf( '<img src="%s" alt="">', apply_filters('lastudio_wp_get_attachment_image_url', $settings['after_image']['url']) );
                $after_deco_html = sprintf( '<span class="lastudio-headline__deco lastudio-headline__deco-image">%1$s</span>', $after_deco_image );
            }
        }

        if ( ! empty( $settings['first_part'] ) ) {

            $first_classes_array = array( 'lastudio-headline__part', 'lastudio-headline__first' );

            if ( filter_var( $settings['use_first_text_image'], FILTER_VALIDATE_BOOLEAN ) ) {
                $first_classes_array[] = 'headline__part--image-text';
            }

            $first_classes = implode( ' ', $first_classes_array );

            $first_part = sprintf( '<span class="%1$s">%2$s<span class="lastudio-headline__label">%3$s</span></span>%4$s', $first_classes, $before_deco_html, $settings['first_part'], $space );
        }

        if ( ! empty( $settings['second_part'] ) ) {
            $second_classes_array = array( 'lastudio-headline__part', 'lastudio-headline__second' );

            if ( filter_var( $settings['use_second_text_image'], FILTER_VALIDATE_BOOLEAN ) ) {
                $second_classes_array[] = 'headline__part--image-text';
            }

            $second_classes = implode( ' ', $second_classes_array );

            $second_part = sprintf( '<span class="%1$s"><span class="lastudio-headline__label">%2$s</span>%3$s</span>', $second_classes, $settings['second_part'], $after_deco_html );
        }

        $deco_devider_left = '';
        $deco_devider_right = '';

        if ( filter_var( $settings['use_divider_deco'], FILTER_VALIDATE_BOOLEAN ) ) {

            if ( filter_var( $settings['use_divider_deco_left'], FILTER_VALIDATE_BOOLEAN ) ) {
                $deco_devider_left ='<span class="lastudio-headline__divider lastudio-headline__left-divider"></span>';
            }

            if ( filter_var( $settings['use_divider_deco_right'], FILTER_VALIDATE_BOOLEAN ) ) {
                $deco_devider_right ='<span class="lastudio-headline__divider lastudio-headline__right-divider"></span>';
            }
        }

        $title = sprintf( '%1$s%2$s%3$s%4$s', $deco_devider_left, $first_part, $second_part, $deco_devider_right );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'url', 'href', $settings['link']['url'] );

            if ( $settings['link']['is_external'] ) {
                $this->add_render_attribute( 'url', 'target', '_blank' );
            }

            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->add_render_attribute( 'url', 'rel', 'nofollow' );
            }

            $title = sprintf( '<a class="lastudio-headline__link" %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
        }

        $title_html = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', $settings['header_size'], $heading_classes, $title );

        echo $title_html;
    }

}
<?php
namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;


/**
 * Dropbar Widget
 */
class Dropbar extends LA_Widget_Base {

    public function get_name() {
        return 'lastudio-dropbar';
    }

    protected function get_widget_title() {
        return esc_html__( 'Dropbar', 'lastudio' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-42';
    }

    public function get_script_depends() {
        return [
            'lastudio-dropbar-elm'
        ];
    }

    public function get_style_depends() {
        return [
            'lastudio-dropbar-elm'
        ];
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'LaStudioElement/dropbar/css-scheme',
            array(
                'dropbar'         => '.lastudio-dropbar',
                'inner'           => '.lastudio-dropbar__inner',
                'button'          => '.lastudio-dropbar__button',
                'button_icon'     => '.lastudio-dropbar__button-icon',
                'button_text'     => '.lastudio-dropbar__button-text',
                'content_wrapper' => '.lastudio-dropbar__content-wrapper',
                'content'         => '.lastudio-dropbar__content',
            )
        );

        /**
         * `Dropbar` Section
         */
        $this->start_controls_section(
            'section_dropbar_content',
            array(
                'label' => esc_html__( 'Dropbar', 'lastudio' ),
            )
        );

        $this->add_control(
            'button_heading',
            array(
                'label' => esc_html__( 'Button', 'lastudio' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'button_text',
            array(
                'label'   => esc_html__( 'Text', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Dropbar', 'lastudio' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'button_before_icon',
            array(
                'label' => esc_html__( 'Before icon', 'lastudio' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon'
            )
        );

        $this->add_control(
            'button_after_icon',
            array(
                'label' => esc_html__( 'After icon', 'lastudio' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon'
            )
        );

        $this->add_responsive_control(
            'button_align',
            array(
                'label' => esc_html__( 'Alignment', 'lastudio' ),
                'type'  => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon' => 'eicon-text-align-right',
                    ),
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio' ),
                        'icon' => 'eicon-text-align-justify',
                    ),
                ),
                'selectors_dictionary' => array(
                    'left'    => 'margin-left: 0; margin-right: auto; width: auto;',
                    'center'  => 'margin-left: auto; margin-right: auto; width: auto;',
                    'right'   => 'margin-left: auto; margin-right: 0; width: auto;',
                    'justify' => 'margin-left: 0; margin-right: 0; width: 100%;',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner'] => '{{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'content_heading',
            array(
                'label'     => esc_html__( 'Content', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'content_type',
            array(
                'label'   => esc_html__( 'Content Type', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'simple',
                'toggle'  => false,
                'options' => array(
                    'simple' => array(
                        'title' => esc_html__( 'Simple Text', 'lastudio' ),
                        'icon'  => 'eicon-edit',
                    ),
                    'template' => array(
                        'title' => esc_html__( 'Template', 'lastudio' ),
                        'icon'  => 'eicon-document-file',
                    ),
                ),
            )
        );

        $this->add_control(
            'simple_content',
            array(
                'label'   => esc_html__( 'Simple Text', 'lastudio' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Dropbar Content', 'lastudio' ),
                'condition' => array(
                    'content_type' => 'simple',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();

        $options = array(
            '0' => '— ' . esc_html__( 'Select', 'lastudio' ) . ' —',
        );

        $types = array();

        foreach ( $templates as $template ) {
            $options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            $types[ $template['template_id'] ] = $template['type'];
        }

        $this->add_control(
            'template_id',
            array(
                'label'       => esc_html__( 'Choose Template', 'lastudio' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '0',
                'options'     => $options,
                'types'       => $types,
                'label_block' => 'true',
                'condition' => array(
                    'content_type' => 'template',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Settings` Section
         */
        $this->start_controls_section(
            'section_dropbar_settings',
            array(
                'label' => esc_html__( 'Popup Settings', 'lastudio' ),
            )
        );

        $this->add_responsive_control(
            'position',
            array(
                'label'   => esc_html__( 'Position', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'bottom-left',
                'label_block' => true,
                'options' => array(
                    'top-left'      => esc_html__( 'Top Left', 'lastudio' ),
                    'top-center'    => esc_html__( 'Top Center', 'lastudio' ),
                    'top-right'     => esc_html__( 'Top Right', 'lastudio' ),
                    'bottom-left'   => esc_html__( 'Bottom Left', 'lastudio' ),
                    'bottom-center' => esc_html__( 'Bottom Center', 'lastudio' ),
                    'center-center' => esc_html__( 'Center Center', 'lastudio' ),
                    'bottom-right'  => esc_html__( 'Bottom Right', 'lastudio' ),
                    'left-top'      => esc_html__( 'Left Top', 'lastudio' ),
                    'left-center'   => esc_html__( 'Left Center', 'lastudio' ),
                    'left-bottom'   => esc_html__( 'Left Bottom', 'lastudio' ),
                    'right-top'     => esc_html__( 'Right Top', 'lastudio' ),
                    'right-center'  => esc_html__( 'Right Center', 'lastudio' ),
                    'right-bottom'  => esc_html__( 'Right Bottom', 'lastudio' ),
                ),
                'selectors_dictionary' => array(
                    'top-left'      => 'top: auto; bottom: 100%; left: 0; right: auto; transform: none;',
                    'top-center'    => 'top: auto; bottom: 100%; left: 50%; right: auto; transform: translateX(-50%);',
                    'top-right'     => 'top: auto; bottom: 100%; left: auto; right: 0; transform: none;',

                    'bottom-left'   => 'top: 100%; bottom: auto; left: 0; right: auto; transform: none;',
                    'bottom-center' => 'top: 100%; bottom: auto; left: 50%; right: auto; transform: translateX(-50%);',
                    'bottom-right'  => 'top: 100%; bottom: auto; left: auto; right: 0; transform: none;',

                    'left-top'      => 'top: 0; bottom: auto; left: auto; right: 100%; transform: none;',
                    'left-center'   => 'top: 50%; bottom: auto; left: auto; right: 100%; transform: translateY(-50%);',
                    'left-bottom'   => 'top: auto; bottom: 0; left: auto; right: 100%; transform: none;',

                    'right-top'     => 'top: 0; bottom: auto; left: 100%; right: auto; transform: none;',
                    'right-center'  => 'top: 50%; bottom: auto; left: 100%; right: auto; transform: translateY(-50%);',
                    'right-bottom'  => 'top: auto; bottom: 0; left: 100%; right: auto; transform: none;',

                    'center-center'   => 'top: 50%; left: 50%; transform: translate(-50%,-50%);',

                ),
                'prefix_class' => 'lastudio-dropbar%s-position-',
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrapper'] => '{{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'pp_fixed',
            array(
                'label'     => esc_html__( 'Popup Fixed Layout ?', 'lastudio' ),
                'type'      => Controls_Manager::SWITCHER,
                'prefix_class' => 'dropbar-pp-fixed-',
            )
        );

        $this->add_control(
            'mode',
            array(
                'label'   => esc_html__( 'Mode', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => array(
                    'hover' => esc_html__( 'Hover', 'lastudio' ),
                    'click' => esc_html__( 'Click', 'lastudio' ),
                ),
            )
        );

        $this->add_control(
            'hide_delay',
            array(
                'label'   => esc_html__( 'Hide Delay', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 500,
                'min'     => 0,
                'max'     => 5000,
                'condition' => array(
                    'mode' => 'hover',
                ),
            )
        );

        $this->add_control(
            'show_effect',
            array(
                'label'   => esc_html__( 'Show Effect', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'             => esc_html__( 'None', 'lastudio' ),
                    'fade'             => esc_html__( 'Fade', 'lastudio' ),
                    'zoom-in'          => esc_html__( 'Zoom In', 'lastudio' ),
                    'zoom-out'         => esc_html__( 'Zoom Out', 'lastudio' ),
                    'slide-up'         => esc_html__( 'Slide Up', 'lastudio' ),
                    'slide-down'       => esc_html__( 'Slide Down', 'lastudio' ),
                    'slide-left'       => esc_html__( 'Slide Left', 'lastudio' ),
                    'slide-right'      => esc_html__( 'Slide Right', 'lastudio' ),
                    'slide-up-big'     => esc_html__( 'Slide Up Big', 'lastudio' ),
                    'slide-down-big'   => esc_html__( 'Slide Down Big', 'lastudio' ),
                    'slide-left-big'   => esc_html__( 'Slide Left Big', 'lastudio' ),
                    'slide-right-big'  => esc_html__( 'Slide Right Big', 'lastudio' ),
                    'fall-perspective' => esc_html__( 'Fall Perspective', 'lastudio' ),
                    'flip-in-x'        => esc_html__( 'Flip In X', 'lastudio' ),
                    'flip-in-y'        => esc_html__( 'Flip In Y', 'lastudio' ),
                ),
            )
        );

        $this->add_control(
            'offset',
            array(
                'label' => esc_html__( 'Offset', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 10,
                ),
                'selectors' => array(
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-top-"] ' . $css_scheme['content_wrapper']    => 'margin: 0 0 {{SIZE}}{{UNIT}} 0;',
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-bottom-"] ' . $css_scheme['content_wrapper'] => 'margin: {{SIZE}}{{UNIT}} 0 0 0;',
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-left-"] ' . $css_scheme['content_wrapper']   => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-right-"] ' . $css_scheme['content_wrapper']  => 'margin: 0 0 0 {{SIZE}}{{UNIT}};',

                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-top-"] ' . $css_scheme['content_wrapper']    => 'margin: 0 0 {{SIZE}}{{UNIT}} 0;',
                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-bottom-"] ' . $css_scheme['content_wrapper'] => 'margin: {{SIZE}}{{UNIT}} 0 0 0;',
                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-left-"] ' . $css_scheme['content_wrapper']   => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-right-"] ' . $css_scheme['content_wrapper']  => 'margin: 0 0 0 {{SIZE}}{{UNIT}};',

                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-top-"] ' . $css_scheme['content_wrapper']    => 'margin: 0 0 {{SIZE}}{{UNIT}} 0;',
                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-bottom-"] ' . $css_scheme['content_wrapper'] => 'margin: {{SIZE}}{{UNIT}} 0 0 0;',
                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-left-"] ' . $css_scheme['content_wrapper']   => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-right-"] ' . $css_scheme['content_wrapper']  => 'margin: 0 0 0 {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}}[class*="lastudio-dropbar-position-top-"] ' . $css_scheme['content_wrapper'] . ':before'    => 'top: 100%; bottom: auto; left: 0; right: 0; height: {{SIZE}}{{UNIT}}; width: 100%;',
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-bottom-"] ' . $css_scheme['content_wrapper'] . ':before' => 'top: auto; bottom: 100%; left: 0; right: 0; height: {{SIZE}}{{UNIT}}; width: 100%;',
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-left-"] ' . $css_scheme['content_wrapper'] . ':before'   => 'top: 0; bottom: 0; left: 100%; right: auto; height: 100%; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}[class*="lastudio-dropbar-position-right-"] ' . $css_scheme['content_wrapper'] . ':before'  => 'top: 0; bottom: 0; left: auto; right: 100%; height: 100%; width: {{SIZE}}{{UNIT}};',

                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-top-"] ' . $css_scheme['content_wrapper'] . ':before'    => 'top: 100%; bottom: auto; left: 0; right: 0; height: {{SIZE}}{{UNIT}}; width: 100%;',
                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-bottom-"] ' . $css_scheme['content_wrapper'] . ':before' => 'top: auto; bottom: 100%; left: 0; right: 0; height: {{SIZE}}{{UNIT}}; width: 100%;',
                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-left-"] ' . $css_scheme['content_wrapper'] . ':before'   => 'top: 0; bottom: 0; left: 100%; right: auto; height: 100%; width: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}[class*="lastudio-dropbar-tablet-position-right-"] ' . $css_scheme['content_wrapper'] . ':before'  => 'top: 0; bottom: 0; left: auto; right: 100%; height: 100%; width: {{SIZE}}{{UNIT}};',

                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-top-"] ' . $css_scheme['content_wrapper'] . ':before'    => 'top: 100%; bottom: auto; left: 0; right: 0; height: {{SIZE}}{{UNIT}}; width: 100%;',
                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-bottom-"] ' . $css_scheme['content_wrapper'] . ':before' => 'top: auto; bottom: 100%; left: 0; right: 0; height: {{SIZE}}{{UNIT}}; width: 100%;',
                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-left-"] ' . $css_scheme['content_wrapper'] . ':before'   => 'top: 0; bottom: 0; left: 100%; right: auto; height: 100%; width: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}[class*="lastudio-dropbar-mobile-position-right-"] ' . $css_scheme['content_wrapper'] . ':before'  => 'top: 0; bottom: 0; left: auto; right: 100%; height: 100%; width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_width',
            array(
                'label' => esc_html__( 'Width', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrapper'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'fixed',
            array(
                'label'     => esc_html__( 'Element Fixed Layout', 'lastudio' ),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'fixed_position',
            array(
                'label' => esc_html__( 'Fixed Position', 'lastudio' ),
                'type'  => Controls_Manager::SELECT,
                'default' => 'top-left',
                'options' => array(
                    'top-left'      => esc_html__( 'Top Left', 'lastudio' ),
                    'top-center'    => esc_html__( 'Top Center', 'lastudio' ),
                    'top-right'     => esc_html__( 'Top Right', 'lastudio' ),
                    'center-left'   => esc_html__( 'Center Left', 'lastudio' ),
                    'center-center' => esc_html__( 'Center Center', 'lastudio' ),
                    'center-right'  => esc_html__( 'Center Right', 'lastudio' ),
                    'bottom-left'   => esc_html__( 'Bottom Left', 'lastudio' ),
                    'bottom-center' => esc_html__( 'Bottom Center', 'lastudio' ),
                    'bottom-right'  => esc_html__( 'Bottom Right', 'lastudio' ),
                ),
                'condition' => array(
                    'fixed' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'fixed_gap',
            array(
                'label'      => esc_html__( 'Gap', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['dropbar'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'fixed' => 'yes',
                ),
            )
        );

        $this->add_control(
            'fixed_z_index',
            array(
                'label' => esc_html__( 'Z-index', 'lastudio' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['dropbar'] => 'z-index: {{VALUE}};',
                ),
                'condition' => array(
                    'fixed' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Button` Style Section
         */
        $this->start_controls_section(
            'section_button_style',
            array(
                'label' => esc_html__( 'Button', 'lastudio' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button_text'],
            )
        );

        $this->add_responsive_control(
            'button_icon_font_size',
            array(
                'label' => esc_html__( 'Icon Font Size', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'button_before_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                        array(
                            'name'     => 'button_after_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                    ),
                ),
            )
        );

        $this->add_control(
            'button_icon_spacing',
            array(
                'label' => esc_html__( 'Icon Spacing', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['button_icon'] . '--before:not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} ' . $css_scheme['button_icon'] . '--before:not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
                    'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['button_icon'] . '--after:not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}} ' . $css_scheme['button_icon'] . '--after:not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'button_before_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                        array(
                            'name'     => 'button_after_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                    ),
                ),
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
                'name'     => 'button_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_control(
            'button_color',
            array(
                'label' => esc_html__( 'Text Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_icon_color',
            array(
                'label' => esc_html__( 'Icon Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'color: {{VALUE}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'button_before_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                        array(
                            'name'     => 'button_after_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                    ),
                ),
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
                'name'     => 'button_background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_control(
            'button_color_hover',
            array(
                'label' => esc_html__( 'Text Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_icon_color_hover',
            array(
                'label' => esc_html__( 'Icon Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_icon'] => 'color: {{VALUE}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'button_before_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                        array(
                            'name'     => 'button_after_icon',
                            'operator' => '!==',
                            'value'    => '',
                        ),
                    ),
                ),
            )
        );

        $this->add_control(
            'button_border_color_hover',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'button_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_control(
            'button_hover_animation',
            array(
                'label' => esc_html__( 'Hover Animation', 'lastudio' ),
                'type'  => Controls_Manager::HOVER_ANIMATION,
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
                'separator' => 'before',
            )
        );

        $this->add_control(
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

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Content` Style Section
         */
        $this->start_controls_section(
            'section_content_style',
            array(
                'label' => esc_html__( 'Content', 'lastudio' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            )
        );

        $this->add_responsive_control(
            'content_align',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
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
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio' ),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label' => esc_html__( 'Text Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'content_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            )
        );

        $this->add_control(
            'content_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'content_z_index',
            array(
                'label' => esc_html__( 'Z-index', 'lastudio' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrapper'] => 'z-index: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

    }

    protected function render() {
        $this->__context = 'render';
        $this->__open_wrap();

        include $this->__get_global_template( 'index' );

        $this->__close_wrap();
    }

    public function get_dropbar_content() {
        $settings = $this->get_settings_for_display();
        $content  = '';

        $content_type = $settings['content_type'];

        switch ( $content_type ) :
            case 'simple':
                $content = $settings['simple_content'];
                break;

            case 'template':
                $template_id = $settings['template_id'];

                if ( '0' !== $template_id ) {
                    $content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );

                    if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
                        $edit_url = add_query_arg(
                            array(
                                'elementor' => '',
                            ),
                            get_permalink( $template_id )
                        );

                        $edit_link = sprintf( '<a class="lastudio-dropbar-edit-link" href="%s" title="%s" target="_blank"><i class="eicon-edit"></i></a>', esc_url( $edit_url ), esc_html__( 'Edit Template', 'lastudio' ) );

                        $content .= $edit_link;
                    }
                }

                break;

        endswitch;

        return $content;
    }

    public function get_dropbar_export_settings() {
        $settings = $this->get_settings_for_display();

        $allowed = apply_filters( 'LaStudioElement/dropbar/export-settings', array(
            'mode',
            'hide_delay',
        ) );

        $result = array();

        foreach ( $allowed as $setting ) {
            $result[ $setting ] = isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
        }

        return json_encode( $result );
    }

}
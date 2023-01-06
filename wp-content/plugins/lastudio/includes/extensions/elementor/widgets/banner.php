<?php

namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}

// If this file is called directly, abort.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

class Banner extends LA_Widget_Base
{

    public function __construct($data = [], $args = null) {

        $this->add_style_depends( $this->get_name() . '-elm' );

        parent::__construct($data, $args);
    }

    public function get_icon() {
        return 'lastudioelements-icon-10';
    }

    public function get_name() {
        return 'lastudio-banner';
    }

    protected function get_widget_title(){
        return __( 'Banner', 'lastudio' );
    }

    protected function register_controls() {

        $hover_effect = apply_filters(
            'LaStudioElement/banner/hover_effect',
            array(
                'none'   => esc_html__( 'None', 'lastudio' ),
                'lily'   => esc_html__( 'Lily', 'lastudio' ),
                'sadie'  => esc_html__( 'Sadie', 'lastudio' ),
                'layla'  => esc_html__( 'Layla', 'lastudio' ),
                'oscar'  => esc_html__( 'Oscar', 'lastudio' ),
                'marley' => esc_html__( 'Marley', 'lastudio' ),
                'ruby'   => esc_html__( 'Ruby', 'lastudio' ),
                'roxy'   => esc_html__( 'Roxy', 'lastudio' ),
                'bubba'  => esc_html__( 'Bubba', 'lastudio' ),
                'romeo'  => esc_html__( 'Romeo', 'lastudio' ),
                'sarah'  => esc_html__( 'Sarah', 'lastudio' ),
                'chico'  => esc_html__( 'Chico', 'lastudio' )
            )
        );

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio' ),
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Image Size', 'lastudio' ),
                'default'    => 'full',
                'options'    => la_get_all_image_sizes(),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'   => esc_html__( 'Sub Title', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'title_tag',
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
            )
        );

        $this->add_control(
            'text',
            array(
                'label'   => esc_html__( 'Description', 'lastudio' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'btn_text',
            array(
                'label'   => esc_html__( 'Item Button Text', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            )
        );

        $this->add_control(
            'link',
            array(
                'label'   => esc_html__( 'Link', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
            )
        );

        $this->add_control(
            'link_target',
            array(
                'label'        => esc_html__( 'Open link in new window', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => '_blank',
                'condition'    => array(
                    'link!' => '',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio' ),
            )
        );

        $this->add_control(
            'custom_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => ''
            )
        );

        $this->add_responsive_control(
            'height',
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
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'default' => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-banner' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lastudio-banner img' => 'position: absolute; width: 100%; height: 100%; left: 0; top: 0; object-fit: cover'
                ),
                'render_type' => 'template',
                'condition' => [
                    'custom_height!' => ''
                ]
            )
        );

        $this->add_control(
            'effect',
            array(
                'label'   => esc_html__( 'Animation Effect', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => $hover_effect
            )
        );

        $this->end_controls_section();

        $css_scheme = apply_filters(
            'LaStudioElement/banner/css-scheme',
            array(
                'banner'         => '.lastudio-banner',
                'banner_content' => '.lastudio-banner__content',
                'banner_content_inner' => '.lastudio-banner__content-wrap',
                'banner_overlay' => '.lastudio-banner__overlay',
                'banner_title'   => '.lastudio-banner__title',
                'banner_subtitle'   => '.lastudio-banner__subtitle',
                'banner_text'    => '.lastudio-banner__text',
                'banner_button'  => '.lastudio-banner__button',
            )
        );

        $this->start_controls_section(
            's_item_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_background' );

        $this->start_controls_tab(
            'tbn',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'aec_n',
            array(
                'label'     => esc_html__( 'Additional Elements Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-effect-layla ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-layla ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-oscar ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-marley ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-ruby ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-roxy ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-roxy ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-bubba ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-bubba ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-romeo ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-romeo ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-sarah ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-chico ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                ),
            )
        );

	    $this->add_control(
		    'bg_n',
		    array(
			    'label'     => esc_html__( 'Overlay Background Color', 'lastudio' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['banner_overlay'] => 'background-color: {{VALUE}};',
			    )
		    )
	    );

	    $this->add_group_control(
            Group_Control_Background::get_type(),
		    [
		        'name'      => 'bg_gn',
                'label'     => esc_html__( 'Overlay Background Gradient', 'lastudio' ),
                'types'     => ['gradient'],
                'selector'  => '{{WRAPPER}} ' . $css_scheme['banner_overlay'],
                'fields_options' => array(
                    'background' => array(
                        'label'  => _x( 'Overlay Gradient?', 'Background Control', 'lastudio' )
                    )
                )
            ]
	    );

        $this->add_control(
            'o_n',
            array(
                'label'   => esc_html__( 'Opacity', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
                ),
            )
        );

	    $this->add_responsive_control(
		    'br_n',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['banner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tbh',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'aec_h',
            array(
                'label'     => esc_html__( 'Additional Elements Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-effect-layla:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-layla:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-oscar:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-marley:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-ruby:hover ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-roxy:hover ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-roxy:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-bubba:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-bubba:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-romeo:hover ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-romeo:hover ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-sarah:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lastudio-effect-chico:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
                ),
            )
        );


	    $this->add_control(
		    'bg_h',
		    array(
			    'label'     => esc_html__( 'Overlay Background Color', 'lastudio' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'] => 'background-color: {{VALUE}};'
			    )
		    )
	    );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'bg_h_gn',
                'label'     => esc_html__( 'Overlay Background Gradient', 'lastudio' ),
                'types'     => ['gradient'],
                'selector'  =>  '{{WRAPPER}} ' .$css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'],
                'fields_options' => array(
                    'background' => array(
                        'label'  => _x( 'Overlay Gradient?', 'Background Control', 'lastudio' )
                    )
                )
            ]
        );

        $this->add_control(
            'o_h',
            array(
                'label'   => esc_html__( 'Opacity', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '0.4',
                'min'     => 0,
                'max'     => 1,
                'step'    => 0.1,
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
                ),
            )
        );

	    $this->add_responsive_control(
		    'br_h',
		    array(
			    'label'      => esc_html__( 'Border Radius', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['banner'] .':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();

	    $this->add_responsive_control(
		    'shape_bd',
		    array(
			    'label'      => esc_html__( 'Shape Border Width', 'lastudio' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array(
				    'px'
			    ),
			    'range'      => array(
				    'px' => array(
					    'min' => 0,
					    'max' => 100,
				    )
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['banner'] => '--shape-bd-width: {{SIZE}}{{UNIT}};',
			    ),
			    'conditions' => [
				    'terms' => [
					    [
						    'name' => 'effect',
						    'operator' => 'in',
						    'value' => ['layla', 'oscar', 'marley', 'ruby', 'roxy', 'bubba', 'romeo', 'sarah', 'chico']
					    ]
				    ]
			    ],
		    )
	    );
	    $this->add_control(
		    'shape_color',
		    array(
			    'label'     => esc_html__( 'Shape Color', 'lastudio' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['banner'] => '--shape-bd-color: {{VALUE}};',
			    ),
			    'conditions' => [
				    'terms' => [
					    [
						    'name' => 'effect',
						    'operator' => 'in',
						    'value' => ['layla', 'oscar', 'marley', 'ruby', 'roxy', 'bubba', 'romeo', 'sarah', 'chico']
					    ]
				    ]
			    ],
		    )
	    );
	    $this->add_responsive_control(
		    'shape_pos',
		    array(
			    'label'      => __( 'Shape Position', 'lastudio' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['banner'] => '--shape-pos-top: {{TOP}}{{UNIT}};--shape-pos-right:{{RIGHT}}{{UNIT}};--shape-pos-bottom:{{BOTTOM}}{{UNIT}};--shape-pos-left:{{LEFT}}{{UNIT}};',
			    ),
			    'conditions' => [
				    'terms' => [
					    [
						    'name' => 'effect',
						    'operator' => 'in',
						    'value' => ['layla', 'oscar', 'marley', 'ruby', 'roxy', 'bubba', 'romeo', 'sarah', 'chico']
					    ]
				    ]
			    ],
		    )
	    );

	    $this->add_control(
		    'bch',
		    array(
			    'label'      => esc_html__( 'Banner Content', 'lastudio' ),
			    'type'       => Controls_Manager::HEADING,
			    'separator'  => 'before',
		    )
	    );

        $this->add_responsive_control(
            'pd',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'mg',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['banner_content'],
            )
        );

        $this->add_control(
            'br',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'bsd',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_content'],
            )
        );

        $this->add_responsive_control(
            'b_w',
            array(
                'label'      => esc_html__( 'Width', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_content_inner'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'ha',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['banner_content'] => 'display: flex;justify-content: {{VALUE}};',
                ),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'effect',
                            'operator' => '!in',
                            'value' => ['sadie', 'marley', 'romeo']
                        ]
                    ]
                ]
            )
        );

        $this->add_responsive_control(
            'va',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Start', 'lastudio' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'End', 'lastudio' ),
                        'icon'  => 'eicon-v-align-bottom',
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} '. $css_scheme['banner_content'] => 'display: flex;align-items: {{VALUE}};',
                ),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'effect',
                            'operator' => '!in',
                            'value' => ['sadie', 'marley', 'romeo']
                        ]
                    ]
                ]
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            's_title_style',
            array(
                'label'      => esc_html__( 'Title Typography', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_c',
            array(
                'label'     => esc_html__( 'Title Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'title_bgc',
            array(
                'label'     => esc_html__( 'Title BG Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'background-color: {{VALUE}}',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_t',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_title'],
            )
        );

        $this->add_responsive_control(
            'title_mg',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_pd',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'title_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['banner_title'],
            )
        );

        $this->add_responsive_control(
            'title_bdr',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'title_a',
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
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_ha',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_title'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            's_subtitle_style',
            array(
                'label'      => esc_html__( 'Sub Title Typography', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'subtitle_c',
            array(
                'label'     => esc_html__( 'Sub Title Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'subtitle_bgc',
            array(
                'label'     => esc_html__( 'Sub Title BG Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'background-color: {{VALUE}}',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_t',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_subtitle'],
            )
        );

        $this->add_responsive_control(
            'subtitle_mg',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'subtitle_pd',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'subtitle_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['banner_subtitle'],
            )
        );

        $this->add_responsive_control(
            'subtitle_bdr',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'subtitle_a',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'    => array(
                        'subtitle' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'subtitle' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'subtitle' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'subtitle_ha',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_subtitle'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            's_text_style',
            array(
                'label'      => esc_html__( 'Description Typography', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'text_c',
            array(
                'label'     => esc_html__( 'Description Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'text_bg',
            array(
                'label'     => esc_html__( 'Description BG Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_t',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_text'],
            )
        );

        $this->add_responsive_control(
            'text_mg',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'text_pd',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'text_bdr',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'text_a',
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
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'text_ha',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_text'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Submit Button Style Section
         */
        $this->start_controls_section(
            's_button_style',
            array(
                'label'      => esc_html__( 'Banner Button', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );


        $this->add_responsive_control(
            'btn_w',
            array(
                'label'      => esc_html__( 'Width', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'btn_a',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_button']  => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_btn_style' );

        $this->start_controls_tab(
            'tab_btn_n',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'btn_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_button'],
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
            'btn_c',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'btn_t',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['banner_button'],
            )
        );

        $this->add_responsive_control(
            'btn_pd',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'btn_mg',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'btn_bdr',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['banner_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'btn_bd',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['banner_button'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'btn_bsd',
                'selector' => '{{WRAPPER}} ' . $css_scheme['banner_button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $button_hover_selector = '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_button'];
        if(lastudio_get_theme_support('lastudio-kit::banner')){
            $button_hover_selector = '{{WRAPPER}} ' . $css_scheme['banner_button']. ':hover';
        }

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'btn_bg_h',
                'selector' => $button_hover_selector,
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
            'btn_c_h',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $button_hover_selector => 'color: {{VALUE}}'
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'btn_t_h',
                'selector' => $button_hover_selector,
            )
        );

        $this->add_responsive_control(
            'btn_pd_h',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    $button_hover_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'btn_mg_h',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    $button_hover_selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'btn_bdr_h',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors'  => array(
                    $button_hover_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'btn_bd_h',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => $button_hover_selector
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'btn_bsd_h',
                'selector' => $button_hover_selector
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            's_order_style',
            array(
                'label'      => esc_html__( 'Content Order', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_o',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '',
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['banner_title'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'subtitle_o',
            array(
                'label'   => esc_html__( 'Sub Title Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '',
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['banner_subtitle'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );


        $this->add_control(
            'text_o',
            array(
                'label'   => esc_html__( 'Description Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '',
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['banner_text'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'btn_o',
            array(
                'label'   => esc_html__( 'Button Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '',
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['banner_button'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
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

    public function __get_banner_image() {

        $image_item = $this->get_settings_for_display( 'image' );

        if ( empty( $image_item['id'] ) && empty( $image_item['url'] ) ) {
            return;
        }

        if ( ! empty( $image_item['id'] ) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], 'full' );

            $params[0] = apply_filters('lastudio_wp_get_attachment_image_url', $image_data[0]);
            $params[1] = $image_data[1];
            $params[2] = $image_data[2];
        }
        else {
            $params[0] = $image_item['url'];
            $params[1] = 600;
            $params[2] = 400;
        }

	    $giflazy = $this->get_gif_img_for_lazy();

        $srcset = sprintf('width="%d" height="%d" srcset="%s"', $params[1], $params[2], $giflazy);

        return sprintf( apply_filters('LaStudioElement/banner/image-format', '<img src="%1$s" data-src="%2$s" alt="" loading="lazy" class="la-lazyload-image %3$s" %4$s>'), $giflazy, $params[0], 'lastudio-banner__img' , $srcset);
    }

    public function __get_banner_image_src() {

        $image = $this->get_settings_for_display( 'image' );

        if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
            return;
        }

        if ( empty( $image['id'] ) ) {
            return $image['url'];
        }

        $size = $this->get_settings_for_display( 'image_size' );

        if ( ! $size ) {
            $size = 'full';
        }

        $image_url = wp_get_attachment_image_url( $image['id'], $size );

        return apply_filters('lastudio_wp_get_attachment_image_url', $image_url);
    }
}
<?php

namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

class Weather extends LA_Widget_Base {

    public $weather_data = array();

    private $api_count = '3.0';

    private $current_weather_api_url = 'https://api.weatherbit.io/v2.0/current';

    private $forecast_weather_api_url = 'https://api.weatherbit.io/v2.0/forecast/daily';

    public function get_name() {
        return 'lastudio-weather';
    }

    protected function get_widget_title() {
        return esc_html__( 'Weather', 'lastudio' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-43';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'LaStudioElement/weather/css-scheme',
            array(
                'title'                 => '.lastudio-weather__title',
                'current_container'     => '.lastudio-weather__current',
                'current_temp'          => '.lastudio-weather__current-temp',
                'current_icon'          => '.lastudio-weather__current-icon .lastudio-weather-icon',
                'current_desc'          => '.lastudio-weather__current-desc',
                'current_details'       => '.lastudio-weather__details',
                'current_details_item'  => '.lastudio-weather__details-item',
                'current_details_icon'  => '.lastudio-weather__details-item .lastudio-weather-icon',
                'current_day'           => '.lastudio-weather__current-day',
                'forecast_container'    => '.lastudio-weather__forecast',
                'forecast_item'         => '.lastudio-weather__forecast-item',
                'forecast_day'          => '.lastudio-weather__forecast-day',
                'forecast_icon'         => '.lastudio-weather__forecast-icon .lastudio-weather-icon',
            )
        );

        $this->start_controls_section(
            'section_weather',
            array(
                'label' => esc_html__( 'Weather', 'lastudio' ),
            )
        );

        $key = $this->get_api_key();

        if ( ! $key ) {

            $this->add_control(
                'set_api_key',
                array(
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => sprintf(
                        esc_html__( 'Please set Weather API key before using this widget. You can create own API key  %1$s. Paste created key on %2$s', 'lastudio' ),
                        '<a target="_blank" href="https://www.weatherbit.io/">' . esc_html__( 'here', 'lastudio' ) . '</a>',
                        '<a target="_blank" href="' . admin_url('themes.php?page=theme_options#tab=50') . '">' . esc_html__( 'settings page', 'lastudio' ) . '</a>'
                    )
                )
            );
        }

        $this->add_control(
            'location',
            array(
                'label'       => esc_html__( 'Location', 'lastudio' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array( 'active' => true, ),
                'placeholder' => esc_html__( 'London, United Kingdom', 'lastudio' ),
                'default'     => esc_html__( 'London, United Kingdom', 'lastudio' ),
            )
        );

        $this->add_control(
            'units',
            array(
                'label'   => esc_html__( 'Units', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'metric',
                'options' => array(
                    'metric'   => esc_html__( 'Metric', 'lastudio' ),
                    'imperial' => esc_html__( 'Imperial', 'lastudio' ),
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
            'show_title',
            array(
                'label'        => esc_html__( 'Show title', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'      => 'true',
            )
        );

        $this->add_control(
            'title_size',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'  => esc_html__( 'H1', 'lastudio' ),
                    'h2'  => esc_html__( 'H2', 'lastudio' ),
                    'h3'  => esc_html__( 'H3', 'lastudio' ),
                    'h4'  => esc_html__( 'H4', 'lastudio' ),
                    'h5'  => esc_html__( 'H5', 'lastudio' ),
                    'h6'  => esc_html__( 'H6', 'lastudio' ),
                    'div' => esc_html__( 'div', 'lastudio' ),
                ),
                'default'   => 'h3',
                'condition' => array(
                    'show_title' => 'true',
                ),
            )
        );

        $this->add_control(
            'show_country_name',
            array(
                'label'        => esc_html__( 'Show country name', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'      => '',
                'condition'    => array(
                    'show_title' => 'true',
                ),
            )
        );

        $this->add_control(
            'show_current_weather',
            array(
                'label'        => esc_html__( 'Show current weather', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default'      => 'true',
                'separator'    => 'before',
            )
        );

        $this->add_control(
            'show_current_weather_details',
            array(
                'label'        => esc_html__( 'Show current weather details', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'condition'    => array(
                    'show_current_weather' => 'true',
                ),
            )
        );

        $this->add_control(
            'show_forecast_weather',
            array(
                'label'        => esc_html__( 'Show forecast weather', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'separator'    => 'before',
            )
        );

        $this->add_control(
            'forecast_count',
            array(
                'label' => esc_html__( 'Number of forecast days', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 5,
                    ),
                ),
                'default' => array(
                    'size' => 5,
                ),
                'condition' => array(
                    'show_forecast_weather' => 'true',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            array(
                'label'     => esc_html__( 'Title', 'lastudio' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_title' => 'true',
                ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}};',
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
            'title_align',
            array(
                'label' => esc_html__( 'Alignment', 'lastudio' ),
                'type'  => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'lastudio' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio' ),
                        'icon'  => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'title_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'title_text_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_current_style',
            array(
                'label'     => esc_html__( 'Current Weather', 'lastudio' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_current_weather' => 'true',
                ),
            )
        );

        $this->add_control(
            'current_container_heading',
            array(
                'label' => esc_html__( 'Container', 'lastudio' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_responsive_control(
            'current_container_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'current_container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'current_container_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_container'],
            )
        );

        $this->add_control(
            'current_temp_heading',
            array(
                'label'     => esc_html__( 'Temperature', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_temp_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_temp'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_temp_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_temp'],
            )
        );

        $this->add_control(
            'current_icon_heading',
            array(
                'label'     => esc_html__( 'Icon', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_icon'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'current_icon_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'current_desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_desc_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_desc'],
            )
        );

        $this->add_control(
            'current_desc_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_desc'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_current_details_style',
            array(
                'label'     => esc_html__( 'Details Weather', 'lastudio' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_current_weather'         => 'true',
                    'show_current_weather_details' => 'true',
                ),
            )
        );

        $this->add_control(
            'current_details_container_heading',
            array(
                'label' => esc_html__( 'Container', 'lastudio' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'current_details_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'current_details_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'current_details_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
            )
        );

        $this->add_control(
            'current_details_items_heading',
            array(
                'label'     => esc_html__( 'Items', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_details_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_details_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'current_day_typography',
                'label'    => esc_html__( 'Day typography', 'lastudio' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['current_day'],
            )
        );

        $this->add_control(
            'current_details_item_gap',
            array(
                'label' => esc_html__( 'Gap', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details_item'] . ' + ' . $css_scheme['current_details_item'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'current_details_icon_heading',
            array(
                'label'     => esc_html__( 'Icon', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'current_details_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'current_details_icon_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_forecast_style',
            array(
                'label'     => esc_html__( 'Forecast Weather', 'lastudio' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_forecast_weather' => 'true',
                ),
            )
        );

        $this->add_control(
            'forecast_container_heading',
            array(
                'label' => esc_html__( 'Container', 'lastudio' ),
                'type'  => Controls_Manager::HEADING,
            )
        );

        $this->add_responsive_control(
            'forecast_container_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'forecast_container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'forecast_container_border',
                'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_container'],
            )
        );

        $this->add_control(
            'forecast_item_heading',
            array(
                'label'     => esc_html__( 'Items', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'forecast_item_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'forecast_item_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_item'],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'forecast_day_typography',
                'label'    => esc_html__( 'Day typography', 'lastudio' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_day'],
            )
        );

        $this->add_responsive_control(
            'forecast_item_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'forecast_item_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'forecast_item_divider',
            array(
                'label'        => esc_html__( 'Divider', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'forecast_item_divider_style',
            array(
                'label' => esc_html__( 'Style', 'lastudio' ),
                'type'  => Controls_Manager::SELECT,
                'options' => array(
                    'solid'  => esc_html__( 'Solid', 'lastudio' ),
                    'double' => esc_html__( 'Double', 'lastudio' ),
                    'dotted' => esc_html__( 'Dotted', 'lastudio' ),
                    'dashed' => esc_html__( 'Dashed', 'lastudio' ),
                ),
                'default' => 'solid',
                'condition' => array(
                    'forecast_item_divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-style: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'forecast_item_divider_weight',
            array(
                'label'   => esc_html__( 'Weight', 'lastudio' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 20,
                    ),
                ),
                'condition' => array(
                    'forecast_item_divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'forecast_item_divider_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'forecast_item_divider' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'forecast_icon_heading',
            array(
                'label'     => esc_html__( 'Icon', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'forecast_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'forecast_icon_size',
            array(
                'label'      => esc_html__( 'Font Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $this->__context = 'render';

        $this->__open_wrap();

        $this->weather_data = $this->get_weather_data();

        if ( ! empty( $this->weather_data ) ) {
            include $this->__get_global_template( 'index' );
        }

        $this->__close_wrap();
    }

    /**
     * Get weather data.
     *
     * @return array|bool|mixed
     */
    public function get_weather_data() {

        $api_key = $this->get_api_key();

        // Do nothing if api key not provided
        if ( ! $api_key ) {
            $message = esc_html__( 'Please set Weather API key before using this widget.', 'lastudio' );

            echo $this->get_weather_notice( $message );
            return false;
        }

        $settings = $this->get_settings_for_display();
        $location = trim( $settings['location'] );

        if ( empty( $location ) ) {
            return false;
        }

        $units = $this->get_units_param( $settings['units'] );

        $transient_key = sprintf( 'lastudio-weather-data-%1$s-%2$s', $this->api_count, md5( $location . $units ) );

        $data = get_transient( $transient_key );

        if ( ! $data ) {
            // Prepare request data
            $location = esc_attr( $location );
            $api_key  = esc_attr( $api_key );

            $request_args = array(
                'key'   => urlencode( $api_key ),
                'units' => urlencode( $units ),
                'city'  => urlencode( $location ),
            );

            $current_request_url = add_query_arg(
                $request_args,
                $this->current_weather_api_url
            );

            $current_request_data = $this->__get_request_data( $current_request_url );

            if ( ! $current_request_data ) {
                $message = esc_html__( 'Weather data of this location not found.', 'lastudio' );

                echo $this->get_weather_notice( $message );

                return false;
            }

            if ( isset( $current_request_data['error'] ) ) {

                echo $this->get_weather_notice( $current_request_data['error'] );

                return false;
            }

            $request_args['days'] = 8;

            $forecast_request_url = add_query_arg(
                $request_args,
                $this->forecast_weather_api_url
            );

            $forecast_request_data = $this->__get_request_data( $forecast_request_url );

            if ( isset( $forecast_request_data['error'] ) ) {

                echo $this->get_weather_notice( $forecast_request_data['error'] );

                return false;
            }

            $data = $this->prepare_weather_data( $current_request_data, $forecast_request_data );

            if ( empty( $data ) ) {
                return false;
            }

            set_transient( $transient_key, $data, apply_filters( 'LaStudioElement/weather/cached-time', HOUR_IN_SECONDS ) );
        }

        return $data;
    }

    /**
     * Get units param for request args.
     *
     * @param string $unit
     *
     * @return string
     */
    public function get_units_param( $unit ) {

        if ( 'imperial' === $unit ) {
            return 'I';
        }

        return 'M';
    }

    /**
     * Get request data.
     *
     * @param string $url Request url.
     *
     * @return array|bool
     */
    public function __get_request_data( $url ) {

        $response = wp_remote_get( $url, array( 'timeout' => 30 ) );

        if ( ! $response || is_wp_error( $response ) ) {
            return false;
        }

        $data = wp_remote_retrieve_body( $response );

        if ( ! $data || is_wp_error( $data ) ) {
            return false;
        }

        $data = json_decode( $data, true );

        if ( empty( $data ) ) {
            return false;
        }

        return $data;
    }

    /**
     * Prepare weather data.
     *
     * @param array $current_data  Current weather data.
     * @param array $forecast_data Forecast weather data.
     *
     * @return array
     */
    public function prepare_weather_data( $current_data = array(), $forecast_data = array() ) {

        $data = array(
            // Location data
            'location' => array(
                'city'    => $current_data['data'][0]['city_name'],
                'country' => $current_data['data'][0]['country_code'],
            ),

            // Current data
            'current' => array(
                'code'       => $current_data['data'][0]['weather']['code'],
                'is_day'     => 'd' === $current_data['data'][0]['pod'],
                'temp'       => $current_data['data'][0]['temp'],
                'temp_min'   => $forecast_data['data'][0]['min_temp'],
                'temp_max'   => $forecast_data['data'][0]['max_temp'],
                'wind_speed' => $current_data['data'][0]['wind_spd'],
                'wind_deg'   => $current_data['data'][0]['wind_dir'],
                'wind_dir'   => $current_data['data'][0]['wind_cdir'],
                'humidity'   => $current_data['data'][0]['rh'] . '%',
                'pressure'   => $current_data['data'][0]['pres'],
                'sunrise'    => $current_data['data'][0]['sunrise'],
                'sunset'     => $current_data['data'][0]['sunset'],
            ),

            // Forecast data
            'forecast' => array(),
        );

        for ( $i = 0; $i < 8; $i ++ ) {
            $data['forecast'][] = array(
                'code'     => $forecast_data['data'][ $i ]['weather']['code'],
                'date'     => $forecast_data['data'][ $i ]['valid_date'],
                'temp_min' => $forecast_data['data'][ $i ]['min_temp'],
                'temp_max' => $forecast_data['data'][ $i ]['max_temp'],
            );
        }

        return $data;
    }

    /**
     * Get weather conditions by weather code.
     *
     * @param int    $code      Weather code.
     * @param string $condition Weather condition: 'desc' or 'icon'.
     * @param bool   $is_day    Is day.
     *
     * @return array|bool|string|int
     */
    public function get_weather_conditions( $code = null, $condition = null, $is_day = true ) {

        $conditions = apply_filters( 'LaStudioElement/weather/conditions', array(
            // Thunderstorm
            '200' => array(
                'desc' => esc_html_x( 'Thunderstorm with light rain', 'Weather description', 'lastudio' ),
                'icon' => 1,
            ),
            '201' => array(
                'desc' => esc_html_x( 'Thunderstorm with rain', 'Weather description', 'lastudio' ),
                'icon' => 1,
            ),
            '202' => array(
                'desc' => esc_html_x( 'Thunderstorm with heavy rain', 'Weather description', 'lastudio' ),
                'icon' => 1,
            ),
            '230' => array(
                'desc' => esc_html_x( 'Thunderstorm with light drizzle', 'Weather description', 'lastudio' ),
                'icon' => 2,
            ),
            '231' => array(
                'desc' => esc_html_x( 'Thunderstorm with drizzle', 'Weather description', 'lastudio' ),
                'icon' => 2,
            ),
            '232' => array(
                'desc' => esc_html_x( 'Thunderstorm with heavy drizzle', 'Weather description', 'lastudio' ),
                'icon' => 2,
            ),
            '233' => array(
                'desc' => esc_html_x( 'Thunderstorm with Hail', 'Weather description', 'lastudio' ),
                'icon' => 2,
            ),

            // Drizzle
            '300' => array(
                'desc' => esc_html_x( 'Light Drizzle', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
            '301' => array(
                'desc' => esc_html_x( 'Drizzle', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
            '302' => array(
                'desc' => esc_html_x( 'Heavy Drizzle', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),

            // Rain
            '500' => array(
                'desc' => esc_html_x( 'Light Rain', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
            '501' => array(
                'desc' => esc_html_x( 'Moderate Rain', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
            '502' => array(
                'desc' => esc_html_x( 'Heavy Rain', 'Weather description', 'lastudio' ),
                'icon' => 4,
            ),
            '511' => array(
                'desc' => esc_html_x( 'Freezing rain', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
            '520' => array(
                'desc' => esc_html_x( 'Light shower rain', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
            '521' => array(
                'desc' => esc_html_x( 'Shower rain', 'Weather description', 'lastudio' ),
                'icon' => 4,
            ),
            '522' => array(
                'desc' => esc_html_x( 'Heavy shower rain', 'Weather description', 'lastudio' ),
                'icon' => 5,
            ),

            // Snow
            '600' => array(
                'desc' => esc_html_x( 'Light snow', 'Weather description', 'lastudio' ),
                'icon' => $is_day ? 6 : 7,
            ),
            '601' => array(
                'desc' => esc_html_x( 'Snow', 'Weather description', 'lastudio' ),
                'icon' => 7,
            ),
            '602' => array(
                'desc' => esc_html_x( 'Heavy Snow', 'Weather description', 'lastudio' ),
                'icon' => 8,
            ),
            '610' => array(
                'desc' => esc_html_x( 'Mix snow/rain', 'Weather description', 'lastudio' ),
                'icon' => 9,
            ),
            '611' => array(
                'desc' => esc_html_x( 'Sleet', 'Weather description', 'lastudio' ),
                'icon' => 10,
            ),
            '612' => array(
                'desc' => esc_html_x( 'Heavy sleet', 'Weather description', 'lastudio' ),
                'icon' => 10,
            ),
            '621' => array(
                'desc' => esc_html_x( 'Snow shower', 'Weather description', 'lastudio' ),
                'icon' => 7,
            ),
            '622' => array(
                'desc' => esc_html_x( 'Heavy snow shower', 'Weather description', 'lastudio' ),
                'icon' => 11,
            ),
            '623' => array(
                'desc' => esc_html_x( 'Flurries', 'Weather description', 'lastudio' ),
                'icon' => 7,
            ),

            // Special
            '700' => array(
                'desc' => esc_html_x( 'Mist', 'Weather description', 'lastudio' ),
                'icon' => 12,
            ),
            '711' => array(
                'desc' => esc_html_x( 'Smoke', 'Weather description', 'lastudio' ),
                'icon' => 13,
            ),
            '721' => array(
                'desc' => esc_html_x( 'Haze', 'Weather description', 'lastudio' ),
                'icon' => 12,
            ),
            '731' => array(
                'desc' => esc_html_x( 'Sand/dust', 'Weather description', 'lastudio' ),
                'icon' => 14,
            ),
            '741' => array(
                'desc' => esc_html_x( 'Fog', 'Weather description', 'lastudio' ),
                'icon' => 15,
            ),
            '751' => array(
                'desc' => esc_html_x( 'Freezing Fog', 'Weather description', 'lastudio' ),
                'icon' => 15,
            ),

            // Clouds
            '800' => array(
                'desc' => esc_html_x( 'Clear sky', 'Weather description', 'lastudio' ),
                'icon' => $is_day ? 16 : 17,
            ),
            '801' => array(
                'desc' => esc_html_x( 'Few clouds', 'Weather description', 'lastudio' ),
                'icon' => $is_day ? 18 : 17,
            ),
            '802' => array(
                'desc' => esc_html_x( 'Scattered clouds', 'Weather description', 'lastudio' ),
                'icon' => $is_day ? 18 : 17,
            ),
            '803' => array(
                'desc' => esc_html_x( 'Broken clouds', 'Weather description', 'lastudio' ),
                'icon' => 19,
            ),
            '804' => array(
                'desc' => esc_html_x( 'Overcast clouds', 'Weather description', 'lastudio' ),
                'icon' => 19,
            ),
            '900' => array(
                'desc' => esc_html_x( 'Unknown Precipitation', 'Weather description', 'lastudio' ),
                'icon' => 3,
            ),
        ) );

        if ( ! $code ) {
            return $conditions;
        }

        $code_key = (string) $code;

        if ( ! isset( $conditions[ $code_key ] ) ) {
            return false;
        }

        if ( $condition && isset( $conditions[ $code_key ][ $condition ] ) ) {
            return $conditions[ $code_key ][ $condition ];
        }

        return $conditions[ $code_key ];
    }

    /**
     * Get weather description.
     *
     * @param int  $code   Weather code.
     * @param bool $is_day Is day.
     *
     * @return string
     */
    public function get_weather_desc( $code, $is_day = true ) {

        if ( ! $code ) {
            return '';
        }

        $desc = $this->get_weather_conditions( $code, 'desc', $is_day );

        if ( empty( $desc ) ) {
            return '';
        }

        return $desc;
    }

    /**
     * Get week day from date.
     *
     * @param string $date Date.
     *
     * @return bool|string
     */
    public function get_week_day_from_date( $date = '' ) {
        return date_i18n( 'l', strtotime( $date ) );
    }

    /**
     * Get title html markup.
     *
     * @return string
     */
    public function get_weather_title() {
        $settings   = $this->get_settings_for_display();
        $show_title = isset( $settings['show_title'] ) ? $settings['show_title'] : 'true';

        if ( ! filter_var( $show_title, FILTER_VALIDATE_BOOLEAN ) ) {
            return '';
        }

        $type = isset( $settings['title_type'] ) ? $settings['title_type'] : 'api';
        $tag  = isset( $settings['title_size'] ) ? $settings['title_size'] : 'h3';

        switch ( $type ) {
            case 'location':
                $title = esc_html( $settings['location'] );
                break;

            case 'custom':
                $title = esc_html( $settings['custom_title'] );
                break;

            default:
                $title = $this->weather_data['location']['city'];
        }

        if ( isset( $settings['show_country_name'] ) && 'true' === $settings['show_country_name'] ) {
            $country = $this->weather_data['location']['country'];

            $title = sprintf( '%1$s, %2$s', $title, $country );
        }

        return sprintf( '<%1$s class="lastudio-weather__title">%2$s</%1$s>', $tag, $title );
    }

    /**
     * Get temperature html markup.
     *
     * @param int|array $temp Temperature value.
     *
     * @return string
     */
    public function get_weather_temp( $temp ) {
        $units     = $this->get_settings_for_display( 'units' );
        $temp_unit = ( 'metric' === $units ) ? '&#176;C' : '&#176;F';

        // For 2.0 API Count
        if ( is_array( $temp ) ) {
            $temp = ( 'metric' === $units ) ? $temp['c'] : $temp['f'];
        }

        $format = apply_filters( 'LaStudioElement/weather/temperature-format', '%1$s%2$s' );

        return sprintf( $format, round( $temp ), $temp_unit );
    }

    /**
     * Get wind.
     *
     * @param int|array  $speed Wind speed.
     * @param int|string $deg   Wind direction, degrees.
     *
     * @return string
     */
    public function get_wind( $speed, $deg ) {
        $units      = $this->get_settings_for_display( 'units' );
        $speed_unit = ( 'metric' === $units ) ? esc_html_x( 'm/s', 'Unit of speed (meters/second)', 'lastudio' ) : esc_html_x( 'mph', 'Unit of speed (miles per hour)', 'lastudio' );

        // For 2.0 API Count
        if ( is_array( $speed ) ) {
            $speed = ( 'metric' === $units ) ? $speed['kph'] : $speed['mph'];
        }

        $direction = '';

        if ( ! is_numeric( $deg ) ) {
            $direction = $deg;
        } else {
            if ( ( $deg >= 0 && $deg <= 11.25 ) || ( $deg > 348.75 && $deg <= 360 ) ) {
                $direction = esc_html_x( 'N', 'Wind direction', 'lastudio' );
            } else if ( $deg > 11.25 && $deg <= 33.75 ) {
                $direction = esc_html_x( 'NNE', 'Wind direction', 'lastudio' );
            } else if ( $deg > 33.75 && $deg <= 56.25 ) {
                $direction = esc_html_x( 'NE', 'Wind direction', 'lastudio' );
            } else if ( $deg > 56.25 && $deg <= 78.75 ) {
                $direction = esc_html_x( 'ENE', 'Wind direction', 'lastudio' );
            } else if ( $deg > 78.75 && $deg <= 101.25 ) {
                $direction = esc_html_x( 'E', 'Wind direction', 'lastudio' );
            } else if ( $deg > 101.25 && $deg <= 123.75 ) {
                $direction = esc_html_x( 'ESE', 'Wind direction', 'lastudio' );
            } else if ( $deg > 123.75 && $deg <= 146.25 ) {
                $direction = esc_html_x( 'SE', 'Wind direction', 'lastudio' );
            } else if ( $deg > 146.25 && $deg <= 168.75 ) {
                $direction = esc_html_x( 'SSE', 'Wind direction', 'lastudio' );
            } else if ( $deg > 168.75 && $deg <= 191.25 ) {
                $direction = esc_html_x( 'S', 'Wind direction', 'lastudio' );
            } else if ( $deg > 191.25 && $deg <= 213.75 ) {
                $direction = esc_html_x( 'SSW', 'Wind direction', 'lastudio' );
            } else if ( $deg > 213.75 && $deg <= 236.25 ) {
                $direction = esc_html_x( 'SW', 'Wind direction', 'lastudio' );
            } else if ( $deg > 236.25 && $deg <= 258.75 ) {
                $direction = esc_html_x( 'WSW', 'Wind direction', 'lastudio' );
            } else if ( $deg > 258.75 && $deg <= 281.25 ) {
                $direction = esc_html_x( 'W', 'Wind direction', 'lastudio' );
            } else if ( $deg > 281.25 && $deg <= 303.75 ) {
                $direction = esc_html_x( 'WNW', 'Wind direction', 'lastudio' );
            } else if ( $deg > 303.75 && $deg <= 326.25 ) {
                $direction = esc_html_x( 'NW', 'Wind direction', 'lastudio' );
            } else if ( $deg > 326.25 && $deg <= 348.75 ) {
                $direction = esc_html_x( 'NNW', 'Wind direction', 'lastudio' );
            }
        }

        $format = apply_filters( 'LaStudioElement/weather/wind-format', '%1$s %2$s %3$s' );

        return sprintf( $format, $direction, round( $speed ), $speed_unit );
    }

    /**
     * Get weather pressure.
     *
     * @param int|array $pressure Pressure value.
     *
     * @return string
     */
    public function get_weather_pressure( $pressure ) {
        $units = $this->get_settings_for_display( 'units' );

        // For 2.0 API Count
        if ( is_array( $pressure ) ) {
            $pressure = ( 'metric' === $units ) ? $pressure['mb'] : $pressure['in'];
        }

        $format = apply_filters( 'LaStudioElement/weather/pressure-format', '%s' );

        return sprintf( $format, round( $pressure ) );
    }

    /**
     * Get weather astro time.
     *
     * @param  string $time
     * @return string
     */
    public function get_weather_astro_time( $time ) {
        $format = $this->get_settings_for_display( 'time_format' );

        if ( '12' === $format ) {
            $time = date( 'h:i A', strtotime( $time ) );
        }

        return $time;
    }

    /**
     * Get weather notice html markup.
     *
     * @param string $message Message.
     *
     * @return string
     */
    public function get_weather_notice( $message ) {
        return sprintf( '<div class="lastudio-weather-notice">%s</div>', $message );
    }

    /**
     * Get weather svg icon.
     *
     * @param string|int $icon            Icon slug or weather code.
     * @param bool       $is_weather_code Is weather code.
     * @param bool       $is_day          Is day.
     *
     * @return bool|string
     */
    public function get_weather_svg_icon( $icon, $is_weather_code = false, $is_day = true ) {

        if ( ! $icon ) {
            return false;
        }

        if ( $is_weather_code ) {
            $icon = $this->get_weather_conditions( $icon, 'icon', $is_day );
        }

        $icon_path = LASTUDIO_PLUGIN_PATH . "public/element/js/lib/weather-icons/{$icon}.svg";

        if ( ! file_exists( $icon_path ) ) {
            return false;
        }

        ob_start();

        include $icon_path;

        $svg = ob_get_clean();

        $_classes   = array();
        $_classes[] = 'lastudio-weather-icon';
        $_classes[] = sprintf( 'lastudio-weather-icon-%s', esc_attr( $icon ) );

        $classes = join( ' ', $_classes );

        return sprintf( '<div class="%2$s">%1$s</div>', $svg, $classes );
    }

    protected function get_api_key(){
        return apply_filters('LaStudioElement/weather/api', '');
    }

}
<?php
namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}


use LaStudio_Element\Controls\Group_Control_Box_Style;
use LaStudio_Element\Controls\Group_Control_Query;
use LaStudio_Element\Classes\Query_Control as Module_Query;
use Elementor\Repeater;
use Elementor\Group_Control_Css_Filter;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

/**
 * Team_Member Widget
 */
class Team_Member extends LA_Widget_Base {

    private $_query = null;

    public $item_counter = 0;

    public function get_name() {
        return 'lastudio-team-member';
    }

    protected function get_widget_title() {
        return esc_html__( 'Team Member', 'lastudio' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-25';
    }

	public function get_style_depends() {
        return ['lastudio-team-member-elm'];
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'LaStudioElement/team-member/css-scheme',
            array(
                'wrap'              => '.lastudio-team-member',
                'column'            => '.lastudio-team-member__item',
                'inner-box'         => '.lastudio-team-member__inner-box',
                'inner-content'     => '.lastudio-team-member__content',
                'image_wrap'        => '.lastudio-team-member__image',
                'image_instance'    => '.lastudio-team-member__image-instance',
                'title'             => '.lastudio-team-member__name',
                'desc'              => '.lastudio-team-member__desc',
                'position'          => '.lastudio-team-member__position',
                'socials'           => '.lastudio-team-member__socials',
                'slick_list'        => '.lastudio-team-member .slick-list'
            )
        );

        $preset_type = apply_filters(
            'LaStudioElement/team-member/control/preset',
            array(
                'type-1' => esc_html__( 'Type-1', 'lastudio' ),
                'type-2' => esc_html__( 'Type-2', 'lastudio' ),
                'type-3' => esc_html__( 'Type-3', 'lastudio' )
            )
        );
        /** Data Source section */
        $this->start_controls_section(
            'section_data_source',
            array(
                'label' => esc_html__( 'Data Source', 'lastudio' ),
            )
        );

        $this->add_control(
            'data_source',
            array(
                'label'     => esc_html__( 'Data Source', 'lastudio' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'post_type',
                'options'   => [
                    'post_type' => __( 'Member Post Type', 'lastudio' ),
                    'custom' => __( 'Custom', 'lastudio' )
                ]
            )
        );

        $repeater = new Repeater();
        $repeater->start_controls_tabs( 'items_repeater' );
        $repeater->start_controls_tab( 'general', [ 'label' => __( 'General', 'lastudio' ) ] );
        $repeater->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true )
            )
        );
        $repeater->add_control(
            'name',
            [
                'label' => __( 'Name', 'lastudio' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Member #1', 'lastudio' ),
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'role',
            [
                'label' => __( 'Role', 'lastudio' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Role', 'lastudio' ),
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'lastudio' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'lastudio' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio' ),
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab( 'socials', [ 'label' => __( 'Social', 'lastudio' ) ] );

        $repeater->add_control(
            's_icon_1',
            array(
                'label'       => esc_html__( 'Item Icon 1', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon()
            )
        );
        $repeater->add_control(
            's_link_1',
            [
                'label' => __( 'Item Link 1', 'lastudio' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio' )
            ]
        );
        $repeater->add_control(
            's_icon_2',
            array(
                'label'       => esc_html__( 'Item Icon 2', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_2',
            [
                'label' => __( 'Item Link 2', 'lastudio' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio' )
            ]
        );
        $repeater->add_control(
            's_icon_3',
            array(
                'label'       => esc_html__( 'Item Icon 3', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_3',
            [
                'label' => __( 'Item Link 3', 'lastudio' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio' )
            ]
        );
        $repeater->add_control(
            's_icon_4',
            array(
                'label'       => esc_html__( 'Item Icon 4', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_4',
            [
                'label' => __( 'Item Link 4', 'lastudio' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio' )
            ]
        );
        $repeater->add_control(
            's_icon_5',
            array(
                'label'       => esc_html__( 'Item Icon 5', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_5',
            [
                'label' => __( 'Item Link 5', 'lastudio' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio' )
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label' => __( 'Custom Member List', 'lastudio' ),
                'type' => Controls_Manager::REPEATER,
                'show_label' => true,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'name' => __( 'Leila Henry', 'lastudio' ),
                        'role' => __( 'Creative Director', 'lastudio' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'lastudio' ),
                        's_icon_1' => 'lastudioicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'lastudioicon-b-twitter',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ],
                    [
                        'name' => __( 'Leila Henry', 'lastudio' ),
                        'role' => __( 'Creative Director', 'lastudio' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'lastudio' ),
                        's_icon_1' => 'lastudioicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'lastudioicon-b-twitter',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ],
                    [
                        'name' => __( 'Leila Henry', 'lastudio' ),
                        'role' => __( 'Creative Director', 'lastudio' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'lastudio' ),
                        's_icon_1' => 'lastudioicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'lastudioicon-b-twitter',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ]
                ],
                'condition' => [
                    'data_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_section();

        /** Layout section */
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Layout', 'lastudio' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => array(
                    'grid'    => esc_html__( 'Grid', 'lastudio' )
                ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-1',
                'options' => $preset_type
            )
        );

        $this->add_control(
            'thumb_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Featured Image Size', 'lastudio' ),
                'default'    => 'full',
                'options'    => la_get_all_image_sizes()
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => lastudio_elementor_tools_get_select_range( 6 )
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
                'default' => 'h4',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'show_role',
            array(
                'label'        => esc_html__( 'Show Role/Position?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_social',
            array(
                'label'        => esc_html__( 'Show Social?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_excerpt',
            array(
                'label'        => esc_html__( 'Show Excerpt?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'excerpt_length',
            array(
                'label'   => esc_html__( 'Custom Excerpt Length', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 20,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'condition' => array(
                    'show_excerpt' => 'true'
                )
            )
        );

        $this->end_controls_section();

        /** Query section */
        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'lastudio' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'data_source' => 'post_type',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Query::get_type(),
            [
                'name' => 'query',
                'post_type' => 'la_team_member',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'la_team_member',
                        'options' => [
                            'current_query' => __( 'Current Query', 'lastudio' ),
                            'la_team_member' => __( 'Latest', 'lastudio' ),
                            'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date'  => __( 'Date', 'lastudio' ),
                            'title' => __( 'Title', 'lastudio' ),
                            'rand' => __( 'Random', 'lastudio' ),
                            'menu_order' => __( 'Menu Order', 'lastudio' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => __( 'Current Post', 'lastudio' ),
                            'manual_selection' => __( 'Manual Selection', 'lastudio' ),
                        ],
                    ],
                    'posts_ids' => [
                        'object_type' => 'la_team_member'
                    ],
                    'exclude_ids' => [
                        'object_type' => 'la_team_member',
                    ],
                    'include_ids' => [
                        'object_type' => 'la_team_member',
                    ]
                ],
                'exclude' => [
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'avoid_duplicates',
                    'ignore_sticky_posts'
                ],
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

        $this->end_controls_section();

        /** Carousel section */
        $this->start_controls_section(
            'section_carousel',
            array(
                'label' => esc_html__( 'Carousel', 'lastudio' ),
                'condition' => array(
                    'layout_type' => array(
                        'grid',
                        'list'
                    )
                )
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
                'options'   => lastudio_elementor_tools_get_select_range( 6 ),
                'condition' => array(
                    'columns!' => '1',
                ),
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
                    '{{WRAPPER}} ' . $css_scheme['slick_list'] . '' => 'padding-left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} ' . $css_scheme['slick_list'] . '' => 'padding-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_column_style',
            array(
                'label'      => esc_html__( 'Column', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Column Padding', 'lastudio' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['wrap'] => 'margin-right: -{{RIGHT}}{{UNIT}}; margin-left: -{{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_box_style',
            array(
                'label'      => esc_html__( 'Item', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_responsive_control(
            'text_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'text-align: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'item_width',
            array(
                'label'      => esc_html__( 'Item Width', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', '%', 'vh', 'vw'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'box_bg',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'box_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'],
            )
        );

        $this->add_responsive_control(
            'box_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
                    '{{WRAPPER}} .lastudio-images-layout__link:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'inner_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'],
            )
        );

        $this->add_responsive_control(
            'box_padding',
            array(
                'label'      => esc_html__( 'Box Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_style',
            array(
                'label'      => esc_html__( 'Content', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'content_bg',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => esc_html__( 'Content Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['inner-content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_margin',
            array(
                'label'      => esc_html__( 'Content Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['inner-content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['inner-content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner-content'],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_thumb_style',
            array(
                'label'      => esc_html__( 'Image', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
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
            )
        );

        $this->add_control(
            'custom_image_height',
            [
                'label' => __( 'Custom Image Height', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 75,
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->start_controls_tabs( 'image_effects' );

        $this->start_controls_tab( 'normal',
            [
                'label' => __( 'Normal', 'lastudio' ),
            ]
        );

        $this->add_control(
            'img_overlay',
            array(
                'label'  => esc_html__( 'Bg Overlay', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-images-layout__link:after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'opacity',
            [
                'label' => __( 'Opacity', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.00,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lastudio-images-layout__link:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image_wrap'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'hover',
            [
                'label' => __( 'Hover', 'lastudio' ),
            ]
        );

        $this->add_control(
            'img_overlay_hover',
            array(
                'label'  => esc_html__( 'Bg Overlay', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-team-member__inner:hover .lastudio-images-layout__link:after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'opacity_hover',
            [
                'label' => __( 'Opacity', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.00,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lastudio-team-member__inner:hover .lastudio-images-layout__link:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .lastudio-team-member__inner:hover .lastudio-team-member__image',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .lastudio-team-member__image_wrap',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lastudio-team-member__image_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['image_wrap'],
            ]
        );

        $this->end_controls_section();


        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_title_style',
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

        $this->add_control(
            'title_color_hover',
            array(
                'label'  => esc_html__( 'Color Hover', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] . ':hover' => 'color: {{VALUE}}',
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

        $this->end_controls_section();

        /**
         * Position Style Section
         */
        $this->start_controls_section(
            'section_position_style',
            array(
                'label'      => esc_html__( 'Position', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'position_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'position_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['position'],
            )
        );

        $this->add_responsive_control(
            'position_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'position_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Desc Style Section
         */
        $this->start_controls_section(
            'section_desc_style',
            array(
                'label'      => esc_html__( 'Excerpt', 'lastudio' ),
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

        $this->end_controls_section();
        /**
         * Social Style Section
         */
        $this->start_controls_section('section_social_style', array(
                'label' => esc_html__('Social', 'lastudio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ));
        $this->start_controls_tabs('social_style');
        $this->start_controls_tab('social_normal', [
                'label' => __('Normal', 'lastudio'),
            ]);
        $this->add_control('social_bg_color', array(
                'label' => esc_html__('Bg Color', 'lastudio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'background-color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_color', array(
                'label' => esc_html__('Color', 'lastudio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'color: {{VALUE}}',
                ),
            ));
        $this->add_responsive_control('social_fz', [
                'label' => __('Font Size', 'lastudio'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'font-size: {{SIZE}}px;',
                ],
            ]);
        $this->add_responsive_control('social_pd', [
                'label' => __('Padding', 'lastudio'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'padding: {{SIZE}}px;',
                ],
            ]);
        $this->add_responsive_control('social_bd_w', [
                'label' => __('Border Width', 'lastudio'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'border-width: {{SIZE}}px;',
                ],
            ]);
        $this->add_control('social_bd_c', array(
                'label' => esc_html__('Border Color', 'lastudio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'border-color: {{VALUE}}',
                ),
            ));
        $this->add_responsive_control('social_br', array(
                'label' => __('Border Radius', 'lastudio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%'
                ),
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ));
        $this->add_responsive_control('social_mr', array(
                'label' => __('Item Margin', 'lastudio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ));
        $this->end_controls_tab();
        $this->start_controls_tab('social_hover', [
                'label' => __('Hover', 'lastudio'),
            ]);
        $this->add_control('social_bg_color_hover', array(
                'label' => esc_html__('Bg Color', 'lastudio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'background-color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_color_hover', array(
                'label' => esc_html__('Color', 'lastudio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_bd_c_hover', array(
                'label' => esc_html__('Border Color', 'lastudio'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'border-color: {{VALUE}}',
                ),
            ));
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /**
         * Pagination
         */
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
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pagination_spacing',
            [
                'label' => __( 'Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} nav.la-pagination ul' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
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
                    '{{WRAPPER}} nav.la-pagination ul li a, {{WRAPPER}} nav.la-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} nav.la-pagination',
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
                    '{{WRAPPER}} nav.la-pagination ul li a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} nav.la-pagination ul li a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_hover',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} nav.la-pagination ul li span.current' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_active',
            [
                'label' => __( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li span.current' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Arrow Style Section
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
                'selector'       => '{{WRAPPER}} .lastudio-team-member .lastudio-arrow',
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
                'selector'       => '{{WRAPPER}} .lastudio-team-member .lastudio-arrow:hover'
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
                    '{{WRAPPER}} .lastudio-team-member .lastudio-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
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

        $this->add_control(
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
                    '{{WRAPPER}} .lastudio-carousel .lastudio-slick-dots' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

    }

    protected function the_query(){
        return $this->_query;
    }

    protected function render() {
        $this->__context = 'render';

        $data_source = $this->get_settings_for_display('data_source');
        if($data_source == 'custom'){
            $this->__open_wrap();
            include $this->__get_global_template( 'custom' );
            $this->__close_wrap();
        }
        else{
            $paged_key = 'member-page' . esc_attr($this->get_id());

            $page = absint( empty( $_GET[$paged_key] ) ? 1 : $_GET[$paged_key] );

            $query_args = [
                'posts_per_page' => $this->get_settings_for_display('query_posts_per_page'),
                'paged' => 1,
            ];

            if ( 1 < $page ) {
                $query_args['paged'] = $page;
            }

            $module_query = Module_Query::instance();
            $this->_query = $module_query->get_query( $this, 'query', $query_args, [] );

            $this->__open_wrap();
            include $this->__get_global_template( 'index' );
            $this->__close_wrap();
        }
    }

    public function __get_member_image( $image_item ) {

        if ( empty( $image_item['id'] ) && empty( $image_item['url'] ) ) {
            return;
        }

        $image_size = $this->get_settings_for_display('thumb_size');

        if ( ! empty( $image_item['id'] ) ) {
            $image_data = wp_get_attachment_image_src( $image_item['id'], $image_size );

            $params[0] = apply_filters('lastudio_wp_get_attachment_image_url', $image_data[0]);
            $params[1] = $image_data[1];
            $params[2] = $image_data[2];
        }
        else {
            $params[0] = $image_item['url'];
            $params[1] = 1200;
            $params[2] = 800;
        }

        $giflazy = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

        $srcset = sprintf('width="%d" height="%d" srcset="%s"', $params[1], $params[2], $giflazy);

        return sprintf( apply_filters('LaStudioElement/team-member/image-format', '<figure class="figure__object_fit lastudio-team-member__image"><img src="%1$s" data-src="%2$s" alt="" class="la-lazyload-image %3$s" %4$s></figure>'), $giflazy, $params[0], 'lastudio-team-member__image-instance wp-post-image' , $srcset);
    }

    public function __get_member_social( $member_item ){

        $html = '';
        $icon_lists = self::get_labrandicon();
        $uid = isset($member_item['_id']) ? $member_item['_id'] : uniqid();
        for ($i = 1; $i <=5; $i++ ){
            $icon_key = 's_icon_' . $i;
            $link_key = 's_link_' . $i;
            $att_uid = 'member_'.$uid . '_social_' . $i;
            if(!empty($member_item[$icon_key])){
                $icon_value = $member_item[$icon_key];
                $icon_name = $icon_lists[$icon_value];
                if ( !empty($member_item[$link_key]) && ! empty( $member_item[$link_key]['url'] ) ) {
                    $this->add_link_attributes( $att_uid, $member_item[$link_key] );
                    $this->add_render_attribute( $att_uid, 'title', esc_attr($icon_name) );
                }
                $this->add_render_attribute( $att_uid, 'class', sprintf('social-%1$s %1$s', strtolower($icon_name)) );
                $html .= sprintf('<a %1$s><i class="%2$s"></i></a>', $this->get_render_attribute_string( $att_uid ), $icon_value);
            }
        }
        if(!empty($html)){
            $html = '<div class="item--social member-social">'.$html.'</div>';
        }
        return $html;
    }

    protected function generate_carousel_setting_json(){
        $settings = $this->get_settings();

        $json_data = '';

        if ( 'yes' !== $settings['carousel_enabled'] ) {
            return $json_data;
        }

        $is_rtl = is_rtl();

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
        );

        if ( 1 === absint( $settings['columns'] ) ) {
            $options['fade'] = ( 'fade' === $settings['effect'] );
        }

        $json_data = htmlspecialchars( json_encode( $options ) );

        return $json_data;

    }

}
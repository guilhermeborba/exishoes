<?php

namespace LaStudio_Element\Widgets;

if (!defined('WPINC')) {
    die;
}

use LaStudio_Element\Controls\Group_Control_Box_Style;
use LaStudio_Element\Controls\Group_Control_Query;
use LaStudio_Element\Classes\Query_Control as Module_Query;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


/**
 * Portfolio Widget
 */
class Portfolio extends LA_Widget_Base {

    /**
     * [$item_counter description]
     * @var integer
     */

    private $_query = null;

    public $item_counter = 0;


    public function get_name() {
        return 'lastudio-portfolio';
    }

    protected function get_widget_title() {
        return esc_html__( 'Portfolio', 'lastudio' );
    }

    public function get_icon() {
        return 'lastudioelements-icon-36';
    }

    public function get_script_depends() {
        return [
            'lastudio'
        ];
    }

    protected function register_controls() {
        if(lastudio_get_theme_support('lastudio-kit::portfolio')){
            $this->new_register_controls();
        }
        else{
            $this->old_register_controls();
        }

    }

    private function new_register_controls() {

        $css_scheme = apply_filters(
            'LaStudioElement/portfolio/css-scheme',
            array(
                'instance'         => '.lastudio-portfolio',
                'list_container_wrap'   => '.lastudio-portfolio__list_wrapper',
                'list_container'   => '.lastudio-portfolio__list',
                'slick_list'       => '.lastudio-portfolio .slick-list',
                'item'             => '.lastudio-portfolio__item',
                'inner'            => '.lastudio-portfolio__inner',
                'image_wrap'       => '.lastudio-portfolio__image',
                'image_instance'   => '.lastudio-portfolio__image-instance',
                'content_wrap'     => '.lastudio-portfolio__content',
                'content_inner'    => '.lastudio-portfolio__content-inner',
                'cover'            => '.lastudio-portfolio__cover',
                'title'            => '.lastudio-portfolio__title',
                'desc'             => '.lastudio-portfolio__desc',
                'category'         => '.lastudio-portfolio__category',
                'button'           => '.lastudio-portfolio__button',
                'divider'          => '.lastudio-portfolio__divider',
                'divider_span'     => '.lastudio-portfolio__divider span',
                'view_more'        => '.lastudio-portfolio__view-more-button',
                'filters_wrap'     => '.lastudio-portfolio__filter',
                'filters'          => '.lastudio-portfolio__filter-list',
                'filter'           => '.lastudio-portfolio__filter-item',
                'filter_separator' => '.lastudio-portfolio__filter-item-separator',
                'meta1'             => '.lastudio-portfolio__metadata1',
                'meta1-item'        => '.lastudio-portfolio__metadata1 .lastudio-post__meta_item',
                'meta1-label'       => '.lastudio-portfolio__metadata1 .meta_item--label',
                'meta1-value'       => '.lastudio-portfolio__metadata1 .meta_item--value',
                'meta1-icon'        => '.lastudio-portfolio__metadata1 .meta_item--icon',
                'meta2'             => '.lastudio-portfolio__metadata2',
                'meta2-item'        => '.lastudio-portfolio__metadata2 .lastudio-post__meta_item',
                'meta2-label'       => '.lastudio-portfolio__metadata2 .meta_item--label',
                'meta2-value'       => '.lastudio-portfolio__metadata2 .meta_item--value',
                'meta2-icon'        => '.lastudio-portfolio__metadata2 .meta_item--icon',
            )
        );

        $preset_type = apply_filters(
            'LaStudioElement/portfolio/control/preset',
            array(
                'type-1' => esc_html__( 'Type-1', 'lastudio' ),
                'type-2' => esc_html__( 'Type-2', 'lastudio' ),
                'type-3' => esc_html__( 'Type-3', 'lastudio' ),
                'type-4' => esc_html__( 'Type-4', 'lastudio' ),
                'type-5' => esc_html__( 'Type-5', 'lastudio' ),
            )
        );

        $preset_list_type = apply_filters(
            'LaStudioElement/portfolio/control/preset_list',
            array(
                'list-type-1' => esc_html__( 'Type-1', 'lastudio' ),
                'list-type-2' => esc_html__( 'Type-2', 'lastudio' ),
                'list-type-3' => esc_html__( 'Type-3', 'lastudio' )
            )
        );

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
                'default' => 'type-1',
                'options' => $preset_type,
                'condition' => array(
                    'layout_type!' => 'list'
                )
            )
        );

        $this->add_control(
            'preset_list',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-list-1',
                'options' => $preset_list_type,
                'condition' => array(
                    'layout_type' => 'list'
                )
            )
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio' ),
                'default'    => 'full',
                'options'    => la_get_all_image_sizes()
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
            'custom_excerpt_length',
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

        $this->add_control(
            'show_divider',
            array(
                'label'        => esc_html__( 'Show Divider?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_readmore_btn',
            array(
                'label'        => esc_html__( 'Show Read more button?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'read_more_label',
            array(
                'label'   => esc_html__( '`Read More` Text', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Read More', 'lastudio' ),
                'condition' => array(
                    'show_readmore_btn' => 'true'
                )
            )
        );
        $this->add_control(
            'read_more_icon',
            [
                'label' => __( 'Icon', 'lastudio' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'condition' => array(
                    'show_readmore_btn' => 'true'
                )
            ]
        );
        $this->add_control(
            'read_more_icon_align',
            [
                'label' => __( 'Icon Position', 'lastudio' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'lastudio' ),
                    'right' => __( 'After', 'lastudio' ),
                    'top' => __( 'Top', 'lastudio' ),
                    'bottom' => __( 'Bottom', 'lastudio' ),
                ],
                'condition' => [
                    'show_readmore_btn' => 'true',
                    'read_more_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

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
                'post_type' => 'la_portfolio',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'la_portfolio',
                        'options' => [
                            'current_query' => __( 'Current Query', 'lastudio' ),
                            'la_portfolio' => __( 'Latest Portfolio', 'lastudio' ),
                            'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date'          => __( 'Date', 'lastudio' ),
                            'title'         => __( 'Title', 'lastudio' ),
                            'rand'          => __( 'Random', 'lastudio' ),
                            'menu_order'    => __( 'Menu Order', 'lastudio' ),
                            'post__in'      => __( 'Manual Selection', 'lastudio' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => __( 'Current Post', 'lastudio' ),
                            'manual_selection' => __( 'Manual Selection', 'lastudio' ),
                            'terms' => __( 'Portfolio Category', 'lastudio' ),
                        ],
                    ],
                    'exclude_ids' => [
                        'object_type' => 'la_portfolio',
                    ],
                    'exclude_term_ids' => [
                        'label' => __( 'Portfolio Category', 'lastudio' ),
                        'object_type' => 'la_portfolio_category',
                    ],
                    'include' => [
                        'options' => [
                            'terms' => __( 'Portfolio Category', 'lastudio' ),
                        ],
                    ],
                    'include_ids' => [
                        'object_type' => 'la_portfolio',
                    ],
                    'include_term_ids' => [
                        'label' => __( 'Portfolio Category', 'lastudio' ),
                        'object_type' => 'la_portfolio_category',
                    ],
                ],
                'exclude' => [
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'avoid_duplicates',
                    'ignore_sticky_posts',
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

        $this->start_controls_section(
            'section_filter_bar',
            [
                'label' => __( 'Filter Bar', 'lastudio' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                ),
            ]
        );

        $this->add_control(
            'all_filter_label',
            array(
                'label'   => esc_html__( '`All` Filter Label', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'All', 'lastudio' )
            )
        );


        $portfolio_categories = get_terms( 'la_portfolio_category' );

        $filter_options = [];
        if(!is_wp_error($portfolio_categories)){
            foreach ( $portfolio_categories as $portfolio_category ) {
                $filter_options[ $portfolio_category->slug ] = $portfolio_category->name;
            }
        }

        $this->add_control(
            'category_filters',
            [
                'label' => __( 'Category Filters', 'lastudio' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $filter_options,
                'default' => [],
                'label_block' => true,
                'multiple' => true,
                'condition' => array(
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                )
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Item Layout', 'lastudio' ),
                'condition' => array(
                    'layout_type!' => array(
                        'list',
                        'grid'
                    )
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
                    '2' => '2 width',
                    '0-5' => '1/2 width'
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
                    '2' => '2 height',
                    '0-5' => '1/2 height',
                    '0-75' => '3/4 height',
                    '0-60' => '3/5 height',
                    '0-80' => '4/5 height'
                )
            )
        );

        $this->add_control(
            'image_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'prevent_empty' => false
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_meta',
            array(
                'label' => esc_html__( 'MetaData', 'lastudio' )
            )
        );

        $this->add_control(
            'show_meta',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Show Meta Data', 'lastudio' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_label',
            array(
                'label'       => esc_html__( 'Label', 'lastudio' ),
                'type'        => Controls_Manager::TEXT,
            )
        );
        $repeater->add_control(
            'item_icon',
            [
                'label' => __( 'Icon', 'lastudio' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'item_type',
            [
                'label'     => esc_html__( 'Type', 'lastudio' ),
                'type'      => Controls_Manager::SELECT2,
                'options'   => apply_filters('LaStudioElement/portfolio/metadata', [
                    'category'      => esc_html__( 'Category', 'lastudio' ),
                    'client'        => esc_html__( 'Client', 'lastudio' ),
                    'location'      => esc_html__( 'Location', 'lastudio' ),
                    'date'          => esc_html__( 'Date', 'lastudio' ),
                ])
            ]
        );

        $this->add_control(
            'metadata1',
            array(
                'label'     => esc_html__( 'Meta Data 1', 'lastudio' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'title_field' => '{{{ item_label }}}',
                'prevent_empty' => false,
                'condition' => array(
                    'show_meta' => 'yes'
                )
            )
        );

        $this->add_control(
            'metadata2',
            array(
                'label'     => esc_html__( 'Meta Data 2', 'lastudio' ),
                'type'    => Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'title_field' => '{{{ item_label }}}',
                'prevent_empty' => false,
                'condition' => array(
                    'show_meta' => 'yes'
                )
            )
        );

        $this->end_controls_section();

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
                    '{{WRAPPER}} ' . $css_scheme['slick_list'] . '' => 'padding-left: {{SIZE}}{{UNIT}} !important;',
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
                    '{{WRAPPER}} ' . $css_scheme['slick_list'] . '' => 'padding-right: {{SIZE}}{{UNIT}} !important;',
                ),
            )
        );

        $this->add_control(
            'center_mode',
            array(
                'label'        => esc_html__( 'Center Mode (optional )', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => ''
            )
        );

        $this->add_control(
            'initial_slide',
            array(
                'label'     => esc_html__( 'Slide to start on', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default'   => 0,
                'min'     => 0,
                'max'     => 6,
                'step'    => 1
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_portfolio_general_style',
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
                'default' => [
                    'size' => 10,
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner']          => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['list_container_wrap'] => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
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
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
         * Filter Style Section
         */
        $this->start_controls_section(
            'section_filters_style',
            array(
                'label'      => esc_html__( 'Filters', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'layout_type!' => array(
                        'list',
                        'grid'
                    )
                )
            )
        );

        $this->add_control(
            'filters_container_styles_heading',
            array(
                'label'     => esc_html__( 'Filters Container Styles', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filters_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_responsive_control(
            'filters_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filters_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filters_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_responsive_control(
            'filters_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filters_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_control(
            'filters_items_styles_heading',
            array(
                'label'     => esc_html__( 'Filters Items Styles', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'filters_items_separator_icon',
            array(
                'label'       => esc_html__( 'Separator Icon', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'include' => self::get_laicon_default(true),
                'options' => self::get_laicon_default()
            )
        );

        $this->add_control(
            'filter_items_separator_color',
            array(
                'label' => esc_html__( 'Separator Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_items_separator_size',
            array(
                'label' => esc_html__( 'Separator Size', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filters_items_aligment',
            array(
                'label'       => esc_html__( 'Alignment', 'lastudio' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'label_block' => false,
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
                    '{{WRAPPER}} ' . $css_scheme['filters_wrap'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_filter_item' );

        $this->start_controls_tab(
            'tab_filter_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'filter_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->add_responsive_control(
            'filter_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->add_responsive_control(
            'filter_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_item_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'filter_color_hover',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography_hover',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'filter_padding_hover',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin_hover',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'filter_border_radius_hover',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_item_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio' ),
            )
        );

        $this->add_control(
            'filter_color_active',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography_active',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_responsive_control(
            'filter_padding_active',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin_active',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border_active',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_responsive_control(
            'filter_border_radius_active',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Image Style Section
         */
        $this->start_controls_section(
            'section_image_style',
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
                'default'      => ''
            )
        );

        $this->add_responsive_control(
            'custom_image_height',
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
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->add_responsive_control(
            'image_pos',
            [
                'label'   => esc_html__( 'Image Position', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'top'       => esc_html__( 'Top', 'lastudio' ),
                    'center'    => esc_html__( 'Center', 'lastudio' ),
                    'bottom'    => esc_html__( 'Bottom', 'lastudio' ),
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'prefix_class' => 'img-pos-',
            ]
        );

        $this->add_control(
            'section_image_overlay',
            array(
                'label'     => esc_html__( 'Overlay', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->start_controls_tabs( 'image_tabs' );

        $this->start_controls_tab(
            'image_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'image_overlay_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'image_overlay_position',
            array(
                'label'      => esc_html__( 'Position', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}}'
                ),
            )
        );

        $this->add_control(
            'image_overlay_opacity',
            array(
                'label'      => esc_html__( 'Opacity', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_overlay_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'selector'    => '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'image_overlay_color_hover',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'image_overlay_position_hover',
            array(
                'label'      => esc_html__( 'Position', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'image_overlay_opacity_hover',
            array(
                'label'      => esc_html__( 'Opacity', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after' => 'opacity: {{SIZE}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_overlay_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'selector'    => '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after',
            )
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Content Style Section
         */
        $this->start_controls_section(
            'section_portfolio_content_style',
            array(
                'label'      => esc_html__( 'Content', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'section_portfolio_content_wrapper_heading',
            array(
                'label'     => esc_html__( 'Container', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_container_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrap'],
            )
        );

        $this->add_responsive_control(
            'content_wrapper_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_wrapper_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'content_wrapper_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['content_wrap'],
            )
        );

        $this->add_responsive_control(
            'content_wrapper_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrap'],
            )
        );

        $this->add_responsive_control(
            'content_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'text-align: {{VALUE}};',
                )
            )
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
         * Meta Section
         */
        $this->start_controls_section(
            'section_meta1',
            array(
                'label'      => esc_html__( 'Meta 1', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta' => 'yes'
                ],
            )
        );

        $this->add_control(
            'meta1_bg',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'meta1_icon_color',
            array(
                'label'  => esc_html__( 'Icon Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1-icon'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'meta1_label_color',
            array(
                'label'  => esc_html__( 'Label Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1-label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'meta1_value_color',
            array(
                'label' => esc_html__( 'Value Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1-value'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => '--metaitem-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'meta1_value_hover_color',
            array(
                'label' => esc_html__( 'Value Hover Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => '--metaitem-hover-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'	   => esc_html__( 'Label Typography', 'lastudio' ),
                'name'     => 'meta1_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta1'],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'	   => esc_html__( 'Value Typography', 'lastudio' ),
                'name'     => 'meta1_value_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta1-value'],
            )
        );

        $this->add_responsive_control(
            'meta1_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['meta1'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta1_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['meta1'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta1_alignment',
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
                    'space-between' => array(
                        'title' => esc_html__( 'Stretch', 'lastudio' ),
                        'icon'  => 'eicon-h-align-stretch',
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => 'justify-content: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'meta1_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
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
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta1_divider',
            array(
                'label'     => esc_html__( 'Meta Divider', 'lastudio' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
                ),
            )
        );

        $this->add_control(
            'meta1_divider_gap',
            array(
                'label'      => esc_html__( 'Divider Gap', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 90,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => '--metaitem-divider-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta1_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1-icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            'meta1_icon_spacing',
            array(
                'label'      => esc_html__( 'Icon Spacing', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => '--metaitem-icon-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            'meta1_label_spacing',
            array(
                'label'      => esc_html__( 'Label Spacing', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta1'] => '--metaitem-label-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Meta Section
         */
        $this->start_controls_section(
            'section_meta2',
            array(
                'label'      => esc_html__( 'Meta 2', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta' => 'yes'
                ],
            )
        );

        $this->add_control(
            'meta2_bg',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'meta2_icon_color',
            array(
                'label'  => esc_html__( 'Icon Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2-icon'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'meta2_label_color',
            array(
                'label'  => esc_html__( 'Label Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2-label'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'meta2_value_color',
            array(
                'label' => esc_html__( 'Value Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2-value'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => '--metaitem-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'meta2_value_hover_color',
            array(
                'label' => esc_html__( 'Value Hover Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => '--metaitem-hover-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'	   => esc_html__( 'Label Typography', 'lastudio' ),
                'name'     => 'meta2_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta2'],
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'	   => esc_html__( 'Value Typography', 'lastudio' ),
                'name'     => 'meta2_value_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['meta2-value'],
            )
        );

        $this->add_responsive_control(
            'meta2_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['meta2'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta2_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['meta2'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta2_alignment',
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
                    'space-between' => array(
                        'title' => esc_html__( 'Stretch', 'lastudio' ),
                        'icon'  => 'eicon-h-align-stretch',
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => 'justify-content: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'meta2_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
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
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta2_divider',
            array(
                'label'     => esc_html__( 'Meta Divider', 'lastudio' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
                ),
            )
        );

        $this->add_control(
            'meta2_divider_gap',
            array(
                'label'      => esc_html__( 'Divider Gap', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 90,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => '--metaitem-divider-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'meta2_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2-icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            'meta2_icon_spacing',
            array(
                'label'      => esc_html__( 'Icon Spacing', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => '--metaitem-icon-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            'meta2_label_spacing',
            array(
                'label'      => esc_html__( 'Label Spacing', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['meta2'] => '--metaitem-label-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_desc_style',
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

        $this->end_controls_section();

        /**
         * More Button Section
         */

        $this->start_controls_section(
            'section_portfolio_more_button_style',
            array(
                'label'      => esc_html__( 'More Button', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'show_readmore_btn' => 'true'
                ],
            )
        );

        $this->add_responsive_control(
            'readmore_icon_size',
            [
                'label' => __( 'Icon Size', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'read_more_icon[value]!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'readmore_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-top' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'read_more_icon[value]!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'readmore_icon_vert_spacing',
            [
                'label' => __( 'Vertical Icon Spacing', 'lastudio' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'read_more_icon[value]!' => '',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_more_button_style' );

        $this->start_controls_tab(
            'tab_more_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'more_button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'more_button_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ' .elementor-button-icon' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'more_button_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
            )
        );

        $this->add_responsive_control(
            'more_button_padding',
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
            'more_button_margin',
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
            'more_button_border_radius',
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
                'name'        => 'more_button_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'more_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_more_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'more_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_icon_hover_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover .elementor-button-icon' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'more_button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'more_button_hover_padding',
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
            'more_button_hover_margin',
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
            'more_button_hover_border_radius',
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
                'name'        => 'more_button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'more_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * More Button Section
         */

        $this->start_controls_section(
            'section_portfolio_divider',
            array(
                'label'      => esc_html__( 'Divider', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'divider_weight',
            array(
                'label'      => esc_html__( 'Weight', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 10,
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider_span'] => 'border-top-width: {{SIZE}}{{UNIT}}; border-top-style: solid;',
                ),
            )
        );

        $this->add_control(
            'divider_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['divider_span'] => 'border-top-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider_span'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_alignment',
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
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

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


        $this->add_responsive_control(
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
         * Arrow Sections
         */

        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'      => esc_html__( 'Carousel Arrows', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'carousel_enabled' => 'yes',
                    'arrows' => 'true',
                ),
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
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow',
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
                'condition' => array(
                    'carousel_enabled' => 'yes',
                    'dots' => 'true',
                ),
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

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'meta1_order',
            array(
                'label'   => esc_html__( 'Meta1 Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['meta1'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta2_order',
            array(
                'label'   => esc_html__( 'Meta2 Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['meta2'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'desc_order',
            array(
                'label'   => esc_html__( 'Description Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_order',
            array(
                'label'   => esc_html__( 'Button Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-portfolio__button_wrap' => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'divider_order',
            array(
                'label'   => esc_html__( 'Divider Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['divider'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function old_register_controls() {

        $css_scheme = apply_filters(
            'LaStudioElement/portfolio/css-scheme',
            array(
                'instance'         => '.lastudio-portfolio',
                'list_container_wrap'   => '.lastudio-portfolio__list_wrapper',
                'list_container'   => '.lastudio-portfolio__list',
                'slick_list'       => '.lastudio-portfolio .slick-list',
                'item'             => '.lastudio-portfolio__item',
                'inner'            => '.lastudio-portfolio__inner',
                'image_wrap'       => '.lastudio-portfolio__image',
                'image_instance'   => '.lastudio-portfolio__image-instance',
                'content_wrap'     => '.lastudio-portfolio__content',
                'content_inner'    => '.lastudio-portfolio__content-inner',
                'cover'            => '.lastudio-portfolio__cover',
                'title'            => '.lastudio-portfolio__title',
                'desc'             => '.lastudio-portfolio__desc',
                'category'         => '.lastudio-portfolio__category',
                'button'           => '.lastudio-portfolio__button',
                'divider'          => '.lastudio-portfolio__divider',
                'divider_span'     => '.lastudio-portfolio__divider span',
                'view_more'        => '.lastudio-portfolio__view-more-button',
                'filters_wrap'     => '.lastudio-portfolio__filter',
                'filters'          => '.lastudio-portfolio__filter-list',
                'filter'           => '.lastudio-portfolio__filter-item',
                'filter_separator' => '.lastudio-portfolio__filter-item-separator',
            )
        );

        $preset_type = apply_filters(
            'LaStudioElement/portfolio/control/preset',
            array(
                'type-1' => esc_html__( 'Type-1', 'lastudio' ),
                'type-2' => esc_html__( 'Type-2', 'lastudio' ),
                'type-3' => esc_html__( 'Type-3', 'lastudio' ),
                'type-4' => esc_html__( 'Type-4', 'lastudio' ),
                'type-5' => esc_html__( 'Type-5', 'lastudio' ),
            )
        );

        $preset_list_type = apply_filters(
            'LaStudioElement/portfolio/control/preset_list',
            array(
                'list-type-1' => esc_html__( 'Type-1', 'lastudio' ),
                'list-type-2' => esc_html__( 'Type-2', 'lastudio' ),
                'list-type-3' => esc_html__( 'Type-3', 'lastudio' )
            )
        );

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
                'post_type' => 'la_portfolio',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'la_portfolio',
                        'options' => [
                            'current_query' => __( 'Current Query', 'lastudio' ),
                            'la_portfolio' => __( 'Latest Portfolio', 'lastudio' ),
                            'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date'          => __( 'Date', 'lastudio' ),
                            'title'         => __( 'Title', 'lastudio' ),
                            'rand'          => __( 'Random', 'lastudio' ),
                            'menu_order'    => __( 'Menu Order', 'lastudio' ),
                            'post__in'      => __( 'Manual Selection', 'lastudio' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => __( 'Current Post', 'lastudio' ),
                            'manual_selection' => __( 'Manual Selection', 'lastudio' ),
                            'terms' => __( 'Portfolio Category', 'lastudio' ),
                        ],
                    ],
                    'exclude_ids' => [
                        'object_type' => 'la_portfolio',
                    ],
                    'exclude_term_ids' => [
                        'label' => __( 'Portfolio Category', 'lastudio' ),
                        'object_type' => 'la_portfolio_category',
                    ],
                    'include' => [
                        'options' => [
                            'terms' => __( 'Portfolio Category', 'lastudio' ),
                        ],
                    ],
                    'include_ids' => [
                        'object_type' => 'la_portfolio',
                    ],
                    'include_term_ids' => [
                        'label' => __( 'Portfolio Category', 'lastudio' ),
                        'object_type' => 'la_portfolio_category',
                    ],
                ],
                'exclude' => [
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'avoid_duplicates',
                    'ignore_sticky_posts',
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
                'default' => 'type-1',
                'options' => $preset_type,
                'condition' => array(
                    'layout_type!' => 'list'
                )
            )
        );

        $this->add_control(
            'preset_list',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-list-1',
                'options' => $preset_list_type,
                'condition' => array(
                    'layout_type' => 'list'
                )
            )
        );

        $this->add_control(
            'image_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Images Size', 'lastudio' ),
                'default'    => 'full',
                'options'    => la_get_all_image_sizes()
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
            'custom_excerpt_length',
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

        $this->add_control(
            'show_category',
            array(
                'label'        => esc_html__( 'Show Category?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_divider',
            array(
                'label'        => esc_html__( 'Show Divider?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_readmore_btn',
            array(
                'label'        => esc_html__( 'Show Read more button?', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'read_more_label',
            array(
                'label'   => esc_html__( '`Read More` Text', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Read More', 'lastudio' ),
                'condition' => array(
                    'show_readmore_btn' => 'true'
                )
            )
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_bar',
            [
                'label' => __( 'Filter Bar', 'lastudio' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                ),
            ]
        );

        $this->add_control(
            'all_filter_label',
            array(
                'label'   => esc_html__( '`All` Filter Label', 'lastudio' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'All', 'lastudio' )
            )
        );


        $portfolio_categories = get_terms( 'la_portfolio_category' );

        $filter_options = [];
        if(!is_wp_error($portfolio_categories)){
            foreach ( $portfolio_categories as $portfolio_category ) {
                $filter_options[ $portfolio_category->slug ] = $portfolio_category->name;
            }
        }

        $this->add_control(
            'category_filters',
            [
                'label' => __( 'Category Filters', 'lastudio' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $filter_options,
                'default' => [],
                'label_block' => true,
                'multiple' => true,
                'condition' => array(
                    'layout_type!' => array(
                        'grid',
                        'list'
                    )
                )
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Item Layout', 'lastudio' ),
                'condition' => array(
                    'layout_type!' => array(
                        'list',
                        'grid'
                    )
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
                    '2' => '2 width',
                    '0-5' => '1/2 width'
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
                    '2' => '2 height',
                    '0-5' => '1/2 height',
                    '0-75' => '3/4 height',
                    '0-60' => '3/5 height',
                    '0-80' => '4/5 height'
                )
            )
        );

        $this->add_control(
            'image_list',
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
                    '{{WRAPPER}} ' . $css_scheme['slick_list'] . '' => 'padding-left: {{SIZE}}{{UNIT}} !important;',
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
                    '{{WRAPPER}} ' . $css_scheme['slick_list'] . '' => 'padding-right: {{SIZE}}{{UNIT}} !important;',
                ),
            )
        );

        $this->add_control(
            'center_mode',
            array(
                'label'        => esc_html__( 'Center Mode (optional )', 'lastudio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio' ),
                'label_off'    => esc_html__( 'No', 'lastudio' ),
                'return_value' => 'true',
                'default'      => ''
            )
        );

        $this->add_control(
            'initial_slide',
            array(
                'label'     => esc_html__( 'Slide to start on', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'default'   => 0,
                'min'     => 0,
                'max'     => 6,
                'step'    => 1
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_portfolio_general_style',
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
                'default' => [
                    'size' => 10,
                ],
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner']          => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['list_container_wrap'] => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
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
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
         * Filter Style Section
         */
        $this->start_controls_section(
            'section_filters_style',
            array(
                'label'      => esc_html__( 'Filters', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'layout_type!' => array(
                        'list',
                        'grid'
                    )
                )
            )
        );

        $this->add_control(
            'filters_container_styles_heading',
            array(
                'label'     => esc_html__( 'Filters Container Styles', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filters_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_responsive_control(
            'filters_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filters_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filters_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_responsive_control(
            'filters_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filters'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filters_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
            )
        );

        $this->add_control(
            'filters_items_styles_heading',
            array(
                'label'     => esc_html__( 'Filters Items Styles', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'filters_items_separator_icon',
            array(
                'label'       => esc_html__( 'Separator Icon', 'lastudio' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'include' => self::get_laicon_default(true),
                'options' => self::get_laicon_default()
            )
        );

        $this->add_control(
            'filter_items_separator_color',
            array(
                'label' => esc_html__( 'Separator Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_items_separator_size',
            array(
                'label' => esc_html__( 'Separator Size', 'lastudio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filters_items_aligment',
            array(
                'label'       => esc_html__( 'Alignment', 'lastudio' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'label_block' => false,
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
                    '{{WRAPPER}} ' . $css_scheme['filters_wrap'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_filter_item' );

        $this->start_controls_tab(
            'tab_filter_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'filter_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'],
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->add_responsive_control(
            'filter_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->add_responsive_control(
            'filter_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_item_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'filter_color_hover',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography_hover',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'filter_padding_hover',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin_hover',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'filter_border_radius_hover',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_filter_item_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio' ),
            )
        );

        $this->add_control(
            'filter_color_active',
            array(
                'label' => esc_html__( 'Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'filter_typography_active',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'filter_background_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_responsive_control(
            'filter_padding_active',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'filter_margin_active',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'filter_border_active',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->add_responsive_control(
            'filter_border_radius_active',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'filter_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Image Style Section
         */
        $this->start_controls_section(
            'section_image_style',
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
                'default'      => ''
            )
        );

        $this->add_responsive_control(
            'custom_image_height',
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
                    'enable_custom_image_height!' => ''
                ]
            )
        );

        $this->add_responsive_control(
            'image_pos',
            [
                'label'   => esc_html__( 'Image Position', 'lastudio' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'top'       => esc_html__( 'Top', 'lastudio' ),
                    'center'    => esc_html__( 'Center', 'lastudio' ),
                    'bottom'    => esc_html__( 'Bottom', 'lastudio' ),
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'prefix_class' => 'img-pos-',
            ]
        );

        $this->add_control(
            'section_image_overlay',
            array(
                'label'     => esc_html__( 'Overlay', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->start_controls_tabs( 'image_tabs' );

        $this->start_controls_tab(
            'image_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'image_overlay_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'image_overlay_position',
            array(
                'label'      => esc_html__( 'Position', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}}'
                ),
            )
        );

        $this->add_control(
            'image_overlay_opacity',
            array(
                'label'      => esc_html__( 'Opacity', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] . ':after' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'image_overlay_color_hover',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'image_overlay_position_hover',
            array(
                'label'      => esc_html__( 'Position', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'image_overlay_opacity_hover',
            array(
                'label'      => esc_html__( 'Opacity', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lastudio-portfolio__inner:hover ' . $css_scheme['image_wrap'] . ':after' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Content Style Section
         */
        $this->start_controls_section(
            'section_portfolio_content_style',
            array(
                'label'      => esc_html__( 'Content', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'section_portfolio_content_wrapper_heading',
            array(
                'label'     => esc_html__( 'Container', 'lastudio' ),
                'type'      => Controls_Manager::HEADING,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_container_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrap'],
            )
        );

        $this->add_responsive_control(
            'content_wrapper_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_wrapper_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'content_wrapper_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['content_wrap'],
            )
        );

        $this->add_responsive_control(
            'content_wrapper_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'content_wrapper_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrap'],
            )
        );

        $this->add_responsive_control(
            'content_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                    '{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'text-align: {{VALUE}};',
                )
            )
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
         * Category Style Section
         */
        $this->start_controls_section(
            'section_category_style',
            array(
                'label'      => esc_html__( 'Category', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'category_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'category_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['category'],
            )
        );

        $this->add_responsive_control(
            'category_padding',
            array(
                'label'      => __( 'Padding', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'category_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['category'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Description Style Section
         */
        $this->start_controls_section(
            'section_desc_style',
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

        $this->end_controls_section();

        /**
         * More Button Section
         */

        $this->start_controls_section(
            'section_portfolio_more_button_style',
            array(
                'label'      => esc_html__( 'More Button', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'tabs_more_button_style' );

        $this->start_controls_tab(
            'tab_more_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio' ),
            )
        );

        $this->add_control(
            'more_button_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_color',
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
                'name'     => 'more_button_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
            )
        );

        $this->add_responsive_control(
            'more_button_padding',
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
            'more_button_margin',
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
            'more_button_border_radius',
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
                'name'        => 'more_button_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'more_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_more_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio' ),
            )
        );

        $this->add_control(
            'more_button_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'more_button_hover_color',
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
                'name'     => 'more_button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'more_button_hover_padding',
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
            'more_button_hover_margin',
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
            'more_button_hover_border_radius',
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
                'name'        => 'more_button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'more_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * More Button Section
         */

        $this->start_controls_section(
            'section_portfolio_divider',
            array(
                'label'      => esc_html__( 'Divider', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'divider_weight',
            array(
                'label'      => esc_html__( 'Weight', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 10,
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider_span'] => 'border-top-width: {{SIZE}}{{UNIT}}; border-top-style: solid;',
                ),
            )
        );

        $this->add_control(
            'divider_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['divider_span'] => 'border-top-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider_span'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_alignment',
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
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'divider_margin',
            array(
                'label'      => __( 'Margin', 'lastudio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['divider'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

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


        $this->add_responsive_control(
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
         * Arrow Sections
         */

        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'      => esc_html__( 'Carousel Arrows', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'carousel_enabled' => 'yes',
                    'arrows' => 'true',
                ),
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
                'selector'       => '{{WRAPPER}} .lastudio-carousel .lastudio-arrow',
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
                'condition' => array(
                    'carousel_enabled' => 'yes',
                    'dots' => 'true',
                ),
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

        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order', 'lastudio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 6,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'category_order',
            array(
                'label'   => esc_html__( 'Category Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 6,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['category'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'desc_order',
            array(
                'label'   => esc_html__( 'Description Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 6,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_order',
            array(
                'label'   => esc_html__( 'Button Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 6,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} .lastudio-portfolio__button_wrap' => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'divider_order',
            array(
                'label'   => esc_html__( 'Divider Order', 'lastudio' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 6,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['divider'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    public function generate_carousel_setting_json(){
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

        $initial_slide = absint(  isset($settings['initial_slide']) ? $settings['initial_slide'] : 0 );

        $options = array(
            'slidesToShow'   => $slidesToShow,
            'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
            'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
            'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
            'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
            'speed'          => absint( $settings['speed'] ),
            'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
            'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
            'centerMode'     => filter_var( $settings['center_mode'], FILTER_VALIDATE_BOOLEAN ),
            'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
            'prevArrow'      => lastudio_elementor_tools_get_carousel_arrow( [ 'prev-arrow', 'slick-prev' ], [ $settings['prev_arrow'] ] ),
            'nextArrow'      => lastudio_elementor_tools_get_carousel_arrow( [ 'next-arrow', 'slick-next' ], [ $settings['next_arrow'] ] ),
            'rtl'            => $is_rtl,
            'initialSlide'   => $initial_slide
        );

        if ( 1 === absint( $settings['columns'] ) ) {
            $options['fade'] = ( 'fade' === $settings['effect'] );
        }

        $json_data = htmlspecialchars( json_encode( $options ) );

        return $json_data;

    }

    /**
     * Get filters html
     *
     * @return html
     */
    public function render_filters() {
        $html = '';

        $separator_html = '';

        $separator_icon = $this->get_settings_for_display( 'filters_items_separator_icon' );

        $category_list_id = $this->get_settings_for_display('category_filters');

        if ( empty( $category_list_id ) ) {
            return false;
        }

        $category_list = array();

        foreach ( $category_list_id as $cat_slug ){
            $tax_obj = get_term_by('slug', $cat_slug, 'la_portfolio_category');
            if(!is_wp_error($tax_obj) && !empty($tax_obj)){
                $category_list[$tax_obj->slug] = $tax_obj->name;
            }
        }

        if(empty($category_list)){
            return false;
        }

        $all_label = $this->get_settings_for_display( 'all_filter_label' );
        $all_label = ( ! empty( $all_label ) ) ? $all_label : esc_html__( 'All', 'lastudio' );
        $all_label_slug = sanitize_title($all_label);

        $html .= sprintf( '<div class="isotope__filter-item lastudio-portfolio__filter-item active" data-filter="*"><span>%1$s</span></div>', $all_label );

        if ( ! empty( $separator_icon ) ) {
            $separator_html = sprintf( '<i class="isotope__filter-item-separator lastudio-portfolio__filter-item-separator %s"></i>', $separator_icon );
        }

        foreach ( $category_list as $slug => $category_name ) {
            if($slug == $all_label_slug) continue;
            $html .= sprintf( '%3$s<div class="isotope__filter-item lastudio-portfolio__filter-item" data-filter="%1$s"><span>%2$s</span></div>', 'la_portfolio_category-' . $slug, $category_name, $separator_html );
        }

        echo sprintf( '<div class="isotope__filter lastudio-portfolio__filter js-el" data-la_component="MasonryFilter" data-isotope_container="%2$s"><div class="isotope__filter-list lastudio-portfolio__filter-list">%1$s</div></div>', $html , '.elementor-element-'. $this->get_id().' .la-isotope-container' );

    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {

        $this->__context = 'render';

        $paged_key = 'portfolio-page' . esc_attr($this->get_id());

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

    protected function the_query(){
        return $this->_query;
    }

    protected function get_masonry_item_sizes( $idx = 0 ){
        $return = array(
            'item_width' => 1,
            'item_height' => 1,
        );
        $image_list = (array) $this->get_settings_for_display('image_list');
        if(!empty($image_list[$idx]['item_width'])){
            $return['item_width'] = str_replace('-', '.', $image_list[$idx]['item_width']);
        }
        if(!empty($image_list[$idx]['item_height'])){
            $return['item_height'] = str_replace('-', '.', $image_list[$idx]['item_height']);
        }

        return $return;
    }

}
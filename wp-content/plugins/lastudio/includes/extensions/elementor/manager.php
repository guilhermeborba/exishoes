<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

add_action('plugins_loaded', function (){
    if( ($typography = LASTUDIO_PLUGIN_PATH . 'includes/extensions/elementor/override/includes/controls/groups/typography.php') && file_exists($typography) ) {
        require_once $typography;
    }

    add_action( 'elementor/init', 'lastudio_elementor_register_default_breakpoint' );
    add_action( 'elementor/init', 'lastudio_elementor_update_old_data' );

}, 0);

function lastudio_elementor_register_default_breakpoint(){
    $has_register_breakpoint = get_option('lastudio_has_register_breakpoint', false);
    if(empty($has_register_breakpoint)){
        update_option('elementor_experiment-additional_custom_breakpoints', 'active');
        $kit_active_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
        $raw_kit_settings = get_post_meta( $kit_active_id, '_elementor_page_settings', true );
        if(empty($raw_kit_settings)){
            $raw_kit_settings = [];
        }
        $default_settings = [
            'space_between_widgets' => '0',
            'page_title_selector' => '#section_page_header',
            'stretched_section_container' => '#outer-wrap > #wrap',
            'active_breakpoints' => [
                'viewport_mobile',
                'viewport_mobile_extra',
                'viewport_tablet',
                'viewport_laptop',
            ],
            'viewport_mobile' => 575,
            'viewport_md' => 576,
            'viewport_mobile_extra' => 991,
            'viewport_tablet' => 1279,
            'viewport_lg' => 1280,
            'viewport_laptop' => 1699,
            'system_colors' => [
                [
                    '_id' => 'primary',
                    'title' => esc_html__( 'Primary', 'elementor' )
                ],
                [
                    '_id' => 'secondary',
                    'title' => esc_html__( 'Secondary', 'elementor' )
                ],
                [
                    '_id' => 'text',
                    'title' => esc_html__( 'Text', 'elementor' )
                ],
                [
                    '_id' => 'accent',
                    'title' => esc_html__( 'Accent', 'elementor' )
                ]
            ],
            'system_typography' => [
                [
                    '_id' => 'primary',
                    'title' => esc_html__( 'Primary', 'elementor' )
                ],
                [
                    '_id' => 'secondary',
                    'title' => esc_html__( 'Secondary', 'elementor' )
                ],
                [
                    '_id' => 'text',
                    'title' => esc_html__( 'Text', 'elementor' )
                ],
                [
                    '_id' => 'accent',
                    'title' => esc_html__( 'Accent', 'elementor' )
                ]
            ]
        ];
        $raw_kit_settings = array_merge($raw_kit_settings, $default_settings);
        update_post_meta( $kit_active_id, '_elementor_page_settings', $raw_kit_settings );
        Elementor\Core\Breakpoints\Manager::compile_stylesheet_templates();
        update_option('lastudio_has_register_breakpoint', true);
    }
}

function lastudio_elementor_update_old_data(){
    global $wpdb;
    $wild = '%';
    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT count(meta_id) as total FROM {$wpdb->postmeta} WHERE `meta_key`='_elementor_data' AND `meta_value` LIKE %s",  $wild . $wpdb->esc_like('tabletportrait') . $wild)
    );
    if(!empty($results) && !empty($results[0]->total)){
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->postmeta} SET `meta_value` = REPLACE(`meta_value`, 'tabletportrait', 'mobile_extra') WHERE `meta_key`='_elementor_data' AND `meta_value` LIKE %s",
                $wild . $wpdb->esc_like('tabletportrait') . $wild
            )
        );
    }
}

require_once LASTUDIO_PLUGIN_PATH . 'includes/extensions/elementor/override/basic.php';
require_once LASTUDIO_PLUGIN_PATH . 'includes/extensions/elementor/override/advance.php';
require_once LASTUDIO_PLUGIN_PATH . 'includes/extensions/elementor/override/widgets.php';

function lastudio_elementor_autoload( $class ) {
    if ( 0 !== strpos( $class, 'LaStudio_Element' ) ) {
        return;
    }
    $filename = strtolower(
        preg_replace(
            [ '/^' . 'LaStudio_Element' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
            [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
            $class
        )
    );

    $filename = LASTUDIO_PLUGIN_PATH .'includes/extensions/elementor/' . $filename . '.php';

    if ( is_readable( $filename ) ) {
        include( $filename );
    }
}

spl_autoload_register( 'lastudio_elementor_autoload' );

function lastudio_elementor_template_path(){
    return apply_filters( 'LaStudioElement/template-path', 'partials/elementor/' );
}

function lastudio_elementor_get_template( $name = null ){

    $template = locate_template( lastudio_elementor_template_path() . $name );

    if ( ! $template ) {
        $template = LASTUDIO_PLUGIN_PATH  . 'includes/extensions/elementor/templates/' . str_replace('lastudio-', '', $name);
    }
    if ( file_exists( $template ) ) {
        return $template;
    }
    else {
        return false;
    }
}

function lastudio_elementor_get_all_modules(){
    $elementor_modules = [
        'advanced-carousel' => 'Advanced_Carousel',
        'advanced-map' => 'Advanced_Map',
        'animated-box' => 'Animated_Box',
        'animated-text' => 'Animated_Text',
        'audio' => 'Audio',
        'banner' => 'Banner',
        'button' => 'Button',
        'circle-progress' => 'Circle_Progress',
        'countdown-timer' => 'Countdown_Timer',
        'dropbar'  => 'Dropbar',
        'headline' => 'Headline',
        'horizontal-timeline' => 'Horizontal_Timeline',
        'image-comparison' => 'Image_Comparison',
        'images-layout' => 'Images_Layout',
        'instagram-gallery' => 'Instagram_Gallery',
        'portfolio' => 'Portfolio',
        'posts' => 'Posts',
        'price-list' => 'Price_List',
        'pricing-table' => 'Pricing_Table',
        'progress-bar' => 'Progress_Bar',
        'scroll-navigation' => 'Scroll_Navigation',
        'services' => 'Services',
        'subscribe-form' => 'Subscribe_Form',
        'table' => 'Table',
        'tabs' => 'Tabs',
        'team-member' => 'Team_Member',
        'testimonials' => 'Testimonials',
        'timeline' => 'Timeline',
        'video' => 'Video',
        'breadcrumbs' => 'Breadcrumbs',
        'post-navigation' => 'Post_Navigation',
        'slides' => 'Slides',
        'weather' => 'Weather',
    ];

    return $elementor_modules;
}

function lastudio_elementor_get_active_modules(){

    $all_modules = lastudio_elementor_get_all_modules();

    $active_modules = get_option('lastudio_elementor_modules');

    $enable_modules = [];

    if(!empty($active_modules)){
        foreach ($active_modules as $module => $active ){
            if(!empty($active) && isset($all_modules[$module])){
                $enable_modules[$module] = $all_modules[$module];
            }
        }
    }

    if(defined('WPCF7_PLUGIN_URL')){
        $enable_modules['contact-form-7'] = 'Contact_Form_7';
    }
    if(class_exists('WooCommerce')){
        $enable_modules['products'] = 'Products';
    }

    return $enable_modules;
}

function lastudio_elementor_get_resource_dependencies(){

    $resource_base_url = apply_filters('LaStudioElement/resource-base-url', LASTUDIO_PLUGIN_URL . 'public/element');

    $resource_lib_url = LASTUDIO_PLUGIN_URL . 'public/element';

    $google_api_key = apply_filters('LaStudioElement/advanced-map/api', '');

    $min = (apply_filters('lasf_dev_mode', false) || WP_DEBUG) ? '' : '.min';

    $resource_dependencies = [
        'advanced-carousel' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-advanced-carousel-elm',
                    'src'       => $resource_base_url . '/css/carousel'.$min.'.css'
                ],
                [
                    'handler'   => 'lastudio-banner-elm',
                    'src'       => $resource_base_url . '/css/banner'.$min.'.css'
                ]
            ]
        ],
        'slides' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-slides-elm',
                    'src'       => $resource_base_url . '/css/slides'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-slides-elm',
                    'src'       => $resource_base_url . '/js/slides'.$min.'.js'
                ]
            ]
        ],
        'advanced-map' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-advanced-map-elm',
                    'src'       => $resource_base_url . '/css/map'.$min.'.css'
                ]
            ],
            'js'    => [
                [
                    'handler'   => 'google-maps-api',
                    'src'       => add_query_arg( array( 'key' => $google_api_key ), 'https://maps.googleapis.com/maps/api/js' )
                ],
                [
                    'handler'   => 'lastudio-advanced-map-elm',
                    'src'       => $resource_base_url . '/js/advanced-map'.$min.'.js'
                ]
            ]
        ],
        'animated-box' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-animated-box-elm',
                    'src'       => $resource_base_url . '/css/animated-box'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-animated-box-elm',
                    'src'       => $resource_base_url . '/js/animated-box'.$min.'.js'
                ]
            ]
        ],
        'animated-text' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-animated-text-elm',
                    'src'       => $resource_base_url . '/css/animated-text'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-anime-js',
                    'src'       => $resource_lib_url . '/js/lib/anime.min.js'
                ],
                [
                    'handler'   => 'lastudio-animated-text-elm',
                    'src'       => $resource_base_url . '/js/animated-text'.$min.'.js'
                ]
            ]
        ],
        'audio' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-audio-elm',
                    'src'       => $resource_base_url . '/css/audio'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-audio-elm',
                    'src'       => $resource_base_url . '/js/audio'.$min.'.js'
                ]
            ]
        ],
        'banner' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-banner-elm',
                    'src'       => $resource_base_url . '/css/banner'.$min.'.css'
                ]
            ],

        ],
        'button' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-button-elm',
                    'src'       => $resource_base_url . '/css/button'.$min.'.css'
                ]
            ]
        ],
        'circle-progress' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-circle-progress-elm',
                    'src'       => $resource_base_url . '/css/circle-progress'.$min.'.css'
                ]
            ]
        ],
        'dropbar'  => [
            'css'   => [
                [
                    'handler'   => 'lastudio-dropbar-elm',
                    'src'       => $resource_base_url . '/css/dropbar'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-dropbar-elm',
                    'src'       => $resource_base_url . '/js/dropbar'.$min.'.js'
                ]
            ]
        ],
        'headline' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-headline-elm',
                    'src'       => $resource_base_url . '/css/headline'.$min.'.css'
                ]
            ]
        ],
        'horizontal-timeline' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-horizontal-timeline-elm',
                    'src'       => $resource_base_url . '/css/horizontal-timeline'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-horizontal-timeline-elm',
                    'src'       => $resource_base_url . '/js/horizontal-timeline'.$min.'.js'
                ]
            ]
        ],
        'image-comparison' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-juxtapose',
                    'src'       => $resource_base_url . '/css/juxtapose'.$min.'.css'
                ],
                [
                    'handler'   => 'lastudio-image-comparison-elm',
                    'src'       => $resource_base_url . '/css/image-comparison'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-juxtapose',
                    'src'       => $resource_lib_url . '/js/lib/juxtapose.min.js'
                ],
                [
                    'handler'   => 'lastudio-image-comparison-elm',
                    'src'       => $resource_base_url . '/js/image-comparison'.$min.'.js'
                ]
            ]
        ],
        'images-layout' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-images-layout-elm',
                    'src'       => $resource_base_url . '/css/image-layout'.$min.'.css'
                ]
            ]
        ],
        'instagram-gallery' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-instagram-gallery-elm',
                    'src'       => $resource_base_url . '/css/instagram'.$min.'.css'
                ]
            ]
        ],
        'price-list' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-price-list-elm',
                    'src'       => $resource_base_url . '/css/price-list'.$min.'.css'
                ]
            ]
        ],
        'pricing-table' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-pricing-table-elm',
                    'src'       => $resource_base_url . '/css/pricing-table'.$min.'.css'
                ]
            ]
        ],
        'progress-bar' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-progress-bar-elm',
                    'src'       => $resource_base_url . '/css/progress-bar'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-anime-js',
                    'src'       => $resource_lib_url . '/js/lib/anime.min.js'
                ],
                [
                    'handler'   => 'lastudio-progress-bar-elm',
                    'src'       => $resource_base_url . '/js/progress-bar'.$min.'.js'
                ]
            ]
        ],
        'scroll-navigation' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-scroll-navigation-elm',
                    'src'       => $resource_base_url . '/css/scroll-navigation'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-scroll-navigation-elm',
                    'src'       => $resource_base_url . '/js/scroll-navigation'.$min.'.js'
                ]
            ],

        ],
        'services' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-services-elm',
                    'src'       => $resource_base_url . '/css/services'.$min.'.css'
                ]
            ]
        ],
        'subscribe-form' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-subscribe-form-elm',
                    'src'       => $resource_base_url . '/css/subscribe-form'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-subscribe-form-elm',
                    'src'       => $resource_base_url . '/js/subscribe-form'.$min.'.js'
                ]
            ]
        ],
        'table' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-table-elm',
                    'src'       => $resource_base_url . '/css/table'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'jquery-tablesorter',
                    'src'       => $resource_lib_url . '/js/lib/tablesorter.min.js'
                ],
                [
                    'handler'   => 'lastudio-table-elm',
                    'src'       => $resource_base_url . '/js/table'.$min.'.js'
                ]
            ],
        ],
        'tabs' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-tabs-elm',
                    'src'       => $resource_base_url . '/css/tabs'.$min.'.css'
                ]
            ]
        ],
        'team-member' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-team-member-elm',
                    'src'       => $resource_base_url . '/css/team-member'.$min.'.css'
                ]
            ]
        ],
        'testimonials' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-testimonials-elm',
                    'src'       => $resource_base_url . '/css/testimonials'.$min.'.css'
                ]
            ]
        ],
        'timeline' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-timeline-elm',
                    'src'       => $resource_base_url . '/css/timeline'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-timeline-elm',
                    'src'       => $resource_base_url . '/js/timeline'.$min.'.js'
                ]
            ]
        ],
        'video' => [
            'css'   => [
                [
                    'handler'   => 'lastudio-video-elm',
                    'src'       => $resource_base_url . '/css/video'.$min.'.css'
                ]
            ],
            'js'   => [
                [
                    'handler'   => 'lastudio-video-elm',
                    'src'       => $resource_base_url . '/js/video'.$min.'.js'
                ]
            ]
        ]
    ];

    $resource_dependencies = apply_filters('LaStudioElement/resource-dependencies', $resource_dependencies);

    $enable_modules = lastudio_elementor_get_active_modules();

    $modules = [];

    if(!empty($enable_modules)){
        foreach ($enable_modules as $k => $v){
            if(isset($resource_dependencies[$k])){
                $modules[$k] = $resource_dependencies[$k];
            }
        }
    }

    return apply_filters('LaStudioElement/module-enabled-resource-dependency', $modules);
}

function lastudio_elementor_register_module_assets(){

    $min = (apply_filters('lasf_dev_mode', false) || WP_DEBUG) ? '' : '.min';

    $theme_version = defined('WP_DEBUG') && WP_DEBUG ? time() : LASTUDIO_VERSION;

    $modules = lastudio_elementor_get_resource_dependencies();

    if(!empty($modules)){
        foreach ($modules as $module => $resource){
            if(!empty($resource['css'])){
                foreach ($resource['css'] as $css){
                    wp_register_style($css['handler'], $css['src'], false, $theme_version);
                }
            }
            if(!empty($resource['js'])){
                foreach ($resource['js'] as $js){
                    wp_register_script($js['handler'], $js['src'], false, $theme_version, true);
                }
            }
        }
    }

    $resource_base_url = apply_filters('LaStudioElement/resource-base-url', LASTUDIO_PLUGIN_URL . 'public/element');
    $resource_lib_url = LASTUDIO_PLUGIN_URL . 'public/element';

    if (lastudio_get_theme_support('elementor::css-transform')) {
        $css_transform = ".la-css-transform-yes{-webkit-transition-duration:var(--la-tfx-transition-duration,.2s);transition-duration:var(--la-tfx-transition-duration,.2s);-webkit-transition-property:-webkit-transform;transition-property:transform;transition-property:transform,-webkit-transform;-webkit-transform:translate(var(--la-tfx-translate-x,0),var(--la-tfx-translate-y,0)) scale(var(--la-tfx-scale-x,1),var(--la-tfx-scale-y,1)) skew(var(--la-tfx-skew-x,0),var(--la-tfx-skew-y,0)) rotateX(var(--la-tfx-rotate-x,0)) rotateY(var(--la-tfx-rotate-y,0)) rotateZ(var(--la-tfx-rotate-z,0));transform:translate(var(--la-tfx-translate-x,0),var(--la-tfx-translate-y,0)) scale(var(--la-tfx-scale-x,1),var(--la-tfx-scale-y,1)) skew(var(--la-tfx-skew-x,0),var(--la-tfx-skew-y,0)) rotateX(var(--la-tfx-rotate-x,0)) rotateY(var(--la-tfx-rotate-y,0)) rotateZ(var(--la-tfx-rotate-z,0))}.la-css-transform-yes:hover{-webkit-transform:translate(var(--la-tfx-translate-x-hover,var(--la-tfx-translate-x,0)),var(--la-tfx-translate-y-hover,var(--la-tfx-translate-y,0))) scale(var(--la-tfx-scale-x-hover,var(--la-tfx-scale-x,1)),var(--la-tfx-scale-y-hover,var(--la-tfx-scale-y,1))) skew(var(--la-tfx-skew-x-hover,var(--la-tfx-skew-x,0)),var(--la-tfx-skew-y-hover,var(--la-tfx-skew-y,0))) rotateX(var(--la-tfx-rotate-x-hover,var(--la-tfx-rotate-x,0))) rotateY(var(--la-tfx-rotate-y-hover,var(--la-tfx-rotate-y,0))) rotateZ(var(--la-tfx-rotate-z-hover,var(--la-tfx-rotate-z,0)));transform:translate(var(--la-tfx-translate-x-hover,var(--la-tfx-translate-x,0)),var(--la-tfx-translate-y-hover,var(--la-tfx-translate-y,0))) scale(var(--la-tfx-scale-x-hover,var(--la-tfx-scale-x,1)),var(--la-tfx-scale-y-hover,var(--la-tfx-scale-y,1))) skew(var(--la-tfx-skew-x-hover,var(--la-tfx-skew-x,0)),var(--la-tfx-skew-y-hover,var(--la-tfx-skew-y,0))) rotateX(var(--la-tfx-rotate-x-hover,var(--la-tfx-rotate-x,0))) rotateY(var(--la-tfx-rotate-y-hover,var(--la-tfx-rotate-y,0))) rotateZ(var(--la-tfx-rotate-z-hover,var(--la-tfx-rotate-z,0)))}";
        wp_add_inline_style('elementor-frontend', $css_transform);
    }
    if (lastudio_get_theme_support('elementor::wrapper-links')) {
        wp_register_script(
            'lastudio-wrapper-links',
            $resource_base_url . '/js/wrapper-links'.$min.'.js',
            [ 'elementor-frontend' ],
            $theme_version,
            true
        );
    }
    if (lastudio_get_theme_support('elementor::floating-effects')) {
        wp_register_script(
            'lastudio-anime-js',
            $resource_lib_url . '/js/lib/anime.min.js',
            false,
            $theme_version,
            true
        );
        wp_register_script(
            'lastudio-floating-effects',
            $resource_base_url . '/js/floating-effects'.$min.'.js',
            [ 'lastudio-anime-js', 'elementor-frontend' ],
            $theme_version,
            true
        );
    }

    /**
     * Enqueue Motion & Sticky Scripts
     */
    if(!defined('ELEMENTOR_PRO_VERSION') && !lastudio_get_theme_support('lastudio-kit::sticky-control')){
        wp_register_script(
            'lastudio-sticky',
            $resource_lib_url . '/js/lib/jquery.sticky.min.js',
            [
                'jquery',
            ],
            $theme_version,
            true
        );
    }

    if(!defined('ELEMENTOR_PRO_VERSION') && !lastudio_get_theme_support('lastudio-kit::motion-effects')){
        wp_register_script(
            'lastudio-motion-fx',
            $resource_lib_url . '/js/lib/motion-fx.js' ,
            [
                'elementor-frontend-modules',
                'lastudio-sticky'
            ],
            $theme_version,
            true
        );
    }

    wp_register_script(
        'lastudio-element-front',
        $resource_base_url . '/js/lastudio-element'.$min.'.js' ,
        [ 'elementor-frontend' ],
        $theme_version,
        true
    );
    wp_localize_script(
        'lastudio-element-front',
        'LaStudioElementConfigs',
        apply_filters( 'LaStudioElement/frontend/localize-data', [
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'invalidMail'   => esc_attr__( 'Please specify a valid e-mail', 'lastudio' ),
        ] )
    );
}

add_action( 'elementor/frontend/after_register_styles', 'lastudio_elementor_register_module_assets' );

function lastudio_elementor_init_hook(){
    LaStudio_Element\Classes\Query_Control::instance();
    Elementor\Plugin::instance()->elements_manager->add_category( 'lastudio', [
            'title' => esc_html__( 'LA-Studio Element', 'lastudio' ),
            'icon'  => 'font'
        ], 1 );
}

add_action('elementor/init', 'lastudio_elementor_init_hook' );

add_action('elementor/controls/controls_registered', function( $controls_manager ){
    $controls_manager->add_group_control( \LaStudio_Element\Controls\Group_Control_Box_Style::get_type(), new \LaStudio_Element\Controls\Group_Control_Box_Style() );
    $controls_manager->add_group_control( \LaStudio_Element\Controls\Group_Control_Query::get_type(), new \LaStudio_Element\Controls\Group_Control_Query() );
    if(!defined('ELEMENTOR_PRO_VERSION')){
        $controls_manager->add_group_control( \LaStudio_Element\Controls\Group_Control_Motion_Fx::get_type(), new \LaStudio_Element\Controls\Group_Control_Motion_Fx() );
    }
});

add_action('elementor/widgets/widgets_registered', function(){

    $modules = lastudio_elementor_get_active_modules();

    if( !empty($modules) ) {
        foreach ($modules as $module => $name){
            $class_name = 'LaStudio_Element\\Widgets\\' . $name;
            if(class_exists($class_name)){
                Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name() );
            }
        }
    }

});

add_action('elementor/editor/after_enqueue_styles', function(){
    $theme_version = defined('WP_DEBUG') && WP_DEBUG ? time() : LASTUDIO_VERSION;
    wp_enqueue_style( 'lastudio-elementor', LASTUDIO_PLUGIN_URL . 'admin/css/elementor.css', false, $theme_version);
});

add_action('elementor/frontend/after_render', function(){
    $scriptNeedRemove = array(
        'jquery-slick',
    );
    foreach ($scriptNeedRemove as $script) {
        if (wp_script_is($script, 'registered')) {
            wp_dequeue_script($script);
        }
    }
});

add_filter('lastudio/theme/defer_scripts', function( $scripts ){

    $modules = lastudio_elementor_get_resource_dependencies();
    if(!empty($modules)){
        foreach ($modules as $module => $resource){
            if(!empty($resource['js'])){
                foreach ($resource['js'] as $js){
                    $scripts[] = $js['handler'];
                }
            }
        }
    }

    $scripts[] = 'lastudio-element-front';
    $scripts[] = 'lastudio-sticky';
    $scripts[] = 'lastudio-motion-fx';

    return $scripts;
});

add_filter('elementor/icons_manager/additional_tabs', function( $tabs ){
    $tabs['dlicon'] = [
        'name' => 'dlicon',
        'label' => __( 'DL Icons', 'lastudio' ),
        'url' =>  LASTUDIO_PLUGIN_URL . 'public/css/dlicon.css',
        'prefix' => '',
        'displayPrefix' => 'dlicon',
        'labelIcon' => 'fas fa-star',
        'ver' => '1.0.0',
        'fetchJson' => LASTUDIO_PLUGIN_URL . 'public/fonts/dlicon.json',
        'native' => false
    ];
    return $tabs;
});

function lastudio_elementor_tools_get_select_range( $to = 10 ){
    $range = range( 1, $to );
    return array_combine( $range, $range );
}

function lastudio_elementor_tools_get_nextprev_arrows_list( $type = '' ){
    if($type == 'prev'){
        return apply_filters(
            'lastudio_elements/carousel/available_arrows/prev',
            array(
                'lastudioicon-left-arrow'           => __( 'Default', 'lastudio' ),
                'lastudioicon-small-triangle-left'  => __( 'Small Triangle', 'lastudio' ),
                'lastudioicon-triangle-left'        => __( 'Triangle', 'lastudio' ),
                'lastudioicon-arrow-left'           => __( 'Arrow', 'lastudio' ),
                'lastudioicon-svgleft'              => __( 'SVG', 'lastudio' ),
            )
        );
    }
    return apply_filters(
        'lastudio_elements/carousel/available_arrows/next',
        array(
            'lastudioicon-right-arrow'           => __( 'Default', 'lastudio' ),
            'lastudioicon-small-triangle-right'  => __( 'Small Triangle', 'lastudio' ),
            'lastudioicon-triangle-right'        => __( 'Triangle', 'lastudio' ),
            'lastudioicon-arrow-right'           => __( 'Arrow', 'lastudio' ),
            'lastudioicon-svgright'              => __( 'SVG', 'lastudio' ),
        )
    );
}

function lastudio_elementor_tools_get_carousel_arrow( $classes = [], $icons = []){
    $format = apply_filters( 'LaStudioElement/carousel/arrows_format', '<button class="lastudio-arrow %1$s"><i class="%2$s"></i></button>', $classes, $icons );

    return sprintf( $format, implode( ' ', $classes ), implode( ' ', $icons ) );
}

function lastudio_elementor_get_public_post_types( $args = [] ){
    $post_type_args = [
        'show_in_nav_menus' => true,
    ];

    if ( ! empty( $args['post_type'] ) ) {
        $post_type_args['name'] = $args['post_type'];
    }

    $_post_types = get_post_types( $post_type_args, 'objects' );

    $post_types = [];

    foreach ( $_post_types as $post_type => $object ) {
        $post_types[ $post_type ] = $object->label;
    }

    return $post_types;
}

function lastudio_element_render_grid_classes( $columns = [] ){
    $columns = wp_parse_args( $columns, array(
        'desktop'  => '1',
        'laptop'   => '',
        'tablet'   => '',
        'mobile'  => '',
        'xmobile'   => ''
    ) );

    $replaces = array(
        'xmobile' => 'xmobile-block-grid',
        'mobile' => 'mobile-block-grid',
        'tablet' => 'tablet-block-grid',
        'laptop' => 'laptop-block-grid',
        'desktop' => 'block-grid'
    );

    $classes = array();

    foreach ( $columns as $device => $cols ) {
        if ( ! empty( $cols ) ) {
            $classes[] = sprintf( '%1$s-%2$s', $replaces[$device], $cols );
        }
    }
    return implode( ' ' , $classes );
}

add_action('la_ajax_lastudio_get_products_output', function ($content, $error){
    $data = __('Nothing found', 'lastudio');
    if(!empty($content['args'])){
        $settings = $content['args'];
        $settings['in_elementor'] = '';
        $settings['enable_ajax_load'] = '';
        $shortcode = new LaStudio_Element\Classes\Products_Renderer( $settings, 'products' );
        $data =  '<div class="ajax-response-wrap">' . $shortcode->get_content() . '</div>';
    }
    wp_send_json_success($data);
}, 10, 2);

/** Fix Elementor AutoSave `revision`  problem */
add_filter('wp_insert_post_data', function ( $data ){
    if(strpos($data['post_content'], '<!-- Created With Elementor -->') !== false ){
        $data['post_content'] = '<!-- Created With Elementor --><!-- ' . current_time('timestamp') . ' -->';
    }
    return $data;
}, 10);
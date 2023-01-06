<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$prefix = 'luxsa_options';

$la_extension_available = get_option('la_extension_available');

LASF::createOptions( $prefix, array(
    'menu_title' => esc_html_x('Theme Options', 'admin-view', 'luxsa'),
    'menu_type' => 'submenu',
    'menu_parent' => 'themes.php',
    'menu_slug' => 'theme_options',
    'show_search' => false,
    'show_all_options' => false,
    'show_reset_all' => true,
    'show_reset_section' => true,
    'output_css' => false,
    'show_in_customizer' => array(
        'output_css' => false,
        'enqueue_webfont' => false
    ),
    'framework_title' => esc_html_x('Luxsa', 'admin-view', 'luxsa')
) );

/**
 * General Panel
 */
LASF::createSection( $prefix, array(
    'id'    => 'general_panel',
    'title' => esc_html_x('General', 'admin-view', 'luxsa'),
    'icon'  => 'fa fa-tachometer'
) );

/**
 * General Panel - General
 */
LASF::createSection( $prefix, array(
    'parent'    => 'general_panel',
    'title' => esc_html_x('General', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'        => 'layout',
            'title'     => esc_html_x('Global Layout', 'admin-view', 'luxsa'),
            'type'      => 'image_select',
            'default'   => 'col-1c',
            'subtitle'  => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_main_layout_opts(true, false)
        ),
        array(
            'id'        => 'body_boxed',
            'type'      => 'button_set',
            'default'   => 'no',
            'title'     => esc_html_x('Enable Layout Boxed', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_opts(false)
        ),
        array(
            'id'        => 'body_max_width',
            'type'      => 'slider',
            'default'    => 1230,
            'title'     => esc_html_x( 'Body Max Width', 'admin-view', 'luxsa' ),
            'dependency' => array('body_boxed', '==', 'yes'),
            'min'       => 800,
            'max'       => 2000,
            'step'      => 5,
            'unit'      => 'px'
        ),
        array(
            'id'        => 'main_full_width',
            'type'      => 'button_set',
            'default'   => 'no',
            'title'     => esc_html_x('100% Main Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_opts(false)
        ),

        luxsa_render_responsive_main_space_options(array(
            'id'    => 'main_space',
            'title' => esc_html_x('Custom Main Space', 'admin-view', 'luxsa')
        )),

        luxsa_render_custom_container_width(array(
            'id'        => 'container_width',
            'title'     => esc_html_x('Custom Container Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 1170px', 'admin-view', 'luxsa'),
        )),

        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_width',
            'title'     => esc_html_x('Custom Sidebar Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 300px', 'admin-view', 'luxsa'),
        )),
        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_space',
            'title'     => esc_html_x('Custom Sidebar Space', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 40px', 'admin-view', 'luxsa'),
        )),


        luxsa_render_custom_container_width(array(
            'id'        => 'container_width_single_page',
            'title'     => esc_html_x('[Pages] Custom Container Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 1170px', 'admin-view', 'luxsa'),
        )),

        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_width_single_page',
            'title'     => esc_html_x('[Pages] Custom Sidebar Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 300px', 'admin-view', 'luxsa'),
        )),
        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_space_single_page',
            'title'     => esc_html_x('[Pages] Custom Sidebar Space', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 40px', 'admin-view', 'luxsa'),
        )),

        array(
            'id'        => 'backtotop_btn',
            'type'      => 'button_set',
            'default'   => 'no',
            'title'     => esc_html_x('"Back to top" Button', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to show "Back to top" button', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_opts(false)
        ),
        array(
            'id'        => 'activate_lazyload',
            'type'      => 'button_set',
            'default'   => 'no',
            'title'     => esc_html_x('"Enable Lazyload Image', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_opts(false)
        )
    )
) );

/**
 * General Panel - Favicon
 */
LASF::createSection( $prefix, array(
    'parent'    => 'general_panel',
    'title' => esc_html_x('Custom Favicon', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'        => 'favicon',
            'type'      => 'media',
            'library'   => 'image',
            'title'     => esc_html_x('Favicon', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Favicon for your website at 16px x 16px.', 'admin-view', 'luxsa')
        ),
        array(
            'id'        => 'favicon_iphone',
            'type'      => 'media',
            'library'   => 'image',
            'title'     => esc_html_x('Apple iPhone Icon Upload', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Favicon for Apple iPhone at 57px x 57px.', 'admin-view', 'luxsa')
        ),
        array(
            'id'        => 'favicon_ipad',
            'type'      => 'media',
            'library'   => 'image',
            'title'     => esc_html_x('Apple iPad Icon Upload', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Favicon for Apple iPad at 72px x 72px.', 'admin-view', 'luxsa')
        )
    )
));

/**
 * General Panel - Logo
 */
if(class_exists('LAHB', false)){
    LASF::createSection( $prefix, array(
        'parent'    => 'general_panel',
        'title' => esc_html_x('Logo', 'admin-view', 'luxsa'),
        'icon'      => 'fas fa-check',
        'fields'      => array(
            array(
                'type'    => 'content',
                'class'   => 'info',
                'content' => sprintf(
                    '<a class="button button-primary big-button" href="%s"><i class="dashicons dashicons-external"></i>%s</a>',
                    add_query_arg('page', 'lastudio_header_builder_setting', admin_url('themes.php')),
                    esc_html__('Open Header Builder', 'luxsa')
                )
            )
        )
    ));
}
else{
    LASF::createSection( $prefix, array(
        'parent'    => 'general_panel',
        'title'     => esc_html_x('Logo', 'admin-view', 'luxsa'),
        'icon'      => 'fas fa-check',
        'fields'      => array(
            array(
                'id'        => 'logo',
                'type'      => 'media',
                'library'   => 'image',
                'title'     => esc_html_x('Default Logo', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Select an image file for your logo.', 'admin-view', 'luxsa')
            )
        )
    ));
}

/**
 * General Panel - Colors
 */
LASF::createSection( $prefix, array(
    'parent'    => 'general_panel',
    'title'     => esc_html_x('Colors', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-paint-brush',
    'fields'      => array(
        array(
            'id'        => 'body_background',
            'type'      => 'background',
            'title'     => esc_html_x('Body Background', 'admin-view', 'luxsa')
        ),
        array(
            'id'        => 'body_boxed_background',
            'type'      => 'background',
            'title'     => esc_html_x('Body Boxed Background', 'admin-view', 'luxsa'),
            'dependency' => array('body_boxed', '==', 'yes'),
        ),
        array(
            'id'        => 'primary_color',
            'default'   => Luxsa_Options::get_color_default('primary_color'),
            'type'      => 'color',
            'title'     => esc_html_x('Primary Color', 'admin-view', 'luxsa')
        ),
        array(
            'id'        => 'secondary_color',
            'default'   => Luxsa_Options::get_color_default('secondary_color'),
            'type'      => 'color',
            'title'     => esc_html_x('Secondary Color', 'admin-view', 'luxsa')
        ),
        array(
            'id'        => 'three_color',
            'default'   => Luxsa_Options::get_color_default('three_color'),
            'type'      => 'color',
            'title'     => esc_html_x('Third Color', 'admin-view', 'luxsa')
        ),
        array(
            'id'        => 'border_color',
            'default'   => Luxsa_Options::get_color_default('border_color'),
            'type'      => 'color',
            'title'     => esc_html_x('Border Color', 'admin-view', 'luxsa')
        )
    )
));

/**
 * General Panel - Preload
 */
LASF::createSection( $prefix, array(
    'parent'    => 'general_panel',
    'title'     => esc_html_x('Page Preload', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-refresh fa-spin',
    'fields'      => array(
        array(
            'id'        => 'page_loading_animation',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Page Preload Animation', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to show the icon/images loading animation before view site', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        ),
        array(
            'id'        => 'page_loading_style',
            'type'      => 'select',
            'default'   => '1',
            'title'     => esc_html_x('Select Preload Style', 'admin-view', 'luxsa'),
            'options'   => array(
                '1'         => esc_html_x('Style 1', 'admin-view', 'luxsa'),
                '2'         => esc_html_x('Style 2', 'admin-view', 'luxsa'),
                '3'         => esc_html_x('Style 3', 'admin-view', 'luxsa'),
                '4'         => esc_html_x('Style 4', 'admin-view', 'luxsa'),
                '5'         => esc_html_x('Style 5', 'admin-view', 'luxsa'),
                'custom'    => esc_html_x('Custom image', 'admin-view', 'luxsa')
            ),
            'dependency' => array( 'page_loading_animation', '==', 'on' ),
        ),
        array(
            'id'        => 'page_loading_custom',
            'type'      => 'media',
            'library'   => 'image',
            'title'     => esc_html_x('Custom Page Loading Image', 'admin-view', 'luxsa'),
            'add_title' => esc_html_x('Add Image', 'admin-view', 'luxsa'),
            'dependency'=> array('page_loading_animation|page_loading_style', '==|==', 'on|custom'),
        ),
        array(
            'id'        => 'page_loading_bgcolor',
            'default'   => '',
            'type'      => 'color',
            'title'     => esc_html_x('Page Loading Background Color', 'admin-view', 'luxsa'),
            'dependency' => array( 'page_loading_animation', '==', 'on' ),
        ),
        array(
            'id'        => 'page_loading_textcolor',
            'default'   => '',
            'type'      => 'color',
            'title'     => esc_html_x('Page Loading Text Color', 'admin-view', 'luxsa'),
            'dependency' => array( 'page_loading_animation', '==', 'on' ),
        ),
    )
));


/**
 * Typography Panel
 */
LASF::createSection( $prefix, array(
    'id'    => 'fonts_panel',
    'title' => esc_html_x('Typography', 'admin-view', 'luxsa'),
    'icon'  => 'fa fa-font'
) );

/**
 * Typography Panel - Body
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Body', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'body_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        ),
	    array(
		    'type'              => 'subheading',
		    'content'           => esc_html_x('Highlight Font Family', 'admin-view', 'luxsa')
	    ),
	    array(
            'id'                => 'three_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'font_weight'       => false,
            'font_style'        => true,
            'font_size'         => false,
            'line_height'       => false,
            'letter_spacing'    => false,
            'text_transform'    => false,
            'color'             => false,
            'preview'           => true,
            'subset'            => true,
            'responsive'        => false
        )
    )
));


/**
 * Typography Panel - All Headings
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('All Headings', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'headings_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Heading 1 ( H1 )
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Heading 1 (H1)', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'heading1_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Heading 2 ( H2 )
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Heading 2 (H2)', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'heading2_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Heading 3 ( H3 )
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Heading 3 (H3)', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'heading3_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Heading 4 ( H4 )
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Heading 4 (H4)', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'heading4_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Blog Entry Title
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Blog Entry Title', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'blog_entry_title_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Blog Entry Meta
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Blog Entry Meta', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'blog_entry_meta_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Blog Entry Content
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Blog Entry Content', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'blog_entry_content_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Blog Post Title
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Blog Post Title', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'blog_post_title_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Blog Post Meta
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Blog Post Meta', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'blog_post_meta_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Blog Post Content
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Blog Post Content', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'                => 'blog_post_content_font_family',
            'type'              => 'typography',
            'text_align'        => false,
            'extra_styles'      => true,
            'responsive'        => true
        )
    )
));

/**
 * Typography Panel - Custom
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Custom Selector', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'      => array(
        array(
            'id'        => 'extra_typography',
            'type'      => 'group',
            'class'     => 'group-extra-typography',
            'title'     => esc_html_x('Custom Selector', 'admin-view', 'luxsa'),
            'fields'    => array(
                array(
                    'id'            => 'gid',
                    'type'          => 'text',
                    'title'         => esc_html_x('Group Title', 'admin-view', 'luxsa'),
                ),
                array(
                    'id'            => 'fonts',
                    'type'          => 'typography',
                    'extra_styles'  => true,
                    'responsive'    => true
                ),
                array(
                    'id'            => 'selector',
                    'type'          => 'textarea',
                    'class'         => 'lasf_css_selector',
                    'subtitle'      =>  sprintf(
                        '<a target="_blank" href="%s">%s</a>',
                        '//www.w3schools.com/cssref/css_selectors.asp',
                        esc_html__('What is this ?', 'luxsa')
                    ),
                    'help'          => esc_html_x('In CSS, selectors are patterns used to select the element(s) you want to style.', 'admin-view', 'luxsa'),
                    'title'         => esc_html_x('CSS elements selector', 'admin-view', 'luxsa')
                )
            )
        ),
    )
));

/**
 * Typography Panel - Custom Fonts
 */
LASF::createSection( $prefix, array(
    'parent'    => 'fonts_panel',
    'title'     => esc_html_x('Custom Fonts', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-file-upload',
    'fields'      => array(
        array(
            'id'        => 'custom_fonts',
            'type'      => 'group',
            'class'     => 'group-custom-fonts',
            'title'     => esc_html_x('Custom Fonts', 'admin-view', 'luxsa'),
            'fields'    => array(
                array(
                    'id'            => 'name',
                    'type'          => 'text',
                    'desc'          => esc_html_x('The name of the font as it appears in the options.', 'admin-view', 'luxsa'),
                    'title'         => esc_html_x('Font Name', 'admin-view', 'luxsa'),
                ),
	            array(
		            'id'            => 'type',
		            'type'          => 'select',
		            'title'         => esc_html_x('Font Type', 'admin-view', 'luxsa'),
		            'options'       => array(
			            'upload' => esc_html__('Upload', 'luxsa'),
			            'custom' => esc_html__('Custom', 'luxsa'),
		            )
	            ),
	            array(
		            'id'            => 'url',
		            'type'          => 'text',
		            'title'         => esc_html_x('Font URL', 'admin-view', 'luxsa'),
		            'desc'          => esc_html_x('Enter Custom URL of the font', 'admin-view', 'luxsa'),
		            'dependency'    => array('type', '==', 'custom'),
	            ),
	            array(
		            'id'            => 'variations',
		            'type'          => 'group',
		            'class'         => 'group-custom-font-variation',
		            'title'         => esc_html_x('Font Variation', 'admin-view', 'luxsa'),
		            'dependency'    => array('type', '==', 'upload'),
		            'fields'        => array(
			            array(
				            'id'            => 'weight',
				            'type'          => 'select',
				            'title'         => esc_html_x('Weight', 'admin-view', 'luxsa'),
				            'options'       => array(
					            'normal'    => esc_html__('Normal', 'luxsa'),
					            'bold'      => esc_html__('Bold', 'luxsa'),
					            '100'      => esc_html__('100', 'luxsa'),
					            '200'      => esc_html__('200', 'luxsa'),
					            '300'      => esc_html__('300', 'luxsa'),
					            '400'      => esc_html__('400', 'luxsa'),
					            '500'      => esc_html__('500', 'luxsa'),
					            '600'      => esc_html__('600', 'luxsa'),
					            '700'      => esc_html__('700', 'luxsa'),
					            '800'      => esc_html__('800', 'luxsa'),
					            '900'      => esc_html__('900', 'luxsa'),
				            )
			            ),
			            array(
				            'id'            => 'style',
				            'type'          => 'select',
				            'title'         => esc_html_x('Style', 'admin-view', 'luxsa'),
				            'options'       => array(
					            'normal'        => esc_html__('Normal', 'luxsa'),
					            'italic'        => esc_html__('Italic', 'luxsa'),
					            'oblique'       => esc_html__('Oblique', 'luxsa')
				            )
			            ),
			            array(
				            'id'            => 'woff',
				            'type'          => 'upload',
				            'title'         => esc_html_x('WOFF File', 'admin-view', 'luxsa'),
				            'desc'          => esc_html_x('Upload the font\'s woff file or enter the URL.', 'admin-view', 'luxsa'),
				            'placeholder'   => esc_html_x('The Web Open Font Format, Used by Modern Browsers', 'admin-view', 'luxsa'),
				            'library'       => array(
					            'font/woff',
					            'application/font-woff',
					            'application/x-font-woff',
					            'application/octet-stream'
				            ),
			            ),
			            array(
				            'id'            => 'woff2',
				            'type'          => 'upload',
				            'title'         => esc_html_x('WOFF2 File', 'admin-view', 'luxsa'),
				            'desc'          => esc_html_x('Upload the font\'s woff2 file or enter the URL.', 'admin-view', 'luxsa'),
				            'placeholder'   => esc_html_x('The Web Open Font Format 2, Used by Super Modern Browsers', 'admin-view', 'luxsa'),
				            'library'       => array(
					            'font/woff2',
					            'application/octet-stream',
					            'font/x-woff2'
				            ),
			            ),
			            array(
				            'id'            => 'ttf',
				            'type'          => 'upload',
				            'title'         => esc_html_x('TTF File', 'admin-view', 'luxsa'),
				            'desc'          => esc_html_x('Upload the font\'s ttf file or enter the URL.', 'admin-view', 'luxsa'),
				            'placeholder'   => esc_html_x('TrueType Fonts, Used for better supporting Safari, Android, iOS', 'admin-view', 'luxsa'),
				            'library'       => array(
					            'application/x-font-ttf',
					            'application/octet-stream',
					            'font/ttf'
				            ),
			            ),
			            array(
				            'id'            => 'svg',
				            'type'          => 'upload',
				            'title'         => esc_html_x('SVG File', 'admin-view', 'luxsa'),
				            'desc'          => esc_html_x('Upload the font\'s svg file or enter the URL.', 'admin-view', 'luxsa'),
				            'placeholder'   => esc_html_x('SVG fonts allow SVG to be used as glyphs when displaying text, Used by Legacy iOS', 'admin-view', 'luxsa'),
				            'library'       => array(
					            'image/svg+xml',
					            'application/octet-stream',
					            'image/x-svg+xml'
				            ),
			            )
		            )
	            )
            )
        ),
    )
));


/**
 * Header Panel
 */

/**
 * Header Panel - General
 */
$header_opts = array();

if(class_exists('LAHB', false)){
    $header_opts[] = array(
        'type'    => 'content',
        'class'   => 'info',
        'content' => sprintf(
            '<a class="button button-primary big-button" href="%s"><i class="dashicons dashicons-external"></i>%s</a>',
            add_query_arg('page', 'lastudio_header_builder_setting', admin_url('themes.php')),
            esc_html__('Open Header Builder', 'luxsa')
        )
    );
}
$header_opts[] = array(
    'id' => 'header_transparency',
    'type' => 'button_set',
    'default' => 'no',
    'title' => esc_html_x('Header Transparency', 'admin-view', 'luxsa'),
    'options' => Luxsa_Options::get_config_radio_opts(false)
);

$header_opts[] = array(
    'id' => 'header_sticky',
    'type' => 'button_set',
    'default' => 'no',
    'title' => esc_html_x('Enable Header Sticky', 'admin-view', 'luxsa'),
    'options' => array(
        'no' => esc_html_x('Disable', 'admin-view', 'luxsa'),
        'auto' => esc_html_x('Activate when scroll up', 'admin-view', 'luxsa'),
        'yes' => esc_html_x('Activate when scroll up & down', 'admin-view', 'luxsa')
    )
);

$header_opts[] = array(
    'id'    => 'header_sticky_offset',
    'title' => esc_html_x('Header Sticky Offset', 'admin-view', 'luxsa'),
    'type'  => 'dimensions',
    'height_icon'  => '<i class="fa fa-arrows-alt-v"></i>',
    'width'  => false,
    'height'  => true,
    'units'   => ['px']
);

LASF::createSection( $prefix, array(
    'id'        => 'header_panel',
    'title'     => esc_html_x('Header', 'admin-view', 'luxsa'),
    'icon'  => 'fa fa-arrow-up',
    'fields'    => $header_opts
));

/**
 * Page Header Bar Panel
 */
LASF::createSection( $prefix, array(
    'id'    => 'page_title_bar_panel',
    'title' => esc_html_x('Page Header Bar', 'admin-view', 'luxsa'),
    'icon'  => 'fa fa-sliders'
) );


/**
 * Page Header Bar Panel - Global Page Header
 */

$breadcrumbs_options = array();

$breadcrumbs_options[] = array(
	'id' => 'enable_breadcrumb',
	'type' => 'button_set',
	'default' => 'on',
	'title' => esc_html_x('Enable Breadcrumb', 'admin-view', 'luxsa'),
	'options' => array(
		'on' => esc_html_x('Yes', 'admin-view', 'luxsa'),
		'off' => esc_html_x('No', 'admin-view', 'luxsa')
	)
);

$breadcrumbs_options[] = array(
    'id'        => 'breadcrumb_separator',
    'type'      => 'text',
    'default'   => '>',
    'title'     => esc_html_x('Breadcrumb Separator', 'admin-view', 'luxsa'),
);

$breadcrumbs_options[] = array(
    'id' => 'breadcrumb_home_item',
    'type' => 'button_set',
    'default' => 'text',
    'title' => esc_html_x('Home Item', 'admin-view', 'luxsa'),
    'options' => array(
        'icon' => esc_html_x('Icon', 'admin-view', 'luxsa'),
        'text' => esc_html_x('Text', 'admin-view', 'luxsa')
    )
);
$breadcrumbs_options[] =  array(
    'id'        => 'breadcrumb_translation_home',
    'type'      => 'text',
    'default'   => esc_html__('Home', 'luxsa'),
    'title'     => esc_html_x('Translation for Homepage', 'admin-view', 'luxsa'),
);
$breadcrumbs_options[] = array(
    'id'        => 'breadcrumb_translation_error',
    'type'      => 'text',
    'default'   => esc_html__('404 Not Found', 'luxsa'),
    'title'     => esc_html_x('Translation for "404 Not Found"', 'admin-view', 'luxsa'),
);

$breadcrumbs_options[] = array(
    'id'        => 'breadcrumb_translation_search',
    'type'      => 'text',
    'default'   => esc_html__('Search results for', 'luxsa'),
    'title'     => esc_html_x('Translation for "Search results for"', 'admin-view', 'luxsa'),
);

$breadcrumbs_options[] = array(
    'id'        => 'breadcrumb_posts_taxonomy',
    'type'      => 'select',
    'title'     => esc_html_x('Posts Taxonomy', 'admin-view', 'luxsa'),
    'options'   => array(
        'none' 		=> esc_html__( 'None', 'luxsa' ),
        'category' 	=> esc_html__( 'Category', 'luxsa' ),
        'post_tag' 	=> esc_html__( 'Tag', 'luxsa' ),
        'blog' 		=> esc_html__( 'Blog Page', 'luxsa' ),
    )
);

if(function_exists('WC')) {
    $breadcrumbs_options[] = array(
        'id' => 'breadcrumb_products_taxonomy',
        'type' => 'select',
        'title' => esc_html_x('Products Taxonomy', 'admin-view', 'luxsa'),
        'options' => array(
            'none' => esc_html__('None', 'luxsa'),
            'product_cat' => esc_html__('Category', 'luxsa'),
            'product_tag' => esc_html__('Tag', 'luxsa'),
            'shop' => esc_html__('Shop Page', 'luxsa'),
            'all' => esc_html__('Shop > Category', 'luxsa')
        )
    );
}

if(!empty($la_extension_available['content_type'])) {
    $breadcrumbs_options[] = array(
        'id' => 'breadcrumb_portfolio_taxonomy',
        'type' => 'select',
        'title' => esc_html_x('Portfolio Taxonomy', 'admin-view', 'luxsa'),
        'options' => array(
            'none' => esc_html__('None', 'luxsa'),
            'la_portfolio_category' => esc_html__('Category', 'luxsa'),
            'portfolio' => esc_html__('Portfolio Page', 'luxsa'),
        )
    );
}

LASF::createSection( $prefix, array(
    'parent'    => 'page_title_bar_panel',
    'title'     => esc_html_x('Breadcrumbs', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-cog',
    'fields'    => $breadcrumbs_options
));

LASF::createSection( $prefix, array(
    'parent'    => 'page_title_bar_panel',
    'title'     => esc_html_x('Global Page Header', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-cog',
    'fields'      => luxsa_options_section_page_title_bar_auto_detect()
));

/**
 * Page Header Bar Panel - Single Post
 */
LASF::createSection( $prefix, array(
    'parent'    => 'page_title_bar_panel',
    'title'     => esc_html_x('Blog Posts', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-cog',
    'fields'      => luxsa_options_section_page_title_bar_auto_detect('blog_post')
));

/**
 * Page Header Bar Panel - Single Post
 */
LASF::createSection( $prefix, array(
    'parent'    => 'page_title_bar_panel',
    'title'     => esc_html_x('Single Post', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-cog',
    'fields'      => luxsa_options_section_page_title_bar_auto_detect('single_post')
));

/**
 * Page Header Bar Panel - Single Product
 */
if(function_exists('WC')){
    LASF::createSection( $prefix, array(
        'parent'    => 'page_title_bar_panel',
        'title'     => esc_html_x('Single Product', 'admin-view', 'luxsa'),
        'icon'      => 'fa fa-cog',
        'fields'      => luxsa_options_section_page_title_bar_auto_detect('single_product')
    ));
}

/**
 * Page Header Bar Panel - Single Product
 */
LASF::createSection( $prefix, array(
    'parent'    => 'page_title_bar_panel',
    'title'     => esc_html_x('Single Portfolio', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-cog',
    'fields'      => luxsa_options_section_page_title_bar_auto_detect('single_portfolio')
));

/**
 * Page Header Bar Panel - WooCommerce
 */
if(function_exists('WC')){
    LASF::createSection( $prefix, array(
        'parent'    => 'page_title_bar_panel',
        'title'     => esc_html_x('WooCommerce', 'admin-view', 'luxsa'),
        'icon'      => 'fa fa-cog',
        'fields'      => luxsa_options_section_page_title_bar_auto_detect('woocommerce')
    ));
}

/**
 * Page Header Bar Panel - Archive Portfolio
 */
LASF::createSection( $prefix, array(
    'parent'    => 'page_title_bar_panel',
    'title'     => esc_html_x('Archive Portfolio', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-cog',
    'fields'      => luxsa_options_section_page_title_bar_auto_detect('archive_portfolio')
));


/**
 * Sidebar Panel
 */
LASF::createSection( $prefix, array(
    'id'    => 'sidebars_panel',
    'title' => esc_html_x('Sidebars', 'admin-view', 'luxsa'),
    'icon'  => 'fa fa-exchange'
) );

/**
 * Sidebar Panel - Pages
 */
LASF::createSection( $prefix, array(
    'parent'    => 'sidebars_panel',
    'title'     => esc_html_x('Pages', 'admin-view', 'luxsa'),
    'fields'    => array(
        array(
            'id'             => 'pages_sidebar',
            'type'           => 'select',
            'title'          => esc_html_x('Global Page Sidebar', 'admin-view', 'luxsa'),
            'subtitle'       => esc_html_x('Select sidebar that will display on all pages.', 'admin-view', 'luxsa'),
            'options'        => 'sidebars',
            'placeholder'    => esc_html_x('None', 'admin-view', 'luxsa')
        ),
        array(
            'id'            => 'pages_global_sidebar',
            'type'          => 'switcher',
            'default'       => false,
            'title'         => esc_html_x('Activate Global Sidebar For Pages', 'admin-view', 'luxsa'),
            'subtitle'      => esc_html_x('Turn on if you want to use the same sidebars on all pages. This option overrides the page options.', 'admin-view', 'luxsa')
        )
    )
));

/**
 * Sidebar Panel - Blog Posts
 */
LASF::createSection( $prefix, array(
    'parent'    => 'sidebars_panel',
    'title'     => esc_html_x('Blog Posts', 'admin-view', 'luxsa'),
    'fields'    => array(
        array(
            'id'             => 'posts_sidebar',
            'type'           => 'select',
            'title'          => esc_html_x('Global Blog Post Sidebar', 'admin-view', 'luxsa'),
            'subtitle'       => esc_html_x('Select sidebar that will display on all blog posts.', 'admin-view', 'luxsa'),
            'options'        => 'sidebars',
            'placeholder'    => esc_html_x('None', 'admin-view', 'luxsa')
        ),
        array(
            'id'            => 'posts_global_sidebar',
            'type'          => 'switcher',
            'default'       => false,
            'title'         => esc_html_x('Activate Global Sidebar For Blog Posts', 'admin-view', 'luxsa'),
            'subtitle'      => esc_html_x('Turn on if you want to use the same sidebars on all blog posts. This option overrides the blog post options.', 'admin-view', 'luxsa')
        )
    )
));

/**
 * Sidebar Panel - Blog Archives
 */
LASF::createSection( $prefix, array(
    'parent'    => 'sidebars_panel',
    'title'     => esc_html_x('Blog Archive', 'admin-view', 'luxsa'),
    'fields'    => array(
        array(
            'id'             => 'blog_archive_sidebar',
            'type'           => 'select',
            'title'          => esc_html_x('Global Blog Archive Sidebar', 'admin-view', 'luxsa'),
            'subtitle'       => esc_html_x('Select sidebar that will display on all post category & tag.', 'admin-view', 'luxsa'),
            'options'        => 'sidebars',
            'placeholder'    => esc_html_x('None', 'admin-view', 'luxsa')
        ),
        array(
            'id'            => 'blog_archive_global_sidebar',
            'type'          => 'switcher',
            'default'       => false,
            'title'         => esc_html_x('Activate Global Sidebar For Blog Archive', 'admin-view', 'luxsa'),
            'subtitle'      => esc_html_x('Turn on if you want to use the same sidebars on all post category & tag. This option overrides the posts options.', 'admin-view', 'luxsa')
        )
    )
));

/**
 * Sidebar Panel - Search Page
 */
LASF::createSection( $prefix, array(
    'parent'    => 'sidebars_panel',
    'title'     => esc_html_x('Search Page', 'admin-view', 'luxsa'),
    'fields'    => array(
        array(
            'id'             => 'blog_archive_sidebar',
            'type'           => 'select',
            'title'          => esc_html_x('Search Page Sidebar', 'admin-view', 'luxsa'),
            'subtitle'       => esc_html_x('Select sidebar that will display on the search results page.', 'admin-view', 'luxsa'),
            'options'        => 'sidebars',
            'placeholder'    => esc_html_x('None', 'admin-view', 'luxsa')
        )
    )
));


if(function_exists('WC')) {
    /**
     * Sidebar Panel - WooCommerce Archive
     */
    LASF::createSection( $prefix, array(
        'parent'    => 'sidebars_panel',
        'title'     => esc_html_x('WooCommerce Archives', 'admin-view', 'luxsa'),
        'fields'    => array(
            array(
                'id'             => 'shop_sidebar',
                'type'           => 'select',
                'title'          => esc_html_x('Global WooCommerce Archives Sidebar', 'admin-view', 'luxsa'),
                'subtitle'       => esc_html_x('Select sidebar that will display on all WooCommerce taxonomy.', 'admin-view', 'luxsa'),
                'options'        => 'sidebars',
                'placeholder'    => esc_html_x('None', 'admin-view', 'luxsa')
            ),
            array(
                'id'            => 'shop_global_sidebar',
                'type'          => 'switcher',
                'default'       => false,
                'title'         => esc_html_x('Activate Global Sidebar For Woocommerce Archives', 'admin-view', 'luxsa'),
                'subtitle'      => esc_html_x('Turn on if you want to use the same sidebars on all WooCommerce archive( shop,category,tag,search ). This option overrides the WooCommerce taxonomy options.', 'admin-view', 'luxsa')
            )
        )
    ));

    /**
     * Sidebar Panel - WooCommerce Single
     */
    LASF::createSection( $prefix, array(
        'parent'    => 'sidebars_panel',
        'title'     => esc_html_x('Products', 'admin-view', 'luxsa'),
        'fields'    => array(
            array(
                'id'             => 'products_sidebar',
                'type'           => 'select',
                'title'          => esc_html_x('Global WooCommerce Product Sidebar', 'admin-view', 'luxsa'),
                'subtitle'       => esc_html_x('Select sidebar that will display on all WooCommerce products.', 'admin-view', 'luxsa'),
                'options'        => 'sidebars',
                'placeholder'    => esc_html_x('None', 'admin-view', 'luxsa')
            ),
            array(
                'id'            => 'products_global_sidebar',
                'type'          => 'switcher',
                'default'       => false,
                'title'         => esc_html_x('Activate Global Sidebar For WooCommerce Products', 'admin-view', 'luxsa'),
                'subtitle'      => esc_html_x('Turn on if you want to use the same sidebars on all WooCommerce products. This option overrides the WooCommerce post options.', 'admin-view', 'luxsa')
            )
        )
    ));
}


/**
 * Footer Panel
 */

/**
 * Footer Panel - Footer Bar
 */
$footer_link = sprintf('<a href="%s">%s</a>', add_query_arg(array('post_type' => 'elementor_library', 'elementor_library_type' => 'footer'), admin_url('edit.php')), esc_html__('here', 'luxsa'));
LASF::createSection( $prefix, array(
    'id'        => 'footer_panel',
    'title'     => esc_html_x('Footer', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-arrow-down',
    'fields'    => array(
        array(
            'id'            => 'footer_layout',
            'type'          => 'select',
            'default'       => '',
            'title'         => esc_html_x('Footer Layout', 'admin-view', 'luxsa'),
            'placeholder'   => esc_html_x('Select a layout', 'admin-view', 'luxsa'),
            'subtitle'      => sprintf(__('You can manage footer layout on %s', 'luxsa'), $footer_link ),
            'options'       => 'posts',
            'query_args'  => array(
                'post_type'         => 'elementor_library',
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'nopaging'          => true,
                'order'             => 'ASC',
                'return_slug'       => true,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'elementor_library_type',
                        'field' => 'slug',
                        'terms' => 'footer'
                    )
                )
            )
        ),
        array(
            'id'        => 'footer_ajax',
            'type'      => 'switcher',
            'default'   => 'on',
            'title'     => esc_html__( 'Enable Ajax load for footer', 'luxsa' ),
            'subtitle'  => esc_html__( 'The footer will be loaded via ajax if the page has been built via Elementor', 'luxsa' ),
        ),
        array(
            'type'    => 'subheading',
            'content' => esc_html_x('Footer Bar', 'admin-view', 'luxsa'),
        ),
        array(
            'id' => 'enable_header_mb_footer_bar',
            'type' => 'button_set',
            'default' => 'no',
            'title' => esc_html_x('Enable Footer Bar?', 'admin-view', 'luxsa'),
            'options' => array(
                'no' => esc_html_x('Hide', 'admin-view', 'luxsa'),
                'yes' => esc_html_x('Yes', 'admin-view', 'luxsa')
            )
        ),
        array(
            'id'        => 'mb_footer_bar_visible',
            'type'      => 'slider',
            'default'    => 600,
            'title'     => esc_html_x( 'Footer Bar Visible', 'admin-view', 'luxsa' ),
            'description' => esc_html_x( 'The footer bar will display on the screen at its maximum width equal to this value', 'admin-view', 'luxsa' ),
            'dependency' => array('enable_header_mb_footer_bar', '==', 'yes'),
            'min'       => 100,
            'max'       => 9999,
            'step'      => 1,
            'unit'      => 'px'
        ),
        array(
            'id' => 'header_mb_footer_bar_component',
            'type' => 'group',
            'wrap_class' => 'group-disable-clone',
            'title' => esc_html_x('Header Footer Bar Component', 'admin-view', 'luxsa'),
            'button_title' => esc_html_x('Add Icon Component ', 'admin-view', 'luxsa'),
            'dependency' => array('enable_header_mb_footer_bar', '==', 'yes'),
            'max' => 10,
            'fields' => array(
                array(
                    'id' => 'type',
                    'type' => 'select',
                    'title' => esc_html_x('Type', 'admin-view', 'luxsa'),
                    'options' => array(
                        'dropdown_menu' => esc_html_x('Dropdown Menu', 'admin-view', 'luxsa'),
                        'text' => esc_html_x('Custom Text', 'admin-view', 'luxsa'),
                        'search_1' => esc_html_x('Search box style 01', 'admin-view', 'luxsa'),
                        'cart' => esc_html_x('Cart Icon', 'admin-view', 'luxsa'),
                        'wishlist' => esc_html_x('Wishlist Icon', 'admin-view', 'luxsa'),
                        'compare' => esc_html_x('Compare Icon', 'admin-view', 'luxsa')
                    )
                ),
                array(
                    'id' => 'icon',
                    'type' => 'icon',
                    'title' => esc_html_x('Custom Icon', 'admin-view', 'luxsa'),
                    'dependency' => array('type', '!=', 'search_1|primary_menu')
                ),
                array(
                    'id' => 'text',
                    'type' => 'text',
                    'title' => esc_html_x('Custom Text', 'admin-view', 'luxsa'),
                    'dependency' => array('type', 'any', 'text,link_text')
                ),
                array(
                    'id' => 'link',
                    'type' => 'text',
                    'default' => '#',
                    'title' => esc_html_x('Link (URL)', 'admin-view', 'luxsa'),
                    'dependency' => array('type', '!=', 'search_1|primary_menu')
                ),
                array(
                    'id' => 'menu_id',
                    'type' => 'select',
                    'title' => esc_html_x('Select the menu', 'admin-view', 'luxsa'),
                    'options' => 'menus',
                    'dependency' => array('type', '==', 'dropdown_menu')
                ),
                array(
                    'id' => 'el_class',
                    'type' => 'text',
                    'default' => '',
                    'title' => esc_html_x('Extra CSS class for item', 'admin-view', 'luxsa')
                )
            )
        ),
        array(
            'id' => 'enable_header_mb_footer_bar_sticky',
            'type' => 'button_set',
            'default' => 'always',
            'title' => esc_html_x('Header Footer Bar Sticky', 'admin-view', 'luxsa'),
            'dependency' => array('enable_header_mb_footer_bar', '==', 'yes'),
            'options' => array(
                'always' => esc_html_x('Always Display', 'admin-view', 'luxsa'),
                'up' => esc_html_x('Display when scroll up', 'admin-view', 'luxsa'),
                'down' => esc_html_x('Display when scroll down', 'admin-view', 'luxsa')
            )
        )
    )
));

/**
 * Blog Panel
 */
LASF::createSection( $prefix, array(
    'id'        => 'blog_panel',
    'title'     => esc_html_x('Blog', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-newspaper-o'
));

/**
 * Blog Panel - General Blog
 */
LASF::createSection( $prefix, array(
    'parent'    => 'blog_panel',
    'title'     => esc_html_x('General Blog', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'    => array(
        array(
            'id'        => 'layout_blog',
            'type'      => 'image_select',
            'title'     => esc_html_x('Blog Page Layout', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'luxsa'),
            'default'   => 'col-1c',
            'options'   => Luxsa_Options::get_config_main_layout_opts(true, true)
        ),
        array(
            'id'        => 'blog_small_layout',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Enable Small Layout', 'admin-view', 'luxsa'),
            'dependency' => array('layout_blog', '==', 'col-1c'),
            'options'   => array(
                'on'        => esc_html_x('On', 'admin-view', 'luxsa'),
                'off'       => esc_html_x('Off', 'admin-view', 'luxsa')
            )
        ),
        array(
            'id'        => 'main_full_width_archive_post',
            'type'      => 'button_set',
            'default'   => 'inherit',
            'title'     => esc_html_x('100% Main Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_opts()
        ),
        array(
            'id'            => 'header_transparency_blog',
            'type'          => 'button_set',
            'default'       => 'inherit',
            'title'         => esc_html_x('[Blog] Header Transparency', 'admin-view', 'luxsa'),
            'options'       => Luxsa_Options::get_config_radio_opts()
        ),
        luxsa_render_responsive_main_space_options(array(
            'id'    => 'main_space_archive_post',
            'title' => esc_html_x('[Blog] Custom Main Space', 'admin-view', 'luxsa')
        )),
        array(
            'id'        => 'page_title_bar_layout_blog_global',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Page Header Bar', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to show the Page Header bar for the assigned blog page in "settings > reading" or blog archive pages', 'admin-view', 'luxsa'),
            'options'   => array(
                'on'        => esc_html_x('On', 'admin-view', 'luxsa'),
                'off'       => esc_html_x('Off', 'admin-view', 'luxsa')
            )
        ),
        luxsa_render_custom_container_width(array(
            'id'        => 'container_width_archive_post',
            'title'     => esc_html_x('[Blog] Custom Container Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 1170px', 'admin-view', 'luxsa'),
        )),

        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_width_archive_post',
            'title'     => esc_html_x('[Blog] Custom Sidebar Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 300px', 'admin-view', 'luxsa'),
        )),
        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_space_archive_post',
            'title'     => esc_html_x('[Blog] Custom Sidebar Space', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 40px', 'admin-view', 'luxsa'),
        )),
        array(
            'id'        => 'blog_design',
            'default'   => 'list-3',
            'title'     => esc_html_x('Blog Design', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Controls the layout for the assigned blog page in "settings > reading" or blog archive pages', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => array(
                'list-1'        => esc_html_x('List Style 01', 'admin-view', 'luxsa'),
                'list-2'        => esc_html_x('List Style 02', 'admin-view', 'luxsa'),
                'list-3'        => esc_html_x('List Style 03', 'admin-view', 'luxsa'),
                'list-4'        => esc_html_x('List Style 04', 'admin-view', 'luxsa'),
                'grid-1'        => esc_html_x('Grid Style 01', 'admin-view', 'luxsa'),
                'grid-2'        => esc_html_x('Grid Style 02', 'admin-view', 'luxsa'),
                'grid-3'        => esc_html_x('Grid Style 03', 'admin-view', 'luxsa'),
            )
        ),

        luxsa_render_responsive_column_options( array(
            'id'         => 'blog_post_column',
            'title'      => esc_html_x('Blog Post Columns', 'admin-view', 'luxsa'),
            'subtitle'   => esc_html_x('Controls the amount of columns for the grid layout when using it for the assigned blog page in "settings > reading" or blog archive pages or search results page.', 'admin-view', 'luxsa'),
            'dependency' => array('blog_design', 'any', 'grid-1,grid-2,grid-3,grid-4,grid-5'),
        ) ),

        luxsa_render_responsive_item_space_options(array(
            'id'            => 'blog_item_space',
            'title'         => esc_html_x('Blog Item Space', 'admin-view', 'luxsa'),
        )),

        array(
            'id'        => 'blog_thumbnail_height_mode',
            'default'   => 'original',
            'title'     => esc_html_x('Blog Image Height Mode', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => array(
                '1-1'       => esc_html_x('1-1', 'admin-view', 'luxsa'),
                'original'  => esc_html_x('Original', 'admin-view', 'luxsa'),
                '4-3'       => esc_html_x('4:3', 'admin-view', 'luxsa'),
                '3-4'       => esc_html_x('3:4', 'admin-view', 'luxsa'),
                '16-9'      => esc_html_x('16:9', 'admin-view', 'luxsa'),
                '9-16'      => esc_html_x('9:16', 'admin-view', 'luxsa'),
                'custom'    => esc_html_x('Custom', 'admin-view', 'luxsa')
            )
        ),

        array(
            'id'        => 'blog_thumbnail_height_custom',
            'type'      => 'text',
            'default'   => '50%',
            'title'     => esc_html_x('Blog Image Height Custom', 'admin-view', 'luxsa'),
            'dependency'=> array('blog_thumbnail_height_mode', '==', 'custom'),
            'subtitle'  => esc_html_x('Enter custom height.', 'admin-view', 'luxsa')
        ),

        array(
            'id'        => 'blog_thumbnail_size',
            'default'   => 'full',
            'title'     => esc_html_x('Blog Image Size', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => luxsa_get_list_image_sizes()
        ),

        array(
            'id'        => 'blog_excerpt_length',
            'type'      => 'slider',
            'default'   => 30,
            'title'     => esc_html_x( 'Blog Excerpt Length', 'admin-view', 'luxsa' ),
            'subtitle'  => esc_html_x('Controls the number of words in the post excerpts for the assigned blog page in "settings > reading" or blog archive pages.', 'admin-view', 'luxsa'),
            'step'    => 1,
            'min'     => 1,
            'max'     => 500,
            'unit'    => ''
        ),

        array(
            'id'        => 'blog_masonry',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Enable Blog Masonry', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false),
            'dependency' => array('blog_design', 'any', 'grid-1,grid-2,grid-3,grid-4,grid-5'),
        ),

        array(
            'id'        => 'blog_pagination_type',
            'type'      => 'button_set',
            'default'   => 'pagination',
            'title'     => esc_html_x('Pagination Type', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Controls the pagination type for the assigned blog page in "settings > reading" or blog pages.', 'admin-view', 'luxsa'),
            'options'   => array(
                'pagination' => esc_html_x('Pagination', 'admin-view', 'luxsa'),
                'infinite_scroll' => esc_html_x('Infinite Scroll', 'admin-view', 'luxsa'),
                'load_more' => esc_html_x('Load More Button', 'admin-view', 'luxsa')
            )
        )
    )
));

/**
 * Blog Panel - Blog Single Post
 */
LASF::createSection( $prefix, array(
    'parent'    => 'blog_panel',
    'title'     => esc_html_x('Blog Single Post', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'    => array(
        array(
            'id'        => 'layout_single_post',
            'type'      => 'image_select',
            'title'     => esc_html_x('Single Page Layout', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'admin-view', 'luxsa'),
            'default'   => 'inherit',
            'options'   => Luxsa_Options::get_config_main_layout_opts(true, true)
        ),
        array(
            'id'        => 'single_small_layout',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Enable Small Layout', 'admin-view', 'luxsa'),
            'dependency' => array('layout_single_post', '==', 'col-1c'),
            'options'   => array(
                'on'        => esc_html_x('On', 'admin-view', 'luxsa'),
                'off'       => esc_html_x('Off', 'admin-view', 'luxsa')
            )
        ),
        array(
            'id'            => 'header_transparency_single_post',
            'type'          => 'button_set',
            'default'       => 'inherit',
            'title'         => esc_html_x('[Post] Header Transparency', 'admin-view', 'luxsa'),
            'options'       => Luxsa_Options::get_config_radio_opts()
        ),
        luxsa_render_custom_container_width(array(
            'id'        => 'container_width_single_post',
            'title'     => esc_html_x('[Posts] Custom Container Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 1170px', 'admin-view', 'luxsa'),
        )),

        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_width_single_post',
            'title'     => esc_html_x('[Posts] Custom Sidebar Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 300px', 'admin-view', 'luxsa'),
        )),
        luxsa_render_custom_container_width(array(
            'id'        => 'custom_sidebar_space_single_post',
            'title'     => esc_html_x('[Posts] Custom Sidebar Space', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Default is 40px', 'admin-view', 'luxsa'),
        )),
        luxsa_render_responsive_main_space_options(array(
            'id'    => 'main_space_single_post',
            'title' => esc_html_x('[Single Post] Custom Main Space', 'admin-view', 'luxsa')
        )),
        array(
            'id'        => 'blog_post_page_title',
            'type'      => 'select',
            'default'   => 'blog',
            'title'     => esc_html_x('Page Header Title', 'admin-view', 'luxsa'),
            'options'   => array(
                'blog'          => esc_html_x('Blog', 'admin-view', 'luxsa'),
                'post-title'    => esc_html_x('Post Title', 'admin-view', 'luxsa'),
                'none'          => esc_html_x('Does not display', 'admin-view', 'luxsa')
            )
        ),
        array(
            'id'        => 'featured_images_single',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Featured Image / Video on Single Blog Post', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to display featured images and videos on single blog posts.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        ),
        array(
            'id'        => 'single_post_thumbnail_size',
            'default'   => 'full',
            'title'     => esc_html_x('Featured Image Size', 'admin-view', 'luxsa'),
            'dependency' => array('featured_images_single', '==', 'on'),
            'type'      => 'select',
            'options'   => luxsa_get_list_image_sizes()
        ),
        array(
            'id'        => 'blog_pn_nav',
            'type'      => 'button_set',
            'default'   => 'on',
            'title'     => esc_html_x('Previous/Next Pagination', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to display the previous/next post pagination for single blog posts.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        ),

        array(
            'id'        => 'blog_post_title',
            'type'      => 'button_set',
            'default'   => 'below',
            'title'     => esc_html_x('Post Title', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Controls if the post title displays above or below the featured post image or is disabled.', 'admin-view', 'luxsa'),
            'options'   => array(
                'below'        => esc_html_x('Below', 'admin-view', 'luxsa'),
                'above'        => esc_html_x('Above', 'admin-view', 'luxsa'),
                'off'          => esc_html_x('Disabled', 'admin-view', 'luxsa')
            )
        ),
        array(
            'id'        => 'blog_social_sharing_box',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Social Sharing Box', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to display the social sharing box.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        ),
        array(
            'id'        => 'blog_author_bio',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Author Biographical', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to display the author biographical info.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        ),
        array(
            'id'        => 'blog_related_posts',
            'type'      => 'button_set',
            'default'   => 'off',
            'title'     => esc_html_x('Related Posts', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to display related posts.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        ),
        array(
            'id'        => 'blog_related_design',
            'default'   => '1',
            'title'     => esc_html_x('Related Design', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => array(
                '1'        => esc_html_x('Style 1', 'admin-view', 'luxsa')
            ),
            'dependency' => array('blog_related_posts', '==', 'on'),
        ),
        array(
            'id'        => 'blog_related_by',
            'default'   => 'random',
            'title'     => esc_html_x('Related Posts By', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => array(
                'category'      => esc_html_x('Category', 'admin-view', 'luxsa'),
                'tag'           => esc_html_x('Tag', 'admin-view', 'luxsa'),
                'both'          => esc_html_x('Category & Tag', 'admin-view', 'luxsa'),
                'random'        => esc_html_x('Random', 'admin-view', 'luxsa')

            ),
            'dependency' => array('blog_related_posts', '==', 'on'),
        ),
        array(
            'id'        => 'blog_related_max_post',
            'type'      => 'slider',
            'default'   => 3,
            'title'     => esc_html_x( 'Maximum Related Posts', 'admin-view', 'luxsa' ),
            'step'    => 1,
            'min'     => 1,
            'max'     => 100,
            'unit'    => '',
            'dependency' => array('blog_related_posts', '==', 'on')
        ),
        luxsa_render_responsive_column_options( array(
            'id'         => 'blog_related_post_columns',
            'title'      => esc_html_x('Related post columns', 'admin-view', 'luxsa'),
            'subtitle'   => esc_html_x('Controls the number of columns for the related posts', 'admin-view', 'luxsa'),
            'dependency' => array('blog_related_posts', '==', 'on')
        ) ),

        array(
            'id'        => 'move_blog_related_to_bottom',
            'type'      => 'button_set',
            'default'   => 'on',
            'title'     => esc_html_x('Move Related Posts before footer', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false),
            'dependency' => array('blog_related_posts', '==', 'on')
        ),

        array(
            'id'        => 'blog_comments',
            'type'      => 'button_set',
            'default'   => 'on',
            'title'     => esc_html_x('Comments', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Turn on to display comments.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_onoff(false)
        )
    )
));

if(function_exists('WC')){
    /**
     * WooCommerce Panel
     */
    LASF::createSection( $prefix, array(
        'id'        => 'woocommerce_panel',
        'title'     => esc_html_x('Shop', 'admin-view', 'luxsa'),
        'icon'      => 'fa fa-shopping-cart'
    ));

    /**
     * WooCommerce Panel - General
     */
    LASF::createSection( $prefix, array(
        'parent'    => 'woocommerce_panel',
        'title'     => esc_html_x('General', 'admin-view', 'luxsa'),
        'icon'      => 'fas fa-check',
        'fields'    => array(
            array(
                'id'        => 'layout_archive_product',
                'type'      => 'image_select',
                'title'     => esc_html_x('WooCommerce Layout', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Controls the layout of shop page, product category, product tags and search page', 'admin-view', 'luxsa'),
                'default'   => 'col-1c',
                'options'   => Luxsa_Options::get_config_main_layout_opts(true, false)
            ),

            array(
                'id' => 'header_transparency_archive_product',
                'type' => 'button_set',
                'default' => 'inherit',
                'title' => esc_html_x('[Shop] Header Transparency', 'admin-view', 'luxsa'),
                'options' => Luxsa_Options::get_config_radio_opts()
            ),

            array(
                'id'        => 'main_full_width_archive_product',
                'type'      => 'button_set',
                'default'   => 'inherit',
                'title'     => esc_html_x('100% Main Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts()
            ),

            luxsa_render_responsive_main_space_options(array(
                'id'    => 'main_space_archive_product',
                'title' => esc_html_x('Custom Main Space', 'admin-view', 'luxsa')
            )),


            luxsa_render_custom_container_width(array(
                'id'        => 'container_width_archive_product',
                'title'     => esc_html_x('[Shop] Custom Container Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Default is 1170px', 'admin-view', 'luxsa'),
            )),

            luxsa_render_custom_container_width(array(
                'id'        => 'custom_sidebar_width_archive_product',
                'title'     => esc_html_x('[Shop] Custom Sidebar Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Default is 300px', 'admin-view', 'luxsa'),
            )),
            luxsa_render_custom_container_width(array(
                'id'        => 'custom_sidebar_space_archive_product',
                'title'     => esc_html_x('[Shop] Custom Sidebar Space', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Default is 40px', 'admin-view', 'luxsa'),
            )),

            array(
                'id'        => 'catalog_mode',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Catalog Mode', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to disable the shopping functionality of WooCommerce.', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'catalog_mode_price',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Catalog Mode Price', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to do not show product price', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false),
                'dependency' => array('catalog_mode', '==', 'on')
            ),
            array(
                'id'        => 'active_shop_filter',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Advanced WooCommerce Filter', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn off/on advance shop filter', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'hide_shop_toolbar',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Hide WooCommerce Toolbar', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn off/on WooCommerce Toolbar', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_toggle_grid_list',
                'type'      => 'button_set',
                'default'   => 'on',
                'title'     => esc_html_x('WooCommerce Product Grid / List View', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to display the grid/list toggle on the main shop page and archive shop pages.', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'shop_catalog_display_type',
                'default'   => 'grid',
                'title'     => esc_html_x('Shop display as type', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Controls the type display of product for the shop page', 'admin-view', 'luxsa'),
                'type'      => 'select',
                'options'   => array(
                    'grid'        => esc_html_x('Grid', 'admin-view', 'luxsa'),
                    'list'        => esc_html_x('List', 'admin-view', 'luxsa')
                )
            ),
            array(
                'id'        => 'shop_catalog_grid_style',
                'default'   => '1',
                'title'     => esc_html_x('Grid Style', 'admin-view', 'luxsa'),
                'subtitle'      => esc_html_x('Controls the type display of product for the shop page', 'admin-view', 'luxsa'),
                'type'  => 'select',
                'options'   => array(
	                '1' => esc_html__( 'Type 1', 'luxsa' ),
	                '2' => esc_html__( 'Type 2', 'luxsa' ),
	                '3' => esc_html__( 'Type 3', 'luxsa' ),
	                '4' => esc_html__( 'Type 4', 'luxsa' ),
	                '5' => esc_html__( 'Type 5', 'luxsa' ),
	                '6' => esc_html__( 'Type 6', 'luxsa' ),
	                '7' => esc_html__( 'Type 7', 'luxsa' ),
	                '8' => esc_html__( 'Type 8', 'luxsa' ),
	                '9' => esc_html__( 'Type 9', 'luxsa' ),
                )
            ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'woocommerce_catalog_columns',
                'title'      => esc_html_x('WooCommerce Number of Product Category Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the main shop page', 'admin-view', 'luxsa')
            ) ),

            array(
                'id'        => 'active_shop_masonry',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Enable Shop Masonry', 'admin-view', 'luxsa'),
                'subtitle'      => esc_html_x('Turn off/on Shop Masonry Mode', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'shop_masonry_column_type',
                'default'   => '1',
                'title'     => esc_html_x('Masonry Column Type', 'admin-view', 'luxsa'),
                'type'      => 'select',
                'options'   => array(
                    'default'        => esc_html_x('Default', 'admin-view', 'luxsa'),
                    'custom'         => esc_html_x('Custom', 'admin-view', 'luxsa')
                ),
                'dependency' => array('active_shop_masonry', '==', 'on')
            ),
            array(
                'id'        => 'product_masonry_container_width',
                'default'   => '1170',
                'title'     => esc_html_x('Container Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('This value will determine the number of items per row', 'admin-view', 'luxsa'),
                'desc'      => esc_html_x('Enter numeric only', 'admin-view', 'luxsa'),
                'type'      => 'text',
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|custom')
            ),
            array(
                'id'        => 'product_masonry_image_size',
                'default'   => 'shop_catalog',
                'title'     => esc_html_x('Masonry Product Image Size', 'admin-view', 'luxsa'),
                'type'      => 'select',
                'options'   => luxsa_get_list_image_sizes(),
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|custom')
            ),
            array(
                'id'        => 'product_masonry_item_width',
                'default'   => '270',
                'title'     => esc_html_x('Item Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Set your product item default width', 'admin-view', 'luxsa'),
                'desc'      => esc_html_x('Enter numeric only', 'admin-view', 'luxsa'),
                'type'      => 'text',
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|custom')
            ),
            array(
                'id'        => 'product_masonry_item_height',
                'default'   => '450',
                'title'     => esc_html_x('Item Height', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Set your product item default height', 'admin-view', 'luxsa'),
                'desc'      => esc_html_x('Enter numeric only', 'admin-view', 'luxsa'),
                'type'      => 'text',
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|custom')
            ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'woocommerce_shop_page_columns',
                'title'      => esc_html_x('WooCommerce Number of Product Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the main shop page', 'admin-view', 'luxsa'),
                'dependency' => array('active_shop_masonry', '==', 'off'),
            ) ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'woocommerce_shop_masonry_columns',
                'title'      => esc_html_x('WooCommerce Number of Product Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the main shop page', 'admin-view', 'luxsa'),
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|default'),
            ) ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'woocommerce_shop_masonry_custom_columns',
                'title'      => esc_html_x('WooCommerce Number of Product Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the main shop page', 'admin-view', 'luxsa'),
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|custom'),
                'class'         => 'lasf-responsive-tabs lasf-responsive-column-tabs',
                'type'          => 'tabbed',
                'tabs'          => array(

                    array(
                        'title'  => esc_html_x('Mobile', 'admin-view', 'luxsa'),
                        'icon'   => 'dashicons dashicons-smartphone',
                        'fields' => array(
                            array(
                                'id'          => 'mobile',
                                'type'        => 'select',
                                'class'       => 'lasf-field-fullwidth',
                                'options'     => array(
                                    '1'  => 1,
                                    '2'  => 2,
                                    '3'  => 3,
                                    '4'  => 4,
                                    '5'  => 5,
                                    '6'  => 6,
                                ),
                                'default'     => 1
                            )
                        ),
                    ),

                    array(
                        'title'  => esc_html_x('Mobile Landscape', 'admin-view', 'luxsa'),
                        'icon'   => 'dashicons dashicons-smartphone fa-rotate-90',
                        'fields' => array(
                            array(
                                'id'          => 'mobile_landscape',
                                'type'        => 'select',
                                'class'       => 'lasf-field-fullwidth',
                                'options'     => array(
                                    '1'  => 1,
                                    '2'  => 2,
                                    '3'  => 3,
                                    '4'  => 4,
                                    '5'  => 5,
                                    '6'  => 6,
                                ),
                                'default'     => 1
                            )
                        ),
                    ),

                    array(
                        'title'  => esc_html_x('Tablet', 'admin-view', 'luxsa'),
                        'icon'   => 'dashicons dashicons-tablet fa-rotate-90',
                        'fields' => array(
                            array(
                                'id'          => 'tablet',
                                'type'        => 'select',
                                'class'       => 'lasf-field-fullwidth',
                                'options'     => array(
                                    '1'  => 1,
                                    '2'  => 2,
                                    '3'  => 3,
                                    '4'  => 4,
                                    '5'  => 5,
                                    '6'  => 6,
                                ),
                                'default'     => 1
                            )
                        ),
                    )
                )
            ) ),

            array(
                'id'        => 'enable_shop_masonry_custom_setting',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Enable Custom Item Settings', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false),
                'dependency' => array('active_shop_masonry|shop_masonry_column_type', '==|==', 'on|custom')
            ),
            array(
                'id'        => 'shop_masonry_item_setting',
                'type'      => 'group',
                'title'     => esc_html_x('Add Item Sizes', 'admin-view', 'luxsa'),
                'button_title'    => esc_html_x('Add','admin-view', 'luxsa'),
                'default'   => array(
                    array(
                        'size_name' => esc_html_x('1x Width + 1x Height', 'admin-view', 'luxsa'),
                        'width' => 1,
                        'height' => 1
                    )
                ),
                'fields'    => array(
                    array(
                        'id'        => 'size_name',
                        'type'      => 'text',
                        'default'   => esc_html_x('1x Width + 1x Height', 'admin-view', 'luxsa'),
                        'title'     => esc_html_x('Size Name', 'admin-view', 'luxsa')
                    ),
                    array(
                        'id'        => 'w',
                        'default'   => '1',
                        'title'     => esc_html_x('Width', 'admin-view', 'luxsa'),
                        'desc'      => esc_html_x('it will occupy x width of base item width ( example: this item will be occupy 2x width of base width you need entered "2")', 'admin-view', 'luxsa'),
                        'type'      => 'select',
                        'options'   => array(
                            '0.5'      => esc_html_x('0.5x width', 'admin-view', 'luxsa'),
                            '1'        => esc_html_x('1x width', 'admin-view', 'luxsa'),
                            '1.5'      => esc_html_x('1.5x width', 'admin-view', 'luxsa'),
                            '2'        => esc_html_x('2x width', 'admin-view', 'luxsa'),
                            '2.5'      => esc_html_x('2.5x width', 'admin-view', 'luxsa'),
                            '3'        => esc_html_x('3x width', 'admin-view', 'luxsa'),
                            '3.5'      => esc_html_x('3.5x width', 'admin-view', 'luxsa'),
                            '4'        => esc_html_x('4x width', 'admin-view', 'luxsa')
                        )
                    ),
                    array(
                        'id'        => 'h',
                        'default'   => '1',
                        'title'     => esc_html_x('Height', 'admin-view', 'luxsa'),
                        'desc'      => esc_html_x('it will occupy x height of base item height ( example: this item will be occupy 2x height of base height you need entered "2")', 'admin-view', 'luxsa'),
                        'type'      => 'select',
                        'options'   => array(
                            '0.5'      => esc_html_x('0.5x height', 'admin-view', 'luxsa'),
                            '1'        => esc_html_x('1x height', 'admin-view', 'luxsa'),
                            '1.5'      => esc_html_x('1.5x height', 'admin-view', 'luxsa'),
                            '2'        => esc_html_x('2x height', 'admin-view', 'luxsa'),
                            '2.5'      => esc_html_x('2.5x height', 'admin-view', 'luxsa'),
                            '3'        => esc_html_x('3x height', 'admin-view', 'luxsa'),
                            '3.5'      => esc_html_x('3.5x height', 'admin-view', 'luxsa'),
                            '4'        => esc_html_x('4x height', 'admin-view', 'luxsa')
                        )
                    )
                ),
                'dependency' => array('active_shop_masonry|shop_masonry_column_type|enable_shop_masonry_custom_setting', '==|==|==', 'on|custom|on')
            ),

            luxsa_render_responsive_item_space_options(
                array(
                    'id'            => 'shop_item_space',
                    'title'         => esc_html_x('Space between product items', 'admin-view', 'luxsa')
                )
            ),
	        array(
		        'id'        => 'product_per_row_allow',
		        'default'   => '',
		        'title'     => esc_html_x('WooCommerce Number of Products per Row', 'admin-view', 'luxsa'),
		        'subtitle'  => esc_html_x('Controls the number of products that display per row.', 'admin-view', 'luxsa'),
		        'desc'      => esc_html_x('Comma-separated. ( i.e: 2,3,4)', 'admin-view', 'luxsa'),
		        'type'      => 'text'
	        ),
            array(
                'id'        => 'product_per_page_allow',
                'default'   => '12,15,30',
                'title'     => esc_html_x('WooCommerce Number of Products per Page Allow', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Controls the number of products that display per page.', 'admin-view', 'luxsa'),
                'desc'      => esc_html_x('Comma-separated. ( i.e: 3,6,9)', 'admin-view', 'luxsa'),
                'type'      => 'text'
            ),
            array(
                'id'        => 'product_per_page_default',
                'type'      => 'slider',
                'default'   => 12,
                'title'     => esc_html_x('WooCommerce Number of Products per Page', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('The value of field must be as one value of setting above', 'admin-view', 'luxsa'),
                'min'       => 1,
                'max'       => 100,
                'step'      => 1,
                'unit'      => ''
            ),
            array(
                'id'        => 'woocommerce_pagination_type',
                'type'      => 'button_set',
                'default'   => 'pagination',
                'title'     => esc_html_x('WooCommerce Pagination Type', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Controls the pagination type for the assigned shop pages', 'admin-view', 'luxsa'),
                'options'   => array(
                    'pagination' => esc_html_x('Pagination', 'admin-view', 'luxsa'),
                    'infinite_scroll' => esc_html_x('Infinite Scroll', 'admin-view', 'luxsa'),
                    'load_more' => esc_html_x('Load More Button', 'admin-view', 'luxsa')
                )
            ),
            array(
                'id'        => 'woocommerce_load_more_text',
                'type'      => 'text',
                'default'   => 'Load More Products',
                'title'     => esc_html_x('Load More Button Text', 'admin-view', 'luxsa'),
                'dependency'=> array('woocommerce_pagination_type', '==', 'load_more')
            ),
            array(
                'id'        => 'woocommerce_enable_crossfade_effect',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('WooCommerce Crossfade Image Effect', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to display the product crossfade image effect on the product.', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_show_rating_on_catalog',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('WooCommerce Show Ratings', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to display the ratings on the main shop page and archive shop pages.', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_show_addcart_btn',
                'type'      => 'button_set',
                'default'   => 'on',
                'title'     => esc_html_x('WooCommerce Show Add Cart Button', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_show_quickview_btn',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('WooCommerce Show Quick View Button', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_show_wishlist_btn',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('WooCommerce Show Wishlist Button', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_show_compare_btn',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('WooCommerce Show Compare Button', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'woocommerce_show_action_btn_mobile',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Force display Product Actions on mobile', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Display add-to-cart, wishlish, compare buttons on the mobile', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            )
        )
    ));

    /**
     * WooCommerce Panel - Product Page
     */
    LASF::createSection( $prefix, array(
        'parent'    => 'woocommerce_panel',
        'title'     => esc_html_x('Product Page', 'admin-view', 'luxsa'),
        'icon'      => 'fas fa-check',
        'fields'    => array(
            array(
                'id'        => 'layout_single_product',
                'type'      => 'image_select',
                'title'     => esc_html_x('Product Page Layout', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Controls the layout for detail product page', 'admin-view', 'luxsa'),
                'default'   => 'col-1c',
                'options'   => Luxsa_Options::get_config_main_layout_opts(true, false)
            ),

            array(
                'id' => 'header_transparency_single_product',
                'type' => 'button_set',
                'default' => 'inherit',
                'title' => esc_html_x('[Product] Header Transparency', 'admin-view', 'luxsa'),
                'options' => Luxsa_Options::get_config_radio_opts()
            ),

            array(
                'id'        => 'main_full_width_single_product',
                'type'      => 'button_set',
                'default'   => 'inherit',
                'title'     => esc_html_x('100% Main Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts()
            ),

            luxsa_render_responsive_main_space_options(array(
                'id'        => 'main_space_single_product',
                'title'     => esc_html_x('Custom Main Space', 'admin-view', 'luxsa')
            )),

            luxsa_render_custom_container_width(array(
                'id'        => 'container_width_single_product',
                'title'     => esc_html_x('[Product] Custom Container Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Default is 1170px', 'admin-view', 'luxsa'),
            )),

            luxsa_render_custom_container_width(array(
                'id'        => 'custom_sidebar_width_single_product',
                'title'     => esc_html_x('[Product] Custom Sidebar Width', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Default is 300px', 'admin-view', 'luxsa'),
            )),
            luxsa_render_custom_container_width(array(
                'id'        => 'custom_sidebar_space_single_product',
                'title'     => esc_html_x('[Product] Custom Sidebar Space', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Default is 40px', 'admin-view', 'luxsa'),
            )),

            array(
                'id'        => 'woocommerce_product_page_design',
                'title'     => esc_html_x('Product Page Design', 'admin-view', 'luxsa'),
                'type'      => 'image_select',
                'class'     => 'specificity_image_select',
                'default'   => '1',
                'options'   => array(
                    '1'     => esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/single-product-layout-1.jpg'),
                    '2'     => esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/single-product-layout-2.jpg'),
                    '3'     => esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/single-product-layout-3.jpg'),
                    '4'     => esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/single-product-layout-4.jpg'),
                    '5'     => esc_url( Luxsa_Theme_Class::$template_dir_url . '/assets/images/theme_options/single-product-layout-5.png'),
                )
            ),
            array(
                'id'        => 'woocommerce_product_page_main_image_width',
                'title'     => esc_html_x('Product Image Main Width', 'admin-view', 'luxsa'),
                'type'  => 'tabbed',
                'class' => 'lasf-responsive-tabs lasf-tab-left',
                'tabs'  => array(
                    array(
                        'title'  => esc_html_x('Tablet', 'admin-view', 'luxsa'),
                        'icon'   => 'dashicons dashicons-tablet fa-rotate-90',
                        'fields' => array(
                            array(
                                'id'    => 'tablet',
                                'type'  => 'dimensions',
                                'height'  => false,
                                'class' => 'lasf-field-fullwidth'
                            )
                        ),
                    ),
                    array(
                        'title'  => esc_html_x('Laptop', 'admin-view', 'luxsa'),
                        'icon'   => 'dashicons dashicons-desktop',
                        'fields' => array(
                            array(
                                'id'    => 'laptop',
                                'type'  => 'dimensions',
                                'height'  => false,
                                'class' => 'lasf-field-fullwidth'
                            )
                        ),
                    ),
                    array(
                        'title'  => esc_html_x('Desktop', 'admin-view', 'luxsa'),
                        'icon'   => 'fa fa-desktop',
                        'fields' => array(
                            array(
                                'id'    => 'desktop',
                                'type'  => 'dimensions',
                                'height'  => false,
                                'class' => 'lasf-field-fullwidth'
                            )
                        ),
                    )
                )
            ),
            array(
                'id'        => 'single_ajax_add_cart',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html_x('Ajax Add to Cart', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Support Ajax Add to cart for all types of products', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'move_woo_tabs_to_bottom',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html_x('Move WooCommerce Tabs To Bottom', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'woo_tabs_top_style',
                'default'   => 'horizontal',
                'title'     => esc_html_x('Woo Tabs Type', 'admin-view', 'luxsa'),
                'type'      => 'select',
                'options'   => array(
                    'horizontal'   => esc_html_x('Horizontal', 'admin-view', 'luxsa'),
                    'vertical'     => esc_html_x('Vertical', 'admin-view', 'luxsa')
                )
            ),
            array(
                'id'        => 'woocommerce_gallery_zoom',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html_x('Enable WooCommerce Zoom', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'woocommerce_gallery_lightbox',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html_x('Enable WooCommerce LightBox', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'product_single_hide_breadcrumb',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html__('Hide Breadcrumbs', 'luxsa'),
                'subtitle'  => esc_html__('In Page Header Bar', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'product_single_hide_page_title',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html__('Hide Page Header', 'luxsa'),
                'subtitle'  => esc_html__('In Page Header Bar', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),
            array(
                'id'        => 'product_single_hide_product_title',
                'type'      => 'button_set',
                'default'   => 'no',
                'title'     => esc_html__('Hide Product Title', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_opts(false)
            ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'product_gallery_column',
                'title'      => esc_html_x('Product gallery columns', 'admin-view', 'luxsa')
            ) ),

            array(
                'id'        => 'product_sharing',
                'type'      => 'button_set',
                'default'   => 'on',
                'title'     => esc_html_x('Product Sharing Option', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to show social sharing on the product page', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'related_products',
                'type'      => 'button_set',
                'default'   => 'on',
                'title'     => esc_html_x('WooCommerce Related Products', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to show related products on the product page', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'related_product_title',
                'type'      => 'text',
                'title'     => esc_html_x('WooCommerce Related Title','admin-view', 'luxsa'),
                'dependency'=> array('related_products', '==', 'on')
            ),
            array(
                'id'        => 'related_product_subtitle',
                'type'      => 'text',
                'title'     => esc_html_x('WooCommerce Related Sub Title','admin-view', 'luxsa'),
                'dependency'=> array('related_products', '==', 'on')
            ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'related_products_columns',
                'title'      => esc_html_x('WooCommerce Related Product Number of Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the related', 'admin-view', 'luxsa'),
                'dependency'=> array('related_products', '==', 'on'),
            ) ),

            array(
                'id'        => 'upsell_products',
                'type'      => 'button_set',
                'default'   => 'on',
                'title'     => esc_html_x('WooCommerce Up-sells Products', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to show Up-sells products on the product page', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'upsell_product_title',
                'type'      => 'text',
                'title'     => esc_html_x('WooCommerce Up-sells Title','admin-view', 'luxsa'),
                'dependency'=> array('upsell_products', '==', 'on')
            ),
            array(
                'id'        => 'upsell_product_subtitle',
                'type'      => 'text',
                'title'     => esc_html_x('WooCommerce Up-sells Sub Title','admin-view', 'luxsa'),
                'dependency'=> array('upsell_products', '==', 'on')
            ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'upsell_products_columns',
                'title'      => esc_html_x('WooCommerce Up-sells Product Number of Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the Up-sells', 'admin-view', 'luxsa'),
                'dependency'=> array('upsell_products', '==', 'on'),
            ) ),

            array(
                'id'        => 'woo_enable_custom_tab',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Custom Tabs Detail Page', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to show custom tabs on the product page', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false),
            ),
            array(
                'id'        => 'woo_custom_tabs',
                'type'      => 'group',
                'title'     => esc_html_x('Custom Tabs', 'admin-view', 'luxsa'),
                'dependency'=> array('woo_enable_custom_tab', '==', 'on'),
                'max'       => 3,
                'fields'    => array(
                    array(
                        'id'    => 'title',
                        'type'  => 'text',
                        'title' => esc_html_x('Tab Title','admin-view', 'luxsa'),
                    ),
                    array(
                        'id'    => 'content',
                        'type'  => 'wp_editor',
                        'title' => esc_html_x('Tab Content', 'admin-view', 'luxsa'),
                    ),
                    array(
                        'id'        => 'el_class',
                        'type'      => 'text',
                        'title'     => esc_html_x('Custom CSS class name for this block','admin-view', 'luxsa'),
                    )
                )
            ),
            array(
                'id'        => 'woo_enable_custom_block_single_product',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('Enable Custom Block on Product Details Page', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false),
            ),
            array(
                'id'        => 'woo_custom_block_single_product',
                'type'      => 'group',
                'title'     => esc_html_x('Custom Block', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Display custom block on the product page', 'admin-view', 'luxsa'),
                'dependency'=> array('woo_enable_custom_block_single_product', '==', 'on'),
                'max'       => 10,
                'fields'    => array(
                    array(
                        'id'        => 'title',
                        'type'      => 'text',
                        'title'     => esc_html_x('Title','admin-view', 'luxsa'),
                    ),
                    array(
                        'id'        => 'position',
                        'default'   => '',
                        'title'     => esc_html_x('Position to display', 'admin-view', 'luxsa'),
                        'type'      => 'select',
                        'options'   => array(
                            ''        => esc_html_x('Select Position', 'admin-view', 'luxsa'),
                            'pos1'    => esc_html_x('After Cart Form', 'admin-view', 'luxsa'),
                            'pos2'    => esc_html_x('After Product Meta', 'admin-view', 'luxsa'),
                            'pos3'    => esc_html_x('After Product Price', 'admin-view', 'luxsa'),
                            'pos4'    => esc_html_x('After Product Title', 'admin-view', 'luxsa'),
                            'pos5'    => esc_html_x('After Product Description', 'admin-view', 'luxsa'),
                            'pos6'    => esc_html_x('Beside Product Summary', 'admin-view', 'luxsa'),
                            'pos7'    => esc_html_x('Before WooCommerce Tabs', 'admin-view', 'luxsa'),
                            'pos8'    => esc_html_x('After WooCommerce Tabs', 'admin-view', 'luxsa'),
                            'pos9'    => esc_html_x('After Product Related', 'admin-view', 'luxsa'),
                            'pos10'   => esc_html_x('After Product Up-Sells', 'admin-view', 'luxsa'),
                            'pos11'   => esc_html_x('Before Main Content', 'admin-view', 'luxsa'),
                            'pos12'   => esc_html_x('After Main Content', 'admin-view', 'luxsa'),
                        )
                    ),
                    array(
                        'id'        => 'content',
                        'type'      => 'wp_editor',
                        'title'     => esc_html_x('Content', 'admin-view', 'luxsa'),
                    ),
                    array(
                        'id'        => 'el_class',
                        'type'      => 'text',
                        'title'     => esc_html_x('Custom CSS class name for this block','admin-view', 'luxsa'),
                    )
                )
            )
        )
    ));

    /**
     * WooCommerce Panel - Cart Page
     */
    LASF::createSection( $prefix, array(
        'parent'    => 'woocommerce_panel',
        'title'     => esc_html_x('Cart Page', 'admin-view', 'luxsa'),
        'icon'      => 'fa fa-shopping-cart',
        'fields'    => array(
            array(
                'id'        => 'freeshipping_thresholds',
                'type'      => 'button_set',
                'default'   => 'off',
                'title'     => esc_html_x('WooCommerce Enable Free Shipping Thresholds', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Enable Free shipping amount notice', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'thresholds_text1',
                'type'      => 'text',
                'title'     => esc_html_x('Shipping Bar text 1','admin-view', 'luxsa'),
                'dependency'=> array('freeshipping_thresholds', '==', 'on'),
                'default'   => esc_html__('[icon]Spend [amount] to get Free Shipping', 'luxsa'),
            ),
            array(
                'id'        => 'thresholds_text2',
                'type'      => 'text',
                'title'     => esc_html_x('Shipping Bar text 2','admin-view', 'luxsa'),
                'dependency'=> array('freeshipping_thresholds', '==', 'on'),
                'default'   => esc_html__('[icon]Congratulations! You\'ve got free shipping!', 'luxsa'),
            ),
            array(
                'id'        => 'crosssell_products',
                'type'      => 'button_set',
                'default'   => 'on',
                'title'     => esc_html_x('WooCommerce Cross-sells Products', 'admin-view', 'luxsa'),
                'subtitle'  => esc_html_x('Turn on to show Cross-sells products on the product page', 'admin-view', 'luxsa'),
                'options'   => Luxsa_Options::get_config_radio_onoff(false)
            ),
            array(
                'id'        => 'crosssell_product_title',
                'type'      => 'text',
                'title'     => esc_html_x('WooCommerce Cross-sells Title','admin-view', 'luxsa'),
                'dependency'=> array('crosssell_products', '==', 'on')
            ),
            array(
                'id'        => 'crosssell_product_subtitle',
                'type'      => 'text',
                'title'     => esc_html_x('WooCommerce Cross-sells Sub Title','admin-view', 'luxsa'),
                'dependency'=> array('crosssell_products', '==', 'on')
            ),

            luxsa_render_responsive_column_options( array(
                'id'         => 'crosssell_products_columns',
                'title'      => esc_html_x('WooCommerce Up-sells Product Number of Columns', 'admin-view', 'luxsa'),
                'subtitle'   => esc_html_x('Controls the number of columns for the Up-sells', 'admin-view', 'luxsa'),
                'dependency' => array('crosssell_products', '==', 'on'),
            ) ),

        )
    ));

    /**
     * WooCommerce Panel - Wishlist
     */
    LASF::createSection( $prefix, array(
        'parent'    => 'woocommerce_panel',
        'title'     => esc_html_x('Wishlist', 'admin-view', 'luxsa'),
        'icon'      => 'fa fa-heart',
        'fields'    => array(
            array(
                'id'        => 'wishlist_page',
                'type'      => 'select',
                'title'     => esc_html_x('Wishlist Page', 'admin-view', 'luxsa'),
                'ajax'          => true,
                'chosen'        => true,
                'options'   => 'pages',
                'subtitle'  => esc_html_x('The content of page must be contain [la_wishlist] shortcode', 'admin-view', 'luxsa'),
                'query_args'    => array(
                    'posts_per_page'  => -1
                ),
                'placeholder' => esc_html_x('Select a page', 'admin-view', 'luxsa')
            )
        )
    ));

    /**
     * WooCommerce Panel - Compare
     */

    $wc_fields_default = luxsa_get_wc_attribute_for_compare();
    $wc_attr_attributes = luxsa_get_wc_attribute_taxonomies();

    $wc_attr_fields = array_merge( $wc_fields_default, $wc_attr_attributes );

    LASF::createSection( $prefix, array(
        'parent'    => 'woocommerce_panel',
        'title'     => esc_html_x('Compare', 'admin-view', 'luxsa'),
        'icon'      => 'fa fa-exchange',
        'fields'    => array(
            array(
                'id'        => 'compare_page',
                'type'      => 'select',
                'title'     => esc_html_x('Compare Page', 'admin-view', 'luxsa'),
                'ajax'          => true,
                'chosen'        => true,
                'options'   => 'pages',
                'subtitle'  => esc_html_x('The content of page must be contain [la_compare] shortcode', 'admin-view', 'luxsa'),
                'query_args'    => array(
                    'posts_per_page'  => -1
                ),
                'placeholder' => esc_html_x('Select a page', 'admin-view', 'luxsa')
            ),
            array(
                'id'       => 'compare_attribute',
                'type'     => 'checkbox',
                'title'    => esc_html_x('Fields to show', 'admin-view', 'luxsa'),
                'subtitle' => esc_html_x('Select the fields to show in the comparison table', 'admin-view', 'luxsa'),
                'options'  => $wc_attr_fields,
                'default'  => array_keys($wc_fields_default)
            ),
        )
    ));
}

/**
 * Portfolio Panel
 */
LASF::createSection( $prefix, array(
    'id'        => 'portfolio_panel',
    'title'     => esc_html_x('Portfolio', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-th'
));

/**
 * Portfolio Panel - Label
 */
LASF::createSection( $prefix, array(
    'parent'    => 'portfolio_panel',
    'title'     => esc_html_x('Label Setting', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'    => array(
        array(
            'id'        => 'portfolio_custom_name',
            'type'      => 'text',
            'default'   => 'Portfolios',
            'title'     => esc_html_x('Portfolio Name', 'admin-view', 'luxsa'),
        ),
        array(
            'id'        => 'portfolio_custom_name2',
            'type'      => 'text',
            'default'   => 'Portfolio',
            'title'     => esc_html_x('Portfolio Singular Name', 'admin-view', 'luxsa'),
        ),
        array(
            'id'        => 'portfolio_custom_slug',
            'type'      => 'text',
            'default'   => 'portfolio',
            'title'     => esc_html_x('Portfolio Slug', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('When you change the portfolio slug, please remember go to Setting -> Permalinks and click to Save Changes button once again', 'admin-view', 'luxsa'),
        ),

        array(
            'id'        => 'portfolio_cat_custom_name',
            'type'      => 'text',
            'default'   => 'Portfolio Categories',
            'title'     => esc_html_x('Portfolio Category Name', 'admin-view', 'luxsa'),
        ),

        array(
            'id'        => 'portfolio_cat_custom_name2',
            'type'      => 'text',
            'default'   => 'Portfolio Category',
            'title'     => esc_html_x('Portfolio Category Singular Name', 'admin-view', 'luxsa'),
        ),
        array(
            'id'        => 'portfolio_cat_custom_slug',
            'type'      => 'text',
            'default'   => 'portfolio-category',
            'title'     => esc_html_x('Portfolio Category Slug', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('When you change the portfolio slug, please remember go to Setting -> Permalinks and click to Save Changes button once again', 'admin-view', 'luxsa'),
        )
    )
));

/**
 * Portfolio Panel - Label
 */
LASF::createSection( $prefix, array(
    'parent'    => 'portfolio_panel',
    'title'     => esc_html_x('General Setting', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'    => array(
        array(
            'id'        => 'layout_archive_portfolio',
            'type'      => 'image_select',
            'title'     => esc_html_x('Archive Portfolio Layout', 'admin-view', 'luxsa'),
            'desc'      => esc_html_x('Controls the layout of archive portfolio page', 'admin-view', 'luxsa'),
            'default'   => 'col-1c',
            'options'   => Luxsa_Options::get_config_main_layout_opts(true, false)
        ),
        array(
            'id' => 'header_transparency_archive_portfolio',
            'type' => 'button_set',
            'default' => 'inherit',
            'title' => esc_html_x('[Portfolio] Header Transparency', 'admin-view', 'luxsa'),
            'options' => Luxsa_Options::get_config_radio_opts()
        ),
        array(
            'id'        => 'main_full_width_archive_portfolio',
            'type'      => 'button_set',
            'default'   => 'inherit',
            'title'     => esc_html_x('100% Main Width', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('[Portfolio] Turn on to have the main area display at 100% width according to the window size. Turn off to follow site width.', 'admin-view', 'luxsa'),
            'options'   => Luxsa_Options::get_config_radio_opts()
        ),

        luxsa_render_responsive_main_space_options(array(
            'id'        => 'main_space_archive_portfolio',
            'title'     => esc_html_x('Custom Main Space', 'admin-view', 'luxsa')
        )),

        array(
            'id'        => 'portfolio_display_type',
            'default'   => 'grid',
            'title'     => esc_html_x('Display Type as', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Controls the type display of portfolio for the archive page', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => array(
                'grid'           => esc_html_x('Grid', 'admin-view', 'luxsa'),
                'masonry'        => esc_html_x('Masonry', 'admin-view', 'luxsa'),
                'list'           => esc_html_x('List', 'admin-view', 'luxsa'),
            )
        ),

        array(
            'id'            => 'portfolio_display_style',
            'default'       => '1',
            'title'         => esc_html_x('Select Style', 'admin-view', 'luxsa'),
            'type'          => 'select',
            'options'       => array(
                'type-1'           => esc_html_x('Type 1', 'admin-view', 'luxsa'),
                'type-2'           => esc_html_x('Type 2', 'admin-view', 'luxsa'),
                'type-3'           => esc_html_x('Type 3', 'admin-view', 'luxsa'),
                'type-4'           => esc_html_x('Type 4', 'admin-view', 'luxsa'),
                'type-5'           => esc_html_x('Type 5', 'admin-view', 'luxsa'),
                'type-6'           => esc_html_x('Type 6', 'admin-view', 'luxsa'),
            ),
            'dependency'    => array('portfolio_display_type', '!=', 'list')
        ),

        array(
            'id'            => 'portfolio_display_list_style',
            'default'       => '1',
            'title'         => esc_html_x('Select Style', 'admin-view', 'luxsa'),
            'type'          => 'select',
            'options'       => array(
                'list-type-1'      => esc_html_x('Type 1', 'admin-view', 'luxsa'),
                'list-type-2'      => esc_html_x('Type 2', 'admin-view', 'luxsa')
            ),
            'dependency'    => array('portfolio_display_type', '==', 'list')
        ),

        array(
            'id'        => 'portfolio_thumbnail_height_mode',
            'default'   => 'original',
            'title'     => esc_html_x('Portfolio Image Height Mode', 'admin-view', 'luxsa'),
            'subtitle'  => esc_html_x('Sizing proportions for height and width. Select "Original" to scale image without cropping.', 'admin-view', 'luxsa'),
            'type'      => 'select',
            'options'   => array(
                '1-1'       => esc_html_x('1-1', 'admin-view', 'luxsa'),
                'original'  => esc_html_x('Original', 'admin-view', 'luxsa'),
                '4-3'       => esc_html_x('4:3', 'admin-view', 'luxsa'),
                '3-4'       => esc_html_x('3:4', 'admin-view', 'luxsa'),
                '16-9'      => esc_html_x('16:9', 'admin-view', 'luxsa'),
                '9-16'      => esc_html_x('9:16', 'admin-view', 'luxsa'),
                'custom'    => esc_html_x('Custom', 'admin-view', 'luxsa')
            )
        ),
        array(
            'id'            => 'portfolio_thumbnail_height_custom',
            'type'          => 'text',
            'default'       => '70%',
            'title'         => esc_html_x('Portfolio Image Height Custom', 'admin-view', 'luxsa'),
            'dependency'    => array('portfolio_thumbnail_height_mode', '==', 'custom'),
            'subtitle'      => esc_html_x('Enter custom height.', 'admin-view', 'luxsa')
        ),

        luxsa_render_responsive_item_space_options(array(
            'id'            => 'portfolio_item_space',
            'title'         => esc_html_x('Item Space', 'admin-view', 'luxsa'),
            'subtitle'      => esc_html_x('Select gap between item in grids', 'admin-view', 'luxsa'),
        )),

        luxsa_render_responsive_column_options( array(
            'id'        => 'portfolio_column',
            'title'     => esc_html_x('Portfolio Column', 'admin-view', 'luxsa')
        ) ),
        array(
            'id'            => 'portfolio_per_page',
            'type'          => 'slider',
            'default'       => 10,
            'title'         => esc_html_x('Total Portfolio will be display in a page', 'admin-view', 'luxsa'),
            'min'           => 1,
            'max'           => 100,
            'step'          => 1,
            'unit'          => ''
        ),
        array(
            'id'            => 'portfolio_thumbnail_size',
            'default'       => 'full',
            'title'         => esc_html_x('Portfolio Thumbnail size', 'admin-view', 'luxsa'),
            'type'          => 'select',
            'options'       => luxsa_get_list_image_sizes()
        )
    )
));

/**
 * Portfolio Panel - Portfolio Single
 */
LASF::createSection( $prefix, array(
    'parent'    => 'portfolio_panel',
    'title'     => esc_html_x('Portfolio Single', 'admin-view', 'luxsa'),
    'icon'      => 'fas fa-check',
    'fields'    => array(
        array(
            'id'            => 'layout_single_portfolio',
            'type'          => 'image_select',
            'title'         => esc_html_x('Single Portfolio Layout', 'admin-view', 'luxsa'),
            'desc'          => esc_html_x('Controls the layout of portfolio detail page', 'admin-view', 'luxsa'),
            'default'       => 'col-1c',
            'options'       => Luxsa_Options::get_config_main_layout_opts(true, false)
        ),

        array(
            'id' => 'header_transparency_single_portfolio',
            'type' => 'button_set',
            'default' => 'inherit',
            'title' => esc_html_x('[Portfolio] Header Transparency', 'admin-view', 'luxsa'),
            'options' => Luxsa_Options::get_config_radio_opts()
        ),
    )
));

/**
 * 404 Panel
 */
LASF::createSection( $prefix, array(
    'id'        => 'error404_panel',
    'title'     => esc_html_x('404 Page', 'admin-view', 'luxsa'),
    'icon'      => 'fa fa-file-o',
    'fields'    => array(
        array(
            'id' => 'header_transparency_404',
            'type' => 'button_set',
            'default' => 'no',
            'title' => esc_html_x('[404] Header Transparency', 'admin-view', 'luxsa'),
            'options' => Luxsa_Options::get_config_radio_opts()
        ),
        array(
            'id'    => '404_page_content',
            'type'  => 'wp_editor',
            'desc'  => esc_html_x('Leaving empty content to inherit from theme', 'admin-view', 'luxsa'),
            'title' => esc_html_x('Custom 404 Page Content', 'admin-view', 'luxsa'),
        )
    )
));
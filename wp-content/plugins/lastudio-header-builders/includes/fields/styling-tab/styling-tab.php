<?php

function lahb_styling_tab_backend( $params ){

    if ( ! $params ) return;

    $screens = array(
        'all'	=> array(
            'list'	=> 'all_list_items',
            'panel'	=> 'all_panel_items',
        ),
        'tablets'	=> array(
            'list'	=> 'tablets_list_items',
            'panel'	=> 'tablets_panel_items',
        ),
        'mobiles'	=> array(
            'list'	=> 'mobiles_list_items',
            'panel'	=> 'mobiles_panel_items',
        ),
    );

    foreach ( $screens as $screen => $vars ) :

        ${$vars['list']} = ${$vars['panel']} = '';

        foreach ( $params as $tab_item ) :

            $el_href = strtolower($tab_item['tab_key']);

            ${$vars['list']} .= sprintf('<li class="lahb-tab"><a href="#%s"><span>%s</span></a></li>', $el_href, esc_html($tab_item['tab_title']) );

            ${$vars['panel']} .= '<div class="lahb-tab-panel lahb-styling-group-panel" data-id="#' . $el_href . '">';

            if(!empty($tab_item['tab_desc'])){
	            ${$vars['panel']} .= sprintf('<div class="lahb-field w-col-sm-12"><div>%s</div></div>', $tab_item['tab_desc']);
            }

            $el_partials = $tab_item['tab_content'];

            foreach ( $el_partials as $el_atts ) :

                switch ( $el_atts['property'] ) :

                    // typography
                    case 'row_layout':
                        ${$vars['panel']} .= lahb_select( array(
                            'title'			=> esc_html__( 'Row Layout', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'default'       => 'auto',
                            'options'		=> array(
                                'auto'	    => esc_html__( 'Auto', 'lastudio-header-builder' ),
                                '4-4-4'	    => esc_html__( '4/12 - 4/12 - 4/12', 'lastudio-header-builder' ),
                                '3-6-3'	    => esc_html__( '3/12 - 6/12 - 3/12', 'lastudio-header-builder' ),
                                '2-8-2'	    => esc_html__( '2/12 - 8/12 - 2/12', 'lastudio-header-builder' ),
                                '5-2-5'	    => esc_html__( '5/12 - 2/12 - 5/12', 'lastudio-header-builder' ),
                                '1-10-1'	=> esc_html__( '1/12 - 10/12 - 1/12', 'lastudio-header-builder' ),
                                '2-6-2'	    => esc_html__( '20% - 60% - 20%', 'lastudio-header-builder' ),
                                '25-5-25'	=> esc_html__( '25% - 50% - 25%', 'lastudio-header-builder' ),
                                '3-4-3'	    => esc_html__( '30% - 40% - 30%', 'lastudio-header-builder' ),
                                '35-3-35'	=> esc_html__( '35% - 30% - 35%', 'lastudio-header-builder' ),
                                '4-2-4'	    => esc_html__( '40% - 20% - 40%', 'lastudio-header-builder' ),
                                '45-1-45'	=> esc_html__( '45% - 10% - 45%', 'lastudio-header-builder' ),
                                '1-8-1'	    => esc_html__( '10% - 80% - 10%', 'lastudio-header-builder' )
                            ),
                            'get'			=> true,
                        ));
                        break;

                    // typography
                    case 'color':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'color_hover':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color Hover', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'fill':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'fill_hover':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color Hover', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'font_size':
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Font Size', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                                'vw'	=> 'vw',
                                'vh'	=> 'vh'
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        break;

                    case 'font_weight':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Font Weight', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                '300'	=> '300',
                                '400'	=> '400',
                                '500'	=> '500',
                                '600'	=> '600',
                                '700'	=> '700',
                                '800'	=> '800',
                                '900'	=> '900',
                                ''		=> '<i class="dashicons dashicons-dismiss"></i>',
                            ),
                            // 'default'	=> '',
                            'get'			=> true,
                        ));
                        break;

                    case 'font_style':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Font Style', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'normal' => '<i class="dashicons dashicons-dismiss"></i>',
                                'italic' => '<span style="font-style:italic;font-family: serif;">T</span>',
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'text_align':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Text Align', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                ''		  => '<i class="dashicons dashicons-dismiss"></i>',
                                'left'	  => '<i class="dashicons dashicons-editor-alignleft"></i>',
                                'center'  => '<i class="dashicons dashicons-editor-aligncenter"></i>',
                                'right'	  => '<i class="dashicons dashicons-editor-alignright"></i>',
                                'justify' => '<i class="dashicons dashicons-editor-justify"></i>',
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'text_transform':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Text Transform', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'none'			=> '<i class="dashicons dashicons-dismiss"></i>',
                                'uppercase'		=> 'TT',
                                'capitalize'	=> 'Tt',
                                'lowercase'		=> 'tt',
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'text_decoration':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Text Decoration', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'none'			=> '<i class="dashicons dashicons-dismiss"></i>',
                                'underline'		=> '<u>T</u>',
                                'line-through'	=> '<span style="text-decoration: line-through">T</span>',
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'width':
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Width', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                                'vw'	=> 'vw',
                                'vh'	=> 'vh'
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        break;

                    case 'max_width':
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Max Width', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                                'vw'	=> 'vw',
                                'vh'	=> 'vh'
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        break;

                    case 'height':
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Height', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                                'vw'	=> 'vw',
                                'vh'	=> 'vh',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        break;

                    case 'line_height':
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Line Height', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        break;

                    case 'letter_spacing':
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Letter Spacing', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        break;

                    case 'overflow':
                        ${$vars['panel']} .= lahb_select( array(
                            'title'			=> esc_html__( 'Overflow', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                ''	  	  => '',
                                'auto'	  => esc_html__( 'Auto', 'lastudio-header-builder' ),
                                'hidden'  => esc_html__( 'Hidden', 'lastudio-header-builder' ),
                                'inherit' => esc_html__( 'Inherit', 'lastudio-header-builder' ),
                                'initial' => esc_html__( 'Initial', 'lastudio-header-builder' ),
                                'overlay' => esc_html__( 'Overlay', 'lastudio-header-builder' ),
                                'visible' => esc_html__( 'Visible', 'lastudio-header-builder' ),
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'word_break':
                        ${$vars['panel']} .= lahb_select( array(
                            'title'			=> esc_html__( 'Word Break', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                ''	  			=> '',
                                'break-all'		=> esc_html__( 'Break All', 'lastudio-header-builder' ),
                                'break-word'	=> esc_html__( 'Break Word', 'lastudio-header-builder' ),
                                'inherit'		=> esc_html__( 'Inherit', 'lastudio-header-builder' ),
                                'initial'		=> esc_html__( 'Initial', 'lastudio-header-builder' ),
                                'normal'		=> esc_html__( 'Normal', 'lastudio-header-builder' ),
                            ),
                            'get'			=> true,
                        ));
                        break;

                    // background
                    case 'background_color':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Background Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'background_color_hover':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Background Hover Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'background_image':
                        ${$vars['panel']} .= lahb_image( array(
                            'title'			=> esc_html__( 'Background Image', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true,
                        ));
                        break;

                    case 'background_position':
                        ${$vars['panel']} .= lahb_select( array(
                            'title'			=> esc_html__( 'Background Position', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'left top'		=> esc_html__( 'Left Top', 'lastudio-header-builder' ),
                                'left center'	=> esc_html__( 'Left Center', 'lastudio-header-builder' ),
                                'left bottom'	=> esc_html__( 'Left Bottom', 'lastudio-header-builder' ),
                                'center top'	=> esc_html__( 'Center Top', 'lastudio-header-builder' ),
                                'center center'	=> esc_html__( 'Center Center', 'lastudio-header-builder' ),
                                'center bottom'	=> esc_html__( 'Center Bottom', 'lastudio-header-builder' ),
                                'right top'		=> esc_html__( 'Right Top', 'lastudio-header-builder' ),
                                'right center'	=> esc_html__( 'Right Center', 'lastudio-header-builder' ),
                                'right bottom'	=> esc_html__( 'Right Bottom', 'lastudio-header-builder' ),
                            ),
                            'default'		=> 'center center',
                            'get'			=> true,
                        ));
                        break;

                    case 'background_repeat':
                        ${$vars['panel']} .= lahb_select( array(
                            'title'			=> esc_html__( 'Background Repeat', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'options'		=> array(
                                'repeat'	=> esc_html__( 'Repeat'	, 'lastudio-header-builder' ),
                                'repeat-x'	=> esc_html__( 'Repeat x', 'lastudio-header-builder' ),
                                'repeat-y'	=> esc_html__( 'Repeat y', 'lastudio-header-builder' ),
                                'no-repeat'	=> esc_html__( 'No Repeat', 'lastudio-header-builder' ),
                            ),
                            'default'		=> 'no-repeat',
                            'get'			=> true,
                        ));
                        break;

                    case 'background_cover':
                        ${$vars['panel']} .= lahb_switcher( array(
                            'title'			=> esc_html__( 'Background Cover ?', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'default'		=> 'true',
                            'get'			=> true,
                        ));
                        break;

                    // box
                    case 'margin':
                        ${$vars['panel']} .= '<div class="lahb-field lahb-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Margin', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                    case 'padding':
                        ${$vars['panel']} .= '<div class="lahb-field lahb-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Padding', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                    case 'border_radius':
                        ${$vars['panel']} .= '<div class="lahb-field lahb-box-wrap lahb-box-border-radius-wrap w-col-sm-12"><h5>' . esc_html__( 'Border Radius', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name('top_left_radius', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name('top_right_radius', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name('bottom_right_radius', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name('bottom_left_radius', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                    case 'border':
                        ${$vars['panel']} .= lahb_select( array(
                            'title'			=> esc_html__( 'Border Style', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_style', $screen, $el_href),
                            'options'		=> array(
                                ''			=> '',
                                'none'		=> esc_html__( 'None', 'lastudio-header-builder' ),
                                'solid'		=> esc_html__( 'Solid', 'lastudio-header-builder' ),
                                'dotted'	=> esc_html__( 'Dotted', 'lastudio-header-builder' ),
                                'dashed'	=> esc_html__( 'Dashed', 'lastudio-header-builder' ),
                                'double'	=> esc_html__( 'Double', 'lastudio-header-builder' ),
                                'groove'	=> esc_html__( 'Groove', 'lastudio-header-builder' ),
                                'ridge'		=> esc_html__( 'Ridge', 'lastudio-header-builder' ),
                                'inset'		=> esc_html__( 'Inset', 'lastudio-header-builder' ),
                                'outset'	=> esc_html__( 'Outset', 'lastudio-header-builder' ),
                                'initial'	=> esc_html__( 'Initial', 'lastudio-header-builder' ),
                                'inherit'	=> esc_html__( 'Inherit', 'lastudio-header-builder' ),
                            ),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Border Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_color', $screen, $el_href),
                            'get'			=> true
                        ));
                        ${$vars['panel']} .= '<div class="lahb-field lahb-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Border Width', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                    case 'border_hover':
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Border Hover Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'get'			=> true
                        ));
                        break;

                    case 'float':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Floating', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'default'		=> 'left',
                            'options'		=> array(
                                'left'	=> 'left',
                                'right'	=> 'right',
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'position_property':
                        ${$vars['panel']} .= lahb_custom_select( array(
                            'title'			=> esc_html__( 'Position', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'], $screen, $el_href),
                            'default'		=> 'static',
                            'options'		=> array(
                                'static'	=> 'static',
                                'absolute'	=> 'absolute',
                                'relative'	=> 'relative',
                            ),
                            'get'			=> true,
                        ));
                        break;

                    case 'position':
                        ${$vars['panel']} .= '<div class="lahb-field lahb-box-wrap w-col-sm-6"><h5>' . esc_html__( 'Position', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_top', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_right', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_bottom', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_textfield( array(
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_left', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div>';
                        ${$vars['panel']} .= '<div class="lahb-field lahb-help-content-wrap w-col-sm-12">';
                        ${$vars['panel']} .= lahb_help( array(
                            'title'			=> esc_html__( 'Help to use calc', 'lastudio-header-builder' ),
                            'id'			=> $el_atts['property'] . '_help_calc_' . $screen . '_el_' . $el_href,
                            'default'		=> '
									To make this element stay center, all you need is using calc code as following:<br>
									calc(50% - half width)<br>
									For Example:<br>
									Width = 40px<br>
									Left = calc(50% - 20px)
								',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                    case 'box_shadow':
                        ${$vars['panel']} .= '<div class="lahb-field lahb-shadow-box-wrap w-col-sm-12"><h5>' . esc_html__( 'Box Shadow', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'X offset', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_xoffset', $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Y offset', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_yoffset', $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Blur', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_blur', $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Spread', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_spread', $screen, $el_href),
                            'options'		=> array(
                                'px'	=> 'px',
                                'em'	=> 'em',
                                '%'		=> '%',
                            ),
                            'default_unit'	=> 'px',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_switcher( array(
                            'title'			=> esc_html__( 'Inset Shadow Status', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_status', $screen, $el_href),
                            'default'       => 'false',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Shadow Color', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_color', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                    case 'gradient':
                        ${$vars['panel']} .= '<div class="lahb-field lahb-gradient-wrap w-col-sm-12"><h5>' . esc_html__( 'Gradient', 'lastudio-header-builder' ) . '</h5>';
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color 1', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_color1', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color 2', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_color2', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color 3', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_color3', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_colorpicker( array(
                            'title'			=> esc_html__( 'Color 4', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_color4', $screen, $el_href),
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= lahb_number_unit( array(
                            'title'			=> esc_html__( 'Direction', 'lastudio-header-builder' ),
                            'id'			=> lahb_pretty_property_name($el_atts['property'] . '_direction', $screen, $el_href),
                            'options'		=> array(
                                'deg'	=> 'deg',
                            ),
                            'default_unit'	=> 'deg',
                            'get'			=> true,
                        ));
                        ${$vars['panel']} .= '</div><div class="wp-clearfix"></div>';
                        break;

                endswitch;
            endforeach;
            ${$vars['panel']} .= '</div>';

        endforeach;

    endforeach;

    ?>

    <ul class="lahb-tabs-list lahb-styling-screens wp-clearfix lahb-tabs-device-controls">
        <li class="lahb-tab">
            <a href="#all" data-device-mode="all">
                <i class="dashicons dashicons-desktop"></i>
                <span><?php esc_html_e( 'Desktop', 'lastudio-header-builder' ); ?></span>
            </a>
        </li>
        <li class="lahb-tab">
            <a href="#tablets" data-device-mode="tablets">
                <i class="dashicons dashicons-tablet"></i>
                <span><?php esc_html_e( 'Tablets', 'lastudio-header-builder' ); ?></span>
            </a>
        </li>
        <li class="lahb-tab">
            <a href="#mobiles" data-device-mode="mobiles">
                <i class="dashicons dashicons-smartphone"></i>
                <span><?php esc_html_e( 'Mobiles', 'lastudio-header-builder' ); ?></span>
            </a>
        </li>
    </ul>

    <!-- all devices -->
    <div class="lahb-tab-panel lahb-styling-screen-panel wp-clearfix" data-id="#all">

        <ul class="lahb-tabs-list lahb-styling-groups wp-clearfix"><?php echo '' . $all_list_items; ?></ul>
        <?php echo '' . $all_panel_items; ?>

    </div> <!-- end all devices -->

    <!-- tablets devices -->
    <div class="lahb-tab-panel lahb-styling-screen-panel" data-id="#tablets">

        <ul class="lahb-tabs-list lahb-styling-groups wp-clearfix"><?php echo '' . $tablets_list_items; ?></ul>
        <?php echo '' . $tablets_panel_items; ?>

    </div> <!-- end tablets devices -->

    <!-- mobiles devices -->
    <div class="lahb-tab-panel lahb-styling-screen-panel" data-id="#mobiles">

        <ul class="lahb-tabs-list lahb-styling-groups wp-clearfix"><?php echo '' . $mobiles_list_items; ?></ul>
        <?php echo '' . $mobiles_panel_items; ?>

    </div> <!-- end mobiles devices -->

    <?php
}

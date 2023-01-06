<!-- modal menu edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="menu">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Menu Settings', 'lastudio-header-builder'); ?></h4>
        <i class="dashicons dashicons-no-alt"></i>
    </div>

    <div class="lahb-modal-contents-wrap">
        <div class="lahb-modal-contents w-row">

            <ul class="lahb-tabs-list lahb-element-groups wp-clearfix">
                <li class="lahb-tab w-active">
                    <a href="#general">
                        <span><?php esc_html_e('General', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#styling">
                        <span><?php esc_html_e('Styling', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#extra-class">
                        <span><?php esc_html_e('Extra Class', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
            </ul> <!-- end .lahb-tabs-list -->

            <!-- general -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#general">

                <?php
                lahb_menu(array(
                    'title' => esc_html__('Select a Menu', 'lastudio-header-builder'),
                    'id' => 'menu',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Enable MegaMenu ?', 'lastudio-header-builder'),
                    'id' => 'show_megamenu',
                    'default' => 'false'
                ));
                lahb_switcher(array(
                    'title' => esc_html__('is Vertical MegaMenu ?', 'lastudio-header-builder'),
                    'id' => 'is_vertical',
                    'default' => 'false',
                    'dependency' => array(
                        'true' => array('vertical_text'),
                        'false' => array('height_100'),
                    )
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Vertical Text', 'lastudio-header-builder'),
                    'id' => 'vertical_text',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Disable hamburger menu on tablet?', 'lastudio-header-builder'),
                    'id' => 'show_tablet_menu',
                    'default' => 'false'
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Use hamburger menu on mobile?', 'lastudio-header-builder'),
                    'id' => 'show_mobile_menu',
                    'default' => 'true',
                    'dependency' => array(
                        'true' => array('hm_box_pos')
                    )
                ));
                lahb_custom_select(array(
                    'title' => esc_html__('Hamburger Box Position', 'lastudio-header-builder'),
                    'id' => 'hm_box_pos',
                    'options' => array(
                        'left' => 'left',
                        'right' => 'right'
                    ),
                    'default' => 'left',
                ));
                lahb_icon(array(
                    'title' => esc_html__('Hamburger Icon', 'lastudio-header-builder'),
                    'id' => 'hamburger_icon'
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Show parent menu arrow ?', 'lastudio-header-builder'),
                    'id' => 'show_parent_arrow',
                    'default' => 'true',
                    'dependency' => array(
                        'true' => array('parent_arrow_direction'),
                    ),
                ));
                lahb_custom_select(array(
                    'title' => esc_html__('Parent menu arrow direction', 'lastudio-header-builder'),
                    'id' => 'parent_arrow_direction',
                    'options' => array(
                        'top' => 'top',
                        'bottom' => 'bottom',
                        'left' => 'left',
                        'right' => 'right',
                    ),
                    'default' => 'bottom',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Display "Description" under menu item?', 'lastudio-header-builder'),
                    'id' => 'desc_item',
                    'default' => 'false',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Full Width?', 'lastudio-header-builder'),
                    'id' => 'full_menu',
                    'default' => 'false',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('100% Height?', 'lastudio-header-builder'),
                    'id' => 'height_100',
                    'default' => 'false',
                ));
                ?>

            </div> <!-- end general -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">
                <?php
                $style_tabs = [];
                $style_tabs[] = array(
	                'tab_title' => __('Menu Item', 'lastudio-header-builder'),
	                'tab_key' => 'menu_item',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_align'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'overflow'),
		                array('property' => 'word_break'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Current Menu Item', 'lastudio-header-builder'),
	                'tab_key' => 'current_menu_item',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'overflow'),
		                array('property' => 'word_break'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                if(function_exists('lastudio_get_theme_support') && lastudio_get_theme_support('header-builder::menu')){

                }
                else{
	                $style_tabs[] = array(
		                'tab_title' => __('Current Item Shape', 'lastudio-header-builder'),
		                'tab_key' => 'current_item_shape',
		                'tab_content' => array(
			                array('property' => 'width'),
			                array('property' => 'height'),
			                array('property' => 'background_color'),
			                array('property' => 'background_color_hover'),
			                array('property' => 'position'),
			                array('property' => 'border'),
			                array('property' => 'border_radius')
		                )
	                );
                }

                $style_tabs[] = array(
	                'tab_title' => __('Parent Menu Arrow', 'lastudio-header-builder'),
	                'tab_key' => 'parent_menu_arrow',
	                'tab_content' => array(
		                array('property' => 'position'),
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Menu Icon', 'lastudio-header-builder'),
	                'tab_key' => 'menu_icon',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'margin'),
		                array('property' => 'padding')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Menu Description', 'lastudio-header-builder'),
	                'tab_key' => 'menu_description',
	                'tab_content' => array(
		                array('property' => 'position'),
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'overflow'),
		                array('property' => 'word_break'),
		                array('property' => 'border')
	                )
                );
                $style_tabs[] =  array(
	                'tab_title' => __('Menu Badge', 'lastudio-header-builder'),
	                'tab_key' => 'menu_badge',
	                'tab_content' => array(
		                array('property' => 'position'),
		                array('property' => 'color'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing')
	                )
                );

                $style_tabs[] = array(
	                'tab_title' => __('Submenu Box', 'lastudio-header-builder'),
	                'tab_key' => 'submenu_box',
	                'tab_content' => array(
		                array('property' => 'width'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'background_image'),
		                array('property' => 'background_position'),
		                array('property' => 'background_repeat'),
		                array('property' => 'background_cover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius'),
		                array('property' => 'box_shadow')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Submenu Item', 'lastudio-header-builder'),
	                'tab_key' => 'submenu_item',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_align'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'overflow'),
		                array('property' => 'word_break'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Submenu Current Item', 'lastudio-header-builder'),
	                'tab_key' => 'submenu_current_item',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Submenu Menu Icon', 'lastudio-header-builder'),
	                'tab_key' => 'submenu_menu_icon',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'margin'),
		                array('property' => 'padding')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Responsive Hamburger Icon', 'lastudio-header-builder'),
	                'tab_key' => 'responsive_hamburger_icon',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Responsive Menu Box', 'lastudio-header-builder'),
	                'tab_key' => 'responsive_menu_box',
	                'tab_content' => array(
		                array('property' => 'width'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'background_image'),
		                array('property' => 'background_position'),
		                array('property' => 'background_repeat'),
		                array('property' => 'background_cover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'position'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Box', 'lastudio-header-builder'),
	                'tab_key' => 'box',
	                'tab_content' => array(
		                array('property' => 'width'),
		                array('property' => 'height'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'background_image'),
		                array('property' => 'background_position'),
		                array('property' => 'background_repeat'),
		                array('property' => 'background_cover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $style_tabs[] = array(
	                'tab_title' => __('Toggle Button', 'lastudio-header-builder'),
	                'tab_key' => 'toggle_button',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_align'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'width'),
		                array('property' => 'height'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'background_image'),
		                array('property' => 'background_position'),
		                array('property' => 'background_repeat'),
		                array('property' => 'background_cover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );

                if(function_exists('lastudio_get_theme_support') && lastudio_get_theme_support('header-builder::menu')){
	                $style_tabs[] = array(
		                'tab_title' => __('Vertical Dropdown', 'lastudio-header-builder'),
		                'tab_key' => 'vertical_dropdown',
		                'tab_content' => array(
			                array('property' => 'color'),
			                array('property' => 'font_size'),
			                array('property' => 'font_weight'),
			                array('property' => 'font_style'),
			                array('property' => 'text_align'),
			                array('property' => 'text_transform'),
			                array('property' => 'text_decoration'),
			                array('property' => 'line_height'),
			                array('property' => 'letter_spacing'),
			                array('property' => 'width'),
			                array('property' => 'height'),
			                array('property' => 'background_color'),
			                array('property' => 'margin'),
			                array('property' => 'padding'),
			                array('property' => 'border'),
			                array('property' => 'border_radius')
		                )
	                );
                }

                lahb_styling_tab_backend($style_tabs);
                ?>

            </div> <!-- end #styling -->

            <!-- extra-class -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#extra-class">

                <?php
                lahb_textfield(array(
                    'title' => esc_html__('Extra class', 'lastudio-header-builder'),
                    'id' => 'extra_class',
                ));
                ?>

            </div> <!-- end #extra-class -->

        </div>
    </div> <!-- end lahb-modal-contents-wrap -->

    <div class="lahb-modal-footer">
        <input type="button" class="lahb_close button" value="<?php esc_html_e('Close', 'lastudio-header-builder'); ?>">
        <input type="button" class="lahb_save button button-primary"
               value="<?php esc_html_e('Save Changes', 'lastudio-header-builder'); ?>">
    </div>

</div> <!-- end lahb-elements -->
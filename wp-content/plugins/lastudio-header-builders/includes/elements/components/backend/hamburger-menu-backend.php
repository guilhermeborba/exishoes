<!-- modal menu edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="hamburger-menu">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Hamburger Menu Settings', 'lastudio-header-builder'); ?></h4>
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
                    <a href="#elements">
                        <span><?php esc_html_e('Elements', 'lastudio-header-builder'); ?></span>
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
                lahb_select(array(
                    'title' => esc_html__('Hamburger Type', 'lastudio-header-builder'),
                    'id' => 'hamburger_type',
                    'default' => 'toggle',
                    'options' => array(
                        'toggle' => esc_html__('Toggle', 'lastudio-header-builder'),
                        'full' => esc_html__('Full', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'toggle' => array(
                            'toggle_from',
                            'copyright',
                            'search',
                            'placeholder'
                        ),
                    ),
                ));
                lahb_select(array(
                    'title' => esc_html__('Hamburger Menu Style', 'lastudio-header-builder'),
                    'id' => 'hm_style',
                    'default' => 'light',
                    'options' => array(
                        'light' => esc_html__('Light', 'lastudio-header-builder'),
                        'dark' => esc_html__('Dark', 'lastudio-header-builder'),
                    ),
                ));
                lahb_select(array(
                    'title' => esc_html__('Open Toggle From', 'lastudio-header-builder'),
                    'id' => 'toggle_from',
                    'default' => 'right',
                    'options' => array(
                        'right' => esc_html__('Right', 'lastudio-header-builder'),
                        'left' => esc_html__('Left', 'lastudio-header-builder'),
                    ),
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Hamburger Text', 'lastudio-header-builder'),
                    'id' => 'hamburger_text'
                ));
                lahb_icon(array(
                    'title' => esc_html__('Hamburger Icon', 'lastudio-header-builder'),
                    'id' => 'hamburger_icon',
                ));
                ?>

            </div> <!-- end general -->

            <!-- general -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#elements">
                <?php
                lahb_image(array(
                    'title' => esc_html__('Logo', 'lastudio-header-builder'),
                    'id' => 'image_logo',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Display Menu?', 'lastudio-header-builder'),
                    'id' => 'display_menu',
                    'default' => 'true',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Display Socials?', 'lastudio-header-builder'),
                    'id' => 'socials',
                    'default' => 'false',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Display Search?', 'lastudio-header-builder'),
                    'id' => 'search',
                    'default' => 'false',
                    'dependency' => array(
                        'true' => array('placeholder'),
                    ),
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Search Placeholder', 'lastudio-header-builder'),
                    'id' => 'placeholder',
                    'default' => 'Search ...',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Display Content?', 'lastudio-header-builder'),
                    'id' => 'content',
                    'default' => 'false',
                    'dependency' => array(
                        'true' => array('text_content'),
                    ),
                ));
                lahb_textarea(array(
                    'title' => esc_html__('Content', 'lastudio-header-builder'),
                    'id' => 'text_content',
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Copyright', 'lastudio-header-builder'),
                    'id' => 'copyright'
                ));
                ?>
            </div>

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">
                <?php
                $tab_data = [];

                $tab_data[] = array(
	                'tab_title' => __('Hamburger Icon Color', 'lastudio-header-builder'),
	                'tab_key' => 'hamburger_icon_color',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size')
	                )
                );

                $tab_data[] = array(
	                'tab_title' => __('Hamburger Icon Text', 'lastudio-header-builder'),
	                'tab_key' => 'hamburger_icon_text',
	                'tab_content' => array(
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'padding')
	                )
                );

                $tab_data[] = array(
	                'tab_title' => __('Hamburger Icon Box', 'lastudio-header-builder'),
	                'tab_key' => 'hamburger_icon_box',
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
		                array('property' => 'border_radius'),
		                array('property' => 'border'),
	                )
                );
                $tab_data[] = array(
	                'tab_title' => __('Hamburger Box', 'lastudio-header-builder'),
	                'tab_key' => 'hamburger_box',
	                'tab_content' => array(
		                array('property' => 'padding'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'background_image'),
		                array('property' => 'background_position'),
		                array('property' => 'background_repeat'),
		                array('property' => 'background_cover'),
		                array('property' => 'gradient')
	                )
                );
                $tab_data[] = array(
	                'tab_title' => __('Logo Box', 'lastudio-header-builder'),
	                'tab_key' => 'logo_box',
	                'tab_content' => array(
		                array('property' => 'width'),
		                array('property' => 'height'),
		                array('property' => 'text_align'),
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
                $tab_data[] = array(
	                'tab_title' => __('Menu Box', 'lastudio-header-builder'),
	                'tab_key' => 'menu_box',
	                'tab_content' => array(
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius'),
	                )
                );
                $tab_data[] = array(
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
                $tab_data[] = array(
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
		                array('property' => 'border_radius'),
	                )
                );

                $tab_data[] = array(
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
                $tab_data[] = array(
	                'tab_title' => __('Search Input', 'lastudio-header-builder'),
	                'tab_key' => 'search_input',
	                'tab_content' => array(
		                array('property' => 'font_size'),
		                array('property' => 'line_height'),
		                array('property' => 'width'),
		                array('property' => 'height'),
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $tab_data[] = array(
	                'tab_title' => __('Search Box', 'lastudio-header-builder'),
	                'tab_key' => 'search_box',
	                'tab_content' => array(
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $tab_data[] = array(
	                'tab_title' => __('Elements Box', 'lastudio-header-builder'),
	                'tab_key' => 'elements_box',
	                'tab_content' => array(
		                array('property' => 'width'),
		                array('property' => 'height'),
		                array('property' => 'text_align'),
		                array('property' => 'background_color'),
		                array('property' => 'background_color_hover'),
		                array('property' => 'margin'),
		                array('property' => 'padding'),
		                array('property' => 'border'),
		                array('property' => 'border_radius')
	                )
                );
                $tab_data[] = array(
	                'tab_title' => __('Content', 'lastudio-header-builder'),
	                'tab_key' => 'content',
	                'tab_content' => array(
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'font_weight'),
		                array('property' => 'font_style'),
		                array('property' => 'text_transform'),
		                array('property' => 'text_decoration'),
		                array('property' => 'line_height'),
		                array('property' => 'letter_spacing'),
		                array('property' => 'overflow'),
		                array('property' => 'word_break')
	                )
                );
                $tab_data[] = array(
	                'tab_title' => __('Socials', 'lastudio-header-builder'),
	                'tab_key' => 'socials',
	                'tab_content' => array(
		                array('property' => 'width'),
		                array('property' => 'height'),
		                array('property' => 'color'),
		                array('property' => 'color_hover'),
		                array('property' => 'font_size'),
		                array('property' => 'text_align'),
		                array('property' => 'line_height'),
		                array('property' => 'margin'),
		                array('property' => 'padding')
	                )
                );

                $tab_data[] = array(
	                'tab_title' => __('Copyright', 'lastudio-header-builder'),
	                'tab_key' => 'copyright',
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
		                array('property' => 'overflow'),
		                array('property' => 'word_break')
	                )
                );

                $tab_data[] = array(
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
		                array('property' => 'border_radius'),
	                )
                );

                lahb_styling_tab_backend($tab_data);
                ?>

            </div> <!-- end #styling -->

            <!-- extra-class -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#extra-class">

                <?php
                lahb_textfield(array(
                    'title' => esc_html__('Extra class', 'lastudio-header-builder'),
                    'id' => 'extra_class',
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Extra class for panel', 'lastudio-header-builder'),
                    'id' => 'extra_class_panel',
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
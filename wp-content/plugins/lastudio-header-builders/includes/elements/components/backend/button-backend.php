<!-- modal search edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="button">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Button Settings', 'lastudio-header-builder'); ?></h4>
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
                lahb_textfield(array(
                    'title' => __('Text', 'lastudio-header-builder'),
                    'id' => 'text',
                    'default' => 'Button',
                    'placeholder' => true,
                ));
                lahb_textfield(array(
                    'title' => __('Link', 'lastudio-header-builder'),
                    'id' => 'link',
                    'default' => 'https://la-studioweb.com',
                ));
                lahb_switcher(array(
                    'title' => __('Open link in a new tab', 'lastudio-header-builder'),
                    'id' => 'link_new_tab',
                    'default' => 'false',
                ));
                // Tooltip Text
                lahb_switcher(array(
                    'title' => __('Show Tooltip Text ?', 'lastudio-header-builder'),
                    'id' => 'show_tooltip',
                    'default' => 'false',
                    'dependency' => array(
                        'true' => array(
                            'tooltip_text',
                            'tooltip_position'
                        ),
                    ),
                ));
                lahb_textfield(array(
                    'title' => __('Tooltip Text', 'lastudio-header-builder'),
                    'id' => 'tooltip_text',
                    'default' => 'Tooltip Text',
                ));
                lahb_select(array(
                    'title' => __('Select Tooltip Position', 'lastudio-header-builder'),
                    'id' => 'tooltip_position',
                    'default' => 'tooltip-on-bottom',
                    'options' => array(
                        'tooltip-on-top' => __('Top', 'lastudio-header-builder'),
                        'tooltip-on-bottom' => __('Bottom', 'lastudio-header-builder'),
                    ),
                ));
                ?>

            </div> <!-- end general -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">
                <?php
                $tabs_data = array(
                    array(
                        'tab_title' => __('Button', 'lastudio-header-builder'),
                        'tab_key' => 'button',
                        'tab_content' => array(
                            array('property' => 'font_size'),
                            array('property' => 'font_weight'),
                            array('property' => 'font_style'),
                            array('property' => 'text_transform'),
                            array('property' => 'color'),
                            array('property' => 'color_hover'),
                            array('property' => 'background_color'),
                            array('property' => 'background_color_hover'),
                            array('property' => 'background_image'),
                            array('property' => 'background_position'),
                            array('property' => 'background_repeat'),
                            array('property' => 'background_cover'),
                            array('property' => 'gradient'),
                            array('property' => 'text_align'),
                            array('property' => 'text_decoration'),
                            array('property' => 'line_height'),
                            array('property' => 'letter_spacing'),
                            array('property' => 'overflow'),
                            array('property' => 'word_break'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_hover'),
                            array('property' => 'border_radius'),
                            array('property' => 'box_shadow')
                        )
                    ),
                    array(
                        'tab_title' => __('Tooltip', 'lastudio-header-builder'),
                        'tab_key' => 'tooltip',
                        'tab_content' => array(
                            array('property' => 'position'),
                            array('property' => 'font_size'),
                            array('property' => 'font_weight'),
                            array('property' => 'font_style'),
                            array('property' => 'text_transform'),
                            array('property' => 'color'),
                            array('property' => 'color_hover'),
                            array('property' => 'background_color'),
                            array('property' => 'background_color_hover'),
                            array('property' => 'text_align'),
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
                    ),
                    array(
                        'tab_title' => __('Tooltip Arrow', 'lastudio-header-builder'),
                        'tab_key' => 'tooltip_arrow',
                        'tab_content' => array(
                            array('property' => 'position'),
                            array('property' => 'background_color'),
                            array('property' => 'background_color_hover')
                        )
                    ),
                    array(
                        'tab_title' => __('Box', 'lastudio-header-builder'),
                        'tab_key' => 'box',
                        'tab_content' => array(
                            array('property' => 'width'),
                            array('property' => 'height'),
                            array('property' => 'background_color'),
                            array('property' => 'background_color_hover'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius')
                        )
                    )
                );
                lahb_styling_tab_backend($tabs_data);
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
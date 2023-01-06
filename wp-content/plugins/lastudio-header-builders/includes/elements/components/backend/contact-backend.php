<!-- modal search edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="contact">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Contact Settings', 'lastudio-header-builder'); ?></h4>
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
                // Select Form
                lahb_contact(array(
                    'title' => esc_html__('Select contact form', 'lastudio-header-builder'),
                    'id' => 'contact_form',
                ));
                // Select Type
                lahb_select(array(
                    'title' => esc_html__('Type', 'lastudio-header-builder'),
                    'id' => 'contact_type',
                    'default' => 'icon',
                    'options' => array(
                        'text' => esc_html__('Text', 'lastudio-header-builder'),
                        'icon' => esc_html__('Icon', 'lastudio-header-builder'),
                        'form' => esc_html__('Just Form', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'text' => array('contact_text'),
                    ),
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Contact Text', 'lastudio-header-builder'),
                    'id' => 'contact_text',
                    'default' => 'Contact Us',
                ));
                // Select Open type
                lahb_select(array(
                    'title' => esc_html__('How to open the form?', 'lastudio-header-builder'),
                    'id' => 'open_form',
                    'default' => 'modal',
                    'options' => array(
                        'modal' => esc_html__('Modal', 'lastudio-header-builder'),
                        'dropdown' => esc_html__('Dropdown', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'modal' => array('contact_modal_text'),
                    ),
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Modal Contact Heading', 'lastudio-header-builder'),
                    'id' => 'contact_modal_text',
                    'default' => 'Contact Us',
                ));
                // Tooltip Text
                lahb_switcher(array(
                    'title' => esc_html__('Show Tooltip Text ?', 'lastudio-header-builder'),
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
                    'title' => esc_html__('Tooltip Text', 'lastudio-header-builder'),
                    'id' => 'tooltip_text',
                    'default' => 'Contact',
                ));
                lahb_select(array(
                    'title' => esc_html__('Select Tooltip Position', 'lastudio-header-builder'),
                    'id' => 'tooltip_position',
                    'default' => 'tooltip-on-bottom',
                    'options' => array(
                        'tooltip-on-top' => esc_html__('Top', 'lastudio-header-builder'),
                        'tooltip-on-bottom' => esc_html__('Bottom', 'lastudio-header-builder'),
                    ),
                ));
                ?>

            </div> <!-- end general -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">

                <?php
                $tabs_data = array(
                    array(
                        'tab_title' => __('Text', 'lastudio-header-builder'),
                        'tab_key' => 'text',
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
                    ),
                    array(
                        'tab_title' => __('Icon', 'lastudio-header-builder'),
                        'tab_key' => 'icon',
                        'tab_content' => array(
                            array('property' => 'color'),
                            array('property' => 'color_hover'),
                            array('property' => 'font_size'),
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
                            array('property' => 'background_image'),
                            array('property' => 'background_position'),
                            array('property' => 'background_repeat'),
                            array('property' => 'background_cover'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius'),
                        )
                    ),
                    array(
                        'tab_title' => __('Form', 'lastudio-header-builder'),
                        'tab_key' => 'form',
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
                            array('property' => 'position'),
                            array('property' => 'box_shadow'),
                        )
                    ),
                    array(
                        'tab_title' => __('Tooltip', 'lastudio-header-builder'),
                        'tab_key' => 'tooltip',
                        'tab_content' => array(
                            array('property' => 'color'),
                            array('property' => 'font_size'),
                            array('property' => 'font_weight'),
                            array('property' => 'font_style'),
                            array('property' => 'letter_spacing'),
                            array('property' => 'overflow'),
                            array('property' => 'word_break')
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
<!-- modal logo edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="logo">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Logo Settings', 'lastudio-header-builder'); ?></h4>
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
                lahb_select(array(
                    'title' => esc_html__('Type', 'lastudio-header-builder'),
                    'id' => 'type',
                    'default' => 'image',
                    'options' => array(
                        'image' => esc_html__('Image', 'lastudio-header-builder'),
                        'text' => esc_html__('Text', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'image' => array(
                            'logo',
                            'transparent_logo'
                        ),
                        'text' => array('logo_text'),
                    ),
                ));
                lahb_image(array(
                    'title' => esc_html__('Logo', 'lastudio-header-builder'),
                    'id' => 'logo',
                    'placeholder' => true,
                ));
                lahb_image(array(
                    'title' => esc_html__('Transparent Logo', 'lastudio-header-builder'),
                    'id' => 'transparent_logo',
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Logo Text', 'lastudio-header-builder'),
                    'id' => 'logo_text',
                ));
                ?>

            </div> <!-- end general -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">


                <?php
                $tab_data = array(
                    array(
                        'tab_title' => __('Logo', 'lastudio-header-builder'),
                        'tab_key' => 'logo',
                        'tab_content' => array(
                            array('property' => 'width'),
                            array('property' => 'height'),
                            array('property' => 'background_color'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius'),
                            array('property' => 'box_shadow')
                        )
                    ),
                    array(
                        'tab_title' => __('Transparent Logo', 'lastudio-header-builder'),
                        'tab_key' => 'transparent_logo',
                        'tab_content' => array(
                            array('property' => 'width'),
                            array('property' => 'height'),
                            array('property' => 'background_color'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius'),
                        )
                    ),
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
                            array('property' => 'word_break'),
                        )
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
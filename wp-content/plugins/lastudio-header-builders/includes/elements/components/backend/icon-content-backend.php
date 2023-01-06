<!-- modal Icon Content edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="icon-content">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Icon Content', 'lastudio-header-builder'); ?></h4>
        <i class="dashicons dashicons-no-alt"></i>
    </div>

    <div class="lahb-modal-contents-wrap">
        <div class="lahb-modal-contents w-row">

            <ul class="lahb-tabs-list lahb-element-groups wp-clearfix">
                <li class="lahb-tab w-active">
                    <a href="#content">
                        <span><?php esc_html_e('Content', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#icon">
                        <span><?php esc_html_e('Icon', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#styling">
                        <span><?php esc_html_e('Styling', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#classID">
                        <span><?php esc_html_e('Class & ID', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
            </ul> <!-- end .lahb-tabs-list -->

            <!-- content -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#content">

                <?php
                lahb_textarea(array(
                    'title' => esc_html__('Content', 'lastudio-header-builder'),
                    'id' => 'content',
                    'placeholder' => true,
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Link (optional)', 'lastudio-header-builder'),
                    'id' => 'link',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Open link in a new tab', 'lastudio-header-builder'),
                    'id' => 'link_new_tab',
                    'default' => 'false',
                ));
                ?>

            </div> <!-- end content -->

            <!-- icon -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#icon">

                <?php
                lahb_icon(array(
                    'title' => esc_html__('Select Icon', 'lastudio-header-builder'),
                    'id' => 'icon',
                ));
                ?>

            </div> <!-- end #icon -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">

                <?php
                $tab_data = array(
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
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius')
                        )
                    ),
                    array(
                        'tab_title' => __('Icon', 'lastudio-header-builder'),
                        'tab_key' => 'icon',
                        'tab_content' => array(
                            array('property' => 'color'),
                            array('property' => 'color_hover'),
                            array('property' => 'font_size'),
                            array('property' => 'font_weight'),
                            array('property' => 'font_style'),
                            array('property' => 'line_height'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                        )
                    ),
                    array(
                        'tab_title' => __('Background', 'lastudio-header-builder'),
                        'tab_key' => 'background',
                        'tab_content' => array(
                            array('property' => 'background_color'),
                            array('property' => 'background_color_hover'),
                            array('property' => 'background_image'),
                            array('property' => 'background_position'),
                            array('property' => 'background_repeat'),
                            array('property' => 'background_cover')
                        )
                    ),
                    array(
                        'tab_title' => __('Box', 'lastudio-header-builder'),
                        'tab_key' => 'box',
                        'tab_content' => array(
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius')
                        )
                    )
                );
                lahb_styling_tab_backend($tab_data);
                ?>

            </div> <!-- end #styling -->

            <!-- classID -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#classID">

                <?php
                lahb_textfield(array(
                    'title' => esc_html__('Extra class', 'lastudio-header-builder'),
                    'id' => 'extra_class',
                ));
                ?>

            </div> <!-- end #classID -->

        </div>
    </div> <!-- end lahb-modal-contents-wrap -->

    <div class="lahb-modal-footer">
        <input type="button" class="lahb_close button" value="<?php esc_html_e('Close', 'lastudio-header-builder'); ?>">
        <input type="button" class="lahb_save button button-primary"
               value="<?php esc_html_e('Save Changes', 'lastudio-header-builder'); ?>">
    </div>

</div> <!-- end lahb-elements -->
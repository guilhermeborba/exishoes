<!-- modal language edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="language">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('language Settings', 'lastudio-header-builder'); ?></h4>
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
                    'title' => esc_html__('Select your installed plugin', 'lastudio-header-builder'),
                    'id' => 'p_type',
                    'default' => 'wpml',
                    'options' => array(
                        'wpml' => esc_html__('WPML', 'lastudio-header-builder'),
                        'polylang' => esc_html__('Polylang', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'polylang' => array('type'),
                    ),
                ));
                lahb_select(array(
                    'title' => esc_html__('Select Type', 'lastudio-header-builder'),
                    'id' => 'type',
                    'default' => 'dropdown',
                    'options' => array(
                        'dropdown' => esc_html__('Dropdown without flag', 'lastudio-header-builder'),
                        'name_flag' => esc_html__('Inline with flag + name', 'lastudio-header-builder'),
                        'flag' => esc_html__('Inline just flag', 'lastudio-header-builder'),
                    ),
                ));
                ?>

            </div> <!-- end general -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">

                <?php
                $tab_data = array(
                    array(
                        'tab_title' => __('Typography', 'lastudio-header-builder'),
                        'tab_key' => 'typography',
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
                            array('property' => 'border_radius'),
                            array('property' => 'border')
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
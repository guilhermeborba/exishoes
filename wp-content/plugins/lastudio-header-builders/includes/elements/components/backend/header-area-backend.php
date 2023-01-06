<!-- modal header area -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="header-area">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Header Area', 'lastudio-header-builder'); ?></h4>
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
                    <a href="#rowlayout">
                        <span><?php esc_html_e('Row Layout', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#styling">
                        <span><?php esc_html_e('Styling', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#transparency_styling">
                        <span><?php esc_html_e('Transparency Styling', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#classID">
                        <span><?php esc_html_e('Class & ID', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
            </ul> <!-- end .lahb-tabs-list -->

            <!-- general -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#general">

                <?php
                lahb_switcher(array(
                    'title' => esc_html__('Full With Container', 'lastudio-header-builder'),
                    'id' => 'full_container',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Container Padding', 'lastudio-header-builder'),
                    'id' => 'container_padd',
                    'default' => 'true',
                ));
                lahb_select(array(
                    'title' => esc_html__('Content Position', 'lastudio-header-builder'),
                    'id' => 'content_position',
                    'default' => 'middle',
                    'options' => array(
                        'top' => esc_html__('Top', 'lastudio-header-builder'),
                        'middle' => esc_html__('Middle', 'lastudio-header-builder'),
                        'bottom' => esc_html__('Bottom', 'lastudio-header-builder'),
                    )
                ));
                ?>

            </div> <!-- end general -->

            <!-- general -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#rowlayout">
                <?php
                lahb_styling_tab_backend(array(
                    array(
                        'tab_title' => __('Row Layout', 'lastudio-header-builder'),
                        'tab_key' => 'row_layout',
                        'tab_content' => array(
                            array('property' => 'row_layout')
                        )
                    )
                ));
                ?>

            </div> <!-- end general -->

            <!-- transparency_styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#transparency_styling">

                <?php
                $tab_data = array(
                    array(
                        'tab_title' => __('Transparency Background', 'lastudio-header-builder'),
                        'tab_key' => 'transparency_background',
                        'tab_content' => array(
                            array('property' => 'background_color'),
                            array('property' => 'background_image'),
                            array('property' => 'background_position'),
                            array('property' => 'background_repeat'),
                            array('property' => 'background_cover'),
                            array('property' => 'gradient')
                        )
                    ),
                    array(
                        'tab_title' => __('Transparency Text Color', 'lastudio-header-builder'),
                        'tab_key' => 'transparency_text_color',
                        'tab_content' => array(
                            array('property' => 'color')
                        )
                    ),
                    array(
                        'tab_title' => __('Transparency Link Color', 'lastudio-header-builder'),
                        'tab_key' => 'transparency_link_color',
                        'tab_content' => array(
                            array('property' => 'color'),
                            array('property' => 'color_hover')
                        )
                    )
                );
                lahb_styling_tab_backend($tab_data);
                ?>

            </div> <!-- end #transparency_styling -->

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
                            array('property' => 'background_cover'),
                            array('property' => 'gradient')
                        )
                    ),
                    array(
                        'tab_title' => __('Box', 'lastudio-header-builder'),
                        'tab_key' => 'box',
                        'tab_content' => array(
                            array('property' => 'height'),
                            array('property' => 'width'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius'),
                            array('property' => 'box_shadow')
                        )
                    ),
                    array(
                        'tab_title' => __('Box Container', 'lastudio-header-builder'),
                        'tab_key' => 'boxcontainer',
                        'tab_content' => array(
                            array('property' => 'width'),
                            array('property' => 'max_width'),
                            array('property' => 'padding'),
	                        array('property' => 'margin'),
	                        array('property' => 'border'),
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
                lahb_textfield(array(
                    'title' => esc_html__('Extra ID', 'lastudio-header-builder'),
                    'id' => 'extra_id',
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
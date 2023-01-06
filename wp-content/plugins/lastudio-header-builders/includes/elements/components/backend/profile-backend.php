<!-- modal profile edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="profile">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Profile Settings', 'lastudio-header-builder'); ?></h4>
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
                    <a href="#socials">
                        <span><?php esc_html_e('Socials', 'lastudio-header-builder'); ?></span>
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

            <!-- general -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#general">

                <?php
                lahb_image(array(
                    'title' => esc_html__('Image', 'lastudio-header-builder'),
                    'id' => 'avatar',
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Name', 'lastudio-header-builder'),
                    'id' => 'profile_name',
                    'default' => 'David Hamilton James',
                    'placeholder' => true,
                ));
                ?>

            </div> <!-- end general -->

            <!-- socials -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#socials">

                <?php
                lahb_switcher(array(
                    'title' => esc_html__('Socials', 'lastudio-header-builder'),
                    'id' => 'socials',
                    'default' => 'true',
                ));
                // Social text 1
                lahb_textfield(array(
                    'title' => esc_html__('1st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_1',
                    'default' => 'Facebook',
                ));
                // Social link 1
                lahb_textfield(array(
                    'title' => esc_html__('1st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_1',
                    'default' => 'https://www.facebook.com/',
                ));
                ?>

                <div class="w-col-sm-12 lahb-line-divider"></div>

                <?php
                // Social text 2
                lahb_textfield(array(
                    'title' => esc_html__('2st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_2',
                ));
                // Social link 2
                lahb_textfield(array(
                    'title' => esc_html__('2st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_2',
                ));
                ?>

                <div class="w-col-sm-12 lahb-line-divider"></div>

                <?php
                // Social text 3
                lahb_textfield(array(
                    'title' => esc_html__('3st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_3',
                ));
                // Social link 3
                lahb_textfield(array(
                    'title' => esc_html__('3st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_3',
                ));
                ?>

                <div class="w-col-sm-12 lahb-line-divider"></div>

                <?php
                // Social text 4
                lahb_textfield(array(
                    'title' => esc_html__('4st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_4',
                ));
                // Social link 4
                lahb_textfield(array(
                    'title' => esc_html__('4st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_4',
                ));
                ?>

                <div class="w-col-sm-12 lahb-line-divider"></div>

                <?php
                // Social text 5
                lahb_textfield(array(
                    'title' => esc_html__('5st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_5',
                ));
                // Social link 5
                lahb_textfield(array(
                    'title' => esc_html__('5st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_5',
                ));
                ?>

                <div class="w-col-sm-12 lahb-line-divider"></div>

                <?php
                // Social text 6
                lahb_textfield(array(
                    'title' => esc_html__('6st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_6',
                ));
                // Social link 6
                lahb_textfield(array(
                    'title' => esc_html__('6st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_6',
                ));
                ?>

                <div class="w-col-sm-12 lahb-line-divider"></div>

                <?php
                // Social text 7
                lahb_textfield(array(
                    'title' => esc_html__('7st Social Text', 'lastudio-header-builder'),
                    'id' => 'social_text_7',
                ));
                // Social link 7
                lahb_textfield(array(
                    'title' => esc_html__('7st Social URL', 'lastudio-header-builder'),
                    'id' => 'social_url_7',
                ));
                ?>

            </div> <!-- socials -->

            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">

                <?php
                lahb_styling_tab_backend(array(
                    array(
                        'tab_title' => __('Image', 'lastudio-header-builder'),
                        'tab_key' => 'image',
                        'tab_content' => array(
                            array('property' => 'width'),
                            array('property' => 'height'),
                            array('property' => 'background_color'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'border'),
                            array('property' => 'border_radius')
                        )
                    ),
                    array(
                        'tab_title' => __('Name', 'lastudio-header-builder'),
                        'tab_key' => 'name',
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
                        'tab_title' => __('Socials Text', 'lastudio-header-builder'),
                        'tab_key' => 'socials_text',
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
                        'tab_title' => __('Socials Box', 'lastudio-header-builder'),
                        'tab_key' => 'socials_box',
                        'tab_content' => array(
                            array('property' => 'width'),
                            array('property' => 'height'),
                            array('property' => 'background_color'),
                            array('property' => 'margin'),
                            array('property' => 'padding'),
                            array('property' => 'position'),
                            array('property' => 'border'),
                            array('property' => 'border_radius')
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
                            array('property' => 'border_radius')
                        )
                    )
                ));
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
<!-- modal search edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="social">

    <div class="lahb-modal-header">
        <h4><?php esc_html_e('Socials Settings', 'lastudio-header-builder'); ?></h4>
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
                    <a href="#display-format">
                        <span><?php esc_html_e('Display Format', 'lastudio-header-builder'); ?></span>
                    </a>
                </li>
                <li class="lahb-tab">
                    <a href="#socialicons">
                        <span><?php esc_html_e('Social Icons', 'lastudio-header-builder'); ?></span>
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
                // Main Icon
                lahb_select(array(
                    'title' => esc_html__('Twitter Icon or Text?', 'lastudio-header-builder'),
                    'id' => 'main_social_icon',
                    'default' => 'icon',
                    'options' => array(
                        'icon' => esc_html__('Icon', 'lastudio-header-builder'),
                        'text' => esc_html__('Text', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'text' => array('main_icon_text'),
                    ),
                ));
                // Twitter Text
                lahb_textfield(array(
                    'title' => esc_html__('Text instead of Main Icon', 'lastudio-header-builder'),
                    'id' => 'main_icon_text',
                    'default' => 'Socials',
                ));
                // Type
                lahb_select(array(
                    'title' => esc_html__('Type', 'lastudio-header-builder'),
                    'id' => 'social_type',
                    'default' => 'simple',
                    'options' => array(
                        'simple' => esc_html__('Simple', 'lastudio-header-builder'),
                        'slide' => esc_html__('Slide', 'lastudio-header-builder'),
                        'dropdown' => esc_html__('Dropdown', 'lastudio-header-builder'),
                        'full' => esc_html__('Full', 'lastudio-header-builder'),
                    ),
                    'dependency' => array(
                        'slide' => array('toggle_text'),
                        'dropdown' => array('default_icon_bg'),
                    ),
                ));
                lahb_textfield(array(
                    'title' => esc_html__('Toggle Text', 'lastudio-header-builder'),
                    'id' => 'toggle_text',
                    'default' => 'Social Network',
                    'placeholder' => true,
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
                lahb_select(array(
                    'title' => esc_html__('Select Tooltip Position', 'lastudio-header-builder'),
                    'id' => 'tooltip_position',
                    'default' => 'tooltip-on-bottom',
                    'options' => array(
                        'tooltip-on-top' => esc_html__('Top', 'lastudio-header-builder'),
                        'tooltip-on-bottom' => esc_html__('Bottom', 'lastudio-header-builder'),
                    ),
                ));
                // Tooltip Text
                lahb_switcher(array(
                    'title' => esc_html__('Replace Background image with defult icon', 'lastudio-header-builder'),
                    'id' => 'default_icon_bg',
                    'default' => 'false',
                ));
                ?>

            </div> <!-- end general -->

            <!-- display-format -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#display-format">

                <?php
                lahb_select(array(
                    'title' => esc_html__('Format', 'lastudio-header-builder'),
                    'id' => 'social_format',
                    'default' => 'icon',
                    'options' => array(
                        'icon' => esc_html__('Icon', 'lastudio-header-builder'),
                        'text' => esc_html__('Text', 'lastudio-header-builder'),
                        'icontext' => esc_html__('Icon + Text', 'lastudio-header-builder'),
                    ),
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Showing icons side to side as inline', 'lastudio-header-builder'),
                    'id' => 'inline',
                    'default' => 'true',
                ));
                lahb_switcher(array(
                    'title' => esc_html__('Showing "-" (dash) at the beginning of icon', 'lastudio-header-builder'),
                    'id' => 'dash',
                    'default' => 'false',
                ));
                ?>

            </div> <!-- end #display-format -->

            <!-- social icons -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#socialicons">

                <?php
                $lastudio_socials = array(
                    'none' => 'None',
                    'dribbble' => 'Dribbble',
                    'facebook' => 'Facebook',
                    'flickr' => 'Flickr',
                    'foursquare' => 'Foursquare',
                    'github' => 'Github',
                    'instagram' => 'Instagram',
                    'lastfm' => 'Lastfm',
                    'linkedin' => 'Linkedin',
                    'pinterest' => 'Pinterest',
                    'reddit' => 'Reddit',
                    'soundcloud' => 'Soundcloud',
                    'spotify' => 'Spotify',
                    'tumblr' => 'Tumblr',
                    'twitter' => 'Twitter',
                    'vimeo' => 'Vimeo',
                    'vine' => 'Vine',
                    'yelp' => 'Yelp',
                    'yahoo' => 'Yahoo',
                    'youtube' => 'Youtube',
                    'wordpress' => 'Wordpress',
                    'dropbox' => 'Dropbox',
                    'evernote' => 'Evernote',
                    'skype' => 'Skype',
                    'telegram' => 'Telegram',
                    'envato' => 'Envato'
                );
                // Social icon 1
                lahb_select(array(
                    'title' => esc_html__('1st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_1',
                    'default' => 'facebook',
                    'options' => $lastudio_socials,
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
                // Social icon 2
                lahb_select(array(
                    'title' => esc_html__('2st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_2',
                    'default' => 'none',
                    'options' => $lastudio_socials,
                ));
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
                // Social icon 3
                lahb_select(array(
                    'title' => esc_html__('3st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_3',
                    'default' => 'none',
                    'options' => $lastudio_socials,
                ));
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
                // Social icon 4
                lahb_select(array(
                    'title' => esc_html__('4st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_4',
                    'default' => 'none',
                    'options' => $lastudio_socials,
                ));
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
                // Social icon 5
                lahb_select(array(
                    'title' => esc_html__('5st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_5',
                    'default' => 'none',
                    'options' => $lastudio_socials,
                ));
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
                // Social icon 6
                lahb_select(array(
                    'title' => esc_html__('6st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_6',
                    'default' => 'none',
                    'options' => $lastudio_socials,
                ));
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
                // Social icon 7
                lahb_select(array(
                    'title' => esc_html__('7st Social Icon', 'lastudio-header-builder'),
                    'id' => 'social_icon_7',
                    'default' => 'none',
                    'options' => $lastudio_socials,
                ));
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

            </div> <!-- social icons -->


            <!-- styling -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#styling">

                <?php
                lahb_styling_tab_backend(array(
                    array(
                        'tab_title' => __('Menu Icon/Text', 'lastudio-header-builder'),
                        'tab_key' => 'menu_icon_text',
                        'tab_content' => array(
                            array('property' => 'color'),
                            array('property' => 'color_hover'),
                            array('property' => 'font_size')
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
                    ),
                    array(
                        'tab_title' => __('Social Box', 'lastudio-header-builder'),
                        'tab_key' => 'social_box',
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
                            array('property' => 'box_shadow')
                        )
                    ),
                    array(
                        'tab_title' => __('Social Icon/Text Box', 'lastudio-header-builder'),
                        'tab_key' => 'social_icon_text_box',
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
                        'tab_title' => __('Social Icon', 'lastudio-header-builder'),
                        'tab_key' => 'social_icon',
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
                            array('property' => 'box_shadow'),
                        )
                    ),
                    array(
                        'tab_title' => __('Social Text', 'lastudio-header-builder'),
                        'tab_key' => 'social_text',
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
                    ),
                    array(
                        'tab_title' => __('Full Page Social', 'lastudio-header-builder'),
                        'tab_key' => 'full_page_social',
                        'tab_content' => array(
                            array('property' => 'background_color'),
                            array('property' => 'background_color_hover'),
                            array('property' => 'background_image'),
                            array('property' => 'background_position'),
                            array('property' => 'background_repeat'),
                            array('property' => 'background_cover'),
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
                            array('property' => 'background_color'),
                            array('property' => 'overflow'),
                            array('property' => 'word_break')
                        )
                    )
                ));
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
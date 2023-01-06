<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.
/**
 *
 * Field: typography
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('LASF_Field_typography')) {
    class LASF_Field_typography extends LASF_Fields
    {
        public $chosen = false;
        public $value = array();

        public function __construct($field, $value = '', $unique = '', $where = '', $parent = '') {
            parent::__construct($field, $value, $unique, $where, $parent);
        }

        public function render() {
            echo $this->field_before();
            $args = wp_parse_args($this->field, array(
                'font_family' => true,
                'font_weight' => true,
                'font_style' => true,
                'font_size' => true,
                'line_height' => true,
                'letter_spacing' => true,
                'text_align' => true,
                'text_transform' => true,
                'color' => true,
                'chosen' => true,
                'preview' => true,
                'subset' => true,
                'multi_subset' => false,
                'extra_styles' => false,
                'backup_font_family' => false,
                'font_variant' => false,
                'word_spacing' => false,
                'text_decoration' => false,
                'custom_style' => false,
                'exclude' => '',
                'unit' => 'px',
                'responsive' => false,
                'preview_text' => 'The quick brown fox jumps over the lazy dog',
            ));
            $default_value = array(
                'font-family' => '',
                'font-weight' => '',
                'font-style' => '',
                'font-variant' => '',
                'font-size' => '',
                'line-height' => '',
                'letter-spacing' => '',
                'word-spacing' => '',
                'text-align' => '',
                'text-transform' => '',
                'text-decoration' => '',
                'backup-font-family' => '',
                'color' => '',
                'custom-style' => '',
                'type' => '',
                'subset' => '',
                'extra-styles' => array(),
            );
            $responsive = $args['responsive'] == true ? true : false;
            $default_value = (!empty($this->field['default'])) ? wp_parse_args($this->field['default'], $default_value) : $default_value;
            $this->value = wp_parse_args($this->value, $default_value);
            $this->chosen = $args['chosen'];
            $chosen_class = ($this->chosen) ? ' lasf--chosen' : '';
            if ($responsive) {
                $chosen_class .= ' lasf--typography--has-responsive';
            }
            echo '<div class="lasf--typography' . $chosen_class . '" data-unit="' . $args['unit'] . '" data-exclude="' . $args['exclude'] . '">';
            echo '<div class="lasf--blocks lasf--blocks-selects">';
            //
            // Font Family
            if (!empty($args['font_family'])) {
                echo '<div class="lasf--block">';
                echo '<div class="lasf--title">' . esc_html__('Font Family', 'lastudio') . '</div>';
                echo $this->create_select(array($this->value['font-family'] => $this->value['font-family']), 'font-family', esc_html__('Select a font', 'lastudio'));
                echo '</div>';
            }
            //
            // Backup Font Family
            if (!empty($args['backup_font_family'])) {
                echo '<div class="lasf--block lasf--block-backup-font-family hidden">';
                echo '<div class="lasf--title">' . esc_html__('Backup Font Family', 'lastudio') . '</div>';
                echo $this->create_select(apply_filters('lasf_field_typography_backup_font_family', array(
                    'Arial, Helvetica, sans-serif',
                    "'Arial Black', Gadget, sans-serif",
                    "'Comic Sans MS', cursive, sans-serif",
                    'Impact, Charcoal, sans-serif',
                    "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
                    'Tahoma, Geneva, sans-serif',
                    "'Trebuchet MS', Helvetica, sans-serif'",
                    'Verdana, Geneva, sans-serif',
                    "'Courier New', Courier, monospace",
                    "'Lucida Console', Monaco, monospace",
                    'Georgia, serif',
                    'Palatino Linotype'
                )), 'backup-font-family', esc_html__('Default', 'lastudio'));
                echo '</div>';
            }
            //
            // Font Style and Extra Style Select
            if (!empty($args['font_weight']) || !empty($args['font_style'])) {
                //
                // Font Style Select
                echo '<div class="lasf--block lasf--block-font-style">';
                echo '<div class="lasf--title">' . esc_html__('Font Style', 'lastudio') . '</div>';
                echo '<select class="lasf--font-style-select" data-placeholder="Default">';
                echo '<option value="">' . (!$this->chosen ? esc_html__('Default', 'lastudio') : '') . '</option>';
                if (!empty($this->value['font-weight']) || !empty($this->value['font-style'])) {
                    echo '<option value="' . strtolower($this->value['font-weight'] . $this->value['font-style']) . '" selected></option>';
                }
                echo '</select>';
                echo '<input type="hidden" name="' . $this->field_name('[font-weight]') . '" class="lasf--font-weight" value="' . $this->value['font-weight'] . '" />';
                echo '<input type="hidden" name="' . $this->field_name('[font-style]') . '" class="lasf--font-style" value="' . $this->value['font-style'] . '" />';
                //
                // Extra Font Style Select
                if (!empty($args['extra_styles'])) {
                    echo '<div class="lasf--block-extra-styles hidden">';
                    echo (!$this->chosen) ? '<div class="lasf--title">' . esc_html__('Load Extra Styles', 'lastudio') . '</div>' : '';
                    $placeholder = ($this->chosen) ? esc_html__('Load Extra Styles', 'lastudio') : esc_html__('Default', 'lastudio');
                    echo $this->create_select($this->value['extra-styles'], 'extra-styles', $placeholder, true);
                    echo '</div>';
                }
                echo '</div>';
            }
            //
            // Subset
            if (!empty($args['subset'])) {
                echo '<div class="lasf--block lasf--block-subset hidden">';
                echo '<div class="lasf--title">' . esc_html__('Subset', 'lastudio') . '</div>';
                $subset = (is_array($this->value['subset'])) ? $this->value['subset'] : array_filter((array)$this->value['subset']);
                echo $this->create_select($subset, 'subset', esc_html__('Default', 'lastudio'), $args['multi_subset']);
                echo '</div>';
            }
            //
            // Text Align
            if (!empty($args['text_align'])) {
                echo '<div class="lasf--block">';
                echo '<div class="lasf--title">' . esc_html__('Text Align', 'lastudio') . '</div>';
                echo $this->create_select(array(
                    'inherit' => esc_html__('Inherit', 'lastudio'),
                    'left' => esc_html__('Left', 'lastudio'),
                    'center' => esc_html__('Center', 'lastudio'),
                    'right' => esc_html__('Right', 'lastudio'),
                    'justify' => esc_html__('Justify', 'lastudio'),
                    'initial' => esc_html__('Initial', 'lastudio')
                ), 'text-align', esc_html__('Default', 'lastudio'));
                echo '</div>';
            }
            //
            // Font Variant
            if (!empty($args['font_variant'])) {
                echo '<div class="lasf--block">';
                echo '<div class="lasf--title">' . esc_html__('Font Variant', 'lastudio') . '</div>';
                echo $this->create_select(array(
                    'normal' => esc_html__('Normal', 'lastudio'),
                    'small-caps' => esc_html__('Small Caps', 'lastudio'),
                    'all-small-caps' => esc_html__('All Small Caps', 'lastudio')
                ), 'font-variant', esc_html__('Default', 'lastudio'));
                echo '</div>';
            }
            //
            // Text Transform
            if (!empty($args['text_transform'])) {
                echo '<div class="lasf--block">';
                echo '<div class="lasf--title">' . esc_html__('Text Transform', 'lastudio') . '</div>';
                echo $this->create_select(array(
                    'none' => esc_html__('None', 'lastudio'),
                    'capitalize' => esc_html__('Capitalize', 'lastudio'),
                    'uppercase' => esc_html__('Uppercase', 'lastudio'),
                    'lowercase' => esc_html__('Lowercase', 'lastudio')
                ), 'text-transform', esc_html__('Default', 'lastudio'));
                echo '</div>';
            }
            //
            // Text Decoration
            if (!empty($args['text_decoration'])) {
                echo '<div class="lasf--block">';
                echo '<div class="lasf--title">' . esc_html__('Text Decoration', 'lastudio') . '</div>';
                echo $this->create_select(array(
                    'none' => esc_html__('None', 'lastudio'),
                    'underline' => esc_html__('Solid', 'lastudio'),
                    'underline double' => esc_html__('Double', 'lastudio'),
                    'underline dotted' => esc_html__('Dotted', 'lastudio'),
                    'underline dashed' => esc_html__('Dashed', 'lastudio'),
                    'underline wavy' => esc_html__('Wavy', 'lastudio'),
                    'underline overline' => esc_html__('Overline', 'lastudio'),
                    'line-through' => esc_html__('Line-through', 'lastudio')
                ), 'text-decoration', esc_html__('Default', 'lastudio'));
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="lasf--blocks lasf--blocks-inputs">';


            $responsive_html = '';

            //
            // Font Size
            if (!empty($args['font_size'])) {
                $responsive_html .= '<div class="lasf--block">';
                $responsive_html .= '<div class="lasf--title">' . esc_html__('Font Size', 'lastudio') . '</div>';
                $responsive_html .= '<div class="lasf--blocks">';
                $responsive_html .= '<div class="lasf--block"><input type="text" name="' . $this->field_name('[font-size][__key_replace__]') . '" class="lasf--font-size lasf--input lasf-number" value="__font_size_value_replace__" /></div>';
                $responsive_html .= '<div class="lasf--block lasf--unit">' . $args['unit'] . '</div>';
                $responsive_html .= '</div>';
                $responsive_html .= '</div>';
            }
            //
            // Line Height
            if (!empty($args['line_height'])) {
                $responsive_html .= '<div class="lasf--block">';
                $responsive_html .= '<div class="lasf--title">' . esc_html__('Line Height', 'lastudio') . '</div>';
                $responsive_html .= '<div class="lasf--blocks">';
                $responsive_html .= '<div class="lasf--block"><input type="text" name="' . $this->field_name('[line-height][__key_replace__]') . '" class="lasf--line-height lasf--input lasf-number" value="__line_height_value_replace__" /></div>';
                $responsive_html .= '<div class="lasf--block lasf--unit">' . $args['unit'] . '</div>';
                $responsive_html .= '</div>';
                $responsive_html .= '</div>';
            }
            //
            // Letter Spacing
            if (!empty($args['letter_spacing'])) {
                $responsive_html .= '<div class="lasf--block">';
                $responsive_html .= '<div class="lasf--title">' . esc_html__('Letter Spacing', 'lastudio') . '</div>';
                $responsive_html .= '<div class="lasf--blocks">';
                $responsive_html .= '<div class="lasf--block"><input type="text" name="' . $this->field_name('[letter-spacing][__key_replace__]') . '" class="lasf--letter-spacing lasf--input lasf-number" value="__letter_spacing_value_replace__" /></div>';
                $responsive_html .= '<div class="lasf--block lasf--unit">' . $args['unit'] . '</div>';
                $responsive_html .= '</div>';
                $responsive_html .= '</div>';
            }
            //
            // Word Spacing
            if (!empty($args['word_spacing'])) {
                $responsive_html .= '<div class="lasf--block">';
                $responsive_html .= '<div class="lasf--title">' . esc_html__('Word Spacing', 'lastudio') . '</div>';
                $responsive_html .= '<div class="lasf--blocks">';
                $responsive_html .= '<div class="lasf--block"><input type="text" name="' . $this->field_name('[word-spacing][__key_replace__]') . '" class="lasf--word-spacing lasf--input lasf-number" value="__word_spacing_value_replace__" /></div>';
                $responsive_html .= '<div class="lasf--block lasf--unit">' . $args['unit'] . '</div>';
                $responsive_html .= '</div>';
                $responsive_html .= '</div>';
            }

            if( $responsive ) {
                $is_first = true;
                $device_lists = ['mobile', 'mobile_landscape', 'tablet', 'laptop', 'desktop'];
                ?>
                <div class="lasf-child-tabbed lasf-responsive-tabs">
                    <div class="lasf-child-tab-controls">
                        <a data-device-mode="mobile" href="#" class="active"><i class="lasf--icon dashicons dashicons-smartphone"></i></a>
                        <a data-device-mode="mobile_landscape" href="#"><i class="lasf--icon dashicons dashicons-smartphone fa-rotate-90"></i></a>
                        <a data-device-mode="tablet" href="#"><i class="lasf--icon dashicons dashicons-tablet fa-rotate-90"></i></a>
                        <a data-device-mode="laptop" href="#"><i class="lasf--icon dashicons dashicons-desktop"></i></a>
                        <a data-device-mode="desktop" href="#"><i class="lasf--icon fa fa-desktop"></i></a>
                    </div>
                    <div class="lasf-child-tab-contents">
                        <?php
                        foreach ($device_lists as $device){
                            ?>
                            <div class="lasf-child-tab-content<?php if($is_first){ echo ' active'; } else { echo ' hidden'; } ?>">
                                <div class="lasf--blocks lasf--blocks-inputs">
                                    <?php

                                    $__val_font_size        = isset($this->value['font-size'][$device]) ? esc_attr($this->value['font-size'][$device]) : '';
                                    $__val_line_height      = isset($this->value['line-height'][$device]) ? esc_attr($this->value['line-height'][$device]) : '';
                                    $__val_letter_spacing   = isset($this->value['letter-spacing'][$device]) ? esc_attr($this->value['letter-spacing'][$device]) : '';
                                    $__val_word_spacing     = isset($this->value['word-spacing'][$device]) ? esc_attr($this->value['word-spacing'][$device]) : '';

                                    echo str_replace(
                                        [
                                            '[__key_replace__]',
                                            '__font_size_value_replace__',
                                            '__line_height_value_replace__',
                                            '__letter_spacing_value_replace__',
                                            '__word_spacing_value_replace__',
                                        ],
                                        [
                                            '['.$device.']',
                                            $__val_font_size,
                                            $__val_line_height,
                                            $__val_letter_spacing,
                                            $__val_word_spacing
                                        ],
                                        $responsive_html
                                    );

                                    ?>
                                </div>
                            </div>
                            <?php
                            $is_first = false;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            else{

                $__val_font_size        = isset($this->value['font-size']) ? esc_attr($this->value['font-size']) : '';
                $__val_line_height      = isset($this->value['line-height']) ? esc_attr($this->value['line-height']) : '';
                $__val_letter_spacing   = isset($this->value['letter-spacing']) ? esc_attr($this->value['letter-spacing']) : '';
                $__val_word_spacing     = isset($this->value['word-spacing']) ? esc_attr($this->value['word-spacing']) : '';

                echo str_replace(
                    [
                        '[__key_replace__]',
                        '__font_size_value_replace__',
                        '__line_height_value_replace__',
                        '__letter_spacing_value_replace__',
                        '__word_spacing_value_replace__',
                    ],
                    [
                        '',
                        $__val_font_size,
                        $__val_line_height,
                        $__val_letter_spacing,
                        $__val_word_spacing
                    ],
                    $responsive_html
                );
            }

            echo '</div>';
            //
            // Font Color
            if (!empty($args['color'])) {
                $default_color_attr = (!empty($default_value['color'])) ? ' data-default-color="' . $default_value['color'] . '"' : '';
                echo '<div class="lasf--block lasf--block-font-color">';
                echo '<div class="lasf--title">' . esc_html__('Font Color', 'lastudio') . '</div>';
                echo '<div class="lasf-field-color">';
                echo '<input type="text" name="' . $this->field_name('[color]') . '" class="lasf-color lasf--color" value="' . $this->value['color'] . '"' . $default_color_attr . ' />';
                echo '</div>';
                echo '</div>';
            }
            //
            // Custom style
            if (!empty($args['custom_style'])) {
                echo '<div class="lasf--block lasf--block-custom-style">';
                echo '<div class="lasf--title">' . esc_html__('Custom Style', 'lastudio') . '</div>';
                echo '<textarea name="' . $this->field_name('[custom-style]') . '" class="lasf--custom-style">' . $this->value['custom-style'] . '</textarea>';
                echo '</div>';
            }
            //
            // Preview
            $always_preview = ($args['preview'] !== 'always') ? ' hidden' : '';
            if (!empty($args['preview'])) {
                echo '<div class="lasf--block lasf--block-preview' . $always_preview . '">';
                echo '<div class="lasf--toggle fa fa-toggle-off"></div>';
                echo '<div class="lasf--preview">' . $args['preview_text'] . '</div>';
                echo '</div>';
            }
            echo '<input type="hidden" name="' . $this->field_name('[type]') . '" class="lasf--type" value="' . $this->value['type'] . '" />';
            echo '<input type="hidden" name="' . $this->field_name('[unit]') . '" class="lasf--unit-save" value="' . $args['unit'] . '" />';
            echo '<input type="hidden" name="' . $this->field_name('[responsive]') . '" class="lasf--responsive-save" value="' . ($responsive ? 'yes' : 'no') . '" />';
            echo '</div>';
            echo $this->field_after();
        }

        public function create_select($options, $name, $placeholder = '', $is_multiple = false) {
            $multiple_name = ($is_multiple) ? '[]' : '';
            $multiple_attr = ($is_multiple) ? ' multiple data-multiple="true"' : '';
            $chosen_rtl = ($this->chosen && is_rtl()) ? ' chosen-rtl' : '';
            $output = '<select name="' . $this->field_name('[' . $name . ']' . $multiple_name) . '" class="lasf--' . $name . $chosen_rtl . '" data-placeholder="' . $placeholder . '"' . $multiple_attr . '>';
            $output .= (!empty($placeholder)) ? '<option value="">' . ((!$this->chosen) ? $placeholder : '') . '</option>' : '';
            if (!empty($options)) {
                foreach ($options as $option_key => $option_value) {
                    if ($is_multiple) {
                        $selected = (in_array($option_value, $this->value[$name])) ? ' selected' : '';
                        $output .= '<option value="' . $option_value . '"' . $selected . '>' . $option_value . '</option>';
                    }
                    else {
                        $option_key = (is_numeric($option_key)) ? $option_value : $option_key;
                        $selected = ($option_key === $this->value[$name]) ? ' selected' : '';
                        $output .= '<option value="' . $option_key . '"' . $selected . '>' . $option_value . '</option>';
                    }
                }
            }
            $output .= '</select>';
            return $output;
        }

        public function enqueue() {
            if (!wp_style_is('lasf-webfont-loader')) {
                LASF::include_plugin_file('fields/typography/google-fonts.php');
                wp_enqueue_script('lasf-webfont-loader', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array('lasf'), '1.6.28', true);
                $webfonts = array();
                $customwebfonts = apply_filters('lasf_field_typography_customwebfonts', array());
                if (!empty($customwebfonts)) {
                    $webfonts['custom'] = array(
                        'label' => esc_html__('Custom Web Fonts', 'lastudio'),
                        'fonts' => $customwebfonts
                    );
                }
                $webfonts['safe'] = array(
                    'label' => esc_html__('Safe Web Fonts', 'lastudio'),
                    'fonts' => apply_filters('lasf_field_typography_safewebfonts', array(
                        'Arial',
                        'Arial Black',
                        'Helvetica',
                        'Times New Roman',
                        'Courier New',
                        'Tahoma',
                        'Verdana',
                        'Impact',
                        'Trebuchet MS',
                        'Comic Sans MS',
                        'Lucida Console',
                        'Lucida Sans Unicode',
                        'Georgia, serif',
                        'Palatino Linotype'
                    ))
                );
                $webfonts['google'] = array(
                    'label' => esc_html__('Google Web Fonts', 'lastudio'),
                    'fonts' => apply_filters('lasf_field_typography_googlewebfonts', lasf_get_google_fonts())
                );
                $defaultstyles = apply_filters('lasf_field_typography_defaultstyles', array(
                    'normal',
                    'italic',
                    '700',
                    '100',
                    '100italic',
                    '200',
                    '300',
                    '300italic',
                    '500',
                    '500italic',
                    '600',
                    '600italic',
                    '800',
                    '800italic',
                    '900',
                    '900italic',
                ));
                $googlestyles = apply_filters('lasf_field_typography_googlestyles', array(
                    '100' => 'Thin 100',
                    '100italic' => 'Thin 100 Italic',
                    '200' => 'Extra-Light 200',
                    '200italic' => 'Extra-Light 200 Italic',
                    '300' => 'Light 300',
                    '300italic' => 'Light 300 Italic',
                    'normal' => 'Normal 400',
                    'italic' => 'Normal 400 Italic',
                    '500' => 'Medium 500',
                    '500italic' => 'Medium 500 Italic',
                    '600' => 'Semi-Bold 600',
                    '600italic' => 'Semi-Bold 600 Italic',
                    '700' => 'Bold 700',
                    '700italic' => 'Bold 700 Italic',
                    '800' => 'Extra-Bold 800',
                    '800italic' => 'Extra-Bold 800 Italic',
                    '900' => 'Black 900',
                    '900italic' => 'Black 900 Italic'
                ));
                $webfonts = apply_filters('lasf_field_typography_webfonts', $webfonts);
                wp_localize_script('lasf', 'lasf_typography_json', array(
                    'webfonts' => $webfonts,
                    'defaultstyles' => $defaultstyles,
                    'googlestyles' => $googlestyles
                ));
            }
        }

        public function enqueue_google_fonts() {

            $value = $this->value;
            $families = array();
            $is_google = false;
            if (!empty($this->value['type'])) {
                $is_google = ($this->value['type'] === 'google') ? true : false;
            }
            else {
                LASF::include_plugin_file('fields/typography/google-fonts.php');
                $is_google = (array_key_exists($this->value['font-family'], lasf_get_google_fonts())) ? true : false;
            }
            if ($is_google) {
                // set style
                $font_weight = (!empty($value['font-weight'])) ? $value['font-weight'] : '';
                $font_style = (!empty($value['font-style'])) ? $value['font-style'] : '';
                if ($font_weight || $font_style) {
                    $style = $font_weight . $font_style;
                    $families['style'][$style] = $style;
                }
                // set extra styles
                if (!empty($value['extra-styles'])) {
                    foreach ($value['extra-styles'] as $extra_style) {
                        $families['style'][$extra_style] = $extra_style;
                    }
                }
                // set subsets
                if (!empty($value['subset'])) {
                    $value['subset'] = (is_array($value['subset'])) ? $value['subset'] : array_filter((array)$value['subset']);
                    foreach ($value['subset'] as $subset) {
                        $families['subset'][$subset] = $subset;
                    }
                }
                $all_styles = (!empty($families['style'])) ? ':' . implode(',', $families['style']) : '';
                $all_subsets = (!empty($families['subset'])) ? ':' . implode(',', $families['subset']) : '';
                $families = $this->value['font-family'] . str_replace(array(
                        'normal',
                        'italic'
                    ), array(
                        'n',
                        'i'
                    ), $all_styles) . $all_subsets;

                $this->parent->typographies[] = $families;
                return $families;
            }
            return false;
        }

        public function output() {
            $output = '';
            $bg_image = array();
            $important = (!empty($this->field['output_important'])) ? '!important' : '';
            $element = (is_array($this->field['output'])) ? join(',', $this->field['output']) : $this->field['output'];
            $font_family = (!empty($this->value['font-family'])) ? $this->value['font-family'] : '';
            $backup_family = (!empty($this->value['backup-font-family'])) ? ', ' . $this->value['backup-font-family'] : '';
            if ($font_family) {
                $output .= 'font-family:"' . $font_family . '"' . $backup_family . $important . ';';
            }

            $responsive = isset($this->field['responsive']) && $this->field['responsive'] ? true : false;

            // Common font properties
            $properties = array(
                'color',
                'font-weight',
                'font-style',
                'font-variant',
                'text-align',
                'text-transform',
                'text-decoration',
            );
            foreach ($properties as $property) {
                if (isset($this->value[$property]) && $this->value[$property] !== '') {
                    $output .= $property . ':' . $this->value[$property] . $important . ';';
                }
            }
            $properties = array(
                'font-size',
                'line-height',
                'letter-spacing',
                'word-spacing',
            );
            $unit = (!empty($this->value['unit'])) ? $this->value['unit'] : '';

            if(!$responsive){
                foreach ($properties as $property) {
                    if (isset($this->value[$property]) && $this->value[$property] !== '') {
                        $output .= $property . ':' . $this->value[$property] . $unit . $important . ';';
                    }
                }
            }
            $custom_style = (!empty($this->value['custom-style'])) ? $this->value['custom-style'] : '';
            if ($output) {
                $output = $element . '{' . $output . $custom_style . '}';
            }
            $this->parent->output_css .= $output;
            return $output;
        }
    }
}

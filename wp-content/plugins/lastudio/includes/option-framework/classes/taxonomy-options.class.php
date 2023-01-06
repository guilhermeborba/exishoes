<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.
/**
 *
 * Taxonomy Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('LASF_Taxonomy_Options')) {
    class LASF_Taxonomy_Options extends LASF_Abstract
    {
        // constans
        public $unique = '';
        public $taxonomy = '';
        public $abstract = 'taxonomy';
        public $sections = array();
        public $taxonomies = array();
        public $args = array(
            'taxonomy' => '',
            'data_type' => 'serialize',
            'defaults' => array(),
        );
        public $pre_tabs = array();
        public $pre_fields = array();
        public $pre_sections = array();

        // run taxonomy construct
        public function __construct($key, $params) {
            $this->unique = $key;
            $this->args = apply_filters("lasf_{$this->unique}_args", wp_parse_args($params['args'], $this->args), $this);
            $this->sections = apply_filters("lasf_{$this->unique}_sections", $params['sections'], $this);
            $this->taxonomies = (is_array($this->args['taxonomy'])) ? $this->args['taxonomy'] : array_filter((array)$this->args['taxonomy']);
            $this->taxonomy = lasf_get_var('taxonomy');
            // run only is admin panel options, avoid performance loss
            $this->pre_tabs = $this->pre_tabs($this->sections);
            $this->pre_fields = $this->pre_fields($this->sections);
            $this->pre_sections = $this->pre_sections($this->sections);
            if (!empty($this->taxonomies) && in_array($this->taxonomy, $this->taxonomies)) {
                add_action('admin_init', array(
                    &$this,
                    'add_taxonomy_options'
                ));
            }
        }

        // instance
        public static function instance($key, $params) {
            return new self($key, $params);
        }

        public function pre_tabs($sections) {
            $result = array();
            $parents = array();
            $count = 100;
            foreach ($sections as $key => $section) {
                if (!empty($section['parent'])) {
                    $section['priority'] = (isset($section['priority'])) ? $section['priority'] : $count;
                    $parents[$section['parent']][] = $section;
                    unset($sections[$key]);
                }
                $count++;
            }
            foreach ($sections as $key => $section) {
                $section['priority'] = (isset($section['priority'])) ? $section['priority'] : $count;
                if (!empty($section['id']) && !empty($parents[$section['id']])) {
                    $section['subs'] = wp_list_sort($parents[$section['id']], array('priority' => 'ASC'), 'ASC', true);
                }
                $result[] = $section;
                $count++;
            }
            return wp_list_sort($result, array('priority' => 'ASC'), 'ASC', true);
        }

        public function pre_fields($sections) {
            $result = array();
            foreach ($sections as $key => $section) {
                if (!empty($section['fields'])) {
                    foreach ($section['fields'] as $field) {
                        $result[] = $field;
                    }
                }
            }
            return $result;
        }

        public function pre_sections($sections) {
            $result = array();
            foreach ($this->pre_tabs as $tab) {
                if (!empty($tab['subs'])) {
                    foreach ($tab['subs'] as $sub) {
                        $result[] = $sub;
                    }
                }
                if (empty($tab['subs'])) {
                    $result[] = $tab;
                }
            }
            return $result;
        }

        // add taxonomy add/edit fields
        public function add_taxonomy_options() {
            add_action($this->taxonomy . '_add_form_fields', array(
                &$this,
                'render_taxonomy_form_fields'
            ));
            add_action($this->taxonomy . '_edit_form', array(
                &$this,
                'render_taxonomy_form_fields'
            ));
            add_action('created_' . $this->taxonomy, array(
                &$this,
                'save_taxonomy'
            ));
            add_action('edited_' . $this->taxonomy, array(
                &$this,
                'save_taxonomy'
            ));
        }

        // get default value
        public function get_default($field) {
            $default = (isset($this->args['defaults'][$field['id']])) ? $this->args['defaults'][$field['id']] : '';
            $default = (isset($field['default'])) ? $field['default'] : $default;
            return $default;
        }

        // get default value
        public function get_meta_value($term_id, $field) {
            $value = '';
            if (!empty($term_id) && !empty($field['id'])) {
                if ($this->args['data_type'] !== 'serialize') {
                    $meta = get_term_meta($term_id, $field['id']);
                    $value = (isset($meta[0])) ? $meta[0] : null;
                }
                else {
                    $meta = get_term_meta($term_id, $this->unique, true);
                    $value = (isset($meta[$field['id']])) ? $meta[$field['id']] : null;
                }
                $default = $this->get_default($field);
                $value = (isset($value)) ? $value : $default;
            }
            if (empty($term_id) && !empty($field['id'])) {
                $value = $this->get_default($field);
            }
            return $value;
        }

        // render taxonomy add/edit form fields
        public function render_taxonomy_form_fields($term) {
            $has_nav = (count($this->pre_tabs) > 1) ? true : false;
            $show_all = (!$has_nav) ? ' lasf-show-all' : '';
            $is_term = (is_object($term) && isset($term->taxonomy)) ? true : false;
            $term_id = ($is_term) ? $term->term_id : 0;
            $taxonomy = ($is_term) ? $term->taxonomy : $term;
            $classname = ($is_term) ? 'edit' : 'add';
            $errors = (!empty($term_id)) ? get_term_meta($term_id, '_lasf_errors', true) : array();
            $errors = (!empty($errors)) ? $errors : array();
            // clear errors
            if (!empty($errors)) {
                delete_term_meta($term_id, '_lasf_errors');
            }
            if ($taxonomy !== $this->taxonomy) {
                return;
            }
            ?>
            <div class="lasf lasf-theme-light lasf-taxonomy lasf-taxonomy-<?php echo $classname; ?>-fields">
                <?php wp_nonce_field('lasf_taxonomy_nonce', 'lasf_taxonomy_nonce'); ?>
                <div class="lasf-container">
                    <div class="lasf-wrapper<?php echo $show_all ?>">
                        <?php if ($has_nav): ?>
                            <div class="lasf-nav lasf-nav-options">
                                <ul>
                                    <?php
                                    $tab_key = 1;
                                    foreach ($this->pre_tabs as $tab) {
                                        if (!empty($tab['taxonomy_visible']) && !in_array($taxonomy, $tab['taxonomy_visible'])) {
                                            continue;
                                        }
                                        $tab_icon = (!empty($tab['icon'])) ? '<i class="' . $tab['icon'] . '"></i>' : '';
                                        if (!empty($tab['subs'])) {
                                            echo '<li class="lasf-tab-depth-0">';
                                            echo '<a href="#tab=' . $tab_key . '" class="lasf-arrow">' . $tab_icon . $tab['title'] . '</a>';
                                            echo '<ul>';
                                            foreach ($tab['subs'] as $sub) {
                                                $sub_icon = (!empty($sub['icon'])) ? '<i class="' . $sub['icon'] . '"></i>' : '';
                                                echo '<li class="lasf-tab-depth-1"><a id="lasf-tab-link-' . $tab_key . '" href="#tab=' . $tab_key . '">' . $sub_icon . $sub['title'] . '</a></li>';
                                                $tab_key++;
                                            }
                                            echo '</ul>';
                                            echo '</li>';
                                        }
                                        else {
                                            echo '<li class="lasf-tab-depth-0"><a id="lasf-tab-link-' . $tab_key . '" href="#tab=' . $tab_key . '">' . $tab_icon . $tab['title'] . '</a></li>';
                                            $tab_key++;
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="lasf-content">
                            <div class="lasf-sections">
                                <?php
                                $section_key = 1;
                                foreach ($this->pre_sections as $section) {
                                    if (!empty($section['taxonomy_visible']) && !in_array($taxonomy, $section['taxonomy_visible'])) {
                                        continue;
                                    }
                                    $onload = (!$has_nav) ? ' lasf-onload' : '';
                                    $section_icon = (!empty($section['icon'])) ? '<i class="lasf-icon ' . $section['icon'] . '"></i>' : '';
                                    echo '<div id="lasf-section-' . $section_key . '" class="lasf-section' . $onload . '">';
                                    echo ($has_nav) ? '<div class="lasf-section-title"><h3>' . $section_icon . $section['title'] . '</h3></div>' : '';
                                    echo (!empty($section['description'])) ? '<div class="lasf-field lasf-section-description">' . $section['description'] . '</div>' : '';
                                    if (!empty($section['fields'])) {
                                        foreach ($section['fields'] as $field) {
                                            if (!empty($field['id']) && !empty($errors[$field['id']])) {
                                                $field['_error'] = $errors[$field['id']];
                                            }
                                            LASF::field($field, $this->get_meta_value($term_id, $field), $this->unique, 'taxonomy');
                                        }
                                    }
                                    echo '</div>';
                                    $section_key++;
                                }
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <?php
        }

        // save taxonomy form fields
        public function save_taxonomy($term_id) {
            if (wp_verify_nonce(lasf_get_var('lasf_taxonomy_nonce'), 'lasf_taxonomy_nonce')) {
                $errors = array();
                $taxonomy = lasf_get_var('taxonomy');
                if ($taxonomy == $this->taxonomy) {
                    $request = lasf_get_var($this->unique, array());
                    // ignore _nonce
                    if (isset($request['_nonce'])) {
                        unset($request['_nonce']);
                    }
                    foreach ($this->sections as $section) {
                        if (!empty($section['taxonomy_visible']) && !in_array($taxonomy, $section['taxonomy_visible'])) {
                            continue;
                        }
                        // sanitize and validate
                        if (!empty($section['fields'])) {
                            foreach ($section['fields'] as $field) {
                                if (!empty($field['id'])) {
                                    // sanitize
                                    if (!empty($field['sanitize'])) {
                                        $sanitize = $field['sanitize'];
                                        $value_sanitize = lasf_get_vars($this->unique, $field['id']);
                                        $request[$field['id']] = call_user_func($sanitize, $value_sanitize);
                                    }
                                    // validate
                                    if (!empty($field['validate'])) {
                                        $validate = $field['validate'];
                                        $value_validate = lasf_get_vars($this->unique, $field['id']);
                                        $has_validated = call_user_func($validate, $value_validate);
                                        if (!empty($has_validated)) {
                                            $errors[$field['id']] = $has_validated;
                                            $request[$field['id']] = $this->get_meta_value($term_id, $field);
                                        }
                                    }
                                    // auto sanitize
                                    if (!isset($request[$field['id']]) || is_null($request[$field['id']])) {
                                        $request[$field['id']] = '';
                                    }
                                }
                            }
                        }
                    }

                    $request = apply_filters("lasf_{$this->unique}_save", $request, $term_id, $this);
                    do_action("lasf_{$this->unique}_save_before", $request, $term_id, $this);
                    if (empty($request)) {
                        if ($this->args['data_type'] !== 'serialize') {
                            foreach ($request as $key => $value) {
                                delete_term_meta($term_id, $key);
                            }
                        }
                        else {
                            delete_term_meta($term_id, $this->unique);
                        }
                    }
                    else {
                        if ($this->args['data_type'] !== 'serialize') {
                            foreach ($request as $key => $value) {
                                update_term_meta($term_id, $key, $value);
                            }
                        }
                        else {
                            update_term_meta($term_id, $this->unique, $request);
                        }
                        if (!empty($errors)) {
                            update_term_meta($term_id, '_lasf_errors', $errors);
                        }
                    }
                    do_action("lasf_{$this->unique}_saved", $request, $term_id, $this);
                    do_action("lasf_{$this->unique}_save_after", $request, $term_id, $this);

                }
            }
        }
    }
}

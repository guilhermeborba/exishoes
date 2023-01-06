<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access directly.
/**
 *
 * Abstract Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!class_exists('LASF_Abstract')) {
    abstract class LASF_Abstract
    {
        public $abstract = '';
        public $output_css = '';
        public $typographies = array();

        public function __construct() {
            // Check for embed google web fonts
            if (!empty($this->args['enqueue_webfont'])) {
                add_action('wp_enqueue_scripts', array(
                    &$this,
                    'add_enqueue_google_fonts'
                ), 100);

                add_action('admin_enqueue_scripts', array(
                    &$this,
                    'add_enqueue_google_fonts'
                ), 100);
            }
            // Check for embed custom css styles
            if (!empty($this->args['output_css'])) {
                add_action('wp_head', array(
                    &$this,
                    'add_output_css'
                ), 100);
            }
        }

        public function add_enqueue_google_fonts() {
            if (!empty($this->pre_fields)) {

                foreach ($this->pre_fields as $field) {

                    $field_id = (!empty($field['id'])) ? $field['id'] : '';
                    $field_type = (!empty($field['type'])) ? $field['type'] : '';
                    $field_output = (!empty($field['output']) || !empty($field['selectors']) ) ? true : false;

                    $field_check = ($field_type === 'typography' || $field_output) ? true : false;
                    if ($field_type && $field_id) {
                        LASF::maybe_include_field($field_type);
                        $class_name = 'LASF_Field_' . $field_type;
                        if (class_exists($class_name)) {

                            if (method_exists($class_name, 'output') || method_exists($class_name, 'enqueue_google_fonts')) {
                                $field_value = '';
                                if ($field_check && ($this->abstract === 'options' || $this->abstract === 'customize')) {
                                    $field_value = (isset($this->options[$field_id]) && $this->options[$field_id] !== '') ? $this->options[$field_id] : '';
                                }
                                else if ($field_check && $this->abstract === 'metabox') {
                                    $field_value = $this->get_meta_value($field);
                                }
                                $instance = new $class_name($field, $field_value, $this->unique, 'wp/enqueue', $this);
                                // typography enqueue and embed google web fonts
                                if ($field_type === 'typography' && $this->args['enqueue_webfont'] && !empty($field_value['font-family'])) {
                                    $instance->enqueue_google_fonts();
                                }
                                // output css
                                if ($field_output && $this->args['output_css']) {
                                    $instance->output();
                                }
                                unset($instance);
                            }

	                        // auto enqueue google font for group field
	                        if( $field_type === 'group' ){

		                        $field_value = '';
		                        if ($this->abstract === 'options' || $this->abstract === 'customize') {
			                        $field_value = (isset($this->options[$field_id]) && $this->options[$field_id] !== '') ? $this->options[$field_id] : '';
		                        }
		                        else if ($this->abstract === 'metabox') {
			                        $field_value = $this->get_meta_value($field);
		                        }

		                        foreach ( $field['fields'] as $k => $sub_field ) {
			                        if($k == 0){
			                        	continue;
			                        }

			                        if(!empty($sub_field['type']) && $sub_field['type'] == 'typography' && $this->args['enqueue_webfont'] && !empty($sub_field['id'])){

			                        	if(!empty($field_value) && is_array($field_value)){
					                        foreach ($field_value as $item){
												if(!empty($item[$sub_field['id']]) && !empty($item[$sub_field['id']]['font-family'])){
													LASF::maybe_include_field('typography');
													$subinstance   = new LASF_Field_typography($sub_field, $item[$sub_field['id']], $this->unique . '[' . $field_id . ']', 'wp/enqueue', $this);
													$subinstance->enqueue_google_fonts();
													unset($subinstance);
												}
					                        }
				                        }
			                        }
		                        }
	                        }

	                        if(!$this->args['output_css']){
                                continue;
                            }

                            /**
                             * check if this field has children
                             */
                            if( $field_type === 'tabbed' ){

                                $field_value = '';
                                if ($this->abstract === 'options' || $this->abstract === 'customize') {
                                    $field_value = (isset($this->options[$field_id]) && $this->options[$field_id] !== '') ? $this->options[$field_id] : '';
                                }
                                else if ($this->abstract === 'metabox') {
                                    $field_value = $this->get_meta_value($field);
                                }

                                foreach ($field['tabs'] as $tab){

                                    foreach ( $tab['fields'] as $sub_field ) {

                                        $sub_field_output = (!empty($sub_field['output']) || !empty($sub_field['selectors']) ) ? true : false;

                                        if($sub_field_output){
                                            $sub_field_id = (!empty($sub_field['id'])) ? $sub_field['id'] : '';
                                            $sub_field_type = (!empty($sub_field['type'])) ? $sub_field['type'] : '';

                                            $sub_field_default = ( isset($sub_field['default'])) ? $sub_field['default'] : '';
                                            $sub_field_value   = ( isset( $field_value[$sub_field_id] ) ) ? $field_value[$sub_field_id] : $sub_field_default;

                                            $unique_id      = $this->unique . '[' . $field_id . ']';

                                            if ($sub_field_type && $sub_field_id) {
                                                LASF::maybe_include_field($sub_field_type);
                                                $sub_class_name = 'LASF_Field_' . $sub_field_type;
                                                if (class_exists($sub_class_name) && ( method_exists($sub_class_name, 'render_css_output') || method_exists($sub_class_name, 'output') )) {
                                                    $sub_instance   = new $sub_class_name($sub_field, $sub_field_value, $unique_id, 'wp/enqueue', $this);
                                                    if(!empty($sub_field['selectors'])){
                                                        $sub_instance->render_css_output();
                                                    }
                                                    elseif (!empty($sub_field['output'])){
                                                        $sub_instance->output();
                                                    }
                                                    unset($sub_instance);
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($this->typographies) && empty($this->args['async_webfont'])) {
                $query = array('family' => implode('%7C', $this->typographies));
                $api = '//fonts.googleapis.com/css';
                $handle = 'lasf-google-web-fonts-' . $this->unique;
                $src = esc_url(add_query_arg($query, $api));
                wp_enqueue_style($handle, $src, array(), null);
            }
            if (!empty($this->typographies) && !empty($this->args['async_webfont'])) {
                $api = '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
                echo '<script type="text/javascript">';
                echo 'WebFontConfig={google:{families:[' . "'" . implode("','", $this->typographies) . "'" . ']}};';
                echo '!function(e){var t=e.createElement("script"),s=e.scripts[0];t.src="' . $api . '",t.async=!0,s.parentNode.insertBefore(t,s)}(document);';
                echo '</script>';
            }
            do_action("lastudio/option-fields/typographies", $this->typographies, $this);
        }

        public function add_output_css() {
            $this->output_css = apply_filters("lasf_{$this->unique}_output_css", $this->output_css, $this);
            if (!empty($this->output_css)) {
                echo '<style type="text/css">' . la_minify_css($this->output_css) . '</style>';
            }
        }
    }
}

<?php
namespace LaStudioAPI;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Define Posts class
 */
class Template_Helper {

    /**
     * [$depended_scripts description]
     * @var array
     */
    public $depended_scripts = [];

    /**
     * [$depended_styles description]
     * @var array
     */
    public $depended_styles = [];

    public $document_data = [];

    /**
     * Instance of this class.
     *
     * @since   1.0.0
     * @access  private
     * @var     Template_Helper
     */
    private static $instance;

    /**
     * Provides access to a single instance of a module using the singleton pattern.
     *
     * @since   1.0.0
     * @return	object
     */
    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function callback( $args, $type = 'rest' ) {

        $template_id = ! empty( $args['id'] ) ? $args['id'] : false;

        $is_dev = isset($args['dev']) ? $args['dev'] : false;

        $dev = filter_var( $is_dev, FILTER_VALIDATE_BOOLEAN ) ? true : false;

        if ( ! $template_id || !defined('ELEMENTOR_VERSION' ) ) {
            $template_data = [
                'template_content' => '',
                'template_scripts' => [],
                'template_styles'  => [],
                'template_metadata' => []
            ];
            return $type == 'rest' ? rest_ensure_response( $template_data ) : wp_send_json($template_data);
        }

        $transient_key = md5( sprintf( 'lastudio_elementor_template_data_%s', $template_id ) );

        $template_data = get_transient( $transient_key );

        if ( !empty( $template_data ) && !$dev ) {
            return $type == 'rest' ? rest_ensure_response( $template_data ) : wp_send_json($template_data);
        }

        $plugin = \Elementor\Plugin::instance();

        $template_scripts = [];
        $template_styles = [];

        $fonts_link = $this->get_elementor_template_fonts_url( $template_id );

        if ( $fonts_link ) {
            $template_styles[ 'lastudio-google-fonts-css-' . $template_id ] = $fonts_link;
        }

        $plugin->frontend->register_scripts();
        $plugin->frontend->register_styles();

        $content = $plugin->frontend->get_builder_content_for_display( $template_id, true );

        $this->get_elementor_template_scripts( $template_id );
        $this->get_elementor_template_icon_fonts_url( $template_id );

        $script_depends = array_unique( $this->depended_scripts );

        $style_depends = array_unique( $this->depended_styles );

        foreach ( $script_depends as $script ) {
            if( $tag = $this->get_script_uri_by_handler( $script ) ){
                $template_scripts[ $script ] = $tag;
            }

        }

        foreach ( $style_depends as $style ) {
            if( $tag = $this->get_style_uri_by_handler( $style ) ){
                $template_styles[ $style ] = $tag;
            }
        }

        $template_metadata = [];

        if(isset($this->document_data[$template_id])){
            $template_metadata = $this->document_data[$template_id];
        }

        $template_data = [
            'template_content' => $content,
            'template_scripts' => $template_scripts,
            'template_styles'  => $template_styles,
            'template_metadata' => $template_metadata
        ];

        set_transient( $transient_key, $template_data, 24 * HOUR_IN_SECONDS );
        Rest_Api::set_transient_key('post_type', $template_id, $transient_key );

        return $type == 'rest' ? rest_ensure_response( $template_data ) : wp_send_json($template_data);
    }

    /**
     * @return [type] [description]
     */
    public function get_elementor_template_fonts_url( $template_id ) {

        $post_css = new \Elementor\Core\Files\CSS\Post( $template_id );

        $post_meta = $post_css->get_meta();

        if ( ! isset( $post_meta['fonts'] ) ) {
            return false;
        }

        $google_fonts = $post_meta['fonts'];

        $google_fonts = array_unique( $google_fonts );

        if ( empty( $google_fonts ) ) {
            return false;
        }

        foreach ( $google_fonts as $k => &$font ) {
            $font_type = \Elementor\Fonts::get_font_type( $font );
            if($font_type == \Elementor\Fonts::GOOGLE || $font_type == \Elementor\Fonts::EARLYACCESS){
                $font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
            }
            else{
                unset($google_fonts[$k]);
            }
        }

        if(empty($google_fonts)){
            return false;
        }

        $fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $google_fonts ) );

        return $fonts_url;
    }

    public function get_elementor_template_icon_fonts_url( $template_id ){

	    $post_css = new \Elementor\Core\Files\CSS\Post( $template_id );

	    $post_meta = $post_css->get_meta();

	    if ( ! isset( $post_meta['icons'] ) ) {
		    return false;
	    }

	    $icon_fonts = $post_meta['icons'];

	    $icon_fonts = array_unique( $icon_fonts );

	    foreach ($icon_fonts as $icon_font){
	    	if(!empty($icon_font)){
	    		$handler = 'elementor-icons-' . $icon_font;
	    		$this->get_style_deps_by_handler($handler);
		    }
	    }
    }

    /**
     * [get_elementor_template_scripts_url description]
     * @param  [type] $template_id [description]
     * @return [type]              [description]
     */
    public function get_elementor_template_scripts( $template_id ) {

        $document = \Elementor\Plugin::$instance->documents->get( $template_id );

        $main_post = $document->get_main_post();

        $document_data = [
            'title' => $main_post->post_title,
            'sub_title' => $main_post->post_type == 'nav_menu_item' ? 'Menu Item' : $document::get_title(),
            'href' => $document->get_edit_url()
        ];

        $this->document_data[$template_id] = $document_data;

        $elements_data = $document->get_elements_raw_data();

        $this->find_widgets_script_handlers( $elements_data );
    }

    /**
     * [find_widgets_script_handlers description]
     * @param  [type] $elements_data [description]
     * @return [type]                [description]
     */
    public function find_widgets_script_handlers( $elements_data ) {

        foreach ( $elements_data as $element_data ) {

            if ( 'widget' === $element_data['elType'] ) {
                $widget = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

                $widget_script_depends = $widget->get_script_depends();

                if ( ! empty( $widget_script_depends ) ) {
                    foreach ( $widget_script_depends as $key => $script_handler ) {
                        $this->depended_scripts[] = $script_handler;
                    }
                }

                $widget_style_depends = $widget->get_style_depends();
                if ( ! empty( $widget_style_depends ) ) {
                    foreach ( $widget_style_depends as $key => $style_handler ) {
                        $this->depended_styles[] = $style_handler;
                    }
                }

            }
            else {
                $element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

                $childrens = $element->get_children();

                foreach ( $childrens as $key => $children ) {
                    $children_data[$key] = $children->get_raw_data();

                    $this->find_widgets_script_handlers( $children_data );
                }
            }
        }
    }

    /**
     * [get_script_uri_by_handler description]
     * @param  [type] $handler [description]
     * @return [type]          [description]
     */
    public function get_script_uri_by_handler( $handler ) {
        global $wp_scripts;

        if ( isset( $wp_scripts->registered[ $handler ] ) ) {

            $src = $wp_scripts->registered[ $handler ]->src;

            if ( ! preg_match( '|^(https?:)?//|', $src ) && ! ( $wp_scripts->content_url && 0 === strpos( $src, $wp_scripts->content_url ) ) ) {
                $src = $wp_scripts->base_url . $src;
            }

            return $src;
        }

        return false;
    }

    /**
     * [get_style_uri_by_handler description]
     * @param  [type] $handler [description]
     * @return [type]          [description]
     */
    public function get_style_uri_by_handler( $handler ) {
        global $wp_styles;

        if ( isset( $wp_styles->registered[ $handler ] ) ) {

            $src = $wp_styles->registered[ $handler ]->src;

            if ( ! preg_match( '|^(https?:)?//|', $src ) && ! ( $wp_styles->content_url && 0 === strpos( $src, $wp_styles->content_url ) ) ) {
                $src = $wp_styles->base_url . $src;
            }

            return $src;
        }

        return false;
    }

    public function get_style_deps_by_handler( $handler ){
	    global $wp_styles;
	    if ( isset( $wp_styles->registered[ $handler ] ) ) {
	    	$deps = $wp_styles->registered[ $handler ]->deps;
	    	if(!empty($deps)){
	    		foreach ($deps as $dep){
				    $this->depended_styles[] = $dep;
			    }
		    }
	    	$this->depended_styles[] = $handler;
	    }
    }

}
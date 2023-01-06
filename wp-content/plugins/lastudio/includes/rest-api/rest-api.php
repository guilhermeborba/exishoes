<?php

namespace LaStudioAPI;

/**
 * API controller class
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Controller class
 */
class Rest_Api {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$api_namespace description]
	 * @var string
	 */
	public $api_namespace = 'lastudio-api/v1';

	/**
	 * [$_endpoints description]
	 * @var null
	 */
	private $_endpoints = null;

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	// Here initialize our namespace and resource name.
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_action( 'wp_update_nav_menu', array( $this, 'clear_transient_for_menu' ) );
		add_action( 'wp_delete_nav_menu', array( $this, 'clear_transient_for_menu' ) );
		add_action( 'post_updated', array( $this, 'clear_transient_for_post' ) );
		add_action( 'deleted_post', array( $this, 'clear_transient_for_post' ) );
		add_action( 'elementor/core/files/clear_cache', array( $this, 'clear_all_transient' ) );
		//add_filter( 'woocommerce_is_rest_api_request', array( $this, 'woocommerce_is_rest_api_request' ) );
		add_action( 'la_ajax_lastudio_get_elementor_template_output', array( $this, 'render_elementor_template_via_ajax' ), 10, 2 );
	}

	/**
	 * Initialize all related Rest API endpoints
	 *
	 * @return [type] [description]
	 */
	public function init_endpoints() {
		$this->_endpoints = array();
        if ( defined('ELEMENTOR_VERSION' ) ) {
		    $this->register_endpoint( new Endpoints\Elementor_Template() );
        }
		$this->register_endpoint( new Endpoints\Get_Menu_Items() );
		do_action( 'lastudio/rest/init-endpoints', $this );
	}

	/**
	 * Register new endpoint
	 *
	 * @param  object $endpoint_instance Endpoint instance
	 * @return void
	 */
	public function register_endpoint( $endpoint_instance = null ) {
		if ( $endpoint_instance ) {
			$this->_endpoints[ $endpoint_instance->get_name() ] = $endpoint_instance;
		}
	}

	/**
	 * Returns all registererd API endpoints
	 *
	 * @return [type] [description]
	 */
	public function get_endpoints() {
		if ( null === $this->_endpoints ) {
			$this->init_endpoints();
		}
		return $this->_endpoints;
	}

	/**
	 * Returns endpoints URLs
	 */
	public function get_endpoints_urls() {

		$result    = array();
		$endpoints = $this->get_endpoints();

		foreach ( $endpoints as $endpoint ) {
			$key = str_replace( '-', '', ucwords( $endpoint->get_name(), '-' ) );
			$result[ $key ] = get_rest_url( null, $this->api_namespace . '/' . $endpoint->get_name() . '/' . $endpoint->get_query_params() , 'rest' );
		}

		return $result;

	}

	/**
	 * Returns route to passed endpoint
	 *
	 * @return [type] [description]
	 */
	public function get_route( $endpoint = '', $full = false ) {

		$path = $this->api_namespace . '/' . $endpoint . '/';

		if ( ! $full ) {
			return $path;
		} else {
			return get_rest_url( null, $path );
		}

	}

	// Register our routes.
	public function register_routes() {

		$endpoints = $this->get_endpoints();

		foreach ( $endpoints as $endpoint ) {

			$args = array(
				'methods'             => $endpoint->get_method(),
				'callback'            => array( $endpoint, 'callback' ),
				'permission_callback' => array( $endpoint, 'permission_callback' ),
			);

			$endpoint_args = $endpoint->get_args();

			if ( ! empty( $endpoint_args ) ) {
				$args['args'] = $endpoint->get_args();
			}

			$route = '/' . $endpoint->get_name() . '/' . $endpoint->get_query_params();

			register_rest_route( $this->api_namespace, $route, $args );
		}
	}

	public static function set_transient_key( $type, $id, $key ){

        $transient_manager = get_option('LaStudioAPI_transient_manager', []);

	    if(!isset($transient_manager[$type])){
            $transient_manager[$type] = [];
        }
        $transient_manager[$type][$key] = $id;
	    update_option('LaStudioAPI_transient_manager', $transient_manager);
    }

    public function clear_transient_for_menu($menu_id){
	    $all_transient = get_option('LaStudioAPI_transient_manager', []);
	    $need_update = false;
	    if(!empty($all_transient['menu'])){
            foreach ($all_transient['menu'] as $k => $id){
                if($id == $menu_id){
                    $need_update = true;
                    delete_transient($k);
                    unset($all_transient['menu'][$k]);
                }
            }
        }
	    if($need_update){
            update_option('LaStudioAPI_transient_manager', $all_transient);
        }
    }

    public function clear_transient_for_post($post_id){
	    $all_transient = get_option('LaStudioAPI_transient_manager', []);
        $need_update = false;
	    if(!empty($all_transient['post_type'])){
	        foreach ($all_transient['post_type'] as $k => $id){
	            if($id == $post_id){
                    $need_update = true;
	                delete_transient($k);
	                unset($all_transient['post_type'][$k]);
                }
            }
        }
        if($need_update){
            update_option('LaStudioAPI_transient_manager', $all_transient);
        }
    }

    public function clear_all_transient(){
        $all_transient = get_option('LaStudioAPI_transient_manager', []);
        if(!empty($all_transient)){
            foreach ($all_transient as $type => $item){
                foreach ($item as $k => $id){
                    delete_transient($k);
                }
            }
        }
        update_option('LaStudioAPI_transient_manager', []);
    }

    public function woocommerce_is_rest_api_request($value){
        if ( empty( $_SERVER['REQUEST_URI'] ) ) {
            return $value;
        }
        if( false !== strpos( $_SERVER['REQUEST_URI'], $this->api_namespace ) ){
            $value = false;
        }
        return $value;
    }

    public function render_elementor_template_via_ajax( $request_args, $error ){
        return Template_Helper::get_instance()->callback($request_args, 'ajax');
    }
}

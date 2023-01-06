<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

if(!class_exists('Luxsa_WooCommerce_Compare')){
    class Luxsa_WooCommerce_Compare {

        protected $setting = null;

        protected static $data = array();

        public function __construct( $setting = array() ){

            $this->setting = array_merge(array(
                'cookie_name'       => 'luxsa_compare',
                'user_meta_key'     => 'la_compare',
                'cookie_life'       => MONTH_IN_SECONDS
            ), $setting);

            $this->load_data();
            $this->init();
        }

        private function get_site_id(){
            global $blog_id;
            return is_multisite() ? absint($blog_id) : 1;
        }

        private function load_data( $data = null ){
            /**
             * We need load data from cookie and user meta ( if user is logged in )
             */
            if(is_null($data)){
                $lists = array_merge(
                    $this->get_lists_from_cookie(),
                    $this->get_lists_from_usermeta()
                );
                $data = array_values( array_unique( $lists ) );
            }
            if(!empty($data)){
                $tmp = array();
                foreach($data as $product_id){
                    if($this->check_is_product($product_id)){
                        array_push($tmp, $product_id);
                    }
                }
                $data = $tmp;
            }
            self::$data = $data;
        }

        public static function get_data(){
            return self::$data;
        }

        private function init(){
            add_action( 'wp_ajax_la_helpers_compare', array( $this, 'ajax_process' ) );
            add_action( 'wp_ajax_nopriv_la_helpers_compare', array( $this, 'ajax_process' ) );
            add_action( 'woocommerce_init', array( $this, 'remove_item_from_url' ), 99 );
            add_filter( 'lastudio/compare/count', array($this, 'get_count') );

        }

        public function remove_item_from_url(){
            if(isset($_GET['la_helpers_compare_remove']) && ( $product_id = absint($_GET['la_helpers_compare_remove'])) ) {
                $product_exists = $this->get_data();
                if(($key = array_search($product_id, $product_exists)) !== false) {
                    unset($product_exists[$key]);
                    $this->set_lists_for_user($product_exists);
                    $this->set_lists_for_cookie($product_exists);
                    self::$data = $product_exists;
                    wc_add_notice( sprintf( esc_html__('"%1$s" has been removed from list', 'luxsa'), get_the_title($product_id) ) );
                }
            }
        }

        public function check_is_product( $product_id ) {
            if(empty($product_id)){
                return false;
            }
            else{
                $post_type = get_post_type($product_id);
                if(!in_array($post_type, array('product', 'product_variation'))){
                    return false;
                }
                return true;
            }
        }

        public static function is_product_in_wishlist( $product_id ) {
            if(empty(self::$data)){
                return false;
            }
            else{
                return in_array( $product_id, self::$data );
            }
        }

        public function ajax_process(){

            $check_ajax_referer = true;

            if(apply_filters('luxsa/check_ajax_referer', false, 'compare_nonce', 'security')){
                $check_ajax_referer = check_ajax_referer( 'compare_nonce', 'security', false );
            }

            if( $check_ajax_referer ) {
                if(!isset($_REQUEST['post_id'])){
                    wp_send_json_error(array(
                        'message' => esc_html__('Invalid Product ID', 'luxsa')
                    ));
                }

                $post_id = absint($_REQUEST['post_id']);

                if(!$post_id){
                    wp_send_json_error(array(
                        'message' => esc_html__('Invalid Product ID', 'luxsa')
                    ));
                }

                if( !$this->check_is_product( $post_id ) ) {
                    wp_send_json_error(array(
                        'message' => esc_html__('Invalid Product ID', 'luxsa')
                    ));
                }

                $method = ( !empty( $_REQUEST['type'] ) && $_REQUEST['type'] == 'remove' ? 'remove' : 'add' );

                $message = $this->$method($post_id);

                wp_send_json_success($message);
            }
            else {
                wp_send_json_error(array(
                    'message' => esc_html__('Invalid security key', 'luxsa')
                ));
            }
        }

        private function add( $post_id = 0 ){
            $lists = $this->get_data();
            $response = array();
            $response['compare_url'] = luxsa_get_compare_url();
            if(in_array($post_id, $lists)){
                $response['message'] = esc_html__('Product already in list', 'luxsa');
                $response['has_error'] = true;
            }
            else{
                $response['message'] = esc_html__('Product added', 'luxsa');
                $response['has_error'] = false;
                array_push($lists, $post_id);
                $this->set_lists_for_user($lists);
                $this->set_lists_for_cookie($lists);
            }

            $response['count'] = count( $lists );
            return $response;
        }

        private function remove( $post_id = 0 ){
            $lists = $this->get_data();
            $response = array();
            $response['compare_url'] = luxsa_get_compare_url();
            if(($key = array_search($post_id, $lists)) !== false) {
                $response['message'] = esc_html__('Product has been removed from list', 'luxsa');
                $response['has_error'] = false;
                unset($lists[$key]);
                $this->set_lists_for_user($lists);
                $this->set_lists_for_cookie($lists);
            }
            else{
                $response['message'] = esc_html__('Product does not exist in list', 'luxsa');
                $response['has_error'] = true;
            }

            $response['count'] = count( $lists );

            return $response;
        }

        private function get_lists_from_cookie( $site_id = null ){

            $lists = array();

            if (empty($_COOKIE[ $this->setting['cookie_name'] ])) return $lists;

            if(empty($site_id)){
                $site_id = (int) $this->get_site_id();
            }

            $values = json_decode(stripslashes($_COOKIE[$this->setting['cookie_name']]), true);

            if(empty($values)) return $lists;

            foreach( $values as $value ){
                if( isset($value['site_id']) && $value['site_id'] == $site_id ){
                    $lists = $value['posts'];
                    break;
                }
            }

            return $lists;
        }

        private function get_lists_from_usermeta( $user_login = '' ){

            $lists = array();
            return $lists;
        }

        private function set_lists_for_user( $lists = array(), $user_login = '' ){
            return;
        }

        private function set_lists_for_cookie( $lists = array() ) {

            $site_id = $this->get_site_id();

            $key = false;

            $values = array();

            if(!empty($_COOKIE[ $this->setting['cookie_name'] ])){
                $values = json_decode(stripslashes($_COOKIE[$this->setting['cookie_name']]), true);
                if(!empty($values)){
                    foreach($values as $k => $value){
                        if( isset($value['site_id']) && $value['site_id'] == $site_id ){
                            $key = $k;
                            break;
                        }
                    }
                    if($key !== false){
                        $values[$key] = array(
                            'site_id'   => $site_id,
                            'posts'     => $lists
                        );
                    }else{
                        $values[] = array(
                            'site_id'   => $site_id,
                            'posts'     => $lists
                        );
                    }
                }else{
                    $values[] = array(
                        'site_id'   => $site_id,
                        'posts'     => $lists
                    );
                }
            }

            else {
                $values[] = array(
                    'site_id'   => $site_id,
                    'posts'     => $lists
                );
            }

            @setcookie( $this->setting['cookie_name'], json_encode($values), time() + $this->setting['cookie_life'], '/' );
        }

        public static function get_count(){
            $lists = self::$data;
            if(is_array($lists)){
                return count($lists);
            }
            return 0;
        }
    }
}

new Luxsa_WooCommerce_Compare();
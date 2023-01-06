<?php
/*
Plugin Name:    LUXSA Package Demo Data
Plugin URI:     http://la-studioweb.com/
Description:    This plugin use only for LA-Studio Theme
Author:         LA-Studio
Author URI:     https://la-studioweb.com/
Version:        1.0.0
Text Domain:    lastudio-demodata
*/

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

if(!function_exists('la_import_check_post_exists')){
    function la_import_check_post_exists( $title, $content = '', $date = '', $type = '' ){
        global $wpdb;

        $post_title = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
        $post_content = wp_unslash( sanitize_post_field( 'post_content', $content, 0, 'db' ) );
        $post_date = wp_unslash( sanitize_post_field( 'post_date', $date, 0, 'db' ) );
        $post_type = wp_unslash( sanitize_post_field( 'post_type', $type, 0, 'db' ) );

        $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
        $args = array();

        if ( !empty ( $date ) ) {
            $query .= ' AND post_date = %s';
            $args[] = $post_date;
        }

        if ( !empty ( $title ) ) {
            $query .= ' AND post_title = %s';
            $args[] = $post_title;
        }

        if ( !empty ( $content ) ) {
            $query .= ' AND post_content = %s';
            $args[] = $post_content;
        }

        if ( !empty ( $type ) ) {
            $query .= ' AND post_type = %s';
            $args[] = $post_type;
        }

        if ( !empty ( $args ) )
            return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );

        return 0;
    }
}


class Luxsa_Data_Demo_Plugin_Class{

    public static $plugin_dir_path = null;

    public static $plugin_dir_url = null;

    public static $instance = null;

    private $preset_allows = array();

    public static $theme_name = 'luxsa';

    public static $demo_site = 'https://luxsa.la-studioweb.com/';

    protected $demo_data = array();

    public static function get_instance() {
        if ( null === static::$instance ) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct( ) {

        self::$plugin_dir_path = plugin_dir_path(__FILE__);

        self::$plugin_dir_url = plugin_dir_url(__FILE__);

        include_once self::$plugin_dir_path . 'demodata.php';

        $this->_setup_demo_data();

        if( self::isLocal() ){
            $this->_load_other_hook();
        }

        $this->load_importer();

        add_filter(self::$theme_name . '/filter/demo_data', array( $this, 'get_data_for_import_demo') );

        add_action( 'wp', array( $this, 'init_override'), 99 );

        add_action( 'init', array( $this, 'register_menu_import_demo'), 99 );

        add_action( 'after_setup_theme', array( $this, 'setup_shortcode' ) );
    }

    private function load_importer(){
        require_once self::$plugin_dir_path . 'importer.php';
        if(class_exists('LaStudio_Importer')){
            new LaStudio_Importer(self::$theme_name, $this->get_data_for_import_demo(), self::$demo_site );
        }
    }

    public function init_override(){
        if(!is_admin()){
            $this->_override_settings();
        }
    }

    public function register_menu_import_demo(){
        if(class_exists('LaStudio')){
            require_once self::$plugin_dir_path . 'panel.php';

            if(self::isLocal()){
	            require_once self::$plugin_dir_path . 'export.php';
	            new LaStudio_Export_Demo();
            }
        }
    }

    public function get_data_for_import_demo(){
        $demo = (array) $this->filter_demo_item_by_category('demo');
        return $demo;
    }

    private function _setup_demo_data(){
        $this->preset_allows = array(

            // shop
            'shop-sidebar',
            'shop-fullwidth',
            'shop-metro',

            // shop details
            'products-simple',
            'products-variable',
            'products-affiliate',
            'products-grouped',
            'products-layout-1',
            'products-layout-2',
            'products-layout-3',
            'products-layout-4',
            'products-layout-5',

            // home
            'main-01',
            'main-02',
            'main-03',
            'main-04',
            'main-05',
            'main-06',
            'main-07',

            // Blog
            'blog-sidebar',
            'blog-without-sidebar',
            'blog-masonry',
            'blog-layout-1',
            'blog-layout-2',

            'blog-custom-02',
        );

        $func_name = 'la_'. self::$theme_name .'_get_demo_array';

        $this->demo_data = call_user_func( $func_name, self::$plugin_dir_url . 'previews/', self::$plugin_dir_path . 'data/');

    }

    private function _get_preset_from_file( $preset = ''){

        if(!empty($preset)){
            $file = self::$plugin_dir_path . 'presets/' . $preset . '.php';
            if(file_exists($file)){
                include_once $file;
                return call_user_func( 'la_'. self::$theme_name .'_preset_' . str_replace('-', '_', $preset) );
            }
            return false;
        }
        return false;
    }

    private function _load_data_from_preset( $preset ){
        $settings = $this->_get_preset_from_file( $preset );
        if(!empty($settings)){
            foreach ( $settings as $setting ) {
                if(isset($setting['filter_name'])){

                    if(!empty($setting['filter_func'])){
                        $filter_priority = isset($setting['filter_priority']) ? $setting['filter_priority'] : 10;
                        $filter_args = isset($setting['filter_args']) ? $setting['filter_args'] : 1;
                        add_filter($setting['filter_name'], $setting['filter_func'], $filter_priority, $filter_args );
                    }
                    else{
                        $new_filter_value = $setting['value'];
                        add_filter("{$setting['filter_name']}", function() use ( $new_filter_value ){
                            return $new_filter_value;
                        },20);
                    }

                }
                else{
                    $new_value = $setting['value'];
                    $keys = explode('|', $setting['key']);
                    foreach( $keys as $key ){
                        add_filter(self::$theme_name . "/filter/get_option", function( $old_val, $old_key ) use ( $new_value, $key ){
                            if( $old_key == $key ){
                                return $new_value;
                            }
                            return $old_val;
                        }, 11, 2);
                    }
                }
            }
        }
    }
    
    private function _override_settings(){
        if(!empty($_GET['la_preset']) && in_array( $_GET['la_preset'], $this->preset_allows )){
            $this->_load_data_from_preset($_GET['la_preset']);
        }
        if(self::isLocal() && is_page()){
            $lists_preset = $this->get_demo_with_preset();
            if(!empty($lists_preset)){
                $current_page_name = get_queried_object()->post_name;
                if( array_key_exists( $current_page_name, $lists_preset ) ) {
                    $this->_load_data_from_preset($lists_preset[$current_page_name]);
                }
            }
        }
    }

    private function get_demo_with_preset(){
        $lists = array();
        $demo_data = (array) $this->demo_data;
        if(!empty($demo_data)){
            foreach($demo_data as $key => $demo){
                if(!empty($demo['demo_preset'])){
                    $lists[$key] = $demo['demo_preset'];
                }
            }
        }
        return $lists;
    }

    public static function isLocal(){
        $is_local = false;
        if (isset($_SERVER['X_FORWARDED_HOST']) && !empty($_SERVER['X_FORWARDED_HOST'])) {
            $hostname = $_SERVER['X_FORWARDED_HOST'];
        } else {
            $hostname = $_SERVER['HTTP_HOST'];
        }
        if ( strpos($hostname, '.la-studioweb.com') !== false || strpos($hostname, '.la-studio.io') !== false ) {
            $is_local = true;
        }
        return $is_local;
    }

    public function filter_demo_item_by_category( $category ){
        $demo_data = (array) $this->demo_data;
        $return = array();
        if(!empty($demo_data) && !empty($category)){
            foreach( $demo_data as $key => $demo ){
                if(!empty($demo['category'])){
                    $demo_category = array_map('strtolower', $demo['category']);
                    if(in_array(strtolower($category), $demo_category)){
                        $return[$key] = $demo;
                    }
                }
            }
        }
        return $return;
    }

    private function _load_other_hook(){
        include_once self::$plugin_dir_path . 'other-hook.php';
    }

    public function setup_shortcode(){
        add_shortcode('lastudio_demo', [ $this, 'create_shortcode'] );
    }

    public function create_shortcode( $atts, $output ){
        $atts = shortcode_atts([
	        'style' => '1'
        ], $atts);
        $demo_lists = $this->get_data_for_import_demo();
        $filters = array();
        foreach ($demo_lists as $demo){
            if(!empty($demo['category'])){
                foreach ($demo['category'] as $k => $v){
                    if(strtolower($v) == 'demo'){
                        continue;
                    }
                    $filters[strtolower($v)] = $v;
                }
            }
        }
        $style = $atts['style'];
        ob_start();
        ?>
        <div class="elementor-lastudio-demo lastudio-elements">
            <div id="la_demo_2021" class="lastudio-demo">
                <?php if($style != '1'): ?>
                <div class="isotope__filter lastudio-demo__filter js-el" data-la_component="MasonryFilter" data-isotope_container="#la_demo_2019 .la-isotope-container">
                    <div class="isotope__filter-list lastudio-demo__filter-list">
                        <div class="isotope__filter-item lastudio-demo__filter-item active" data-filter="*"><span>Show All</span></div><?php
                        if(!empty($filters)){
                            foreach ($filters as $filter){
                                echo '<div class="isotope__filter-item lastudio-demo__filter-item" data-filter="la_demo_category-'.esc_attr(strtolower(str_replace(' ', '-', $filter))) .'"><span>'.esc_html($filter).'</span></div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="lastudio-demo__list_wrapper">
                    <div class="lastudio-demo__list <?php if($style != '1'): ?>js-el la-isotope-container<?php endif; ?> grid-items block-grid-3 mobile-block-grid-2 xmobile-block-grid-1" <?php if($style != '1'): ?>data-item_selector=".loop__item" data-la_component="DefaultMasonry"<?php endif; ?>>
                        <?php
                        foreach ($demo_lists as $demo){
                            ?><div class="loop__item grid-item lastudio-demo__item<?php
                                foreach ($demo['category'] as $dc){
                                    echo ' la_demo_category-' . esc_attr( strtolower( str_replace(' ', '-', $dc) ) );
                                }
                            ?>">
                                <div class="lastudio-demo__item__inner">
                                    <a href="<?php echo esc_url($demo['demo_url']) ?>" title="<?php echo esc_attr($demo['title']) ?>" target="_blank">
                                        <span class="demo__item-image la-lazyload-image" data-background-image="<?php echo esc_attr($demo['preview']) ?>"></span>
                                        <h2><span><?php echo esc_html($demo['title']) ?></span></h2>
                                    </a>
                                </div>

                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

add_action('plugins_loaded', function(){

    $theme = wp_get_theme();

    if(strpos(strtolower($theme->get_template()), 'luxsa') === false){

        add_action( 'admin_notices', function(){
            printf( __( '%1$s"LUXSA Package Demo Data" requires %3$s"LUXSA"%4$s theme to be installed and activated. Please active %3$s"LUXSA"%4$s to continue.%2$s', 'lastudio-demodata' ), '<div class="error"><p>', '</p></div>' ,'<strong>', '</strong>' );
        });

        add_action( 'admin_init', function(){
            deactivate_plugins( plugin_basename( __FILE__ ) );
        });

        return;
    }

    Luxsa_Data_Demo_Plugin_Class::get_instance();
}, 999);
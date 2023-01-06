<?php if ( ! defined( 'ABSPATH' ) ) { die; }

if(!class_exists('Luxsa_MegaMenu_Init')){
    
    class Luxsa_MegaMenu_Init{

        protected $fields = array();

        protected $default_metakey = '';

        public function __construct() {

            $query_args = array(
                'post_type'         => 'elementor_library',
                'orderby'           => 'title',
                'order'             => 'ASC',
                'posts_per_page'    => -1
            );

            $this->default_metakey = '_mm_meta';
            $this->fields = array(
                'icon' => array(
                    'id'    => 'icon',
                    'type'  => 'icon',
                    'class' => 'lasf-mm-icon',
                    'title' => esc_html__('Custom Icon','luxsa')
                ),
                'only_icon' => array(
                    'id'    => 'only_icon',
                    'type'  => 'switcher',
                    'class' => 'lasf-mm-only-icon',
                    'title' => esc_html__("Show Only Icon",'luxsa')
                ),
                'menu_type' => array(
                    'id'    => 'menu_type',
                    'class' => 'lasf-mm-menu-type',
                    'type'  => 'select',
                    'title' => esc_html__('Menu Type','luxsa'),
                    'options' => array(
                        'narrow'      => esc_html__('Narrow','luxsa'),
                        'wide'  => esc_html__('Mega','luxsa')
                    ),
                    'default' => 'narrow',
                    'desc' => esc_html__('If value is Mega, all the sub menu items will be hidden on desktop','luxsa')
                ),
                'force_full_width' => array(
                    'id'    => 'force_full_width',
                    'class' => 'lasf-mm-force-full-width',
                    'type'  => 'switcher',
                    'title' => esc_html__('Force Full Width','luxsa'),
                    'desc' => esc_html__('Set 100% window width for popup','luxsa')
                ),
                'popup_max_width' =>array(
                    'id'     => 'popup_max_width',
                    'class' => 'lasf-mm-popup-max-width',
                    'type'   => 'dimensions',
                    'title'  => esc_html__('Popup Max Width','luxsa'),
                    'height' => false,
                    'units'  => array( 'px' ),
                ),
                'tip_label' => array(
                    'id'        => 'tip_label',
                    'class'     => 'lasf-mm-tip-label',
                    'type'      => 'text',
                    'title' 	=> esc_html__('Tip Label','luxsa')
                ),
                'tip_color' => array(
                    'id'        => 'tip_color',
                    'class'     => 'lasf-mm-tip-color',
                    'type'      => 'color',
                    'title' 	=> esc_html__('Tip Color','luxsa')
                ),
                'tip_background_color' => array(
                    'id'        => 'tip_background_color',
                    'class'     => 'lasf-mm-tip-background-color',
                    'type'      => 'color',
                    'title' 	=> esc_html__('Tip Background','luxsa')
                )
            );

            $this->load_hooks();
        }

        private function load_hooks(){
            add_action( 'wp_loaded',                        array( $this, 'load_walker_edit' ), 9);
            add_filter( 'wp_setup_nav_menu_item',           array( $this, 'setup_nav_menu_item' ));
            add_action( 'wp_nav_menu_item_custom_fields',   array( $this, 'add_megamu_field_to_menu_item' ), 10, 4);
            add_action( 'wp_update_nav_menu_item',          array( $this, 'update_nav_menu_item' ), 10, 3);
            add_filter( 'nav_menu_item_title',              array( $this, 'add_icon_to_menu_item' ),10, 4);
            add_action( 'elementor/init',                   array( $this, 'elementor_support'));
            add_filter( 'pre_get_posts',                    array( $this, 'fix_elementor_pre_get_posts'), 100 );
            add_filter( 'elementor/document/urls/wp_preview', array( $this, 'fix_elementor_preview' ), 10, 2 );
            add_action( 'elementor/document/after_save',    array( $this, 'after_save') );
        }

        public function elementor_support(){
            add_post_type_support( 'nav_menu_item', 'elementor' );
        }

        public function after_save( $instance ){
            $post = $instance->get_post();
            $post_id = $instance->get_main_id();
            $old_content = $post->post_content;
            wp_update_post([
                'ID' => $post_id,
                'post_content' => $old_content,
            ]);
        }

        public function fix_elementor_pre_get_posts( $query ){
            if ( is_admin() || ! $query->is_main_query() ) {
                return;
            }
            if((isset($_GET['elementor-preview']) || isset($_GET['preview_id'])) && current_user_can('edit_theme_options') ){
                $current_id = 0;
                if(isset($_GET['elementor-preview'])){
                    $current_id = absint($_GET['elementor-preview']);
                }
                if(isset($_GET['preview_id'])){
                    $current_id = absint($_GET['preview_id']);
                }
                if( 'nav_menu_item' == get_post_type($current_id) ) {
                    $query->set('post_type', 'nav_menu_item');
                }
            }
        }

        public function fix_elementor_preview( $url, $instance ){
            if(empty($url)){
                $main_post_id = $instance->get_main_id();
                $preview_link = set_url_scheme( get_permalink( $main_post_id ) );
                $preview_link = add_query_arg(
                    [
                        'preview_id' => $main_post_id,
                        'preview_nonce' => wp_create_nonce( 'post_preview_' . $main_post_id ),
                        'preview' => 'true'
                    ],
                    $preview_link
                );
                $url = $preview_link;
            }
            return $url;
        }

        public function load_walker_edit() {
            global $wp_version;
            if(version_compare($wp_version, '5.4', '<')){
                add_filter( 'wp_edit_nav_menu_walker', array( $this, 'detect_edit_nav_menu_walker' ), 99 );
            }
        }

        public function detect_edit_nav_menu_walker( $walker ) {
            require_once Luxsa_Theme_Class::$template_dir_path . '/framework/classes/class-megamenu-walker-edit.php';
            $walker = 'Luxsa_MegaMenu_Walker_Edit';
            return $walker;
        }

        public function setup_nav_menu_item($menu_item){
            $meta_value = luxsa_get_post_meta($menu_item->ID, '', $this->default_metakey, true);
            foreach ( $this->fields as $key => $value ){
                $menu_item->$key = isset($meta_value[$key]) ? $meta_value[$key] : '';
            }
            return $menu_item;
        }

        public function update_nav_menu_item( $menu_id, $menu_item_db_id, $menu_item_args ) {
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                return;
            }
            check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

            $key = $this->default_metakey;

            if ( ! empty( $_POST[$key][$menu_item_db_id] ) ) {
                $value = $_POST[$key][$menu_item_db_id];
                if(isset($value['popup_max_width']['width'])){
                    $value['popup_max_width'] = $value['popup_max_width']['width'];
                }
            }
            else {
                $value = null;
            }

            if(!empty($value)){
                update_post_meta( $menu_item_db_id, $key, $value );
            }
            else {
                delete_post_meta( $menu_item_db_id, $key );
            }
        }

        public function add_megamu_field_to_menu_item( $id, $item, $depth, $args ) {
            if(class_exists('LASF', false)){
            ?>
            <div class="lastudio-megamenu-settings description-wide la-content">
                <h3><?php esc_html_e('Extra Settings','luxsa');?></h3>
                <div class="lastudio-megamenu-custom-fields">
                    <?php
                    foreach ( $this->fields as $key => $field ) {
                        $unique     = $this->default_metakey . '['.$item->ID.']';
                        $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                        $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';

                        $field['name'] = $unique. '[' . $elem_id . ']';
                        $field['id'] = $elem_id . '_' . $item->ID;
                        $elem_value =  isset($item->$key) ? $item->$key : $default;

                        if($key == 'popup_max_width'){
                            if(!isset($elem_value['width'])){
                                $elem_value = array(
                                    'width' => absint($elem_value),
                                    'unit'  => 'px'
                                );
                            }
                        }
                        LASF::field( $field, $elem_value, $unique );
                    }
                    ?>
                </div>
            </div><?php
            }
        }

        public function add_icon_to_menu_item($output, $item, $args, $depth){
            if ( !is_a( $args->walker, 'Luxsa_MegaMenu_Walker' ) && $item->icon){
                $icon_class = 'mm-icon ' . $item->icon;
                $icon = "<i class=\"".esc_attr($icon_class)."\"></i>";
                $output = $icon . $output;
            }
            return $output;
        }
    }
}
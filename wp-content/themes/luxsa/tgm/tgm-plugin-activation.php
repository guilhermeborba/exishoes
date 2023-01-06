<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'tgmpa_register', 'luxsa_register_required_plugins' );

if(!function_exists('lasf_get_plugin_source')){
    function lasf_get_plugin_source( $new, $initial, $plugin_name, $type = 'source'){
        if(isset($new[$plugin_name], $new[$plugin_name][$type]) && version_compare($initial[$plugin_name]['version'], $new[$plugin_name]['version']) < 0 ){
            return $new[$plugin_name][$type];
        }
        else{
            return $initial[$plugin_name][$type];
        }
    }
}

if(!function_exists('luxsa_register_required_plugins')){

	function luxsa_register_required_plugins() {

        $initial_required = array(
            'lastudio' => array(
                'source'    => 'https://la-studioweb.com/file-resouces/shared/plugins/lastudio_v2.1.3.zip',
                'version'   => '2.1.3'
            ),
            'lastudio-header-builders' => array(
                'source'    => 'https://la-studioweb.com/file-resouces/shared/plugins/lastudio-header-builders_v1.1.9.3.zip',
                'version'   => '1.1.9.3'
            ),
            'revslider' => array(
                'source'    => 'https://la-studioweb.com/file-resouces/shared/plugins/revslider_v6.5.2.zip',
                'version'   => '6.5.2'
            ),
            'luxsa-demo-data' => array(
                'source'    => 'https://la-studioweb.com/file-resouces/luxsa/plugins/luxsa-demo-data_v1.0.0.zip',
                'version'   => '1.0.0'
            )
        );

        $from_option = get_option('luxsa_required_plugins_list', $initial_required);

		$plugins = array();

		$plugins[] = array(
			'name'					=> esc_html_x('LaStudio Core', 'admin-view', 'luxsa'),
			'slug'					=> 'lastudio',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio'),
            'required'				=> true,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio', 'version')
		);

		$plugins[] = array(
			'name'					=> esc_html_x('LaStudio Header Builder', 'admin-view', 'luxsa'),
			'slug'					=> 'lastudio-header-builders',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio-header-builders'),
            'required'				=> true,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'lastudio-header-builders', 'version')
		);

        $plugins[] = array(
            'name' 					=> esc_html_x('Elementor', 'admin-view', 'luxsa'),
            'slug' 					=> 'elementor',
            'required' 				=> true,
            'version'				=> '3.2.4'
        );

		$plugins[] = array(
			'name'     				=> esc_html_x('WooCommerce', 'admin-view', 'luxsa'),
			'slug'     				=> 'woocommerce',
			'version'				=> '5.4.1',
			'required' 				=> false
		);
        
        $plugins[] = array(
			'name'     				=> esc_html_x('Luxsa Package Demo Data', 'admin-view', 'luxsa'),
			'slug'					=> 'luxsa-demo-data',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'luxsa-demo-data'),
            'required'				=> false,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'luxsa-demo-data', 'version')
		);

		$plugins[] = array(
			'name'     				=> esc_html_x('Envato Market', 'admin-view', 'luxsa'),
			'slug'     				=> 'envato-market',
			'source'   				=> 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			'required' 				=> false,
			'version' 				=> '2.0.6'
		);

		$plugins[] = array(
			'name' 					=> esc_html_x('Contact Form 7', 'admin-view', 'luxsa'),
			'slug' 					=> 'contact-form-7',
			'required' 				=> false
		);

		$plugins[] = array(
			'name'					=> esc_html_x('Slider Revolution', 'admin-view', 'luxsa'),
			'slug'					=> 'revslider',
            'source'				=> lasf_get_plugin_source($from_option, $initial_required, 'revslider'),
            'required'				=> false,
            'version'				=> lasf_get_plugin_source($from_option, $initial_required, 'revslider', 'version')
		);

		$config = array(
			'id'           				=> 'luxsa',
			'default_path' 				=> '',
			'menu'         				=> 'tgmpa-install-plugins',
			'has_notices'  				=> true,
			'dismissable'  				=> true,
			'dismiss_msg'  				=> '',
			'is_automatic' 				=> false,
			'message'      				=> ''
		);

		tgmpa( $plugins, $config );

	}

}

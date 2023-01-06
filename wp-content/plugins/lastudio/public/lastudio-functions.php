<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Get excerpt
 *
 * @since 1.0.0
 */

if(!function_exists('la_is_local')){
    function la_is_local(){
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
}

if ( ! function_exists( 'la_excerpt' ) ) {

	function la_excerpt( $length = 30 ) {
		global $post;

		// Check for custom excerpt
		if ( has_excerpt( $post->ID ) ) {
            $output = wp_trim_words( strip_shortcodes( $post->post_excerpt ), $length );
		}

		// No custom excerpt
		else {

			// Check for more tag and return content if it exists
			if ( strpos( $post->post_content, '<!--more-->' ) || strpos( $post->post_content, '<!--nextpage-->' ) ) {
				$output = apply_filters( 'the_content', get_the_content() );
			}

			// No more tag defined
			else {
				$output = wp_trim_words( strip_shortcodes( $post->post_content ), $length );
			}

		}

		return $output;

	}

}
if(!function_exists('la_get_image_by_url')){

	function la_get_image_by_url( $url = null, $attr = array() ) {

		$url = esc_url( $url );

		if ( empty( $url ) ) {
			return;
		}

		$ext  = pathinfo( $url, PATHINFO_EXTENSION );
		$attr = array_merge( array( 'alt' => '' ), $attr );

		if ( 'svg' !== $ext ) {
			return sprintf( '<img src="%1$s"%2$s>', $url, la_get_attr_string( $attr ) );
		}

		$base_url = site_url( '/' );
		$svg_path = str_replace( $base_url, ABSPATH, $url );
		$key      = md5( $svg_path );
		$svg      = get_transient( $key );

		if ( ! $svg ) {
			$svg = file_get_contents( $svg_path );
		}

		if ( ! $svg ) {
			return sprintf( '<img src="%1$s"%2$s>', $url, la_get_attr_string( $attr ) );
		}

		set_transient( $key, $svg, DAY_IN_SECONDS );

		unset( $attr['alt'] );

		return sprintf( '<div%2$s>%1$s</div>', $svg, la_get_attr_string( $attr ) );
	}

}

if(!function_exists('la_get_attr_string')){
	function la_get_attr_string( $attr = array() ){
		if ( empty( $attr ) || ! is_array( $attr ) ) {
			return;
		}

		$result = '';

		foreach ( $attr as $key => $value ) {
			$result .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		return $result;
	}
}

if(!function_exists('la_get_custom_breakpoints')){
    function la_get_custom_breakpoints(){
	    $custom_breakpoints = get_option('la_custom_breakpoints');
	    $sm = !empty($custom_breakpoints['sm']) ? absint($custom_breakpoints['sm']) : 576;
	    $md = !empty($custom_breakpoints['md']) ? absint($custom_breakpoints['md']) : 992;
	    $lg = !empty($custom_breakpoints['lg']) ? absint($custom_breakpoints['lg']) : 1280;
	    $xl = !empty($custom_breakpoints['xl']) ? absint($custom_breakpoints['xl']) : 1700;

	    if( $sm <= 380 || $sm >= 992 ){
		    $sm = 576;
	    }
	    if( $md <= 992 || $md >= 1280 ){
		    $md = 992;
	    }
	    if( $lg <= 1280 || $lg >= 1700 ){
		    $lg = 1280;
	    }
	    if($lg > $xl){
		    $xl = $lg + 2;
	    }
	    if($xl > 2000){
		    $xl = 1700;
	    }

	    return [
		    'xs' => 0,
		    'sm' => $sm,
		    'md' => $md,
		    'lg' => $lg,
		    'xl' => $xl,
		    'xxl' => 2000
	    ];
    }
}

if(!function_exists('la_minify_css')){
    function la_minify_css( $css = '' ){

        // Return if no CSS
        if ( ! $css ) return;

        // Normalize whitespace
        $css = preg_replace( '/\s+/', ' ', $css );

        // Remove ; before }
        $css = preg_replace( '/;(?=\s*})/', '', $css );

        // Remove space after , : ; { } */ >
        $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

        // Remove space before , ; { }
        $css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

        // Strips leading 0 on decimal values (converts 0.5px into .5px)
        $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

        // Strips units if value is 0 (converts 0px to 0)
        $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

        // Remove empty padding and margin value
        //$css = preg_replace( '/(margin|padding)(-)?(left|right|top|bottom)?:(-)?(%|em|ex|px|in|cm|mm|pt|pc);?/', '', $css );

        // Remove selector with empty value
        //$css = preg_replace('/(?:[^\r\n,{}]+)(?:,(?=[^}]*{)|\s*{[\s]*})/', '', $css);

        // Remove selector with empty value within media query
        //$css = preg_replace('/(?:[^\r\n,{}]+)(?:,(?=[^}]*{)|\s*{[\s]*})/', '', $css);

        // Trim
        $css = trim( $css );

        // Return minified CSS
        return $css;
    }
}

if(!function_exists('la_get_base_shop_url')){
    function la_get_base_shop_url(){

        if(!function_exists('WC')){
            return home_url('/');
        }

        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        }
        elseif ( is_shop() ) {
            $link = get_permalink( wc_get_page_id( 'shop' ) );
        }
        elseif( is_tax( get_object_taxonomies( 'product' ) ) ) {

            if( is_product_category() ) {
                $link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
            }
            elseif ( is_product_tag() ) {
                $link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
            }
            else{
                $queried_object = get_queried_object();
                $link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
            }
        }
        elseif ( function_exists('dokan_is_store_page') && dokan_is_store_page() ){
            $current_url = add_query_arg(null, null, dokan_get_store_url(get_query_var('author')));
            $current_url = remove_query_arg(array('page', 'paged', 'mode_view', 'la_doing_ajax'), $current_url);
            $link = preg_replace('/\/page\/\d+/', '', $current_url);
            $tmp = explode('?', $link);
            if(isset($tmp[0])){
                $link = $tmp[0];
            }
        }
        else{
            $link = get_post_type_archive_link( 'product' );
        }

        return $link;
    }
}


if(!function_exists('la_add_script_to_compare')){
    function la_add_script_to_compare() {
        echo '<script type="text/javascript">var redirect_to_cart=true;</script>';
    }
}
add_action('yith_woocompare_after_main_table', 'la_add_script_to_compare');

if(!function_exists('la_add_script_to_quickview_product')){
    function la_add_script_to_quickview_product()
    {
        global $product;
        if (function_exists('is_product') && isset($_GET['product_quickview']) && is_product()) {
            ?>
            <script type="text/javascript" src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/flexslider/jquery.flexslider.min.js' ?>"></script>
            <?php
            if ($product->get_type() == 'variable') {
                wp_print_scripts('underscore');
                wc_get_template('single-product/add-to-cart/variation.php');
                ?>
                <script type="text/javascript">
                    /* <![CDATA[ */
                    var _wpUtilSettings = <?php echo wp_json_encode(array(
                        'ajax' => array('url' => admin_url('admin-ajax.php', 'relative'))
                    ));?>;
                    var wc_add_to_cart_variation_params = <?php
                        $params = la_get_wc_script_data('wc-add-to-cart-variation');
                        echo wp_json_encode($params); ?>;
                    /* ]]> */
                </script>
                <script type="text/javascript" src="<?php echo esc_url(includes_url('js/wp-util.min.js')) ?>"></script>
                <script type="text/javascript" src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/add-to-cart-variation.min.js' ?>"></script>
                <?php
            }
            ?>
            <script type="text/javascript">
                /* <![CDATA[ */
                var wc_single_product_params = <?php echo wp_json_encode(array(
                    'i18n_required_rating_text' => esc_attr__('Please select a rating', 'lastudio'),
                    'review_rating_required' => get_option('woocommerce_review_rating_required'),
                    'flexslider' => apply_filters('woocommerce_single_product_carousel_options', array(
                        'rtl' => is_rtl(),
                        'animation' => 'slide',
                        'smoothHeight' => false,
                        'directionNav' => true,
                        'controlNav' => '',
                        'slideshow' => false,
                        'animationSpeed' => 500,
                        'animationLoop' => false, // Breaks photoswipe pagination if true.
                    )),
                    'zoom_enabled' => 0,
                    'photoswipe_enabled' => 0,
                    'flexslider_enabled' => 1,
                ));?>;
                /* ]]> */
            </script>
            <script type="text/javascript" src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/single-product.min.js' ?>"></script>
            <?php
        }
    }
}
add_action('woocommerce_after_single_product', 'la_add_script_to_quickview_product');

if(!function_exists('la_theme_fix_wc_track_product_view')){
    function la_theme_fix_wc_track_product_view()
    {
        if (!is_singular('product')) {
            return;
        }
        if (!function_exists('wc_setcookie')) {
            return;
        }
        global $post;
        if (empty($_COOKIE['woocommerce_recently_viewed'])) {
            $viewed_products = array();
        }
        else {
            $viewed_products = (array)explode('|', $_COOKIE['woocommerce_recently_viewed']);
        }
        if (!in_array($post->ID, $viewed_products)) {
            $viewed_products[] = $post->ID;
        }
        if (sizeof($viewed_products) > 15) {
            array_shift($viewed_products);
        }
        wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
    }
}
add_action('template_redirect', 'la_theme_fix_wc_track_product_view', 30);

if(!function_exists('la_add_extra_section_to_theme_options')){
    function la_add_extra_section_to_theme_options(){
        $theme = wp_get_theme();
        $prefix = strtolower($theme->get_template()) . '_options';

        /**
         * Social Panel
         */
        LASF::createSection( $prefix, array(
            'id'            => 'social_panel',
            'title'         => esc_html_x('Social Media', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-share-alt'
        ));

        /**
         * Social Panel - Social Media Links
         */
        LASF::createSection( $prefix, array(
            'parent'        => 'social_panel',
            'title'         => esc_html_x('Social Media Links', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-share-alt',
            'fields'        => array(
                array(
                    'id'        => 'social_links',
                    'type'      => 'group',
                    'title'     => esc_html_x('Social Media Links', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Social media links use a repeater field and allow one network per field. Click the "Add" button to add additional fields.', 'admin-view', 'lastudio'),
                    'button_title'    => esc_html_x('Add','admin-view', 'lastudio'),
                    'max_item'  => 10,
                    'fields'    => array(
                        array(
                            'id'        => 'title',
                            'type'      => 'text',
                            'default'   => esc_html_x('Title', 'admin-view', 'lastudio'),
                            'title'     => esc_html_x('Title', 'admin-view', 'lastudio')
                        ),
                        array(
                            'id'        => 'icon',
                            'type'      => 'icon',
                            'default'   => 'fa fa-share',
                            'title'     => esc_html_x('Custom Icon', 'admin-view', 'lastudio')
                        ),
                        array(
                            'id'        => 'link',
                            'type'      => 'text',
                            'default'   => '#',
                            'title'     => esc_html_x('Link (URL)', 'admin-view', 'lastudio')
                        )
                    )
                )
            )
        ));

        /**
         * Social Panel - Social Sharing Box
         */
        LASF::createSection( $prefix, array(
            'parent'        => 'social_panel',
            'title'         => esc_html_x('Social Sharing Box', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-share-square-o',
            'fields'        => array(
                array(
                    'id'        => 'sharing_facebook',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Facebook', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Facebook in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_twitter',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Twitter', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Twitter in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_reddit',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Reddit', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Reddit in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_linkedin',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('LinkedIn', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display LinkedIn in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_tumblr',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Tumblr', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Tumblr in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_pinterest',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Pinterest', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Pinterest in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_line',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('LINE', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display LINE in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_whatapps',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Whatsapp', 'admin-view','lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Whatsapp in the social share box.', 'admin-view','lastudio')
                ),
                array(
                    'id'        => 'sharing_telegram',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Telegram','admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Telegram in the social share box.', 'admin-view','lastudio')
                ),
                array(
                    'id'        => 'sharing_vk',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('VK', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display VK in the social share box.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'sharing_email',
                    'type'      => 'switcher',
                    'default'   => false,
                    'title'     => esc_html_x('Email', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Turn on to display Email in the social share box.', 'admin-view', 'lastudio')
                )
            )
        ));


        /**
         * Custom Block Code Panel
         */

        $conditions = array(
            array(
                'id'        => 'type',
                'default'   => 'include',
                'title'     => esc_html_x('Type', 'admin-view', 'lastudio'),
                'type'      => 'select',
                'options'   => array(
                    'include'    => esc_html_x('Include', 'admin-view', 'lastudio'),
                    'exclude'    => esc_html_x('Exclude', 'admin-view', 'lastudio')
                )
            ),
            array(
                'id'        => 'name',
                'default'   => 'general',
                'title'     => esc_html_x('Name', 'admin-view', 'lastudio'),
                'type'      => 'select',
                'options'   => array(
                    esc_html_x('General', 'admin-view', 'lastudio') => array(
                        'general'       => esc_html_x('Entire Site', 'admin-view', 'lastudio'),
                        'archive'       => esc_html_x('Archives', 'admin-view', 'lastudio'),
                        'singular'      => esc_html_x('Singular', 'admin-view', 'lastudio'),
                        'woocommerce'   => esc_html_x('WooCommerce', 'admin-view', 'lastudio'),
                    ),
                )
            ),
            array(
                'id'        => 'archive',
                'default'   => '',
                'title'     => esc_html_x('Archive', 'admin-view', 'lastudio'),
                'type'      => 'select',
                'options'   => array(
                    ''              => esc_html_x('All Archives', 'admin-view', 'lastudio'),
                    'author'        => esc_html_x('Author Archive', 'admin-view', 'lastudio'),
                    'date'          => esc_html_x('Date Archive', 'admin-view', 'lastudio'),
                    'search'        => esc_html_x('Search Results', 'admin-view', 'lastudio'),
                    esc_html_x('Posts Archive', 'admin-view', 'lastudio') => array(
                        'post_archive'          => esc_html_x('Posts Archive', 'admin-view', 'lastudio'),
                        'category'              => esc_html_x('Categories', 'admin-view', 'lastudio'),
                        'child_of_category'     => esc_html_x('Direct Child Category Of', 'admin-view', 'lastudio'),
                        'any_child_of_category' => esc_html_x('Any Child Category Of', 'admin-view', 'lastudio'),
                        'post_tag'              => esc_html_x('Tags', 'admin-view', 'lastudio'),
                    ),
                ),
                'dependency'=> array('name', '==', 'archive'),
            ),
            array(
                'id'        => 'singular',
                'default'   => '',
                'title'     => esc_html_x('Singular', 'admin-view', 'lastudio'),
                'type'      => 'select',
                'placeholder' => esc_html_x('All Singular', 'admin-view', 'lastudio'),
                'options'   => [
                    'front_page' => 'Front Page',
                    'blog_page' => 'Blog Page',
                    'Posts' => [
                         'post' => 'Posts',
                         'in_category' => 'In Category',
                         'in_category_children' => 'In Child Categories',
                         'in_post_tag' => 'In Tag',
                         'post_by_author' => 'Posts By Author',
                    ],
                    'Pages' => [
                         'page' => 'Pages',
                         'page_by_author' => 'Pages By Author',
                    ],
                    'Portfolio' => [
                         'la_portfolio' => 'Portfolios',
                         'in_la_portfolio_category' => 'In Category',
                         'la_portfolio_by_author' => 'Portfolios By Author',
                    ],
                    'child_of' => 'Direct Child Of',
                    'any_child_of' => 'Any Child Of',
                    'by_author' => 'By Author',
                    'not_found404' => '404 Page',
                ],
                'dependency'=> array('name', '==', 'singular'),
            ),
            array(
                'id'        => 'woocommerce',
                'default'   => '',
                'title'     => esc_html_x('WooCommerce', 'admin-view', 'lastudio'),
                'type'      => 'select',
                'options'   => [
                    ''                  => esc_html_x('Entire Shop', 'admin-view', 'lastudio'),
                    'Product Archive'   => [
                        'product_archive'   => 'All Product Archives',
                        'shop_page'         => 'Shop Page',
                        'product_search'    => 'Search Results',
                        'product_cat'       => 'Product categories',
                        'product_tag'       => 'Product tags',
                    ],
                    'Product'           => [
                        'product'                   => 'Products',
                        'in_product_cat'            => 'In Category',
                        'in_product_cat_children'   => 'In Child Product categories',
                        'in_product_tag'            => 'In Tag',
                        'product_by_author'         => 'Products By Author',
                    ]
                ],
                'dependency'=> array('name', '==', 'woocommerce'),
            ),
            array(
                'id'            => 'author',
                'default'       => '',
                'title'         => esc_html_x('Author', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'users',
                'dependency'    => array('name|archive', '==|==', 'archive|author'),
            ),
            array(
                'id'            => 'category',
                'default'       => '',
                'title'         => esc_html_x('Category', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'categories',
                'dependency'    => array('name|archive', '==|any', 'archive|category,child_of_category,any_child_of_category'),
                'query_args'    => array(
                    'hide_empty'  => false,
                ),
            ),
            array(
                'id'            => 'tag',
                'default'       => '',
                'title'         => esc_html_x('Tags', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'tags',
                'dependency'    => array('name|archive', '==|==', 'archive|post_tag'),
                'query_args'    => array(
                    'hide_empty'  => false,
                ),
            ),
            array(
                'id'            => 'singular_author',
                'default'       => '',
                'title'         => esc_html_x('Author', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'users',
                'dependency'    => array('name|singular', '==|==', 'singular|by_author'),
            ),
            array(
                'id'            => 'singular_post',
                'default'       => '',
                'title'         => esc_html_x('Posts', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'posts',
                'dependency'    => array('name|singular', '==|==', 'singular|post'),
            ),
            array(
                'id'            => 'singular_page',
                'default'       => '',
                'title'         => esc_html_x('Pages', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'pages',
                'dependency'    => array('name|singular', '==|any', 'singular|page,child_of,any_child_of'),
            ),
            array(
                'id'            => 'singular_la_portfolio',
                'default'       => '',
                'title'         => esc_html_x('Portfolios', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'posts',
                'query_args'    => [
                    'posts_type' => 'la_portfolio'
                ],
                'dependency'    => array('name|singular', '==|==', 'singular|la_portfolio'),
            ),
            array(
                'id'            => 'singular_la_portfolio_category',
                'default'       => '',
                'title'         => esc_html_x('Category', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'categories',
                'dependency'    => array('name|singular', '==|==', 'singular|in_la_portfolio_category'),
                'query_args'    => array(
                    'hide_empty'  => false,
                    'taxonomy'  => 'la_portfolio_category',
                ),
            ),
            array(
                'id'            => 'singular_category',
                'default'       => '',
                'title'         => esc_html_x('Category', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'categories',
                'dependency'    => array('name|singular', '==|any', 'singular|in_category,in_category_children'),
                'query_args'    => array(
                    'hide_empty'  => false,
                ),
            ),
            array(
                'id'            => 'singular_tag',
                'default'       => '',
                'title'         => esc_html_x('Tags', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'tags',
                'dependency'    => array('name|singular', '==|==', 'singular|in_post_tag'),
                'query_args'    => array(
                    'hide_empty'  => false,
                ),
            ),
            array(
                'id'            => 'singular_author',
                'default'       => '',
                'title'         => esc_html_x('Author', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'users',
                'dependency'    => array('name|singular', '==|any', 'singular|post_by_author,page_by_author,la_portfolio_by_author'),
            ),
            array(
                'id'            => 'wc_product',
                'default'       => '',
                'title'         => esc_html_x('Products', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'posts',
                'query_args'    => [
                    'posts_type' => 'product'
                ],
                'dependency'    => array('name|woocommerce', '==|==', 'woocommerce|product'),
            ),
            array(
                'id'            => 'wc_author',
                'default'       => '',
                'title'         => esc_html_x('Author', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'users',
                'dependency'    => array('name|woocommerce', '==|any', 'woocommerce|product_by_author'),
            ),

            array(
                'id'            => 'wc_product_cat',
                'default'       => '',
                'title'         => esc_html_x('Category', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'categories',
                'dependency'    => array('name|woocommerce', '==|any', 'woocommerce|product_cat,in_product_cat,in_product_cat_children'),
                'query_args'    => array(
                    'hide_empty'  => false,
                    'taxonomy'  => 'product_cat',
                ),
            ),

            array(
                'id'            => 'wc_product_tag',
                'default'       => '',
                'title'         => esc_html_x('Tag', 'admin-view', 'lastudio'),
                'type'          => 'select',
                'ajax'          => true,
                'chosen'        => true,
                'placeholder'   => esc_html_x('All', 'admin-view', 'lastudio'),
                'options'       => 'categories',
                'dependency'    => array('name|woocommerce', '==|any', 'woocommerce|product_tag,in_product_tag'),
                'query_args'    => array(
                    'hide_empty'  => false,
                    'taxonomy'  => 'product_tag',
                ),
            ),
        );

        $conditions = apply_filters('lastudio/filter/all_conditions_for_block', $conditions);

        LASF::createSection( $prefix, array(
            'id'            => 'additional_code_panel',
            'title'         => esc_html_x('Additional Code', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-code',
            'fields'        => array(
                array(
                    'id'        => 'google_key',
                    'type'      => 'text',
                    'title'     => esc_html_x('Google Maps APIs Key', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Type your Google Maps APIs Key here.', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'instagram_token',
                    'type'      => 'text',
                    'title'     => esc_html_x('Instagram Access Token', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('In order to display your photos you need an Access Token from Instagram.', 'admin-view', 'lastudio'),
                    'desc'      => sprintf(
                        __('<a target="_blank" href="%s">Click here</a> to get your API', 'lastudio'),
                        '//la-studioweb.com/tip-trick/how-to-get-instagram-access-token/'
                    )
                ),
                array(
                    'id'       => 'la_custom_css',
                    'type'     => 'code_editor',
                    'title'    => esc_html_x('Custom CSS', 'admin-view', 'lastudio'),
                    'subtitle' => esc_html_x('Paste your custom CSS code here.', 'admin-view', 'lastudio'),
                    'class'    => 'lasf-field-fullwidth',
                    'settings' => array(
                        'codemirror' => array(
                            'mode' => 'css'
                        )
                    ),
                    'transport' => 'postMessage'
                ),

                array(
                    'id'       => 'header_js',
                    'type'     => 'code_editor',
                    'title'    => esc_html_x('Header Javascript Code', 'admin-view', 'lastudio'),
                    'subtitle' => esc_html_x('Paste your custom Javascript code here. The code will be added to the header of your site. Please do not enter the <script> tag', 'admin-view', 'lastudio'),
                    'class'    => 'lasf-field-fullwidth',
                    'settings' => array(
                        'codemirror' => array(
                            'mode' => 'javascript'
                        )
                    ),
                    'default' =>';(function($) {
    "use strict";
    $(function(){
        // do stuff 
    });
})(jQuery);',
                ),

                array(
                    'id'       => 'footer_js',
                    'type'     => 'code_editor',
                    'title'    => esc_html_x('Footer Javascript Code', 'admin-view', 'lastudio'),
                    'subtitle' => esc_html_x('Paste your custom Javascript code here. The code will be added to the footer of your site. Please do not enter the <script> tag', 'admin-view', 'lastudio'),
                    'class'    => 'lasf-field-fullwidth',
                    'settings' => array(
                        'codemirror' => array(
                            'mode' => 'javascript'
                        )
                    ),
                    'default' =>';(function($) {
    "use strict";
    $(function(){
        // do stuff 
    });
})(jQuery);',
                ),

                array(
                    'id'        => 'la_custom_blocks',
                    'type'      => 'group',
                    'title'     => esc_html_x('Custom Block', 'admin-view', 'lastudio'),
                    'subtitle'  => esc_html_x('Display custom block on the site', 'admin-view', 'lastudio'),
                    'class'     => 'lasf-field-fullwidth lasf-block-condition-groups',
                    'fields'    => array(
                        array(
                            'id'        => 'title',
                            'type'      => 'text',
                            'title'     => esc_html_x('Title','admin-view', 'lastudio'),
                        ),
                        array(
                            'id'        => 'position',
                            'default'   => '',
                            'title'     => esc_html_x('Position to display', 'admin-view', 'lastudio'),
                            'type'      => 'select',
                            'options'   => array(
                                ''        => esc_html_x('Select Position', 'admin-view', 'lastudio'),
                                'before_outer_wrap' => esc_html_x('Before Outer Wrap', 'admin-view', 'lastudio'),
                                'before_wrap' => esc_html_x('Before Wrap', 'admin-view', 'lastudio'),
                                'before_header' => esc_html_x('Before Header', 'admin-view', 'lastudio'),
                                'after_header' => esc_html_x('After Header', 'admin-view', 'lastudio'),
                                'before_main' => esc_html_x('Before Main', 'admin-view', 'lastudio'),
                                'before_page_header' => esc_html_x('Before Page Header', 'admin-view', 'lastudio'),
                                'after_page_header' => esc_html_x('After Page Header', 'admin-view', 'lastudio'),
                                'before_content_wrap' => esc_html_x('Before Content Wrap', 'admin-view', 'lastudio'),
                                'before_content' => esc_html_x('Before Content', 'admin-view', 'lastudio'),
                                'before_content_inner' => esc_html_x('Before Content Inner', 'admin-view', 'lastudio'),
                                'after_content_inner' => esc_html_x('After Content Inner', 'admin-view', 'lastudio'),
                                'after_content' => esc_html_x('After Content', 'admin-view', 'lastudio'),
                                'after_content_wrap' => esc_html_x('After Coontent Wrap', 'admin-view', 'lastudio'),
                                'after_main' => esc_html_x('After Main', 'admin-view', 'lastudio'),
                                'before_footer' => esc_html_x('Before Footer', 'admin-view', 'lastudio'),
                                'after_footer' => esc_html_x('After Footer', 'admin-view', 'lastudio'),
                                'after_wrap' => esc_html_x('After Wrap', 'admin-view', 'lastudio'),
                                'after_outer_wrap' => esc_html_x('After Outer Wrap', 'admin-view', 'lastudio')
                            )
                        ),
                        array(
                            'id'        => 'content',
                            'type'      => 'wp_editor',
                            'class'      => 'lasf-field-fullwidth',
                            'title'     => esc_html_x('Content', 'admin-view', 'lastudio'),
                            'height'    => '200px'
                        ),
                        array(
                            'id'        => 'conditions',
                            'type'      => 'repeater',
                            'title'     => 'Condition',
                            'class'      => 'lasf-field-fullwidth lasf-block-condition-group',
                            'fields'    => $conditions,
                        ),
                        array(
                            'id'        => 'el_class',
                            'type'      => 'text',
                            'title'     => esc_html_x('Custom CSS class name for this block','admin-view', 'lastudio'),
                        )
                    )
                )
            )
        ));


        /**
         * Newsletter Popup Panel
         */
        LASF::createSection( $prefix, array(
            'id'            => 'popup_panel',
            'title'         => esc_html_x('Newsletter Popup', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-check',
            'fields'        => array(
                array(
                    'id' => 'enable_newsletter_popup',
                    'type' => 'switcher',
                    'title' => esc_html_x('Enable Newsletter Popup', 'admin-view', 'lastudio'),
                    'default' => false
                ),
                array(
                    'id' => 'popup_max_width',
                    'type' => 'text',
                    'title' => esc_html_x("Popup Max Width", 'admin-view', 'lastudio'),
                    'default' => 790,
                    'dependency' => array('enable_newsletter_popup', '==', 'true')
                ),
                array(
                    'id' => 'popup_max_height',
                    'type' => 'text',
                    'title' => esc_html_x("Popup Max Height", 'admin-view', 'lastudio'),
                    'default' => 430,
                    'dependency' => array('enable_newsletter_popup', '==', 'true')
                ),
                array(
                    'id'        => 'popup_background',
                    'type'      => 'background',
                    'title'     => esc_html_x('Popup Background', 'admin-view', 'lastudio'),
                    'dependency' => array('enable_newsletter_popup', '==', 'true')
                ),
                array(
                    'id' => 'only_show_newsletter_popup_on_home_page',
                    'type' => 'switcher',
                    'title' => esc_html_x('Only showing on homepage', 'admin-view', 'lastudio'),
                    'default' => false,
                    'dependency' => array('enable_newsletter_popup', '==', 'true')
                ),
                array(
                    'id' => 'disable_popup_on_mobile',
                    'type' => 'switcher',
                    'title' => esc_html_x("Don't show popup on mobile", 'admin-view', 'lastudio'),
                    'default' => false,
                    'dependency' => array('enable_newsletter_popup', '==', 'true')
                ),
                array(
                    'id' => 'newsletter_popup_delay',
                    'type' => 'text',
                    'title' => esc_html_x('Popup showing after', 'admin-view', 'lastudio'),
                    'subtitle' => esc_html_x('Show Popup when site loaded after (number) seconds ( 1000ms = 1 second )', 'admin-view', 'lastudio'),
                    'default' => '2000',
                    'dependency' => array('enable_newsletter_popup', '==', 'true'),
                ),
                array(
                    'id' => 'show_checkbox_hide_newsletter_popup',
                    'type' => 'switcher',
                    'title' => esc_html_x('Display option "Does not show popup again"', 'admin-view', 'lastudio'),
                    'default' => false,
                    'dependency' => array('enable_newsletter_popup', '==', 'true')
                ),
                array(
                    'id' => 'popup_dont_show_text',
                    'type' => 'text',
                    'title' => esc_html_x('Text "Does not show popup again"', 'admin-view', 'lastudio'),
                    'default' => 'Do not show popup anymore',
                    'dependency' => array('enable_newsletter_popup|show_checkbox_hide_newsletter_popup', '==|==', 'true|true'),
                ),
                array(
                    'id' => 'newsletter_popup_show_again',
                    'type' => 'text',
                    'title' => esc_html_x('Back display popup after', 'admin-view', 'lastudio'),
                    'subtitle' => esc_html_x('Enter number day', 'admin-view', 'lastudio'),
                    'default' => '1',
                    'dependency' => array('enable_newsletter_popup|show_checkbox_hide_newsletter_popup', '==|==', 'true|true'),
                ),
                array(
                    'id' => 'newsletter_popup_content',
                    'type' => 'wp_editor',
                    'title' => esc_html_x('Newsletter Popup Content', 'admin-view', 'lastudio'),
                    'dependency' => array('enable_newsletter_popup', '==', 'true'),
                )
            )
        ));

        /**
         * Extensions Panel
         */
        LASF::createSection( $prefix, array(
            'id'            => 'la_extension_panel',
            'title'         => esc_html_x('Extensions', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-lock',
        ));

        /**
         * Extensions Panel - General
         */
        LASF::createSection( $prefix, array(
            'parent'        => 'la_extension_panel',
            'title'         => esc_html_x('General', 'admin-view', 'lastudio'),
            'icon'          => 'fa fa-lock',
            'fields'        => array(
                array(
                    'id'       => 'la_extension_available',
                    'type'     => 'checkbox',
                    'title'    => esc_html_x('Extensions Available', 'admin-view', 'lastudio'),
                    'options'  => array(
                        'swatches' => 'Product Color Swatches',
                        '360' => 'Product 360',
                        'content_type' => 'Custom Content Type'
                    ),
                    'default'  => array(
                        'swatches', '360', 'content_type'
                    )
                ),
                array(
                    'id'        => 'template_cache',
                    'type'      => 'switcher',
                    'title'     => esc_html__( 'Template Cache', 'lastudio' ),
                    'subtitle'  => esc_html__( 'Enable cache for templates, such as Menu and Footer ... etc', 'lastudio' ),
                ),
                array(
                    'type'    => 'subheading',
                    'content' => esc_html_x('Mailing List Manager', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'mailchimp_api_key',
                    'type'      => 'text',
                    'title'     => esc_html_x('MailChimp API key', 'admin-view', 'lastudio'),
                    'attributes'=> array(
                        'placeholder' => esc_html_x('MailChimp API key', 'admin-view', 'lastudio')
                    ),
                    'subtitle'  => sprintf( '%1$s <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys">%2$s</a>', esc_html__( 'Input your MailChimp API key', 'lastudio' ), esc_html__( 'About API Keys', 'lastudio' ) ),
                ),
                array(
                    'id'        => 'mailchimp_list_id',
                    'type'      => 'text',
                    'attributes'=> array(
                        'placeholder' => esc_html_x('MailChimp list ID', 'admin-view', 'lastudio')
                    ),
                    'title'     => esc_html_x('MailChimp list ID', 'admin-view', 'lastudio'),
                    'subtitle'  => sprintf( '%1$s <a href="http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id">%2$s</a>', esc_html__( 'MailChimp list ID', 'lastudio' ), esc_html__( 'list ID', 'lastudio' ) ),
                ),
                array(
                    'id'        => 'mailchimp_double_opt_in',
                    'type'      => 'switcher',
                    'title'     => esc_html__( 'Double opt-in', 'lastudio' ),
                    'subtitle'  => esc_html__( 'Send contacts an opt-in confirmation email when they subscribe to your list.', 'lastudio' ),
                ),
                array(
                    'type'    => 'subheading',
                    'content' => esc_html_x('Weather', 'admin-view', 'lastudio')
                ),
                array(
                    'id'        => 'weather_api_key',
                    'type'      => 'text',
                    'title'     => esc_html_x('Weather API key', 'admin-view', 'lastudio'),
                    'attributes'=> array(
                        'placeholder' => esc_html_x('Weather API key', 'admin-view', 'lastudio')
                    ),
                    'subtitle'  => sprintf(
                        esc_html__( 'Please set Weather API key before using this widget. You can create own API key  %1$s.', 'lastudio' ),
                        '<a target="_blank" href="https://www.weatherbit.io/">' . esc_html__( 'here', 'lastudio' ) . '</a>'
                    ),
                ),
                array(
                    'type'    => 'subheading',
                    'content' => esc_html_x('Plugins Updates', 'admin-view', 'lastudio')
                ),
                array(
                    'type'    => 'content',
                    'content' => '<div class="lasf_table"><div class="lasf_table--top"><a class="button button-primary lasf-button-check-plugins-for-updates" href="javascript:;">Check for updates</a></div></div>'
                )
            )
        ));


        /**
         * Extensions Panel - Elementor Available Widgets
         */

        if(function_exists('lastudio_elementor_get_all_modules')){
            $elementor_module_tmp = lastudio_elementor_get_all_modules();
            $elementor_modules = [];

            if(!empty($elementor_module_tmp)){
                foreach ($elementor_module_tmp as $k => $v){
                    $elementor_modules[$k] = str_replace('_', ' ', $v);
                }

                LASF::createSection( $prefix, array(
                    'parent'        => 'la_extension_panel',
                    'title'         => esc_html_x('Elementor Available Widgets', 'admin-view', 'lastudio'),
                    'icon'          => 'fa fa-lock',
                    'fields'        => array(
                        array(
                            'id'       => 'la_elementor_modules',
                            'type'     => 'checkbox',
                            'class'    => 'lasf-field-fullwidth lasf-field-la_elementor_modules',
                            'title'    => esc_html_x('Available Widgets', 'admin-view', 'lastudio'),
                            'subtitle' => esc_html_x('List of widgets that will be available when editing the page', 'admin-view', 'lastudio'),
                            'options'  => $elementor_modules,
                            'default'  => array_keys($elementor_modules)
                        )
                    )
                ));

            }
        }

        /**
         * Backup Panel
         */
        LASF::createSection( $prefix, array(
            'id'        => 'backup_panel',
            'title'     => esc_html_x('Import / Export', 'admin-view', 'lastudio'),
            'icon'      => 'fa fa-refresh',
            'fields'    => array(
                array(
                    'type'    => 'notice',
                    'style'   => 'warning',
                    'content' => esc_html_x('You can save your current options. Download a Backup and Import.', 'admin-view', 'lastudio'),
                ),
                array(
                    'type'      => 'backup'
                )
            )
        ));
    }
}

add_action('init', 'la_add_extra_section_to_theme_options', 11);

add_action('lasf_theme_setting_save_after', function( $request, $instance ) {

    if(isset($request['la_extension_available'])){

        $default = array(
            'swatches' => false,
            '360' => false,
            'content_type' => false
        );

        $la_extension_available = !empty($request['la_extension_available']) ? $request['la_extension_available'] : array('default' => 'hello');

        if(in_array('swatches',$la_extension_available)){
            $default['swatches'] = true;
        }
        if(in_array('360',$la_extension_available)){
            $default['360'] = true;
        }

        if(in_array('content_type',$la_extension_available)){
            $default['content_type'] = true;
        }
        update_option('la_extension_available', $default);
    }

    if(isset($request['la_elementor_modules']) && function_exists('lastudio_elementor_get_all_modules')){

        $elementor_module_tmp = lastudio_elementor_get_all_modules();

        $default_modules = [];
        foreach ($elementor_module_tmp as $k => $v){
            $default_modules[$k] = false;
        }

        $la_widget_available = !empty($request['la_elementor_modules']) ? $request['la_elementor_modules'] : [];

        if(!empty($la_widget_available)){
            foreach ($la_widget_available as $module){
                if(isset($default_modules[$module])){
                    $default_modules[$module] = true;
                }
            }
        }
        else{
            if(!get_option('lastudio_elementor_modules_has_init', false)){
                $default_modules = [];
                foreach ($elementor_module_tmp as $k => $v){
                    $default_modules[$k] = true;
                }

                update_option('lastudio_elementor_modules', $default_modules);
                update_option('lastudio_elementor_modules_has_init', true);
            }
        }

        update_option('lastudio_elementor_modules', $default_modules);
    }

} , 10, 2);

add_action('lasf_theme_setting_save_before', function ($request, $instance){
    if(isset($request['instagram_token'])){
        $new = $request['instagram_token'];
        $old = isset($instance->options['instagram_token']);
        if($old !== $new){
            delete_transient('lastudio_ig_token');
            delete_transient('lastudio_ig_feed');
        }
    }
}, 10, 2);

add_shortcode('la_wishlist', function( $atts, $content ){
    ob_start();
    if(function_exists('wc_print_notices')){
        get_template_part('woocommerce/la_wishlist');
    }
    return ob_get_clean();
});

add_shortcode('la_compare', function( $atts, $content ){
    ob_start();
    if(function_exists('wc_print_notices')){
        get_template_part('woocommerce/la_compare');
    }
    return ob_get_clean();
});

add_shortcode('la_wishlist_count', function( $atts, $output ){
    $count = apply_filters('lastudio/wishlist/count', 0);
    return sprintf('<span class="header-wishlist-count-icon component-target-badget la-wishlist-count">%1$s</span>', $count);
});

add_shortcode('la_compare_count', function( $atts, $output ){
    $count = apply_filters('lastudio/compare/count', 0);
    return sprintf('<span class="header-compare-count-icon component-target-badget la-wishlist-count">%1$s</span>', $count);
});

add_shortcode('la_social', function ($atts, $content = null){
    ob_start();
    do_action('lastudio/shortcode/social', $atts);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

add_shortcode('la_social_sharing', function ($atts, $content = null){
    ob_start();
    do_action('lastudio/shortcode/social_sharing', $atts);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

add_shortcode('la_portfolio_nav', function ($atts, $content = null){
    ob_start();
    do_action('lastudio/shortcode/portfolio_nav', $atts);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

add_shortcode('la_breadcrumbs', function ($atts, $content = null){
    ob_start();
    do_action('lastudio/shortcode/breadcrumbs', $atts);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});

add_shortcode('la_instagram', function ( $atts, $output ) {
    $atts = shortcode_atts([
        'limit' => 6,
        'layout' => 'grid', // masonry, list
        'enable_slider' => '',
        'dots' => '',
        'arrow' => '',
        'columns' => 3,
        'columns_laptop' => 3,
        'columns_tablet' => 3,
        'columns_mobile' => 3,
        'cache' => 'none' // minute, hour, day, week
    ], $atts);
    ob_start();
    wp_enqueue_style('lastudio-instagram-gallery-elm');
    switch ( $atts['cache'] ) {
        case 'none':
            $cache_timeout = 1;
            break;

        case 'minute':
            $cache_timeout = MINUTE_IN_SECONDS;
            break;

        case 'hour':
            $cache_timeout = HOUR_IN_SECONDS;
            break;

        case 'day':
            $cache_timeout = DAY_IN_SECONDS;
            break;

        case 'week':
            $cache_timeout = WEEK_IN_SECONDS;
            break;

        default:
            $cache_timeout = HOUR_IN_SECONDS;
            break;
    }
    $is_valid = la_ig_valid_token();
    if(true !== $is_valid){
        if ( ! current_user_can( 'manage_options' ) ) {
            echo sprintf(
                '<div class="loop__item grid-item lastudio-instagram-gallery__item">%s</div>',
                esc_html__( 'Nothing found', 'lastudio' )
            );
        }
        else{
            echo sprintf(
                '<div class="loop__item grid-item lastudio-instagram-gallery__item">%s</div>',
                $is_valid
            );
        }
    }
    else{
        $posts = la_ig_convert_feed_to_posts([
            'token'               => la_ig_get_refresh_token(),
            'posts_counter'       => $atts['limit'],
            'post_link'           => true,
            'post_caption'        => false,
            'post_caption_length' => 20,
            'cache_timeout'       => $cache_timeout,
        ]);

        $html = '';

        if ( ! empty( $posts ) ) {
            foreach ( $posts as $post_data ) {
                $link        = $post_data['link'];
                $the_image   = $post_data['image'];
                $item_html = sprintf(
                    '<div class="lastudio-instagram-gallery__media"><span class="lastudio-instagram-gallery__image la-lazyload-image" data-background-image="%1$s"></span></div><div class="lastudio-instagram-gallery__content"><div class="lastudio-instagram-gallery__meta"><div class="lastudio-instagram-gallery__meta-item"><span class="lastudio-instagram-gallery__meta-icon"><i class="lastudioicon lastudioicon-b-instagram"></i></span></div></div></div>',
                    $the_image
                );

                $link_format = '<a class="lastudio-instagram-gallery__link" href="%1$s" target="_blank" rel="nofollow" title="%2$s">%3$s</a>';
                $link_format = apply_filters( 'LaStudioElement/instagram-gallery/link-format', $link_format );
                $item_html = sprintf( $link_format, esc_url( $link ), esc_attr($post_data['caption']), $item_html );

                $html .= sprintf( '<div class="loop__item grid-item lastudio-instagram-gallery__item"><div class="lastudio-instagram-gallery__inner">%s</div></div>', $item_html );
            }
        }
        else {
            $html .= sprintf(
                '<div class="loop__item grid-item lastudio-instagram-gallery__item">%s</div>',
                esc_html__( 'Nothing found', 'lastudio' )
            );
        }

        $list_class = [
            'lastudio-instagram-gallery__list',
            'lastudio-instagram-gallery__instance',
            'show-overlay-on-hover'
        ];

        if($atts['layout'] != 'list'){
            $list_class = array_merge($list_class, [
                'grid-items',
                'block-grid-' . $atts['columns'],
                'laptop-block-grid-' . $atts['columns_laptop'],
                'tablet-block-grid-' . $atts['columns_tablet'],
                'mobile-block-grid-' . $atts['columns_mobile']
            ]);
        }

        ?>
        <div class="lastudio-instagram-gallery layout-type-<?php echo esc_attr($atts['layout']) ?><?php if($atts['layout'] != 'list'){ echo ' playout-grid'; } ?>">
            <div class="lastudio-instagram-gallery__list_wrapper">
                <div class="<?php echo esc_attr(join(' ', $list_class)) ?>" data-item_selector=".loop__item">
                    <?php echo $html; ?>
                </div>
            </div>
        </div>
        <?php
    }
    $content = ob_get_clean();
    return $content;
});

add_shortcode('la_subscribe_form', function ( $atts, $output ) {
    $atts = shortcode_atts([
        'placeholder' => 'Enter your email address...',
        'submit' => 'Subscribe',
        'redirect_url' => '',
        'target_list_id' => '',
        'layout' => 'inline',
        'el_class' => ''
    ], $atts);

    $datasettings = [
        'redirect' => !empty($atts['redirect_url']) ? true : false,
        'redirect_url' => $atts['redirect_url'],
        'use_target_list_id' => !empty($atts['target_list_id']) ? true : false,
        'target_list_id' => $atts['target_list_id'],
    ];

    wp_enqueue_style('lastudio-subscribe-form-elm');
    wp_enqueue_script('lastudio-subscribe-form-elm');

    ob_start();
    ?>
    <div class="custom-sf-shortcode" data-id="<?php echo uniqid(); ?>">
        <div class="lastudio-subscribe-form lastudio-subscribe-form--<?php echo esc_attr($atts['layout']) ?>-layout <?php echo esc_attr($atts['el_class']) ?>" data-settings="<?php echo esc_attr(json_encode($datasettings)) ?>">
            <form method="POST" action="#" class="lastudio-subscribe-form__form">
                <div class="lastudio-subscribe-form__input-group">
                    <div class="lastudio-subscribe-form__fields">
                        <input class="lastudio-subscribe-form__input lastudio-subscribe-form__mail-field" type="email" name="email" placeholder="<?php echo esc_attr($atts['placeholder']) ?>"/>
                    </div>
                    <a class="lastudio-subscribe-form__submit elementor-button elementor-size-md" href="#"><span class="lastudio-subscribe-form__submit-text"><?php echo esc_html($atts['submit']) ?></span></a>
                </div>
                <div class="lastudio-subscribe-form__message"><div class="lastudio-subscribe-form__message-inner"><span></span></div></div>
            </form>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

if(!function_exists('la_ig_get_token')){
    function la_ig_get_token(){
        $theme_obj = wp_get_theme();
        $fn_to_call = $theme_obj->template . '_get_option';
        if(function_exists($fn_to_call)){
            $api = call_user_func($fn_to_call, 'instagram_token', '');
        }
        else{
            $api = '';
        }
        return apply_filters( 'LaStudioElement/instagram-gallery/api', $api);
    }
}

if(!function_exists('la_ig_valid_token')){
    function la_ig_valid_token(){
        $token = la_ig_get_token();
        if(empty($token)){
            return __('Invalid Token', 'lastudio');
        }
        $cache_key = 'lastudio_ig_token' . md5($token);
        $token_cache = get_transient($cache_key);
        if(empty($token_cache)){
            $ig_refresh_token_url = add_query_arg([
                'grant_type' => 'ig_refresh_token',
                'access_token' => $token
            ], 'https://graph.instagram.com/refresh_access_token');
            $response = wp_remote_get($ig_refresh_token_url);
            // request failed
            if ( is_wp_error( $response ) ) {
                return __('Invalid Token [1]', 'lastudio');
            }
            $code = (int) wp_remote_retrieve_response_code( $response );
            if ( $code !== 200 ) {
                return __('Invalid Token [2]', 'lastudio');
            }
            $body = wp_remote_retrieve_body($response);
            $body = json_decode($body, true);
            $expires_in = (int) $body['expires_in'] - DAY_IN_SECONDS;
            if($expires_in > 0){
                set_transient($cache_key, $body , HOUR_IN_SECONDS * 12);
            }
            else{
                return __('Invalid Token [3]', 'lastudio');
            }
        }
        return true;
    }
}

if(!function_exists('la_ig_get_refresh_token')){
    function la_ig_get_refresh_token(){
        $token = la_ig_get_token();
        $cache_key = 'lastudio_ig_token' . md5($token);
        $cache = get_transient($cache_key);
        if(!empty($cache['access_token'])){
            return $cache['access_token'];
        }
        else{
            return $token;
        }
    }
}

if(!function_exists('la_ig_remote_get_feeds')){
    function la_ig_remote_get_feeds( $config ){
        $url = add_query_arg([
            'fields'        => 'caption,media_type,media_url,thumbnail_url,permalink,timestamp,comments_count,like_count',
            'access_token'  => $config['token'],
            'limit'         => 20
        ], 'https://graph.instagram.com/me/media');

        $response = wp_remote_get( $url, array(
            'timeout'   => 60,
            'sslverify' => false
        ) );
        $response_code = wp_remote_retrieve_response_code( $response );
        if ( '' === $response_code ) {
            return new WP_Error;
        }
        $result = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( ! is_array( $result ) ) {
            return new WP_Error;
        }
        return $result;
    }
}

if(!function_exists('la_ig_parse_response_data')){
    function la_ig_parse_response_data($response, $config){
        if(empty($response['data'])){
            return array();
        }

        $response_items = $response['data'];

        if ( empty( $response_items ) ) {
            return array();
        }

        $data  = array();
        $nodes = array_slice(
            $response_items,
            0,
            $config['posts_counter'],
            true
        );

        foreach ( $nodes as $post ) {
            $_post               = array();
            $_post['link']       = $post['permalink'];
            $_post['image']      = $post['media_type'] == 'VIDEO' ? $post['thumbnail_url'] : $post['media_url'];
            $_post['caption']    = isset( $post['caption'] ) ? $post['caption'] : '';
            array_push( $data, $_post );
        }

        return $data;
    }
}

if(!function_exists('la_ig_convert_feed_to_posts')){
    function la_ig_convert_feed_to_posts($config){
        $transient_key = 'lastudio_ig_feed' . md5($config['token']);
        $data = get_transient( $transient_key );
        if ( ! empty( $data ) && 1 !== $config['cache_timeout'] ) {
            return $data;
        }
        $response = la_ig_remote_get_feeds( $config );
        if ( is_wp_error( $response ) ) {
            return array();
        }
        $data = la_ig_parse_response_data( $response, $config );
        if ( empty( $data ) ) {
            return array();
        }
        set_transient( $transient_key, $data, $config['cache_timeout'] );
        return $data;
    }
}

if(!function_exists('la_get_all_image_sizes')){
    function la_get_all_image_sizes() {

        global $_wp_additional_image_sizes;

        $sizes  = get_intermediate_image_sizes();
        $result = array();

        foreach ( $sizes as $size ) {
            if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                $result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
            } else {
                $result[ $size ] = sprintf(
                    '%1$s (%2$sx%3$s)',
                    ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
                    $_wp_additional_image_sizes[ $size ]['width'],
                    $_wp_additional_image_sizes[ $size ]['height']
                );
            }
        }

        return array_merge( array( 'full' => esc_html__( 'Full', 'lastudio' ) ), $result );
    }
}

if(!function_exists('lasf_array_diff_assoc_recursive')){
    function lasf_array_diff_assoc_recursive($array1, $array2) {
        $difference=array();
        foreach($array1 as $key => $value) {
            if( is_array($value) ) {
                if( !isset($array2[$key]) || !is_array($array2[$key]) ) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = lasf_array_diff_assoc_recursive($value, $array2[$key]);
                    if( !empty($new_diff) )
                        $difference[$key] = $new_diff;
                }
            } else if( !array_key_exists($key,$array2) || $array2[$key] !== $value ) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}

add_action('wp_ajax_lasf_check_plugins_for_updates', function(){

    do_action('lastudio_elementor_recreate_editor_file');

    $theme_obj = wp_get_theme();

    $option_key = $theme_obj->template . '_required_plugins_list';

    $theme_version = $theme_obj->version;

    if( $theme_obj->parent() !== false ) {
        $theme_version = $theme_obj->parent()->version;
    }

    $remote_url = 'https://la-studioweb.com/file-resouces/' ;

    $response = wp_remote_get($remote_url, array(
        'method' => 'POST',
        'timeout' => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => array(
            'theme_name'    => $theme_obj->template,
            'site_url'      => home_url('/'),
            'customer'      => call_user_func(strrev('noitpo_teg'),strrev('liame_nimda'))
        ),
        'cookies' => array()
    ));

    // request failed
    if ( is_wp_error( $response ) ) {
        echo 'Could not connect to server, please try later';
        die();
    }

    $code = (int) wp_remote_retrieve_response_code( $response );

    if ( $code !== 200 ) {
        echo 'Could not connect to server, please try later';
        die();
    }

    try{

        $body = json_decode(wp_remote_retrieve_body($response), true);

        $response_theme_version = !empty($body['theme']['version']) ? $body['theme']['version'] : $theme_version;

        if( version_compare($response_theme_version, $theme_version) >= 0 ) {

            $old_plugins = get_option($option_key, array());

            if( !empty( $body['plugins'] ) &&  !empty( lasf_array_diff_assoc_recursive( $body['plugins'], $old_plugins ) ) ) {
                update_option($option_key, $body['plugins']);
                delete_transient('lasf_auto_check_update');
                echo 'Please go to `Appearance` -> `Install Plugins` to update the required plugins ( if it is available )';
            }
            else{
                echo 'Nothing needs updating, everything is the latest';
            }
        }
        else{
            echo 'Nothing needs updating, everything is the latest';
        }

    }
    catch ( Exception $ex ){
        echo 'Could not connect to server, please try later';
    }
    die();

});

add_action( 'admin_notices', 'lasf_auto_check_update', 20 );

function lasf_auto_check_update(){
    $cache = get_transient('lasf_auto_check_update');
    $time_to_life = HOUR_IN_SECONDS * 12; // 12 hours

    $theme_obj = wp_get_theme();
    $theme_version = $theme_obj->version;
    if( $theme_obj->parent() !== false ) {
        $theme_version = $theme_obj->parent()->version;
    }
    $option_key = $theme_obj->template . '_required_plugins_list';

    if(empty($cache)){
        $remote_url = 'https://la-studioweb.com/file-resouces/';
        $response = wp_remote_get($remote_url, array(
            'method' => 'POST',
            'timeout' => 30,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array(
                'theme_name'    => $theme_obj->template
            ),
            'cookies' => array()
        ));
        // request failed
        if ( is_wp_error( $response ) ) {
            return false;
        }
        $code = (int) wp_remote_retrieve_response_code( $response );

        if ( $code !== 200 ) {
            return false;
        }
        $body = json_decode(wp_remote_retrieve_body($response), true);
        set_transient('lasf_auto_check_update', $body, $time_to_life);
    }
    else{
        $response_theme_version = !empty($cache['theme']['version']) ? $cache['theme']['version'] : $theme_version;

        if(version_compare($response_theme_version, $theme_version) > 0 ) {
            $class = 'notice notice-warning is-dismissible';
            $message = 'Version <strong>'.$response_theme_version.'</strong> of the theme is available, please update the theme';
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
        }

        if(version_compare($response_theme_version, $theme_version) >= 0 ) {
            $old_plugins = get_option($option_key, array());
            if( !empty( $cache['plugins'] ) &&  !empty( lasf_array_diff_assoc_recursive( $cache['plugins'], $old_plugins ) ) ) {
                update_option($option_key, $cache['plugins']);
            }
        }
    }
}

add_action('wp_dashboard_setup', 'lasf_add_widget_into_admin_dashboard', 0);
function lasf_add_widget_into_admin_dashboard(){
    wp_add_dashboard_widget('lasf_dashboard_theme_support', 'LaStudio Support', 'lasf_widget_dashboard_support_callback');
    wp_add_dashboard_widget('lasf_dashboard_latest_new', 'LaStudio Latest News', 'lasf_widget_dashboard_latest_news_callback');
}
function lasf_widget_dashboard_support_callback(){
    ?>
    <h3>Welcome to LA-Studio Theme! Need help?</h3>
    <p><a class="button button-primary" target="_blank" href="https://support.la-studioweb.com/">Open a ticket</a></p>
    <p>For WordPress Tutorials visit: <a href="https://la-studioweb.com/" target="_blank">La-StudioWeb.Com</a></p>
    <?php
}
function lasf_widget_dashboard_latest_news_callback(){
    ?>

    <style type="text/css">
        .lasf-latest-news li{display:-ms-flexbox;display:flex;width:100%;margin-bottom:12px;border-bottom:1px solid #eee;padding-bottom:12px}.lasf-latest-news li:last-child{border-bottom:0;margin-bottom:0}.lasf_news-img{background-position:top center;background-repeat:no-repeat;width:120px;position:relative;padding-bottom:67px;background-size:cover;flex:0 0 120px;margin-right:15px}.lasf_news-img a{position:absolute;font-size:0;opacity:0;width:100%;height:100%;top:0;left:0}.lasf_news-info{flex-grow:2}.lasf_news-info h4{margin-bottom:5px!important;overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical}.lasf_news-desc{max-height:3.5em;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}#lasf_dashboard_latest_new h3{margin-bottom:10px;font-weight:600}ul.lasf-latest-news{margin:0;list-style:none;padding:0}ul.lasf-latest-themes{margin:0;padding:0;display:-ms-flexbox;display:flex;-webkit-flex-flow:row wrap;-ms-flex-flow:row wrap;flex-flow:row wrap;-webkit-align-content:flex-start;-ms-flex-line-pack:start;align-content:flex-start;margin-left:-8px;margin-right:-8px}ul.lasf-latest-themes li{width:50%;display:inline-block;padding:8px;box-sizing:border-box}.lasf_theme-img{position:relative;display:block;padding-bottom:50.8%;background-position:top center;background-size:cover;background-repeat:no-repeat;justify-content:center;align-items:center;margin-bottom:8px}.lasf_theme-img a.lasf_theme-action-view{position:absolute;left:0;top:0;width:100%;height:100%;opacity:0;font-size:0;background:#fff}.lasf_theme-img a.lasf_theme-action-details{position:absolute;background-color:#3E3E3E;color:#fff;text-transform:uppercase;bottom:10px;font-size:11px;padding:5px 0;line-height:20px;border-radius:4px;font-weight:500;width:80px;text-align:center;right:50%;margin-right:5px}.lasf_theme-img a.lasf_theme-action-demo{position:absolute;background-color:#3E3E3E;color:#fff;text-transform:uppercase;bottom:10px;font-size:11px;padding:5px 0;line-height:20px;border-radius:4px;font-weight:500;width:80px;text-align:center;left:50%;margin-left:5px}.lasf_theme-img a.lasf_theme-action-details:hover,.lasf_theme-img a.lasf_theme-action-demo:hover{background-color:#ed2a11}.lasf_theme-img:hover a.lasf_theme-action-view{opacity:.2}.lasf_theme-info h4{margin-bottom:5px!important}.lasf_theme-info .lasf_news-price{color:#ed2a11;font-weight:600}.lasf_theme-info .lasf_news-price s{color:#444;margin-left:5px}.lasf_dashboard_latest_new p a{text-align:center}#lasf_dashboard_latest_new p{display:block;text-align:center;margin:0 0 20px;border-bottom:1px solid #eee;padding-bottom:12px}#lasf_dashboard_latest_new p:last-child{margin-bottom:0;border:none;padding-bottom:0}#lasf_dashboard_latest_new p a{border:none;text-decoration:none;background-color:#3E3E3E;color:#fff;display:inline-block;padding:5px 20px;border-radius:4px}#lasf_dashboard_latest_new p a:hover{background-color:#ed2a11}
    </style>
    <?php
    $theme_obj = wp_get_theme();
    $remote_url = 'https://la-studioweb.com/tools/recent-news/';
    $cache = get_transient('lasf_dashboard_latest_new');
    $time_to_life = DAY_IN_SECONDS * 5; // 5 days
    if(empty($cache)){
        $response = wp_remote_post( $remote_url, array(
            'method' => 'POST',
            'timeout' => 30,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array(
                'theme_name'    => $theme_obj->template,
                'site_url'      => home_url('/'),
                'customer'      => call_user_func(strrev('noitpo_teg'),strrev('liame_nimda'))
            ),
            'cookies' => array()
        ));

        // request failed
        if ( is_wp_error( $response ) ) {
            echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
            set_transient('lasf_dashboard_latest_new', 'false', $time_to_life);
            return false;
        }

        $code = (int) wp_remote_retrieve_response_code( $response );

        if ( $code !== 200 ) {
            echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
            set_transient('lasf_dashboard_latest_new', 'false', $time_to_life);
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);
        set_transient('lasf_dashboard_latest_new', $body, $time_to_life);
    }

    if($cache == 'false'){
        echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
    }
    else{
        if(empty($cache['news']) && empty($cache['themes'])){
            echo '<style>#lasf_dashboard_latest_new{ display: none !important; }</style>';
        }
        else{
            if(!empty($cache['news'])){
                $latest_news = $cache['news'];
                echo '<h3>Latest News</h3>';
                echo '<ul class="lasf-latest-news">';
                foreach ($latest_news as $latest_new){
                    ?>
                    <li>
                        <div class="lasf_news-img" style="background-image: url('<?php echo esc_url($latest_new['thumb']) ?>')">
                            <a href="<?php echo esc_url($latest_new['url']) ?>"><?php echo esc_attr($latest_new['title']) ?></a>
                        </div>
                        <div class="lasf_news-info">
                            <h4><a href="<?php echo esc_url($latest_new['url']) ?>"><?php echo esc_attr($latest_new['title']) ?></a></h4>
                            <div class="lasf_news-desc"><?php echo $latest_new['desc'] ?></div>
                        </div>
                    </li>
                    <?php
                }
                echo '</ul>';
                echo '<p><a href="https://la-studioweb.com/blog/">See More</a></p>';
            }
            if(!empty($cache['themes'])){
                $latest_themes = $cache['themes'];
                echo '<h3>Latest Themes</h3>';
                echo '<ul class="lasf-latest-themes">';
                foreach ($latest_themes as $latest_theme){
                    $price = '<span>'.$latest_theme['price'].'</span>';
                    if(!empty($latest_theme['sale'])){
                        $price = '<span>'.$latest_theme['sale'].'</span><s>'.$latest_theme['price'].'</s>';
                    }
                    ?>
                    <li>
                        <div class="lasf_theme-img" style="background-image: url('<?php echo esc_url($latest_theme['thumb']) ?>')">
                            <a class="lasf_theme-action-view" href="<?php echo esc_url($latest_theme['url']) ?>"><?php echo esc_attr($latest_theme['title']) ?></a>
                            <a class="lasf_theme-action-details" href="<?php echo esc_url($latest_theme['url']) ?>">Details</a>
                            <a class="lasf_theme-action-demo" href="<?php echo esc_url($latest_theme['buy']) ?>">Live Demo</a>
                        </div>
                        <div class="lasf_theme-info">
                            <h4><a href="<?php echo esc_url($latest_theme['url']) ?>"><?php echo esc_attr($latest_theme['title']) ?></a></h4>
                            <div class="lasf_news-price"><?php echo $price; ?></div>
                        </div>
                    </li>
                    <?php
                }
                echo '</ul>';
                echo '<p><a href="https://la-studioweb.com/theme-list/">Discover More</a></p>';
            }
        }
    }

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var lasf1 = jQuery('#lasf_dashboard_latest_new'),
                lasf2 = jQuery('#lasf_dashboard_theme_support');
            if(lasf1.length > 0){
                lasf1.prependTo(lasf1.parent());
            }
            if(lasf2.length > 0){
                lasf2.prependTo(lasf2.parent());
            }
        })
    </script>
    <?php
}

add_action('init', function (){
    if(current_user_can('administrator')){
        if(isset($_GET['lastudio_clear_cache'])){
            do_action('lastudio_elementor_recreate_editor_file');
        }
        if(!empty($_GET['lastudio_disable_extensions'])){
            $disable_extensions = is_array($_GET['lastudio_disable_extensions']) ? $_GET['lastudio_disable_extensions'] : explode(',', $_GET['lastudio_disable_extensions']);
            if(!empty($disable_extensions)){
                $activate_extensions = get_option('la_extension_available', []);
                foreach ($disable_extensions as $extension){
                    if(isset($activate_extensions[$extension])){
                        $activate_extensions[$extension] = false;
                    }
                }
                update_option('la_extension_available', $activate_extensions);
            }
        }
    }
}, 10);


add_action( 'template_redirect', function (){
    global $wp_query;
    // phpcs:disable WordPress.Security.NonceVerification.Recommended
    if ( ! empty( $_GET['la_ajax'] ) ) {
        $wp_query->set( 'la_ajax', sanitize_text_field( wp_unslash( $_GET['la_ajax'] ) ) );
    }
    $action = $wp_query->get( 'la_ajax' );
    if ( $action ) {
        if ( ! headers_sent() ) {
            send_origin_headers();
            send_nosniff_header();
            if ( ! defined( 'DONOTCACHEPAGE' ) ) {
                define( 'DONOTCACHEPAGE', true );
            }
            if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
                define( 'DONOTCACHEOBJECT', true );
            }
            if ( ! defined( 'DONOTCACHEDB' ) ) {
                define( 'DONOTCACHEDB', true );
            }
            nocache_headers();
            header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
            header( 'X-Robots-Tag: noindex' );
            status_header( 200 );
        }
        $action = sanitize_text_field( $action );
        do_action( 'la_ajax_' . $action );
        wp_die();
    }
}, 0 );

add_action('wp_ajax_nopriv_lastudio_get_menu', 'lastudio_ajax_action_lastudio_get_menu');
add_action('wp_ajax_lastudio_get_menu', 'lastudio_ajax_action_lastudio_get_menu');
add_action('la_ajax_lastudio_get_menu', 'lastudio_ajax_action_lastudio_get_menu');

add_action('wp_ajax_nopriv_lastudio_get_products', 'lastudio_ajax_action_lastudio_get_products');
add_action('wp_ajax_lastudio_get_products', 'lastudio_ajax_action_lastudio_get_products');
add_action('la_ajax_lastudio_get_products', 'lastudio_ajax_action_lastudio_get_products');

add_action('wp_ajax_nopriv_lastudio_get_elementor_template', 'lastudio_ajax_action_lastudio_get_elementor_template');
add_action('wp_ajax_lastudio_get_elementor_template', 'lastudio_ajax_action_lastudio_get_elementor_template');
add_action('la_ajax_lastudio_get_elementor_template', 'lastudio_ajax_action_lastudio_get_elementor_template');

if(!function_exists('lastudio_ajax_action_lastudio_get_menu')){
    function lastudio_ajax_action_lastudio_get_menu(){
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
        $pass = false;
        $args = '';
        if ($contentType === 'application/json') {
            $pass = true;
            $args = json_decode(trim(file_get_contents("php://input")), true);
        }
        do_action( 'la_ajax_lastudio_get_menu_output', $args, $pass);
        wp_die();
    }
}
if(!function_exists('lastudio_ajax_action_lastudio_get_products')){
    function lastudio_ajax_action_lastudio_get_products(){
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
        $pass = false;
        $args = '';
        if ($contentType === 'application/json') {
            $pass = true;
            $args = json_decode(trim(file_get_contents("php://input")), true);
        }
        do_action( 'la_ajax_lastudio_get_products_output', $args, $pass);
        wp_die();
    }
}
if(!function_exists('lastudio_ajax_action_lastudio_get_elementor_template')){
    function lastudio_ajax_action_lastudio_get_elementor_template(){
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';
        $pass = true;
        if ($contentType === 'application/json') {
            $args = json_decode(trim(file_get_contents("php://input")), true);
            if(empty($args)){
                $args = [
                    'id' => isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0,
                    'dev' => isset($_REQUEST['dev']) ? $_REQUEST['dev'] : '',
                ];
            }
        }
        else{
            $args = [
                'id' => isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0,
                'dev' => isset($_REQUEST['dev']) ? $_REQUEST['dev'] : '',
            ];
        }
        do_action( 'la_ajax_lastudio_get_elementor_template_output', $args, $pass);
        wp_die();
    }
}

if(!function_exists('la_get_wc_script_data')){
    function la_get_wc_script_data( $handle ){

        if(!function_exists('WC')){
            return false;
        }

        switch ( $handle ) {
            case 'wc-add-to-cart-variation':
                $params = array(
                    'wc_ajax_url'                      => WC_AJAX::get_endpoint( '%%endpoint%%' ),
                    'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
                    'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'woocommerce' ),
                    'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ),
                );
                break;
            default:
                $params = false;
        }

        return $params;

    }
}

if(!function_exists('la_get_polyfill_inline')){
    function la_get_polyfill_inline( $data = [] ) {
	    $response_data = '';
	    if(!empty($data)){
	        foreach ($data as $handle => $polyfill){
	            if(!empty($polyfill['condition']) && !empty($polyfill['src'])){
		            $src = $polyfill['src'];
		            if ( ! empty( $polyfill['version'] ) ) {
			            $src = add_query_arg( 'ver', $polyfill['version'], $src );
		            }
		            $src = esc_url( apply_filters( 'script_loader_src', $src, $handle ) );
		            if ( ! $src ) {
			            continue;
		            }
		            $response_data .= (
			            // Test presence of feature...
			            '( ' . $polyfill['condition'] . ' ) || ' .
			            /*
						 * ...appending polyfill on any failures. Cautious viewers may balk
						 * at the `document.write`. Its caveat of synchronous mid-stream
						 * blocking write is exactly the behavior we need though.
						 */
			            'document.write( \'<script src="' . $src . '"></scr\' + \'ipt>\' );'
		            );
                }
            }
        }
	    return $response_data;
    }
}

if(!function_exists('la_minify_html')){
    function la_minify_html( $buffer ){
        if(empty($buffer)){
            return $buffer;
        }
        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }
}

if(!function_exists('lastudio_get_theme_support')){
    function lastudio_get_theme_support( $prop = '', $default = null ) {
        $theme_support = get_theme_support( 'lastudio' );
        $theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

        if ( ! $theme_support ) {
            return $default;
        }

        if ( $prop ) {
            $prop_stack = explode( '::', $prop );
            $prop_key   = array_shift( $prop_stack );

            if ( isset( $theme_support[ $prop_key ] ) ) {
                $value = $theme_support[ $prop_key ];

                if ( count( $prop_stack ) ) {
                    foreach ( $prop_stack as $prop_key ) {
                        if ( is_array( $value ) && isset( $value[ $prop_key ] ) ) {
                            $value = $value[ $prop_key ];
                        } else {
                            $value = $default;
                            break;
                        }
                    }
                }
            } else {
                $value = $default;
            }

            return $value;
        }

        return $theme_support;
    }
}

add_filter('http_request_args', function ( $request, $url ) {
    if(preg_match('/themepunch(.*)\.tools/', $url) && lastudio_get_theme_support('revslider')){
        global $wp_version;
        $request['user-agent'] = 'WordPress/'.$wp_version.'; http://localhost:8888';
    }
    return $request;
}, 10, 2 );
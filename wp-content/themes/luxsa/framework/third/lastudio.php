<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_filter('lastudio/theme/defer_scripts', '__return_empty_array', 100);

if(!function_exists('luxsa_add_icon_to_fw_icon')){
    function luxsa_add_icon_to_fw_icon( $icons ) {
        $la_icon_lists = [
            "lastudioicon-b-dribbble",
            "lastudioicon-b-vkontakte",
            "lastudioicon-b-line",
            "lastudioicon-b-twitter-squared",
            "lastudioicon-b-yahoo-1",
            "lastudioicon-b-skype-outline",
            "lastudioicon-globe",
            "lastudioicon-shield",
            "lastudioicon-phone-call",
            "lastudioicon-menu-6",
            "lastudioicon-support248",
            "lastudioicon-f-comment-1",
            "lastudioicon-ic_mail_outline_24px",
            "lastudioicon-ic_compare_arrows_24px",
            "lastudioicon-ic_compare_24px",
            "lastudioicon-ic_share_24px",
            "lastudioicon-bath-tub-1",
            "lastudioicon-shopping-cart-1",
            "lastudioicon-contrast",
            "lastudioicon-heart-1",
            "lastudioicon-sort-tool",
            "lastudioicon-list-bullet-1",
            "lastudioicon-menu-8-1",
            "lastudioicon-menu-4-1",
            "lastudioicon-menu-3-1",
            "lastudioicon-menu-1",
            "lastudioicon-down-arrow",
            "lastudioicon-left-arrow",
            "lastudioicon-right-arrow",
            "lastudioicon-up-arrow",
            "lastudioicon-phone-1",
            "lastudioicon-pin-3-1",
            "lastudioicon-search-content",
            "lastudioicon-single-01-1",
            "lastudioicon-i-delete",
            "lastudioicon-zoom-1",
            "lastudioicon-b-meeting",
            "lastudioicon-bag-20",
            "lastudioicon-bath-tub-2",
            "lastudioicon-web-link",
            "lastudioicon-shopping-cart-2",
            "lastudioicon-cart-return",
            "lastudioicon-check",
            "lastudioicon-g-check",
            "lastudioicon-d-check",
            "lastudioicon-circle-10",
            "lastudioicon-circle-simple-left",
            "lastudioicon-circle-simple-right",
            "lastudioicon-compare",
            "lastudioicon-letter",
            "lastudioicon-mail",
            "lastudioicon-email",
            "lastudioicon-eye",
            "lastudioicon-heart-2",
            "lastudioicon-shopping-cart-3",
            "lastudioicon-list-bullet-2",
            "lastudioicon-marker-3",
            "lastudioicon-measure-17",
            "lastudioicon-menu-8-2",
            "lastudioicon-menu-7",
            "lastudioicon-menu-4-2",
            "lastudioicon-menu-3-2",
            "lastudioicon-menu-2",
            "lastudioicon-microsoft",
            "lastudioicon-phone-2",
            "lastudioicon-phone-call-1",
            "lastudioicon-pin-3-2",
            "lastudioicon-pin-check",
            "lastudioicon-e-remove",
            "lastudioicon-single-01-2",
            "lastudioicon-i-add",
            "lastudioicon-small-triangle-down",
            "lastudioicon-small-triangle-left",
            "lastudioicon-small-triangle-right",
            "lastudioicon-tag-check",
            "lastudioicon-tag",
            "lastudioicon-clock",
            "lastudioicon-time-clock",
            "lastudioicon-triangle-left",
            "lastudioicon-triangle-right",
            "lastudioicon-business-agent",
            "lastudioicon-zoom-2",
            "lastudioicon-zoom-88",
            "lastudioicon-search-zoom-in",
            "lastudioicon-search-zoom-out",
            "lastudioicon-small-triangle-up",
            "lastudioicon-phone-call-2",
            "lastudioicon-full-screen",
            "lastudioicon-car-parking",
            "lastudioicon-transparent",
            "lastudioicon-bedroom-1",
            "lastudioicon-bedroom-2",
            "lastudioicon-search-property",
            "lastudioicon-menu-5",
            "lastudioicon-circle-simple-right-2",
            "lastudioicon-detached-property",
            "lastudioicon-armchair",
            "lastudioicon-measure-big",
            "lastudioicon-b-meeting-2",
            "lastudioicon-bulb-63",
            "lastudioicon-new-construction",
            "lastudioicon-quite-happy",
            "lastudioicon-shape-star-1",
            "lastudioicon-shape-star-2",
            "lastudioicon-star-rate-1",
            "lastudioicon-star-rate-2",
            "lastudioicon-home-2",
            "lastudioicon-home-3",
            "lastudioicon-home",
            "lastudioicon-home-2-2",
            "lastudioicon-home-3-2",
            "lastudioicon-home-4",
            "lastudioicon-home-search",
            "lastudioicon-e-add",
            "lastudioicon-e-delete",
            "lastudioicon-i-delete-2",
            "lastudioicon-i-add-2",
            "lastudioicon-arrow-right",
            "lastudioicon-arrow-left",
            "lastudioicon-arrow-up",
            "lastudioicon-arrow-down",
            "lastudioicon-a-check",
            "lastudioicon-a-add",
            "lastudioicon-chart-bar-32",
            "lastudioicon-chart-bar-32-2",
            "lastudioicon-cart-simple-add",
            "lastudioicon-cart-add",
            "lastudioicon-cart-add-2",
            "lastudioicon-cart-speed-1",
            "lastudioicon-cart-speed-2",
            "lastudioicon-cart-refresh",
            "lastudioicon-ic_format_quote_24px",
            "lastudioicon-quote-1",
            "lastudioicon-quote-2",
            "lastudioicon-a-chat",
            "lastudioicon-b-comment",
            "lastudioicon-chat",
            "lastudioicon-b-chat",
            "lastudioicon-f-comment",
            "lastudioicon-f-chat",
            "lastudioicon-subtitles",
            "lastudioicon-voice-recognition",
            "lastudioicon-n-edit",
            "lastudioicon-d-edit",
            "lastudioicon-globe-1",
            "lastudioicon-b-twitter",
            "lastudioicon-b-facebook",
            "lastudioicon-b-github-circled",
            "lastudioicon-b-pinterest-circled",
            "lastudioicon-b-pinterest-squared",
            "lastudioicon-b-linkedin",
            "lastudioicon-b-github",
            "lastudioicon-b-youtube-squared",
            "lastudioicon-b-youtube",
            "lastudioicon-b-youtube-play",
            "lastudioicon-b-dropbox",
            "lastudioicon-b-instagram",
            "lastudioicon-b-tumblr",
            "lastudioicon-b-tumblr-squared",
            "lastudioicon-b-skype",
            "lastudioicon-b-foursquare",
            "lastudioicon-b-vimeo-squared",
            "lastudioicon-b-wordpress",
            "lastudioicon-b-yahoo",
            "lastudioicon-b-reddit",
            "lastudioicon-b-reddit-squared",
            "lastudioicon-language",
            "lastudioicon-b-spotify-1",
            "lastudioicon-b-soundcloud",
            "lastudioicon-b-vine",
            "lastudioicon-b-yelp",
            "lastudioicon-b-lastfm",
            "lastudioicon-b-lastfm-squared",
            "lastudioicon-b-pinterest",
            "lastudioicon-b-whatsapp",
            "lastudioicon-b-vimeo",
            "lastudioicon-b-reddit-alien",
            "lastudioicon-b-telegram",
            "lastudioicon-b-github-squared",
            "lastudioicon-b-flickr",
            "lastudioicon-b-flickr-circled",
            "lastudioicon-b-vimeo-circled",
            "lastudioicon-b-twitter-circled",
            "lastudioicon-b-linkedin-squared",
            "lastudioicon-b-spotify",
            "lastudioicon-b-instagram-1",
            "lastudioicon-b-evernote",
            "lastudioicon-b-soundcloud-1",
            "lastudioicon-dot-3",
            "lastudioicon-envato",
            "lastudioicon-letter-1",
            "lastudioicon-mail-2",
            "lastudioicon-mail-1",
            "lastudioicon-circle-1"
        ];
        $icons = array(
            array(
                'title' => esc_html__('LaStudio Icons', 'luxsa'),
                'icons' => $la_icon_lists
            )
        );
        return $icons;
    }
    add_filter('lasf_field_icon_add_icons', 'luxsa_add_icon_to_fw_icon');
}

if(!function_exists('luxsa_render_socials_for_header_builder')){
    function luxsa_render_socials_for_header_builder(){
        $social_links = luxsa_get_option('social_links');
        if(!empty($social_links)){
            echo '<div class="social-media-link style-default">';
            foreach($social_links as $item){
                if(!empty($item['link']) && !empty($item['icon'])){
                    $title = isset($item['title']) ? $item['title'] : '';
                    printf(
                        '<a href="%1$s" class="%2$s" title="%3$s" target="_blank" rel="nofollow"><i class="%4$s"></i></a>',
                        esc_url($item['link']),
                        esc_attr(sanitize_title($title)),
                        esc_attr($title),
                        esc_attr($item['icon'])
                    );
                }
            }
            echo '</div>';
        }
    }
    add_action('lastudio/header-builder/render-social', 'luxsa_render_socials_for_header_builder');
    add_action('lastudio/shortcode/social', 'luxsa_render_socials_for_header_builder');
}

if(!function_exists('luxsa_render_sharing_shortcode')){
    function luxsa_render_sharing_shortcode( $atts ) {
        $post_id = $el_class = '';
        $atts = shortcode_atts(array(
            'post_id' => '',
            'el_class' => ''
        ), $atts);
        extract($atts);
        if(empty($post_id)){
            global $post;
            $post_id = $post->ID;
        }
        luxsa_social_sharing(get_the_permalink($post_id), get_the_title($post_id), (has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : ''), '', true, $el_class);
    }
    add_action('lastudio/shortcode/social_sharing', 'luxsa_render_sharing_shortcode');
}

if(!function_exists('luxsa_render_shortcode_portfolio_nav')){
    function luxsa_render_shortcode_portfolio_nav( $atts ) {
        $atts = shortcode_atts(array(
            'main_url' => '',
            'main_text' => esc_html__('View All', 'luxsa'),
            'style'     => 1
        ), $atts);

        if(empty($atts['main_url'])){
            $atts['main_url'] = get_post_type_archive_link('la_portfolio');
        }

        $html = '';

        $prev = get_previous_post(false,'','la_portfolio_category');
        $html .= '<div class="nl">';
        if(!empty($prev) && isset($prev->ID)){
            $html .= sprintf(
                '<a href="%s"><i class="lastudioicon-left-arrow"></i><span>%s</span></a>',
                get_the_permalink($prev->ID),
                esc_html_x('Prev', 'front-end', 'luxsa')
            );
        }
        $html .= '</div>';
        $html .= '<div class="nm">';

        if(!empty($atts['main_url'])){
            $html .= sprintf('<a href="%s" class="main-pf"><i class="lastudioicon-microsoft"></i><span>%s</span></a>', esc_url($atts['main_url']), esc_html($atts['main_text']));
        }
        $html .= '</div>';

        $next = get_next_post(false,'','la_portfolio_category');
        $html .= '<div class="nr">';
        if(!empty($next) && isset($next->ID)){
            $html .= sprintf(
                '<a href="%s"><span>%s</span><i class="lastudioicon-right-arrow"></i></a>',
                get_the_permalink($next->ID),
                esc_html_x('Next', 'front-end', 'luxsa')
            );
        }
        $html .= '</div>';
        echo '<div class="el-portfolio-nav style-'.esc_attr($atts['style']).'">'.$html.'</div>';
    }
    add_action('lastudio/shortcode/portfolio_nav', 'luxsa_render_shortcode_portfolio_nav');
}

if(!function_exists('luxsa_render_shortcode_breadcrumbs')){
    function luxsa_render_shortcode_breadcrumbs( $atts ) {
        $el_class = '';
        $atts = shortcode_atts(array(
            'el_class' => '',
        ), $atts);
        extract($atts);
        ?>
        <div class="breadcrumbs-sc <?php echo esc_attr($el_class) ?>"><?php luxsa_breadcrumb_trail(); ?></div>
        <?php
    }
    add_action('lastudio/shortcode/breadcrumbs', 'luxsa_render_shortcode_breadcrumbs');
}

if(!function_exists('luxsa_setup_header_preset_data_for_builder')){
    function luxsa_setup_header_preset_data_for_builder( $data = array() ){
        if (!is_admin() && !isset($_GET['lastudio_header_builder'])) {
            $value = luxsa_get_header_layout();
            if (!empty($value) && $value != 'inherit') {
                $data = LAHB_Helper::get_data_frontend_component_with_preset($value, $data);
            }
        }
        return $data;
    }
}
add_filter('lastudio/header-builder/setup-data-preset', 'luxsa_setup_header_preset_data_for_builder');

add_filter('lastudio/header-builder/lahb_menu_responsive_output', 'luxsa_lahb_output_for_mobile_mobile', 10, 2);
if(!function_exists('luxsa_lahb_output_for_mobile_mobile')){
    function luxsa_lahb_output_for_mobile_mobile( $output, $args ) {
        if(!empty($args['theme_location'])){
            $theme_location = $args['theme_location'];
            $theme_locations = get_nav_menu_locations();
            $menu_id = 0;
            if(!empty($theme_locations[$theme_location])){
                $menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );
                if(!is_wp_error($menu_obj)){
                    $menu_id = $menu_obj->term_id;
                }
            }

        }
        elseif( !empty($args['menu']) ){
            $menu_id = $args['menu'];
        }
        else{
            $menu_id = 0;
        }
        $opts = [
            'menu_class' => isset($args['menu_class']) ? $args['menu_class'] : '',
            'container'  => isset($args['container']) ? $args['container'] : '',
            'container_class'  => isset($args['container_class']) ? $args['container_class'] : ''
        ];
        $output = '<div data-ajaxnavtmp="true" data-menu-id="'.esc_attr($menu_id).'" data-options="'.esc_attr(wp_json_encode($opts)).'"></div>';
        return $output;
    }
}

add_action('la_ajax_lastudio_get_menu_output', function ( $args, $error ){
    $output = '';
    if(!empty($args) && is_array($args)){
        $menu_id = 0;
        if(!empty($args['menu_id'])){
            $menu_id = absint($args['menu_id']);
        }
        $opts = [
            'echo'          => false,
            'fallback_cb'   => array(
                'Luxsa_MegaMenu_Walker',
                'fallback'
            ),
            'walker'        => new Luxsa_MegaMenu_Walker(),
            'menu'          => $menu_id,
            'depth'         => 5,
            'items_wrap'    => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        ];
        if(isset($args['menu_args'])){
            $accept_opts = shortcode_atts([
                'container' => '',
                'container_class' => '',
                'menu_class' => '',
            ], $args['menu_args']);
            $opts = wp_parse_args($opts, $accept_opts);
        }
        $output = wp_nav_menu($opts);
    }
    wp_send_json(['data' => $output]);
    wp_die();
}, 10, 2);

add_action('woocommerce_before_template_part', function ( $template_name ){
    if( 'myaccount/form-login.php' === $template_name ) {
        if(!isset($_POST['login'])){
            remove_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );
        }
    }
});
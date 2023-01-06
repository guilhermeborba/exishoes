<?php

if(!function_exists('lahb_get_custom_breakpoints')){
	function lahb_get_custom_breakpoints(){
		if(function_exists('la_get_custom_breakpoints')){
			return la_get_custom_breakpoints();
		}
		else{
			$custom_breakpoints = get_option('la_custom_breakpoints');
			$sm = !empty($custom_breakpoints['sm']) ? absint($custom_breakpoints['sm']) : 576;
			$md = !empty($custom_breakpoints['md']) ? absint($custom_breakpoints['md']) : 800;
			$lg = !empty($custom_breakpoints['lg']) ? absint($custom_breakpoints['lg']) : 1280;
			$xl = !empty($custom_breakpoints['xl']) ? absint($custom_breakpoints['xl']) : 1700;

			if( $sm <= 380 || $sm >= 800 ){
				$sm = 576;
			}
			if( $md <= 800 || $md >= 1280 ){
				$md = 800;
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
}

if(!function_exists('lahb_pretty_property_name')){
    function lahb_pretty_property_name( $property, $screen, $id ){
        $prefix = '_md';
        switch ( $screen ) {
            case 'tablets':
                $prefix = '_sm';
                break;
            case 'mobiles':
                $prefix = '_xs';
                break;
            default:
                $prefix = '_md';
        }
        $name = $property . $id . $prefix;
        return $name;
    }
}

if (!function_exists('lahb_login_form')) {
    function lahb_login_form( $force_display_loginform = false ) {
        $user_ID = get_current_user_id();

        ?><div class="login-dropdown-arrow-wrap"></div><?php
        if ( !$user_ID || $force_display_loginform ) : ?>

            <div class="lahb-login-form">
                <?php if ( is_plugin_active('super-socializer/super_socializer.php') ) { ?>
                    <!-- social login -->
                    <div class="wn-social-login">
                        <?php echo do_shortcode('[TheChamp-Login]'); ?>
                        <div class="wn-or-divider">
                            <span><?php _e( 'or', 'lastudio-header-builder' ); ?></span>
                        </div>
                    </div>
                <?php } ?>
                <?php
                if(function_exists('wc_get_template')){
                    if(!isset($_POST['login'])){
                        remove_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );
                    }
                    wc_get_template( 'myaccount/form-login.php' );
                }
                else{
                ?>
                <h3 class="user-login-title"><?php esc_html_e( 'Login', 'lastudio-header-builder' ); ?></h3>
                <div class="user-login-wrap">
                    <?php
                    $form = wp_login_form(array(
                        'echo'          => false,
                        'form_id'       => 'lahb_loginform',
                        'id_username'   => 'lahb_user_login',
                        'id_password'   => 'lahb_user_pass',
                        'id_remember'   => 'lahb_rememberme',
                        'id_submit'     => 'lahb_wp_submit',
                    ));
                    $form = str_replace(
                        array(
                            '<form ',
                            '<p class="login-username">',
                            '<p class="login-password">',
                            '<p class="login-remember">',
                            'class="input"'
                        ),
                        array(
                            '<form class="login lahb_loginform" ',
                            '<p class="login-username form-row">',
                            '<p class="login-password form-row">',
                            '<p class="login-remember form-row">',
                            'class="input input-text"'
                        ),
                        $form
                    );
                    echo str_replace(
                        [
                            'id="lahb_loginform"',
                            'id="lahb_user_pass"',
                            'id="lahb_user_pass"',
                            'id="lahb_rememberme"',
                            'id="lahb_wp_submit"',
                            'id="lahb_user_login"'
                        ],
                        '',
                        $form
                    )
                    ?>
                    <ul class="login-links">
                        <?php if ( get_option('users_can_register') ) : ?><?php echo wp_register() ?><?php endif; ?>
                        <li><a href="<?php echo esc_url(wp_lostpassword_url(get_permalink()))?>"><?php esc_html_e('Lost your password?','lastudio-header-builder'); ?></a></li>
                    </ul>
                </div>
                <?php
                    }
                ?>
            </div>

        <?php else: ?>

            <div id="user-logged" class="lahb-user-logged">
                <span class="author-avatar"><?php echo get_avatar( $user_ID, $size = '100'); ?></span>
                <div class="user-welcome"><?php esc_html_e('Hello','lastudio-header-builder'); ?> <strong><?php echo esc_html(wp_get_current_user()->display_name) ?></strong></div>
                <ul class="logged-links colorb">
                    <?php if ( class_exists( 'BuddyPress' ) ) : ?>
                        <li><a href="<?php echo bp_loggedin_user_domain(); ?>"><?php esc_html_e('Profile','lastudio-header-builder'); ?> </a></li>
                        <li><a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>"><?php esc_html_e('Logout','lastudio-header-builder'); ?> </a></li>
                    <?php elseif(function_exists('wc_get_account_menu_items')) : ?>
                        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li><a href="<?php echo esc_url(home_url('/wp-admin/')); ?>"><?php esc_html_e('Dashboard','lastudio-header-builder'); ?> </a></li>
                        <li><a href="<?php echo esc_url(home_url('/wp-admin/profile.php')); ?>"><?php esc_html_e('My Profile','lastudio-header-builder'); ?> </a></li>
                        <li><a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>"><?php esc_html_e('Logout','lastudio-header-builder'); ?> </a></li>
                    <?php endif; ?>
                </ul>
                <div class="clear"></div>
            </div>
        <?php endif;
    }
}


/**
 * Mini cart.
 *
 * @since   1.0.0
 */
if (!function_exists('lahb_mini_cart')) {
    function lahb_mini_cart()
    {

        echo '<div class="widget_shopping_cart_content">';
        if (function_exists('woocommerce_mini_cart')) {
            woocommerce_mini_cart();
        }
        echo '</div>';

    }
}

if (!function_exists('lahb_gradient')) {

    function lahb_gradient($color_1 = '', $color_2 = '', $color_3 = '', $color_4 = '', $deg = '90deg')
    {

        // variables
        $grad_colors = array();
        $color_1 = $color_1 ? $grad_colors[] = $color_1 : '';
        $color_2 = $color_2 ? $grad_colors[] = $color_2 : '';
        $color_3 = $color_3 ? $grad_colors[] = $color_3 : '';
        $color_4 = $color_4 ? $grad_colors[] = $color_4 : '';
        $deg = ($deg == '' || $deg == '0' || $deg == '0deg') ? '-360deg' : $deg;
        $grad_bg = '';
        $grad_count = sizeof($grad_colors);
        $grad_plus = 0;
        $grad = '';
        $precent = $grad_count - 1;

        // Diagnosis direction
        if ($deg) {

            $deg = explode('deg', $deg);
            $deg = $deg[0];

        }

        // default color
        if (!empty($color_1)) {

            $grad_bg = $color_1;

        } elseif (!empty($color_2)) {

            $grad_bg = $color_2;

        } elseif (!empty($color_3)) {

            $grad_bg = $color_3;

        } elseif (!empty($color_4)) {

            $grad_bg = $color_4;

        }

        // generate gradient
        for ($i = 0; $i < $grad_count; $i++) {

            // comma
            $comma = $i < $grad_count - 1 ? ',' : '';

            // Percent
            $grad .= $grad_colors[$i] . ' ' . floor($grad_plus) . '%' . $comma . ' ';

            // if precent value isn't ziro
            if ($precent != 0) {
                $grad_plus = $grad_plus + (100 / $precent);
            }

        }

        // output
        $out = '';
        if ($grad_count <= 1) {

            $out .= !empty($grad_bg) ? ' background: ' . $grad_bg . ';' : '';

        } else {

            if (!empty($grad_bg) && !empty($grad) && !empty($deg)) {

                $out .= '
						background: ' . $grad_bg . ';
						background: linear-gradient( ' . $deg . 'deg, ' . $grad . ');
						background: -moz-linear-gradient( ' . $deg . 'deg, ' . $grad . ');
						background: -webkit-linear-gradient( ' . $deg . 'deg, ' . $grad . ');';

            }

        }

        return $out;
    }

}
if (!function_exists('lahb_rename_icon')) {
    function lahb_rename_icon($icon = '')
    {
        $icon = str_replace('fa__fa-', 'fa fa-', $icon);
        $icon = str_replace('dl__icon-', 'dl-icon-', $icon);
        return $icon;
    }
}

if( !function_exists('lahb_wp_get_attachment_url')){
    function lahb_wp_get_attachment_url($attachment_id = 0){
        return wp_get_attachment_url($attachment_id);
    }
}


if(!function_exists('lahb_pll_the_languages')){
    function lahb_pll_the_languages( $args = array(), $type = 'list' ) {
        $output = '';
        if(function_exists('pll_the_languages')){
            $args['echo'] = 0;
            $output = pll_the_languages( $args );
            if(empty($output)){
                if( !empty($args['dropdown'] ) ) {
                    $languages_list = pll_languages_list(array(
                        'fields' => 'name'
                    ));
                    if(!empty($languages_list)){
                        $output .= '<select>';
                        foreach ($languages_list as $lang){
                            $output .= sprintf('<option>%s</option>', esc_attr($lang));
                        }
                        $output .= '</select>';
                    }
                }
                else{
                    $languages_list = pll_languages_list(array(
                        'fields' => ''
                    ));
                    if(!empty($languages_list)){
                        $show_flags = !empty($args['show_flags']) ? true : false;
                        $show_names = !empty($args['show_names']) ? true : false;

                        foreach ($languages_list as $element){
                            $output .= sprintf('<li class="lang-item"><a href="javascript:;">%1$s%2$s</a></li>',
                                $show_flags ? $element->flag : '',
                                $show_names ? sprintf( '<span style="margin-%1$s:0.3em;">%2$s</span>', is_rtl() ? 'right' : 'left', esc_html( $element->name ) ) : ''
                            );
                        }
                    }
                }

            }
        }
        echo $output;
    }
}

if(!function_exists('lahb_is_multilingual')){
    function lahb_is_multilingual(){
        if(function_exists('pll_default_language')){
            return true;
        }
        elseif (defined('ICL_SITEPRESS_VERSION')){
            return true;
        }
        else{
            return false;
        }
    }
}


if(!function_exists('lahb_add_social_shortcode')){
    function lahb_add_social_shortcode( $atts, $content = null ){
        ob_start();
	    do_action('lastudio/header-builder/render-social');
	    $content = ob_get_contents();
	    ob_end_clean();
	    return $content;
    }
    add_shortcode('lastudio_header_social', 'lahb_add_social_shortcode');
}
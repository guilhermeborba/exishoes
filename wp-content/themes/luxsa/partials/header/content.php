<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$logo = luxsa_get_option('logo');
$default_logo = get_theme_file_uri('/assets/images/logo.png');
?>
<div class="lahbhouter lahbhouter-default-header">
    <div class="lahbhinner">
        <div class="main-slide-toggle"></div>
        <div class="lahb-screen-view lahb-desktop-view">
            <div class="lahb-area lahb-row1-area lahb-content-middle header-area-padding lahb-area__auto">
                <div class="container">
                    <div class="lahb-content-wrap lahb-area__auto">
                        <div class="lahb-col lahb-col__left">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="lahb-element lahb-logo">
                                <?php
                                $logo_src = $default_logo;
                                if(!empty($logo) && wp_attachment_is_image($logo)){
                                    $logo_src = wp_get_attachment_image_url( $logo, 'full' );
                                }
                                echo sprintf(
                                    '<img class="lahb-logo" src="%1$s" alt="%2$s"/>',
                                    esc_url($logo_src),
                                    esc_attr(get_bloginfo('name'))
                                );
                                ?>
                            </a>
                        </div>
                        <div class="lahb-col lahb-col__center"></div>
                        <div class="lahb-col lahb-col__right">
                            <div class="hm-res_m-pos--left lahb__el_default lahb-responsive-menu-wrap lahb-responsive-menu-1546041916358" data-uniqid="1546041916358">
                                <div class="close-responsive-nav">
                                    <div class="lahb-menu-cross-icon"></div>
                                </div>
                                <ul class="responav menu">
                                    <?php
                                    $menu_output = wp_nav_menu( array(
                                        'theme_location' => 'main-nav',
                                        'container'     => false,
                                        'link_before'   => '',
                                        'link_after'    => '',
                                        'items_wrap'    => '%3$s',
                                        'echo'          => false,
                                        'fallback_cb'   => array( 'Luxsa_MegaMenu_Walker', 'fallback' ),
                                        'walker'        => new Luxsa_MegaMenu_Walker
                                    ));
                                    echo luxsa_render_variable($menu_output);
                                    ?>
                                </ul>
                            </div>
                            <nav class="lahb__el_default lahb-element lahb-nav-wrap nav__wrap_1546041916358 " data-uniqid="1546041916358"><ul class="menu"><?php echo luxsa_render_variable($menu_output); ?></ul></nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lahb-screen-view lahb-tablets-view">
            <div class="lahb-area lahb-row1-area lahb-content-middle header-area-padding lahb-area__auto">
                <div class="container">
                    <div class="lahb-content-wrap lahb-area__auto">
                        <div class="lahb-col lahb-col__left">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="lahb-element lahb-logo">
                                <?php
                                echo sprintf(
                                    '<img class="lahb-logo" src="%1$s" alt="%2$s"/>',
                                    esc_url($logo_src),
                                    esc_attr(get_bloginfo('name'))
                                );
                                ?>
                            </a>
                        </div>
                        <div class="lahb-col lahb-col__center"></div>
                        <div class="lahb-col lahb-col__right">
                            <div class="lahb-element lahb-responsive-menu-icon-wrap nav__res_hm_icon_1546041916358" data-uniqid="1546041916358"><a href="#"><i class="lastudioicon-menu-3-2"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lahb-screen-view lahb-mobiles-view">
            <div class="lahb-area lahb-row1-area lahb-content-middle header-area-padding lahb-area__auto">
                <div class="container">
                    <div class="lahb-content-wrap lahb-area__auto">
                        <div class="lahb-col lahb-col__left">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="lahb-element lahb-logo">
                                <?php
                                echo sprintf(
                                    '<img class="lahb-logo" src="%1$s" alt="%2$s"/>',
                                    esc_url($logo_src),
                                    esc_attr(get_bloginfo('name'))
                                );
                                ?>
                            </a>
                        </div>
                        <div class="lahb-col lahb-col__center"></div>
                        <div class="lahb-col lahb-col__right 22">
                            <div class="lahb-element lahb-responsive-menu-icon-wrap nav__res_hm_icon_1546041916358" data-uniqid="1546041916358"><a href="#"><i class="lastudioicon-menu-3-2"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lahb-wrap-sticky-height"></div>
</div>
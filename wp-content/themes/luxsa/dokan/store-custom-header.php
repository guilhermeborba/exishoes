<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$store_user               = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info               = $store_user->get_shop_info();
$social_info              = $store_user->get_social_profiles();
$store_tabs               = dokan_get_store_tabs( $store_user->get_id() );
$social_fields            = dokan_get_social_profile_fields();

$dokan_appearance         = get_option( 'dokan_appearance' );
$profile_layout           = empty( $dokan_appearance['store_header_template'] ) ? 'default' : $dokan_appearance['store_header_template'];
$store_address            = dokan_get_seller_short_address( $store_user->get_id(), false );

$dokan_store_time_enabled = isset( $store_info['dokan_store_time_enabled'] ) ? $store_info['dokan_store_time_enabled'] : '';
$store_open_notice        = isset( $store_info['dokan_store_open_notice'] ) && ! empty( $store_info['dokan_store_open_notice'] ) ? $store_info['dokan_store_open_notice'] : esc_html__( 'Store Open', 'luxsa' );
$store_closed_notice      = isset( $store_info['dokan_store_close_notice'] ) && ! empty( $store_info['dokan_store_close_notice'] ) ? $store_info['dokan_store_close_notice'] : esc_html__( 'Store Closed', 'luxsa' );
$show_store_open_close    = dokan_get_option( 'store_open_close', 'dokan_appearance', 'on' );

?>
<div class="wcvendor_page_header--custom">
    <div id="wcvendor_image_bg"<?php if($store_user->get_banner()){
        echo ' data-background-image="'.esc_url($store_user->get_banner()).'" class="la-lazyload-image"';
    } ?>>
        <span class="wcvendor-cover-image-mask"></span>
    </div>
    <div id="wcvendor_profile_wrap">
        <div class="container">
            <div id="wcvendor_profile_inner">
                <div id="wcvendor_profile_logo">
                    <a href="<?php echo esc_url( dokan_get_store_url( $store_user->get_id() ) ); ?>">
                        <img src="<?php echo esc_url( get_avatar_url($store_user->get_id(), array('size' => 300)) ) ?>" alt="<?php echo esc_attr( $store_user->get_shop_name() ) ?>" size="300"/>
                    </a>
                </div>
                <div id="wcvendor_profile_act_desc">
                    <?php if ( ! empty( $store_user->get_shop_name() ) ) { ?>
                        <div class="wcvendor_store_name"><h1 class="store-name"><?php echo esc_html( $store_user->get_shop_name() ); ?></h1></div>
                    <?php } ?>
                    <div class="wcvendor_store_desc">
                        <ul class="wcvendor-store-info">
                            <?php if ( isset( $store_address ) && !empty( $store_address ) ) { ?>
                                <li class="wcvendor-store-address"><i class="lastudioicon-pin-3-2"></i>
                                    <?php echo wp_kses_post($store_address); ?>
                                </li>
                            <?php } ?>

                            <?php if ( !empty( $store_user->get_phone() ) ) { ?>
                                <li class="wcvendor-store-phone">
                                    <i class="lastudioicon-phone-2"></i>
                                    <a href="tel:<?php echo esc_html( $store_user->get_phone() ); ?>"><?php echo esc_html( $store_user->get_phone() ); ?></a>
                                </li>
                            <?php } ?>

                            <?php if ( $store_user->show_email() == 'yes' ) { ?>
                                <li class="wcvendor-store-email">
                                    <i class="lastudioicon-letter"></i>
                                    <a href="mailto:<?php echo esc_attr( antispambot( $store_user->get_email() ) ); ?>"><?php echo esc_attr( antispambot( $store_user->get_email() ) ); ?></a>
                                </li>
                            <?php } ?>

                            <li class="wcvendor-store-rating">
                                <i class="lastudioicon-shape-star-2"></i> <span><?php echo dokan_get_readable_seller_rating( $store_user->get_id() ); ?></span>
                            </li>

                            <?php if ( $show_store_open_close == 'on' && $dokan_store_time_enabled == 'yes') : ?>
                                <li class="wcvendor-store-open-close">
                                    <i class="lastudioicon-clock"></i> <span><?php if ( dokan_is_store_open( $store_user->get_id() ) ) {
                                            echo esc_html( $store_open_notice );
                                        } else {
                                            echo esc_html( $store_closed_notice );
                                        } ?></span>
                                </li>
                            <?php endif ?>

                            <?php do_action( 'dokan_store_header_info_fields',  $store_user->get_id() ); ?>
                        </ul>

                        <?php if ( $social_fields ) { ?>
                            <div class="store-social-wrapper">
                                <ul class="store-social">
                                    <?php foreach( $social_fields as $key => $field ) { ?>
                                        <?php if ( !empty( $social_info[ $key ] ) && $key != 'gplus' ) { ?>
                                            <li>
                                                <a href="<?php echo esc_url( $social_info[ $key ] ); ?>" target="_blank"><i class="lastudioicon-b-<?php echo esc_attr( $field['icon'] ); ?>"></i></a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <div id="wcvendor_profile_menu">
                        <form id="wcvendor_search_shops" action="<?php echo esc_url( dokan_get_store_url( $store_user->get_id() ) ); ?>" method="get" class="wcvendor-search-inside search-form">
                            <input class="search-field" autocomplete="off" type="search" name="s" placeholder="<?php esc_attr_e('Search in this shop', 'luxsa') ?>" value=""/>
                            <button class="search-button" type="submit"><i class="lastudioicon-zoom-1"></i></button>
                        </form>
                        <?php if ( $store_tabs ) { ?>
                            <div class="wcvendor_profile_menu_tabs">
                                <ul><?php
                                    foreach( $store_tabs as $key => $tab ) {
                                        if ( $tab['url'] ){
                                            ?><li><a href="<?php echo esc_url( $tab['url'] ); ?>"><?php echo esc_html( $tab['title'] ); ?></a></li><?php
                                        }
                                    }
                                    ?>
                                    <?php do_action( 'dokan_after_store_tabs', $store_user->get_id() ); ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
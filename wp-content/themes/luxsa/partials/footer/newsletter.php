<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$show_popup = luxsa_get_option('enable_newsletter_popup');
$only_home_page = luxsa_get_option('only_show_newsletter_popup_on_home_page');
$delay = luxsa_get_option('newsletter_popup_delay', 2000);
$popup_content = luxsa_get_option('newsletter_popup_content');
$show_checkbox = luxsa_get_option('show_checkbox_hide_newsletter_popup', false);
$back_display_time = luxsa_get_option('newsletter_popup_show_again', '1');

$popup_dont_show_text = luxsa_get_option('popup_dont_show_text');

if($show_popup){
    if($only_home_page && !is_front_page()){
        $show_popup = false;
    }
    $show_popup = apply_filters('luxsa/filter/condition_for_displaying_newsletter', $show_popup);
}
if($show_popup && !empty($popup_content)):
    ?>
    <div data-la_component="NewsletterPopup" class="js-el la-newsletter-popup" data-waitfortrigger="0" data-back-time="<?php echo esc_attr( floatval($back_display_time) ); ?>" data-show-mobile="<?php echo luxsa_get_option('disable_popup_on_mobile') ? 1 : 0 ?>" id="la_newsletter_popup" data-delay="<?php echo esc_attr( absint($delay) ); ?>">
        <a href="#" class="btn-close-newsletter-popup"><i class="lastudioicon-e-remove"></i></a>
        <div class="newsletter-popup-content"><?php echo luxsa_transfer_text_to_format( $popup_content ); ?></div>
        <?php if($show_checkbox): ?>
            <label class="lbl-dont-show-popup"><input type="checkbox" class="cbo-dont-show-popup" id="dont_show_popup"/><?php
            if(!empty($popup_dont_show_text)){
                echo esc_html($popup_dont_show_text);
            }
            ?></label>
        <?php endif;?>
    </div>
<?php endif; ?>
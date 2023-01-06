<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$footer_copyright = luxsa_get_option('footer_copyright');
?>
<?php if(!empty($footer_copyright)): ?>
    <footer id="footer" class="<?php echo esc_attr( luxsa_footer_classes() ); ?>"<?php luxsa_schema_markup( 'footer' ); ?>>
        <?php do_action( 'luxsa/action/before_footer_inner' ); ?>
        <div id="footer-inner" class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner"><?php echo luxsa_transfer_text_to_format( $footer_copyright );?></div>
            </div>
        </div><!-- #footer-inner -->
        <?php do_action( 'luxsa/action/after_footer_inner' ); ?>
    </footer><!-- #footer -->
<?php endif; ?>
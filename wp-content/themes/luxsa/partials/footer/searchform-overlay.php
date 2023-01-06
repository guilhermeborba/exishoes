<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}?>
<div class="searchform-fly-overlay la-ajax-searchform">
    <a href="javascript:;" class="btn-close-search"><i class="lastudioicon-e-remove"></i></a>
    <div class="searchform-fly">
        <p><?php echo esc_html_x('Digite aqui o que você procura', 'front-view', 'luxsa')?></p>
        <?php
        if(function_exists('get_product_search_form')){
            get_product_search_form();
        }
        else{
            get_search_form();
        }
        ?>
        <div class="search-results">
            <div class="loading"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
            <div class="results-container"></div>
            <div class="view-more-results text-center">
                <a href="#" class="button search-results-button"><?php echo esc_html_x('Ver mais', 'front-end', 'luxsa') ?></a>
            </div>
        </div>
    </div>
</div>
<!-- .searchform-fly-overlay -->
<?php if(luxsa_string_to_bool(luxsa_get_option('backtotop_btn', 'no'))): ?>
<div class="backtotop-container"> <a href="#outer-wrap" class="btn-backtotop button"><span class="lastudioicon-arrow-up"></span></a></div>
<?php endif; ?>
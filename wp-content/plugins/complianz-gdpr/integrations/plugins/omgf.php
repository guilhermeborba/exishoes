<?php
defined( 'ABSPATH' ) or die();
define("CMPLZ_SELF_HOSTED_PLUGIN_ACTIVE", true);
function cmplz_omgf_filter_pro_fields($fields) {
	if ( isset( $fields['consent-mode'] ) ) {
		$fields['self_host_google_fonts']['help'] = sprintf( __("You have %s installed. We recommend saying 'Yes' to self-hosting Google Fonts", "complianz-gdpr") ,"OMGF");
	}
	return $fields;
}
add_filter('cmplz_fields', 'cmplz_omgf_filter_pro_fields', 10, 1);

<?php
if(!function_exists('lahb_map')){
function lahb_map( $atts, $uniqid, $once_run_flag = true) {

    if ( !$once_run_flag  && apply_filters('lastudio/header-builder/use_element_placeholder', true) ){
        return '<div data-element-id="'.esc_attr( $uniqid ).'" class="lahb-element lahb-element--placeholder"></div>';
    }

    $com_uniqid = ' [Map ' . filter_var($uniqid, FILTER_SANITIZE_NUMBER_INT) .']';

	extract( LAHB_Helper::component_atts( array(
		'address'		=> '3175 Highland Ave, Selma, CA 93662',
		'show_icon'		=> 'true',
		'text'			=> '',
		'extra_class'	=> '',
	), $atts ));

	$out = '';

	// styles
	if ( $once_run_flag ) :

		$dynamic_style = '';
        $dynamic_style .= lahb_styling_tab_output( $atts, 'text', '#lastudio-header-builder #lahb-map-' . esc_attr( $uniqid ) .' span' ,'#lastudio-header-builder #lahb-map-' . esc_attr( $uniqid ) .':hover span'  );
        $dynamic_style .= lahb_styling_tab_output( $atts, 'icon', '#lastudio-header-builder #lahb-map-' . esc_attr( $uniqid ) . ' > a > i:before', '#lastudio-header-builder #lahb-map-' . esc_attr( $uniqid ) . ' > a:hover i:before'  );
        $dynamic_style .= lahb_styling_tab_output( $atts, 'background', '#lastudio-header-builder #lahb-map-' . esc_attr( $uniqid ) . ' a' );
        $dynamic_style .= lahb_styling_tab_output( $atts, 'box', '#lastudio-header-builder #lahb-map-' . esc_attr( $uniqid ). ' a' );

        if ( $dynamic_style ) :
            LAHB_Helper::set_dynamic_styles( $dynamic_style );
        endif;

	endif;

	// vars
	$address 		= ! empty( $address ) ? esc_attr( $address ) : '';
	$icon 			= $show_icon == 'true' ? '<i class="lastudioicon-pin-3-2" ></i>' : '';
	$text			= ! empty( $text ) ? LAHB_Helper::translate_string($text, 'Content' . $com_uniqid) : '';
	$extra_class	= ! empty( $extra_class ) ? ' ' . $extra_class : '';

	// render
	$out .= '<div data-element-id="'.esc_attr( $uniqid ).'" class="lahb-element-wrap lahb-map-wrap lahb-map ' . esc_attr( $extra_class ) . '" id="lahb-map-' . esc_attr( $uniqid ) . '"><a href="https://maps.google.com/maps?q='. $address .'" class="popup-gmaps"><span>'. $text .'</span>'. $icon .'</a></div>';
	return $out;

}
}

LAHB_Helper::add_element( 'map', 'lahb_map', ['text'] );

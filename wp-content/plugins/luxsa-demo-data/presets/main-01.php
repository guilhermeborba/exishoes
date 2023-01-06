<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

function la_luxsa_preset_main_01()
{
    return [
        [
            'key'               => 'header_layout',
            'value'             => 'luxsa-header-04'
        ],
        [
            'key'               => 'footer_layout',
            'value'             => 'luxsa-footer-01'
        ],
        [
            'key'               => 'primary_color',
            'value'             => '#967468'
        ],
        [
            'key'               => 'header_transparency',
            'value'             => 'yes'
        ],
        [
            'filter_name'       => 'luxsa/filter/get_option',
            'filter_func'       => function( $value, $key ) {
                if( $key == 'la_custom_css'){
                    $value .= '
.enable-header-transparency .lahb-wrap:not(.is-sticky) .lahb-tablets-view  .lahb-icon-wrap .hamburger-op-icon,
.enable-header-transparency .lahb-wrap:not(.is-sticky) .lahb-desktop-view  .lahb-icon-wrap .hamburger-op-icon {
    color: #212121 !important;
}
';
                }
                return $value;
            },
            'filter_priority'   => 10,
            'filter_args'       => 2
        ],
    ];
}
<?php
// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
    die;
}

add_action('elementor/core/files/clear_cache', function (){
	$key = 'lastudio-gmap-style-' . LASTUDIO_VERSION;
	delete_transient($key);
});

function lastudio_elementor_get_widgets_black_list( $black_list ){
    $new_black_list = array(
        'WP_Widget_Calendar',
        'WP_Widget_Pages',
        'WP_Widget_Archives',
        'WP_Widget_Media_Audio',
        'WP_Widget_Media_Image',
        'WP_Widget_Media_Gallery',
        'WP_Widget_Media_Video',
        'WP_Widget_Meta',
        'WP_Widget_Text',
        'WP_Widget_RSS',
        'WP_Widget_Custom_HTML',
        'RevSliderWidget',
        'LaStudio_Widget_Recent_Posts',
        //        'LaStudio_Widget_Product_Sort_By',
        //        'LaStudio_Widget_Price_Filter_List',
        //        'LaStudio_Widget_Product_Tag',
        //        'WP_Widget_Recent_Posts',
        //        'WP_Widget_Recent_Comments',
        //        'WC_Widget_Cart',
        //        'WC_Widget_Layered_Nav_Filters',
        //        'WC_Widget_Layered_Nav',
        //        'WC_Widget_Price_Filter',
        //        'WC_Widget_Product_Search',
        //        'WC_Widget_Product_Tag_Cloud',
        //        'WC_Widget_Products',
        //        'WC_Widget_Recently_Viewed',
        //        'WC_Widget_Top_Rated_Products',
        //        'WC_Widget_Recent_Reviews',
        //        'WC_Widget_Rating_Filter'
    );

    $new_black_list = array_merge($black_list, $new_black_list);
    return $new_black_list;
}
add_filter('elementor/widgets/black_list', 'lastudio_elementor_get_widgets_black_list', 20);

function lastudio_elementor_backend_enqueue_scripts(){
    wp_enqueue_script(
        'lastudio-elementor-backend',
        LASTUDIO_PLUGIN_URL . 'public/element/js/editor-backend.js' ,
        ['jquery'],
        LASTUDIO_VERSION,
        true
    );
    $breakpoints = [
        'laptop' => [
            'name' => __( 'Laptop', 'lastudio' ),
            'text' => __( 'Preview for 1366px', 'lastudio' )
        ],
        'tablet' => [
            'name' => __( 'Tablet Landscape', 'lastudio' ),
            'text' => __( 'Preview for 1024px', 'lastudio' )
        ],
        'mobile_extra' => [
            'name' => __( 'Tablet Portrait', 'lastudio' ),
            'text' => __( 'Preview for 768px', 'lastudio' )
        ]
    ];
    if(la_is_local()){
        $breakpoints = [
            'laptop1' => [
                'name' => __( 'Laptop 1', 'lastudio' ),
                'text' => __( 'Preview for 1680px', 'lastudio' )
            ],
            'laptop2' => [
                'name' => __( 'Laptop 2', 'lastudio' ),
                'text' => __( 'Preview for 1440px', 'lastudio' )
            ],
            'laptop' => [
                'name' => __( 'Laptop', 'lastudio' ),
                'text' => __( 'Preview for 1366px', 'lastudio' )
            ],
            'tablet' => [
                'name' => __( 'Tablet Landscape', 'lastudio' ),
                'text' => __( 'Preview for 1024px', 'lastudio' )
            ],
            'mobile_extra' => [
                'name' => __( 'Tablet Portrait', 'lastudio' ),
                'text' => __( 'Preview for 768px', 'lastudio' )
            ]
        ];
    }
    wp_localize_script('lastudio-elementor-backend', 'LaCustomBPFE', $breakpoints);
}
add_action( 'elementor/editor/before_enqueue_scripts', 'lastudio_elementor_backend_enqueue_scripts');
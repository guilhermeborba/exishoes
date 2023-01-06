<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'lasf_array_search' ) ) {
  function lasf_array_search( $array, $key, $value ) {

    $results = array();

    if ( is_array( $array ) ) {
      if ( isset( $array[$key] ) && $array[$key] == $value ) {
        $results[] = $array;
      }

      foreach ( $array as $sub_array ) {
        $results = array_merge( $results, lasf_array_search( $sub_array, $key, $value ) );
      }

    }

    return $results;

  }
}

/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'lasf_get_var' ) ) {
  function lasf_get_var( $var, $default = '' ) {

    if( isset( $_POST[$var] ) ) {
      return $_POST[$var];
    }
    if( isset( $_GET[$var] ) ) {
      return $_GET[$var];
    }

    return $default;

  }
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'lasf_get_vars' ) ) {
  function lasf_get_vars( $var, $depth, $default = '' ) {

    if( isset( $_POST[$var][$depth] ) ) {
      return $_POST[$var][$depth];
    }

    if( isset( $_GET[$var][$depth] ) ) {
      return $_GET[$var][$depth];
    }

    return $default;

  }
}

/**
 *
 * Between Microtime
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'lasf_microtime' ) ) {
  function lasf_timeout( $timenow, $starttime, $timeout = 30 ) {

    return ( ( $timenow - $starttime ) < $timeout ) ? true : false;

  }
}

/**
 *
 * Check for wp editor api
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'lasf_wp_editor_api' ) ) {
  function lasf_wp_editor_api() {

    global $wp_version;

    return version_compare( $wp_version, '4.8', '>=' );

  }
}

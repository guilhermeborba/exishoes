<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Swatch_Widget extends WC_Widget_Layered_Nav{

    /**
     * Init settings after post types are registered.
     */
    public function init_settings() {
        $attribute_array      = array();
        $std_attribute        = '';
        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if ( ! empty( $attribute_taxonomies ) ) {
            foreach ( $attribute_taxonomies as $tax ) {
                if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
                    $attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
                }
            }
            $std_attribute = current( $attribute_array );
        }

        $this->settings = array(
            'title'        => array(
                'type'  => 'text',
                'std'   => __( 'Filter by', 'woocommerce' ),
                'label' => __( 'Title', 'woocommerce' ),
            ),
            'attribute'    => array(
                'type'    => 'select',
                'std'     => $std_attribute,
                'label'   => __( 'Attribute', 'woocommerce' ),
                'options' => $attribute_array,
            ),
            'display_type' => array(
                'type'    => 'select',
                'std'     => 'list',
                'label'   => __( 'Display type', 'woocommerce' ),
                'options' => array(
                    'swatches'     => __( 'Swatches', 'lastudio' ),
                    'list'     => __( 'List', 'woocommerce' ),
                    'dropdown' => __( 'Dropdown', 'woocommerce' ),
                ),
            ),
            'query_type'   => array(
                'type'    => 'select',
                'std'     => 'and',
                'label'   => __( 'Query type', 'woocommerce' ),
                'options' => array(
                    'and' => __( 'AND', 'woocommerce' ),
                    'or'  => __( 'OR', 'woocommerce' ),
                ),
            ),
        );
    }

    /**
     * Output widget.
     *
     * @see WP_Widget
     *
     * @param array $args Arguments.
     * @param array $instance Instance.
     */
    public function widget( $args, $instance ) {

        if ( !is_woocommerce() ) {
            return;
        }

        $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
        $taxonomy           = $this->get_instance_taxonomy( $instance );
        $query_type         = $this->get_instance_query_type( $instance );
        $display_type       = $this->get_instance_display_type( $instance );

        if ( ! taxonomy_exists( $taxonomy ) ) {
            return;
        }

        $terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );

        if ( 0 === count( $terms ) ) {
            return;
        }

        $__tmp = 'widget_layered_nav--' . str_replace('pa_', '', $taxonomy);

        if($display_type !== 'dropdown' && $display_type !== 'swatches'){
            $__tmp .= ' widget_layered_nav--querytype-' . $query_type;
        }

        $args['before_widget'] = str_replace('widget_layered_nav ', 'widget_layered_nav '. $__tmp . ' ' , $args['before_widget']);

        ob_start();

        $this->widget_start( $args, $instance );

        if ( 'dropdown' === $display_type ) {
            $found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
        }
        elseif ( 'swatches' === $display_type ){
            $found = $this->layered_nav_swatches( $terms, $taxonomy, $query_type );
        }
        else {
            $found = $this->layered_nav_list( $terms, $taxonomy, $query_type );
        }

        $this->widget_end( $args );

        // Force found when option is selected - do not force found on taxonomy attributes.
        if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
            $found = true;
        }

        if ( ! $found ) {
            ob_end_clean();
        } else {
            echo ob_get_clean(); // @codingStandardsIgnoreLine
        }
    }

    protected function layered_nav_swatches($terms, $taxonomy, $query_type){
        echo '<ul class="la-swatches-widget-opts">';

        $term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
        $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
        $found              = false;

        $base_link          = $this->get_current_page_url();

        foreach ( $terms as $term ) {
            $current_values    = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
            $option_is_set     = in_array( $term->slug, $current_values );
            $count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

            // skip the term for the current archive
            if ( $this->get_current_term_id() === $term->term_id ) {
                continue;
            }

            // Only show options with count > 0
            if ( 0 < $count ) {
                $found = true;
            }
            elseif ( 'and' === $query_type && 0 === $count && ! $option_is_set ) {
                continue;
            }

            $filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
            $current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
            $current_filter = array_map( 'sanitize_title', $current_filter );

            if ( ! in_array( $term->slug, $current_filter, true ) ) {
                $current_filter[] = $term->slug;
            }

            $link = remove_query_arg( $filter_name, $base_link );

            // Add current filters to URL.
            foreach ( $current_filter as $key => $value ) {
                // Exclude query arg for current term archive term.
                if ( $value === $this->get_current_term_slug() ) {
                    unset( $current_filter[ $key ] );
                }

                // Exclude self so filter can be unset on click.
                if ( $option_is_set && $value === $term->slug ) {
                    unset( $current_filter[ $key ] );
                }
            }

            if ( ! empty( $current_filter ) ) {
                asort( $current_filter );
                $link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

                // Add Query type Arg to URL.
                if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
                    $link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
                }
                $link = str_replace( '%2C', ',', $link );
            }

            echo '<li class="wc-layered-nav-term ' . ( $option_is_set ? 'active' : '' ) . '">';

            echo ( $count > 0 || $option_is_set ) ? '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '">' : '<span>';

            $swatch_term = new LaStudio_Swatch_Term( null, $term->term_id, $taxonomy );

            echo str_replace(array('<a href="#"', '</a>'), array('<span', '</span>'), $swatch_term->get_output());

            echo ( $count > 0 || $option_is_set ) ? '</a> ' : '</span> ';

            echo apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );

            echo '</li>';
        }

        echo '</ul>';

        return $found;
    }

    protected function get_current_page_url() {

        $link = la_get_base_shop_url();

        // Min/Max.
        if ( isset( $_GET['min_price'] ) ) {
            $link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
        }

        if ( isset( $_GET['max_price'] ) ) {
            $link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
        }

        // Order by.
        if ( isset( $_GET['orderby'] ) ) {
            $link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
        }

        // Per Page
        if ( isset( $_GET['per_page'] ) ) {
            $link = add_query_arg( 'per_page', wc_clean( $_GET['per_page'] ), $link );
        }
        // La Preset
        if ( isset( $_GET['la_preset'] ) ) {
            $link = add_query_arg( 'la_preset', wc_clean( $_GET['la_preset'] ), $link );
        }

        /**
         * Search Arg.
         * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
         */
        if ( get_search_query() ) {
            $link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
        }

        // Post Type Arg.
        if ( isset( $_GET['post_type'] ) ) {
            $link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

            // Prevent post type and page id when pretty permalinks are disabled.
            if ( is_shop() ) {
                $link = remove_query_arg( 'page_id', $link );
            }
        }

        // Min Rating Arg.
        if ( isset( $_GET['rating_filter'] ) ) {
            $link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
        }

        // All current filters.
        if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
            foreach ( $_chosen_attributes as $name => $data ) {
                $filter_name = wc_attribute_taxonomy_slug( $name );
                if ( ! empty( $data['terms'] ) ) {
                    $link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
                }
                if ( 'or' === $data['query_type'] ) {
                    $link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
                }
            }
        }

        return apply_filters( 'woocommerce_widget_get_current_page_url', $link, $this );
    }

}
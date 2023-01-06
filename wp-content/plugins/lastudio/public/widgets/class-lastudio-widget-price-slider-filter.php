<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class LaStudio_Widget_Price_Slider_Filter extends WC_Widget_Price_Filter {

    public function widget($args, $instance) {
        global $wp;

        if ( !is_woocommerce() ) {
            return;
        }

        // If there are not posts and we're not filtering, hide the widget.
        if ( ! WC()->query->get_main_query()->post_count && ! isset( $_GET['min_price'] ) && ! isset( $_GET['max_price'] ) ) { // WPCS: input var ok, CSRF ok.
            return;
        }

        wp_enqueue_script( 'wc-price-slider' );

        // Round values to nearest 10 by default.
        $step = max( apply_filters( 'woocommerce_price_filter_widget_step', 10 ), 1 );

        // Find min and max price in current result set.
        $prices    = $this->get_filtered_price();
        $min_price = $prices->min_price;
        $max_price = $prices->max_price;

        // Check to see if we should add taxes to the prices if store are excl tax but display incl.
        $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

        if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
            $tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ); // Uses standard tax class.
            $tax_rates = WC_Tax::get_rates( $tax_class );

            if ( $tax_rates ) {
                $min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
                $max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
            }
        }

        $min_price = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $min_price / $step ) * $step );
        $max_price = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $max_price / $step ) * $step );

        // If both min and max are equal, we don't need a slider.
        if ( $min_price === $max_price ) {
            return;
        }

        $current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
        $current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

        $this->widget_start( $args, $instance );

        if ( '' === get_option( 'permalink_structure' ) ) {
            $form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
        }

        echo '<form method="get" action="' . esc_url( $form_action ) . '">
			<div class="price_slider_wrapper">
				<div class="price_slider" style="display:none;"></div>
				<div class="price_slider_amount" data-step="' . esc_attr( $step ) . '">
					<input type="text" id="min_price" name="min_price" value="' . esc_attr( $current_min_price ) . '" data-min="' . esc_attr( $min_price ) . '" placeholder="' . esc_attr__( 'Min price', 'lastudio' ) . '" />
					<input type="text" id="max_price" name="max_price" value="' . esc_attr( $current_max_price ) . '" data-max="' . esc_attr( $max_price ) . '" placeholder="' . esc_attr__( 'Max price', 'lastudio' ) . '" />
					<button type="submit" class="button">' . esc_html__( 'Filter', 'woocommerce' ) . '</button>
					<div class="price_label" style="display:none;">
						' . esc_html__( 'Price:', 'lastudio' ) . ' <span class="from"></span> &mdash; <span class="to"></span>
					</div>
					' . wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ) . '
					<div class="clear"></div>
				</div>
			</div>
		</form>'; // WPCS: XSS ok.

        $this->widget_end( $args );
    }

    /**
     * Based on WP_Query::parse_search
     */
    public static function get_main_search_query_sql() {
        global $wpdb;

        $product_query = WC_Query::get_main_query();

        $args         = isset($product_query->query_vars) ? $product_query->query_vars : [];
        $search_terms = isset( $args['search_terms'] ) ? $args['search_terms'] : array();
        $sql          = array();

        foreach ( $search_terms as $term ) {
            // Terms prefixed with '-' should be excluded.
            $include = '-' !== substr( $term, 0, 1 );

            if ( $include ) {
                $like_op  = 'LIKE';
                $andor_op = 'OR';
            } else {
                $like_op  = 'NOT LIKE';
                $andor_op = 'AND';
                $term     = substr( $term, 1 );
            }

            $like  = '%' . $wpdb->esc_like( $term ) . '%';
            $sql[] = $wpdb->prepare( "(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_excerpt $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s))", $like, $like, $like ); // unprepared SQL ok.
        }

        if ( ! empty( $sql ) && ! is_user_logged_in() ) {
            $sql[] = "($wpdb->posts.post_password = '')";
        }

        return implode( ' AND ', $sql );
    }


    protected function get_filtered_price() {
        global $wpdb;

        $args       = WC()->query->get_main_query()->query_vars;
        $tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
        $meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

        if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
            $tax_query[] = WC()->query->get_main_tax_query();
        }

        foreach ( $meta_query + $tax_query as $key => $query ) {
            if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
                unset( $meta_query[ $key ] );
            }
        }

        $meta_query = new WP_Meta_Query( $meta_query );
        $tax_query  = new WP_Tax_Query( $tax_query );
        $search     = self::get_main_search_query_sql();

        $meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
        $search_query_sql = $search ? ' AND ' . $search : '';

        $sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

        $sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

        return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
    }
}
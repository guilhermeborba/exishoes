<?php if ( ! defined( 'ABSPATH' ) ) { die; }

if(!class_exists('Luxsa_MegaMenu_Walker')){

	class Luxsa_MegaMenu_Walker extends Walker_Nav_Menu {

		public $current_popup_column = 4;
		public $is_megamenu = false;
		private $current_item_process = 0;

		// add popup class to ul sub-menus
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$submenu_custom_style = '';
			$out_div = '';
			if($depth == 0){
				$submenu_custom_style = isset( $args->popup_custom_style ) ? ' style="' . esc_attr( $args->popup_custom_style ) . '"' : '';
				$args->popup_custom_style = '';
			}
			$classes = 'sub-menu';
			if( $depth == 0 && $this->is_megamenu ) {
				$classes .= ' mm-sub-menu mm-sub-megamenu';
			}
			else{
				$classes .= ' mm-sub-menu';
			}
			$output .= "{$out_div}<ul class=\"{$classes}\">";
			if( $depth == 0 && $this->is_megamenu ) {
				$output .= '<li class="mm-mega-li"'.$submenu_custom_style.'>';
				$output .= '<div class="mm-mega-ul" data-ajaxtmp="true" data-template-id="'.$this->current_item_process.'">';
			}
		}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$out_div = '</ul>';
			if($depth == 0){
				$this->current_popup_column = 4;
				if($this->is_megamenu){
					$out_div = '</div></li></ul>';
				}
				$this->is_megamenu = false;
			}
			$output .= $out_div;
		}

		public function mega_elementor_start_el( &$output, $item, $depth=0, $args=array(),$current_object_id=0 ){
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$class_names = $value = $mega_class = '';

			$is_mega = false;

			if($depth == 0 && isset($item->menu_type) && $item->menu_type == "wide"){
				$is_mega = true;
			}

			if( $is_mega ) {
				$mega_class = ' mega';
			}

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'mm-lv-' . $depth;
			$classes[] = 'mm-menu-item';
			if($depth > 0 ){
				$classes[] = 'mm-sub-menu-item';
			}

			if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ){
				$classes[] = 'active';
			}

			if ($depth == 0 && in_array( 'menu-item-has-children', $classes, true )) {
				$popup_custom_styles = '';
				if ($item->menu_type == "wide") {

					$this->is_megamenu = true;

					$classes[] = "mm-popup-wide";

					if(isset($item->popup_max_width) && !empty($item->popup_max_width)){
						$popup_custom_styles .= 'max-width:' . absint($item->popup_max_width) . 'px;';
						$classes[] = "mm-popup-max-width";
					}
					if( $item->force_full_width ){
						$classes[] = "mm-popup-force-fullwidth";
					}
				}
				else {
					$classes[] = "mm-popup-narrow";
				}

				$args->popup_custom_style = $popup_custom_styles;
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . $mega_class . '"' : '';


			$output .= $indent . '<li'  . $value . $class_names;

			$output .= '>';

			$atts = array();
			$atts['title']      = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target']     = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']        = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']       = ! empty( $item->url )        ? $item->url        : '';
			$atts['data-description']       = ! empty( $item->description )        ? $item->description        : '';
			if($depth == 0){
				$atts['class'] = 'top-level-link';
			}
			else{
				$atts['class'] = 'sub-level-link';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
			$attributes = '';
			$item_output = '';

			foreach ( $atts as $attr => $value ) {
				if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$badge_text = isset($item->tip_label) && !empty($item->tip_label) ? $item->tip_label : false;
			$badge_text_css1 = $badge_text_css2 = '';
			if($badge_text){
				if(isset($item->tip_color) && !empty($item->tip_color)){
					$badge_text_css1 .= 'color:' . $item->tip_color . ';';
					$badge_text_css2 = 'border-top-color:' . $item->tip_color;
				}
				if(isset($item->tip_background_color) && !empty($item->tip_background_color)){
					$badge_text_css1 .= 'background-color:' . $item->tip_background_color;
					$badge_text_css2 = 'border-top-color:' . $item->tip_background_color;
				}
			}

			if(isset($args->before)){
				$item_output .= $args->before;
			}

			$item_output .= '<a'. $attributes. '>';
			$item_output .= '<span class="text-wrap">';
			if(!empty($item->icon)){
				$item_output .= '<i class="mm-icon '.$item->icon.'"></i>';
			}

			if(isset($args->link_before)){
				$item_output .= $args->link_before;
			}

			$item_output .= '<span class="menu-text">';
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
			$item_output .= '</span>';
			if(isset($args->link_after)){
				$item_output .= $args->link_after;
			}

			if(!empty($badge_text)){
				$item_output .= '<span class="menu-item-badge"><span class="menu-item-badge-text" style="'.$badge_text_css1.'">'.$badge_text.'</span><span class="menu-item-badge-border" style="'.$badge_text_css2.'"></span></span>';
			}

			if ( ! empty( $item->description ) ) {
				$item_output .= '<span class="la-menu-desc">' . apply_filters('LaStudio/menu_item_description', $item->description) . '</span>';
			}
			$item_output .= '</span>';
			$item_output .= '</a>';

			$item_output = apply_filters('LaStudio/menu_item_after', $item_output, $item, $depth, $args);

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		}

		public function default_start_el( &$output, $item, $depth=0, $args=array(),$current_object_id=0 ){

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$class_names = $value = $mega_class = '';

			$is_mega = false;

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'mm-lv-' . $depth;
			$classes[] = 'mm-menu-item';
			if($depth > 0 ){
				$classes[] = 'mm-sub-menu-item';
			}

			if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ){
				$classes[] = 'active';
			}


			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . $mega_class . '"' : '';


			$output .= $indent . '<li'  . $value . $class_names;

			$output .= '>';

			$atts = array();
			$atts['title']      = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target']     = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']        = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']       = ! empty( $item->url )        ? $item->url        : '';
			$atts['data-description']       = ! empty( $item->description )        ? $item->description        : '';
			if($depth == 0){
				$atts['class'] = 'top-level-link';
			}
			else{
				$atts['class'] = 'sub-level-link';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
			$attributes = '';
			$item_output = '';

			foreach ( $atts as $attr => $value ) {
				if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$badge_text = isset($item->tip_label) && !empty($item->tip_label) ? $item->tip_label : false;
			$badge_text_css1 = $badge_text_css2 = '';
			if($badge_text){
				if(isset($item->tip_color) && !empty($item->tip_color)){
					$badge_text_css1 .= 'color:' . $item->tip_color . ';';
					$badge_text_css2 = 'border-top-color:' . $item->tip_color;
				}
				if(isset($item->tip_background_color) && !empty($item->tip_background_color)){
					$badge_text_css1 .= 'background-color:' . $item->tip_background_color;
					$badge_text_css2 = 'border-top-color:' . $item->tip_background_color;
				}
			}

			if(isset($args->before)){
				$item_output .= $args->before;
			}

			$item_output .= '<a'. $attributes. '>';
			$item_output .= '<span class="text-wrap">';
			if(!empty($item->icon)){
				$item_output .= '<i class="mm-icon '.$item->icon.'"></i>';
			}

			if(isset($args->link_before)){
				$item_output .= $args->link_before;
			}
			$item_output .= '<span class="menu-text">';
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID ) ;
			$item_output .= '</span>';
			if(isset($args->link_after)){
				$item_output .= $args->link_after;
			}

			if(!empty($badge_text)){
				$item_output .= '<span class="menu-item-badge"><span class="menu-item-badge-text" style="'.$badge_text_css1.'">'.$badge_text.'</span><span class="menu-item-badge-border" style="'.$badge_text_css2.'"></span></span>';
			}

			if ( ! empty( $item->description ) ) {
				$item_output .= '<span class="la-menu-desc">' . apply_filters('LaStudio/menu_item_description', $item->description) . '</span>';
			}
			$item_output .= '</span>';
			$item_output .= '</a>';

			if(isset($args->after)){
				$item_output .= $args->after;
			}

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args);

		}

		public function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id=0 ) {
			if(isset($args->show_megamenu) && $args->show_megamenu == 'true') {
				$this->mega_elementor_start_el( $output, $item, $depth, $args, $current_object_id );
			}
			else{
				$this->default_start_el( $output, $item, $depth, $args, $current_object_id);
			}

		}

		public static function fallback( $args ){
			$menu_id = uniqid('fallback-menu-');
            $output = str_replace( array("page_item", "menu-item_has_children","<ul class='children'>"), array("page_item mm-menu-item", "menu-item-has-children","<ul class='sub-menu mm-sub-menu'>"), wp_list_pages('echo=0&title_li=') );
			return sprintf( $args['items_wrap'], $menu_id, $args['menu_class'], $output );
		}


		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];
			$id       = $element->$id_field;
			$this->current_item_process = $id;


			$ignore_child = false;

			if(isset($args[0]->show_megamenu) && $args[0]->show_megamenu == 'true' && isset($element->menu_type) && $element->menu_type == 'wide') {
				$ignore_child = true;
				$element->classes[] = 'menu-item-has-children';
			}

			// Display this element.
			$this->has_children = ! empty( $children_elements[ $id ] );
			if ( isset( $args[0] ) && is_array( $args[0] ) ) {
				$args[0]['has_children'] = $this->has_children; // Back-compat.
			}

			$this->start_el( $output, $element, $depth, ...array_values( $args ) );

			if($ignore_child){
				$this->start_lvl( $output, $depth, ...array_values( $args ) );
				$this->end_lvl( $output, $depth, ...array_values( $args ) );
			}

			// Descend only when the depth is right and there are childrens for this element.
			if ( ( 0 == $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {

				if(!$ignore_child){
					foreach ( $children_elements[ $id ] as $child ) {
						if ( ! isset( $newlevel ) ) {
							$newlevel = true;
							// Start the child delimiter.
							$this->start_lvl( $output, $depth, ...array_values( $args ) );
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
				}

				unset( $children_elements[ $id ] );
			}

			if ( isset( $newlevel ) && $newlevel ) {
				// End the child delimiter.
				$this->end_lvl( $output, $depth, ...array_values( $args ) );
			}

			// End this element.
			$this->end_el( $output, $element, $depth, ...array_values( $args ) );
		}

	}
}
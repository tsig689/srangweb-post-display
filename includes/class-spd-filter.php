<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Filter {

	public static function get_filter_category_ids( $atts ) {
		$terms = self::get_filter_categories( $atts );
		if ( empty( $terms ) ) {
			return array();
		}
		return array_map( 'absint', wp_list_pluck( $terms, 'term_id' ) );
	}

	public static function get_active_category_slug( $pager_id = 'main' ) {
		$key = 'spd_filter_' . sanitize_key( $pager_id );

		if ( isset( $_GET[ $key ] ) ) {
			$value = sanitize_title( wp_unslash( $_GET[ $key ] ) );
			return $value;
		}

		return '';
	}

	public static function render_category_filter( $atts ) {
		$pager_id = sanitize_key( $atts['pager_id'] );
		$key      = 'spd_filter_' . $pager_id;
		$active   = self::get_active_category_slug( $pager_id );
		$base_url = remove_query_arg( array( $key, 'spd_page_' . $pager_id ) );
		$cats     = self::get_filter_categories( $atts );

		if ( empty( $cats ) ) {
			return '';
		}

		$all_label = ! empty( $atts['filter_all_label'] ) ? $atts['filter_all_label'] : 'All';
		$output    = '<div class="spd-filter">';

		$all_url = esc_url( remove_query_arg( $key, $base_url ) );
		$all_cls = empty( $active ) ? ' is-active' : '';
		$output .= '<a class="spd-filter-link' . esc_attr( $all_cls ) . '" href="' . $all_url . '">' . esc_html( $all_label ) . '</a>';

		foreach ( $cats as $cat ) {
			$url = esc_url( add_query_arg( $key, $cat->slug, $base_url ) );
			$cls = ( $active === $cat->slug ) ? ' is-active' : '';
			$output .= '<a class="spd-filter-link' . esc_attr( $cls ) . '" href="' . $url . '">' . esc_html( $cat->name ) . '</a>';
		}

		$output .= '</div>';

		return $output;
	}

	public static function get_filter_categories( $atts ) {
		// If source is category and a category is set, use siblings of that category if possible.
		if ( 'category' === $atts['source'] && ! empty( $atts['category'] ) ) {
			$base_cat = get_category_by_slug( $atts['category'] );

			if ( $base_cat && ! is_wp_error( $base_cat ) ) {
				$args = array(
					'taxonomy'   => 'category',
					'hide_empty' => true,
				);

				if ( $base_cat->parent ) {
					$args['parent'] = (int) $base_cat->parent;
				} else {
					$args['parent'] = 0;
				}

				$terms = get_terms( $args );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					return $terms;
				}
			}
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
				'number'     => 20,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return $terms;
	}
}

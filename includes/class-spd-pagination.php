<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Pagination {

	public static function get_current_page( $pager_id = 'main' ) {
		$key = 'spd_page_' . sanitize_key( $pager_id );
		if ( isset( $_GET[ $key ] ) ) {
			return max( 1, absint( $_GET[ $key ] ) );
		}
		return 1;
	}

	public static function render( $max_pages, $current_page, $pager_id = 'main' ) {
		if ( $max_pages <= 1 ) {
			return '';
		}

		$key     = 'spd_page_' . sanitize_key( $pager_id );
		$base    = remove_query_arg( $key );
		$output  = '<nav class="spd-pagination" aria-label="Posts pagination">';

		for ( $i = 1; $i <= $max_pages; $i++ ) {
			$url   = esc_url( add_query_arg( $key, $i, $base ) );
			$class = 'spd-page-link' . ( (int) $i === (int) $current_page ? ' is-active' : '' );
			$output .= '<a class="' . esc_attr( $class ) . '" href="' . $url . '">' . esc_html( $i ) . '</a>';
		}

		$output .= '</nav>';
		return $output;
	}
}

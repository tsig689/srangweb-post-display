<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SPD_Pagination {
	public static function get_current_page( $pager_id = 'main' ) {
		$key = 'spd_page_' . sanitize_key( $pager_id );
		if ( isset( $_GET[ $key ] ) ) {
			$page = absint( $_GET[ $key ] );
			return max( 1, $page );
		}
		return 1;
	}

	public static function render( $max_pages, $current_page, $pager_id = 'main' ) {
		$max_pages = absint( $max_pages );
		$current_page = absint( $current_page );
		if ( $max_pages <= 1 ) {
			return '';
		}
		$key = 'spd_page_' . sanitize_key( $pager_id );
		$output = '<nav class="spd-pagination" aria-label="' . esc_attr__( 'Posts pagination', 'srangweb-post-display' ) . '">';
		for ( $i = 1; $i <= $max_pages; $i++ ) {
			$url = esc_url( add_query_arg( $key, $i ) );
			$class = 'spd-page-link' . ( $i === $current_page ? ' is-active' : '' );
			$output .= '<a class="' . esc_attr( $class ) . '" href="' . $url . '">' . esc_html( (string) $i ) . '</a>';
		}
		$output .= '</nav>';
		return $output;
	}
}
